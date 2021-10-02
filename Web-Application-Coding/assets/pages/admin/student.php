<?php
    $title = 'Student';
    $breadcrumb = 'Overview';

    include('../../../utilities/QueryBuilder.php');
    include('header.php');

    $obj = new QueryBuilder();
    $filieres = $obj->Requete("SELECT ID_FILIERE , NOM_FILIERE FROM filieres")->fetchAll();

    $students = $obj->Requete("SELECT e.MATRICULE , e.PRENOM , e.NOM , e.SEXE , c.NOM_CLASSE , u.ID_USER , u.PROFILE_PIC FROM inscription i , classe c , etudiant e , user u WHERE i.ID_CLASSE = c.ID_CLASSE AND i.MATRICULE = e.MATRICULE AND e.ID_USER = u.ID_USER AND i.ID_ANNEE = '".getAnnee(0)["ID_ANNEE"]."'")->fetchAll();

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card wow fadeIn d-none" id="vue_card">
                <div class="card-header">
                    <div class="row my-auto">
                        <div class="col-lg-5">
                            <h6>Student Management</h6>
                        </div>

                        <div class="col-lg-3">
                            <form action="" method="post">
                                <div class="form-group">
                                    <select name="classroom" id="classroom" class="form-control"  onchange="load_student(this.value)">
                                        <option value="all">All Students</option>
                                        <?php
                                            foreach ($filieres as $filiere):
                                        ?>
                                    
                                            <optgroup label="<?= $filiere["NOM_FILIERE"] ?>">
                                                <?php
                                                    $classes = $obj->Requete("SELECT ID_CLASSE , NOM_CLASSE FROM classe WHERE ID_FILIERE = '".$filiere["ID_FILIERE"]."'")->fetchAll();
                                                    foreach ($classes as $class):
                                                ?>
                                                    <option value="<?= $class["ID_CLASSE"] ?>"><?= $class["NOM_CLASSE"] ?></option>
                                                <?php
                                                    endforeach;
                                                ?>
                                            </optgroup>

                                        <?php
                                            endforeach;
                                        ?>
                                    </select>
                                </div>
                            </form>
                        </div>

                        <div class="col-lg-3">
                            <input type="text" class="form-control" onkeyup="search_student()" name="searchbar" id="searchbar" placeholder="Search by matricule">
                        </div>
                        <div class="col-lg-1 text-right">
                            <i class="fas fa-th-large" id="vue_table"></i>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row student_card" id="student_area">
                        
                    </div>
                </div>
            </div>

            <div class="card wow fadeIn" id="vue_datatable">
                <div class="card-header">
                    <div class="row">
                        <div class="col-10">
                            <h6>Student Management</h6>
                        </div>
                        <div class="col-2 text-right">
                            <i class="fas fa-th-large" id="vue_table_1"></i>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover table-responsive-lg table-responsive-md table-responsive-sm" id="table">
                        <thead>
                            <tr>
                                <th class="text-truncate">Profile Picture</th>
                                <th class="text-truncate">Matricule</th>
                                <th class="text-truncate">First Name</th>
                                <th class="text-truncate">Last name</th>
                                <th class="text-truncate">Classroom</th>
                                <th class="text-truncate">Gender</th>
                                <th class="text-truncate">More information</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach ($students as $student):
                                
                            ?>
                                <tr>
                                    <td class="text-center"><img class="img-profile rounded-circle" src="<?= '..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.$student['PROFILE_PIC'] ?>" width="50px" height="50px"></td>
                                    <td class="text-truncate"><?= $student["MATRICULE"] ?></td>
                                    <td class="text-truncate"><?= $student["NOM"] ?></td>
                                    <td class="text-truncate"><?= $student["PRENOM"] ?></td>
                                    <td class="text-truncate"><?= $student["NOM_CLASSE"] ?></td>
                                    <td class="text-truncate"><?= $student["SEXE"] ?></td>
                                    <td class="text-truncate"><a href=<?= "student_info.php?matricule=".$student["MATRICULE"] ?> role="button" class="btn btn-outline-primary rounded-pill mt-2">See more</a></td>
                                </tr>
                            <?php
                                endforeach;
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th class="text-truncate">Profile Picture</th>
                                <th class="text-truncate">Matricule</th>
                                <th class="text-truncate">First Name</th>
                                <th class="text-truncate">Last name</th>
                                <th class="text-truncate">Classroom</th>
                                <th class="text-truncate">Gender</th>
                                <th class="text-truncate">More information</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
    include("../footer.php");
?>

<script>        
    
    function load_student(param) 
    {  
        $.ajax({
            url: "../../../ajax.php?action=load_student&classroom="+param,
            method: "post",
            dataType: "text",
            success: function (response) {
               $("#student_area").html(response);
            }
        })
    }

    $("#vue_table").click(function (e) { 
        $("#vue_card").addClass("d-none");
        $("#vue_datatable").removeClass("d-none");
    });

    $("#vue_table_1").click(function (e) { 
        $("#vue_datatable").addClass("d-none");
        $("#vue_card").removeClass("d-none");
    });

    function search_student() {
        var input , filter , student_card , card , student_unique_matricule , i , matricule_value;

        input = document.getElementById('searchbar');
        filter = input.value.toUpperCase();
        student_card = document.getElementById('student_area');
        card = student_card.getElementsByClassName('card_box');

        for (i = 0; i < card.length; i++) {
            student_unique_matricule = card[i].getElementsByClassName('student_unique_matricule')[0];

            matricule_value = student_unique_matricule.textContent || student_unique_matricule.innerText;

            if (matricule_value.toUpperCase().indexOf(filter) > -1) {
                card[i].style.display = "";
            }
            else
            {
                card[i].style.display = 'none';
            }
            
        }
         
    }

    window.onload(load_student('all'));

</script>