<?php
    include '../../../utilities/QueryBuilder.php';
    $title = 'Manage Teachers';
    $breadcrumb = 'Edit teacher';

    $prof = null;

    //if the session for updated taught courses exists, it will be destroyed
    if(isset($_SESSION['updt_subject']))
    {
        alert('success', 'Lecturer\'s taught courses updated successfully !');
        unset($_SESSION['updt_subject']);
    }
    //if the session for updated lecturer exists, it will be destroyed
    if(isset($_SESSION['updt_lecturer']))
    {
        alert('success', 'Lecturer\'s profile reset successfully !');
        unset($_SESSION['updt_lecturer']);
    }

    //if the url variable is defined
    if(!empty($_GET['prof']) and isset($_GET['prof']))
    {
        $obj = new QueryBuilder();
        $max_date = $obj->Requete('SELECT NOW()-INTERVAL 16 YEAR as dat')->fetch();
        $max_date = $max_date['dat'];

        extract($_GET);
        $prof = $obj->Select('professeur p, user u', [], ['ID_PROFESSEUR'=>$prof, 'u.ID_USER'=>'p.ID_USER']);
        //if the teacher doesn't exist in the database function of the provided id, so
        if(!is_object($prof))
        {
            Redirect("http://localhost/Opensch_final_version/Web-Application-Coding/assets/pages/chef_department/edit_teacher.php?page=edit_teacher");
        }
        else
        {
            // selects driven courses of the teacher
            $driven_courses = $obj->Select("enseigner", [], ["ID_PROFESSEUR"=>$_GET['prof']]);
            //die(var_dump($driven_courses->fetchAll()));
            // select all available courses
            $all_courses = $obj->Select("module m, classe c", [], ["c.ID_CLASSE"=>"m.ID_CLASSE"], $oderBy = 'c.NOM_CLASSE', $order = 1);
            $id_driven_courses = [];
            $arr_all_courses = [];
            $arr_classes = [];
            // transforms all driven courses into an array
            while($course = $driven_courses->fetch())
            {
                $id_driven_courses[] = $course['ID_MODULE'];
            }
            // transforms all courses into an array
            while($course = $all_courses->fetch())
            {
                $arr_all_courses[$course['ID_MODULE']][] = [$course['NOM_MODULE'], $course['VOLUME_HORAIRE'], $course['ID_CLASSE']];
                if(!in_array($course['ID_CLASSE'], array_keys($arr_classes)))
                {
                    $arr_classes[$course['ID_CLASSE']][] = $course['NOM_CLASSE'];
                }
            }
            //die(var_dump($arr_classes));
            //fetches professor data
            $prof = $prof->fetch();
            //fetches user's data
            $user = $obj->Select("user", [], ["ID_USER"=>$_GET["prof"]]);
            //if the user hits the personal info update button
            if(isset($_POST['information_update']))
            {
                extract($_POST);
                $obj->Update("professeur", ['NOM_PROF', 'PRENOM_PROF', 'SEXE_PROF', 'DATE_NAISSANCE_PROF', 'CNIB_PROF', 'TELEPHONE_PROF', 'EMAIL_PROF', 'GRADE', 'SPECIALITE'], [$first_name, $last_name, $gender, $birthday, $cnib, $phone, $email, $grade, $speciality], ["ID_PROFESSEUR"=>$_GET['prof']]);
                //reloads the page
                $_SESSION['updt_lecturer'] = 1;
                echo("<script>location.assign('../refresh.html')</script>");
            }
            //if the user clicks on teacher_update
            if(isset($_POST['teacher_update']))
            {
                extract($_POST);
                $obj->Requete("DELETE FROM enseigner WHERE ID_PROFESSEUR=".$_GET['prof']);
                for($i = 0; $i<count($modulus); $i++)
                {
                    $current_year = $obj->Requete('SELECT * FROM annee_scolaire WHERE DATE_FIN IS NULL ORDER BY ID_ANNEE DESC LIMIT 1 ')->fetch();
                    $obj->Insert("enseigner", array("ID_MODULE", "ID_PROFESSEUR", "ANNEE"), array($modulus[$i], $_GET['prof'], $current_year['ID_ANNEE']));
                }

                //reloads the page
                $_SESSION['updt_subject'] = 1;
                echo("<script>location.assign('../refresh.html')</script>");

                
            }
        }
    }
    //otherwise, the user is returned to the previous page
    else
    {
        Redirect("http://localhost/Opensch_final_version/Web-Application-Coding/assets/pages/chef_department/edit_teacher.php?page=edit_teacher");
    }


    include('header.php');
?>

<div class="container-fluid">
    <div class="row wow fadeInDown">
        <div class="col-lg-4">
            <div class="card shadow">
                <img class="card-img-top rounded-top" src="../../media/<?= empty($prof['PROFILE_PIC']) ? 'user_24px.png': $prof['PROFILE_PIC']?>" height="350px">
                <div class="card-body text-center">
                    <h5 class="card-title h6"><strong>Full name : </strong> <?= $prof['NOM_PROF']." ".$prof['PRENOM_PROF']?></h5>
                    <p class="card-text"><strong>Email : </strong> <?= $prof['EMAIL_PROF']?></p>

                    <div class="btn-group" role="group">
                        <a class="btn btn-dark rounded-circle mx-3" href="<?= $prof['TELEPHONE_PROF']?>"><i class="text-white fas fa-phone"></i></a>  
                        <a class="btn btn-dark rounded-circle mx-3 " href="<?= $prof['EMAIL_PROF']?>"><i class="text-white fab fa-google-plus-g"></i></a> 
                        <!-- <a class="btn btn-dark rounded-circle mx-2 " href="mailto:patrick2yanogo@gmail.com"><i class="text-white fab fa-linkedin-in"></i></a>  -->
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-white">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a href="#basic_info" class="nav-link active" id="basic_info" data-target="#basic_info_form" data-toggle="tab">Personal Information</a>
                        </li>
                        <li class="nav-item">
                            <a href="#basic_info" class="nav-link" id="driven_course" data-target="#driven_course_form" data-toggle="tab">Driven Courses</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content py-lg-4">
                        <!-- Basic Information -->
                        <div class="tab-pane fade show active" id="basic_info_form">
                            <form action="" method="post" id="form">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                          <label for="first_name">First Name</label>
                                          <input type="text" name="first_name" id="first_name" class="form-control" value="<?= $prof['NOM_PROF']?>" required>
                                        </div>
                                    </div>

                                    <!-- ------------------------------------------------------------------------------------------------------ -->

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                          <label for="last_name">Last Name</label>
                                          <input type="text" name="last_name" id="last_name" class="form-control" value="<?= $prof['PRENOM_PROF']?>" required>
                                        </div>
                                    </div>

                                    <!-- ------------------------------------------------------------------------------------------------------ -->

                                    <div class="col-lg-6">
                                        <div class="form-group my-3">
                                            <label for="gender">Gender</label>
                                            <select type="text" class="form-control"  id="gender" name="gender" required>
                                                <option value=<?= $prof['SEXE_PROF']?> selected ><?= $prof['SEXE_PROF']?></option>
                                                <option value=<?= $prof['EMAIL_PROF'] == "Female"? "Male": "Female"?>><?= $prof['EMAIL_PROF'] == "Female"? "Male": "Female"?></option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- ------------------------------------------------------------------------------------------------------ -->

                                    <div class="col-lg-6">
                                        <div class="form-group my-3">
                                            <label for="birthday">Date of birth</label>
                                            <input type="date" class="form-control"  id="birthday" name="birthday" max="<?= substr($max_date, 0, 10) ?>" value="<?= $prof['DATE_NAISSANCE_PROF']?>" required>
                                        </div>
                                    </div>

                                    <!-- ------------------------------------------------------------------------------------------------------ -->

                                    <div class="col-lg-6">
                                        <div class="form-group my-3">
                                          <label for="email">Email</label>
                                          <input type="email" name="email" id="email" class="form-control" value="<?= $prof['EMAIL_PROF']?>" required>
                                        </div>
                                    </div>

                                    <!-- ------------------------------------------------------------------------------------------------------ -->

                                    <div class="col-lg-6">
                                        <div class="form-group my-3">
                                          <label for="phone">Phone Number</label>
                                          <input type="tel" name="phone" id="phone" class="form-control" value="<?= $prof['TELEPHONE_PROF']?>" required>
                                        </div>
                                    </div>

                                    <!-- ------------------------------------------------------------------------------------------------------ -->

                                    <div class="col-lg-6">
                                        <div class="form-group my-3">
                                          <label for="cnib">ID Card Registration Number</label>
                                          <input type="text" name="cnib" id="cnib" class="form-control" value="<?= $prof['CNIB_PROF']?>" required>
                                        </div>
                                    </div>

                                    <!-- ------------------------------------------------------------------------------------------------------ -->

                                    <div class="col-lg-6">
                                        <div class="form-group my-3">
                                          <label for="grade">Grade</label>
                                          <select name="grade" id="grade" class="form-control" value="<?= "Doctorant" ?>" required>
                                                <option value=<?= $prof['GRADE']?> selected ><?= $prof['GRADE']?></option>
                                                <?php 
                                                    $grades = ["Master 1", "Master 2", "Phd"];
                                                    for($i = 0; $i < count($grades); $i++)
                                                    {
                                                        if($prof['GRADE'] != $grades[$i])
                                                        { ?>
                                                            <option value=<?= $grades[$i]?>><?= $grades[$i]?></option>
                                                       <?php }
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- ------------------------------------------------------------------------------------------------------ -->

                                    <div class="col-lg-12">
                                        <div class="form-group my-3">
                                            <label for="speciality">Specialities</label>
                                            <textarea type="text" class="form-control" name="speciality" id="speciality"  rows="6" cols="30" required><?= $prof['SPECIALITE']?></textarea>
                                        </div>
                                    </div>

                                    <!-- ------------------------------------------------------------------------------------------------------ -->

                                    <div class="col-lg-12 text-center my-3">
                                        <button class="btn btn-outline-primary w-50" name="information_update" id="information_update" type="submit">Update Information</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- Driven courses -->
                        <div class="tab-pane fade" id="driven_course_form">
                           <form action="" method="post" class="my-3">
                             <span class="h3 text-warning">Edit Info</span>
                              <div class="row">
                              
                               <div class="form-group col-lg-6 col-md-6 col-sm-12 col-sm-12">
                                    <select class="form-control" name="modulus[]" id="modulus" multiple required></select>
                              </div>
                              <div class="text-center col-lg-6 col-md-6 col-sm-12 col-sm-12">
                                 <button name="teacher_update" type="submit" class="btn btn-primary ">
                                     Update
                                 </button>
                             </div>
                           </form>
                           <hr class="text-primary">
                           <div class="col-12 row-container text-center mt-4">
                                <span class="h4 text-secondary col-12">Taught courses</span>
                                <div class="row">
                                    <table class="table table-bordered ">
                                        <thead>
                                            <th class="bg-secondary text-white">Modulus Name</th>
                                            <th class="bg-secondary text-white">Credit Points</th>
                                            <th class="bg-secondary text-white">Classe</th>
                                        </thead>
                                        <tbody id="display_module">
                                        </tbody>
                                    </table>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
    include('../footer.php');
?>

<script>
    const id_driven_courses = <?= json_encode($id_driven_courses)?>;
    const all_courses = <?= json_encode($arr_all_courses)?>;
    const classes = <?= json_encode($arr_classes)?>;


    // appends a list of options for the select field of the modulus
    const select = document.getElementById("modulus");
    for(idclass in classes)
    {
        var optgroup = document.createElement('optgroup')
        optgroup.setAttribute("label", classes[idclass])
        for(index in all_courses)
        {
            if(idclass == all_courses[index][0][2])
            {
                var option = document.createElement('option')
                var text = document.createTextNode(all_courses[index][0][0])
                option.appendChild(text)
                option.setAttribute('value', index)
                if(id_driven_courses.indexOf(index)>=0)
                {
                    option.setAttribute("selected", "")
                }
                optgroup.appendChild(option)
            }
        }
        select.appendChild(optgroup)
    }

    // applies the function when the page gets loading
    window.onload = appendToBoard(id_driven_courses)


    //appends the list of drove courses to a html table
    function appendToBoard(array_index)
    {
        const tbody = document.getElementById("display_module");
        while(tbody.firstChild)
        {
            tbody.removeChild(tbody.firstChild)
        }
        for(var i=0; i < array_index.length; i++)
        {
            var tr = document.createElement('tr')
            var td1 = document.createElement('td')
            var td2 = document.createElement('td')
            var td3 = document.createElement('td')
            var text1 = document.createTextNode(all_courses[array_index[i]][0][0])
            var text2 = document.createTextNode(all_courses[array_index[i]][0][1])
            var text3 = document.createTextNode(classes[all_courses[array_index[i]][0][2]])

            td1.appendChild(text1)
            td2.appendChild(text2)
            td3.appendChild(text3)

            tr.appendChild(td1)
            tr.appendChild(td2)
            tr.appendChild(td3)
            
            tbody.appendChild(tr)
        }
    }
    
    $("#modulus").change(function (e) { 
        e.preventDefault();
        var selectedOptions = []
        $("#modulus option:selected").each(function () { 
             selectedOptions.push($(this).val())
        });
        var id_selected = []
        for(index in all_courses)
        {
            if(selectedOptions.indexOf(index)>=0)
            {
                id_selected.push(index)
            }
        }
        appendToBoard(id_selected)
        
    });
    


    //adapt the select field to jquery-bootstrap multiselect
    $("#modulus").multiselect({
        nonSelectedText: "Select a subject",
        enableFiltering: true,
        enableCaseIncensitiveFiltering: true,
        buttonWidth: "100%"
    });

</script>
<script>
    var updtLecturer = $('#form')
    $.validator.addMethod('noSpace', function(value, element)
    {
        return value == '' || value.trim().length != 0;
    }, 'Blank fields are not allowed !')
    updtLecturer.validate({
        rules:
        {
            first_name:
            {
                required: true,
                noSpace: true
            },
            speciality:
            {
                required: true,
                noSpace: true
            },
            last_name:
            {
                required: true,
                noSpace: true
            },
            gender:
            {
                required: true,
                noSpace: true
            },
            birthday:
            {
                required: true
            },
            phone:
            {
                required: true,
                noSpace: true
            },
            email:
            {
                required: true,
                noSpace: true
            },
            grade:
            {
                required: true
            }
        },
        messages:
        {
            first_name: 
            {
                required: 'Username is mandatory !'
            },
            speciality:
            {
                required: 'Speciality is mandatory !'
            }
        }
    })
</script>
