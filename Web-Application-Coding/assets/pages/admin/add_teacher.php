<?php
    include '../../../utilities/QueryBuilder.php';
    $title = 'Gestion des enseignants';
    $breadcrumb = 'Ajouter un enseignant';

    $obj = new QueryBuilder();
    $max_date = $obj->Requete('SELECT NOW()-INTERVAL 16 YEAR as dat')->fetch();
    $max_date = $max_date['dat'];

    //selection of classes
    $niveau = $obj->Select('niveau', array('ID_NIVEAU', 'NOM_NIVEAU'), [], [], $orderBy = 'NOM_NIVEAU', $order = 1);
    
    
    //if the lecturer is added, the testimonial session will be destroyed
    if(isset($_SESSION['add_lecturer']))
    {
        alert('success', 'New Teacher added successfully !');
        unset($_SESSION['add_lecturer']);
    }

    // if the submit button is pushed
    if(isset($_POST['submit_teacher']))
    {
        extract($_POST);
        extract($_FILES);
        // echo RelativePath("Opensch_final_version\Web-Application-Coding\assets");
        // die();

        //send the picture that has been loaded
        $pic_name = $upload_profile['name'] == "" ? "user_24px.png" : Files("upload_profile", $folder = Correct_sep("../../media"), $type=1);
        //die(var_dump($pic_name != ""));
        // if the picture is rejected
        if($pic_name != "")
        {
            
            $inserted = 0;
            // data are sent to the database for insertion
            $inserted = $obj->Inscription('professeur', array('NOM_PROF', 'PRENOM_PROF', 'SEXE_PROF', 'DATE_NAISSANCE_PROF', 'CNIB_PROF','TELEPHONE_PROF','EMAIL_PROF','GRADE','SPECIALITE'), array($first_name, $last_name, $gender, $birthday, $cnib, $phone, $email, $grade, $speciality), array('CNIB_PROF'=>$cnib, 'EMAIL_PROF'=>$email));
            if($inserted)
            {
                //gets the id of the teacher inserted (ID_PROFESSEURs)
                $newTeacher = $obj->Select('professeur', [], [], 'ID_PROFESSEUR', $order = 0)->fetch();
                $newID = intval($newTeacher['ID_PROFESSEUR']);

                //insertion of the new user into the user's table'
                $obj->Insert('user', ['PASSWORD', 'USERNAME', 'DROITS', 'PROFILE_PIC'], [$first_name."".$last_name, $first_name."".$last_name, 'teacher', $pic_name]);

                // fetch the last id of the user
                $lastInserted = $obj->Select("user", [], [], 'ID_USER', $order = 0)->fetch();
                //var_dump($lastInserted);
                $lastId = intval($lastInserted['ID_USER']);

                //updates the id_user for the bew inserted teacher
                $obj->Update('professeur', ['ID_USER'], [$lastId], ['ID_PROFESSEUR'=>$newID]);
                //insertion of id of the teacher, id of the modulus into "enseigner" table
                for($i = 0; $i < count($modulus); $i++)
                {
                    $current_year = $obj->Requete('SELECT * FROM annee_scolaire WHERE DATE_FIN IS NULL ORDER BY ID_ANNEE DESC LIMIT 1 ')->fetch();
                    $obj->Insert("enseigner", array("ID_MODULE", "ID_PROFESSEUR", "ANNEE"), array($modulus[$i], $newID, $current_year['ID_ANNEE']));
                }

                $_SESSION['add_lecturer'] = 1;
                echo("<script>location.assign('../refresh.html')</script>");
            }
            else
            {
                alert("warning", "The email address or/and the ID card number already exists", $animation = "slideInRight");
            }
        }
        else
        {
            alert("warning", "The sent file is not supported !", $animation = "slideInRight");
        }
    }
    include('header.php');

?>

    <div class="container-fluid">
        
            <div class="col-lg-12">
                <div class="card shadow wow slideInDown">
                    <form action="" method="post" enctype="multipart/form-data" id="form">
                        <div class="card-header">
                            <ul class="nav nav-tabs">
                                <li class="nav-item">
                                    <a href="#basic_info" class="nav-link font-weight-bolder active text-bluesky" id="basic_info" data-target="#basic_info_form" data-toggle="tab">Personal Information</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#account_info" class="nav-link disabled" id="account_info" data-target="#account_info_form" data-toggle="tab">Teaching Information</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content py-lg-4">
                                <!-- Basic Information -->
                                <div class="tab-pane fade show active" id="basic_info_form">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group my-3">
                                                <label for="first_name">First Name <span class="text-danger"> * </span></label>
                                                <input type="text" class="form-control" id="first_name" name="first_name" required>
                                            </div>
                                        </div>

                                        <!-- ------------------------------------------------------------------------------------------------------ -->

                                        <div class="col-lg-4">
                                            <div class="form-group my-3">
                                                <label for="last_name">Last Name <span class="text-danger"> * </span></label>
                                                <input type="text" class="form-control"  id="last_name" name="last_name" required>
                                            </div>
                                        </div>

                                        <!-- ------------------------------------------------------------------------------------------------------ -->

                                        <div class="col-lg-4">
                                            <div class="form-group my-3">
                                                <label for="gender">Gender <span class="text-danger"> * </span></label>
                                                <select type="text" class="form-control"  id="gender" name="gender" required>
                                                    <option value="null" selected disabled>Select gender</option>
                                                    <option value="Male">Male</option>
                                                    <option value="Female">Female</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- ------------------------------------------------------------------------------------------------------ -->

                                        <div class="col-lg-4">
                                            <div class="form-group my-3">
                                                <label for="birthday">Birthday <span class="text-danger"> * </span></label>
                                                <input type="date" class="form-control"  id="birthday" name="birthday" max="<?= substr($max_date, 0, 10) ?>" required>
                                            </div>
                                        </div>

                                        <!-- ------------------------------------------------------------------------------------------------------ -->

                                        <!-- ------------------------------------------------------------------------------------------------------ -->

                                        <div class="col-lg-4">
                                            <div class="form-group my-3">
                                                <label for="cnib">CNIB <span class="text-danger"> * </span></label>
                                                <input type="text" class="form-control"  id="cnib" name="cnib" required>
                                            </div>
                                        </div>
                                        
                                        <!-- ------------------------------------------------------------------------------------------------------ -->
                                        <div class="col-lg-4">
                                            <div class="form-group my-3">
                                                <label for="phone">Phone Number <span class="text-danger"> * </span></label>
                                                <input type="tel" class="form-control"  id="phone" name="phone" required>
                                            </div>
                                        </div>
                                        <!-- ------------------------------------------------------------------------------------------------------ -->

                                        <div class="col-lg-4">
                                            <div class="form-group my-3">
                                            <label for="upload-file">Profile Picture </label>
                                                <div class="custom-file">
                                                    <input type="file" name="upload_profile" class="form-control custom-file-input">
                                                    <label for="upload_profile" class="custom-file-label">Choose Picture</label>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- ------------------------------------------------------------------------------------------------------ -->

                                        <div class="col-lg-12 text-right my-3">
                                            <button type="button" class="btn btn-outline-primary my-3 w-25" name="next_step" id="next_step">Next</button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Account Information -->
                                <div class="tab-pane fade" id="account_info_form">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group my-3">
                                                <label for="email">Email <span class="text-danger"> * </span></label>
                                                <input type="email" class="form-control" name="email" id="email" required>
                                            </div>
                                        </div>

                                        <!-- ------------------------------------------------------------------------------------------------------ -->


                                        <div class="col-lg-4">
                                            <div class="form-group my-3">
                                                <label for="grade">Grade <span class="text-danger"> * </span></label>
                                                <select name="grade" id="grade" class="form-control" required>
                                                    <option value="null" selected disabled>Selection le</option>
                                                    <option value="master 1">BEPC</option>
                                                    <option value="master 2">Bac</option>
                                                    <option value="Phd">Bac+1</option>
                                                    <option value="master 2">Bac+2</option>
                                                    <option value="Phd">Licence</option>
                                                    <option value="master 2">Master1</option>
                                                    <option value="Phd">Master2</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- ------------------------------------------------------------------------------------------------------ -->


                                        <div class="col-lg-4">
                                            <div class="form-group my-3">
                                                <label for="modulus">Assign Modulus <span class="text-danger"> * </span></label>
                                                <select class="form-control" name="modulus[]" id="modulus" multiple required>
                                                <?php while($niveauliste = $niveau->fetch()){ ?>
                                                        <optgroup label="<?= $niveauliste['NOM_NIVEAU'] ?>">
                                                        <?php 
                                                        $classe = $obj->Requete(" SELECT ID_CLASSE, NOM_CLASSE FROM classe WHERE ID_NIVEAU='".$niveauliste['ID_NIVEAU']."'");
                                                        while($listclass = $classe->fetch())
                                                        {?>
                                                        <option value="<?= $listclass['ID_CLASSE']?>"><?= $listclass["NOM_CLASSE"]?></option>
                                                        <?php } ?>
                                                        </optgroup>
                                                <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <script>
                                        
                                        </script>

                                        <!-- ------------------------------------------------------------------------------------------------------ -->


                                        <div class="col-lg-12">
                                            <div class="form-group my-3">
                                                <label for="speciality">Specialities <span class="text-danger"> * </span></label>
                                                <textarea type="text" class="form-control" name="speciality" id="speciality"  rows="6" required></textarea>
                                            </div>
                                        </div>

                                        <!-- ------------------------------------------------------------------------------------------------------ -->

                                        <div class="col-lg-12 text-right my-3">
                                            <button type="button" class="btn btn-outline-secondary w-25" name="prev_step" id="prev_step">Previous</button>
                                            <button type="submit" class="btn btn-outline-success w-25" name="submit_teacher" id="submit_teacher">Validate</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php
    include('../footer.php');
?>

<script>
    
$(document).ready(function(){

    $('#next_step').click(function(){
        $('#basic_info').removeClass('active');
        $('#basic_info').addClass('disabled');
        $('#basic_info_form').removeClass('show active');

        $('#account_info').removeClass('disabled');
        $('#account_info').addClass('active');
        $('#account_info_form').addClass('show active');
    });

    $('#prev_step').click(function(){
        $('#account_info').removeClass('active');
        $('#account_info').addClass('disabled');
        $('#account_info_form').removeClass('show active');

        $('#basic_info').removeClass('disabled');
        $('#basic_info').addClass('active');
        $('#basic_info_form').addClass('show active');
    });

    $("#modulus").multiselect({
        nonSelectedText: "Select a subject",
        enableFiltering: true,
        enableCaseIncensitiveFiltering: true,
        buttonWidth: "100%"
    });
})

</script>

<script>
    // Script pour remplacer l'affichage standar de type="file" 
    $(".custom-file-input").on("change", function() {
    var fileName = $(this).val().split("\\").pop();
    $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });

// Appliquer edit table aux cellules
</script>

<script>
    var registrateLecturer = $('#form')
    $.validator.addMethod('noSpace', function(value, element)
    {
        return value == '' || value.trim().length != 0;
    }, 'Blank fields are not allowed !')
    registrateLecturer.validate({
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
                required: true,
            },
            modulus:
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
    }).settings.ignore = []
</script>
