<?php 
    include('../../../utilities/QueryBuilder.php');
    $title = 'Department Dashboard';
    $breadcrumb = 'Home';
    include_once ('header.php');

    $obj = new QueryBuilder();

    $student_number = $obj->Requete("SELECT COUNT(ID_INSCRIPTION) as total FROM `inscription` WHERE ID_ANNEE = '".getAnnee(0)["ID_ANNEE"]."'")->fetch();
    $all_new_student = $obj->Select('newetudiant', array("COUNT(ID_NEW_ETUDIANT) as total"), array('STATUT' => 'in_meeting'))->fetch();
    $scholarships = $obj->Requete("SELECT COUNT(i.MATRICULE) as total FROM inscription i , bourse b WHERE i.ID_BOURSE = b.ID_BOURSE AND b.TAUX > 0 AND i.ID_ANNEE = '".getAnnee(0)['ID_ANNEE']."'")->fetch();
    $teacher = $obj->Requete("SELECT COUNT(ID_PROFESSEUR) as total FROM `professeur`")->fetch();
 
    $male_student = $obj->Requete("SELECT COUNT(e.MATRICULE) as male FROM inscription i , etudiant e WHERE e.MATRICULE = i.MATRICULE AND e.SEXE = 'Male' AND i.ID_ANNEE = '".getAnnee(0)["ID_ANNEE"]."'")->fetch();
    $female_student = $obj->Requete("SELECT COUNT(e.MATRICULE) as female FROM inscription i , etudiant e WHERE e.MATRICULE = i.MATRICULE AND e.SEXE = 'Female' AND i.ID_ANNEE = '".getAnnee(0)["ID_ANNEE"]."'")->fetch();
    $scholarships_male = $obj->Requete("SELECT COUNT(i.MATRICULE) as total FROM inscription i , etudiant e, bourse b WHERE i.ID_BOURSE = b.ID_BOURSE AND b.TAUX > 0 AND i.MATRICULE = e.MATRICULE AND e.SEXE = 'Male' AND i.ID_ANNEE = '".getAnnee(0)['ID_ANNEE']."'")->fetch();
    $scholarships_female = $obj->Requete("SELECT COUNT(i.MATRICULE) as total FROM inscription i , etudiant e, bourse b WHERE i.ID_BOURSE = b.ID_BOURSE AND b.TAUX > 0 AND i.MATRICULE = e.MATRICULE AND e.SEXE = 'Female' AND i.ID_ANNEE = '".getAnnee(0)['ID_ANNEE']."'")->fetch();

    $result_table_mois = ['January' , 'February' , 'March' , 'April' , 'May' , 'June' , 'July' , 'August' , 'September' , 'October' , 'November' , 'December'];
    $school_fees = $obj ->Requete("SELECT MONTH, MAX(somme) as moneys FROM 
                                        ( SELECT MONTH(h.DATE_PAYEMENT) AS MONTH, SUM(h.MONTANT) AS somme FROM inscription i , historique_payement h WHERE h.ID_INSCRIPTION = i.ID_INSCRIPTION AND i.ID_ANNEE = '".getAnnee(0)["ID_ANNEE"]."' GROUP BY MONTH(h.DATE_PAYEMENT) 
                                            UNION SELECT 1 , 0 UNION SELECT 2 , 0 UNION SELECT 3 , 0 UNION SELECT 4 , 0 UNION SELECT 5 , 0 UNION SELECT 6 , 0 UNION SELECT 7 , 0 UNION SELECT 8 , 0 UNION SELECT 9 , 0 UNION SELECT 10, 0 UNION SELECT 11, 0 UNION SELECT 12, 0
                                        ) AS X GROUP BY MONTH")->fetchAll();
    foreach ($school_fees as $item) 
    {
      $school_fees_table[] = (int)$item['moneys'];
    }

    
    // *******************************Mise a  jour de dashboard, les a jour et les out of day
    $toDay= $obj->Requete('SELECT NOW() as today FROM annee_scolaire')->fetch();
    $today=$toDay["today"];
    $versement=1;

    if($today<=getAnnee(0)["FIN_VERSEMENT_1"])
    {
        $versement=0.5;
    }

    else if($today>getAnnee(0)["FIN_VERSEMENT_1"] AND $today<=getAnnee(0)["FIN_VERSEMENT_2"])
    {
        $versement=0.75;
    }

    else if($today>getAnnee(0)["FIN_VERSEMENT_2"])
    {
        $versement=1;
    }

   $outDate= $obj->Requete('SELECT COUNT(*) as outToDate FROM scolarite s, inscription i WHERE i.ID_ANNEE='.getAnnee(0)["ID_ANNEE"].' AND i.ID_INSCRIPTION=s.ID_INSCRIPTION AND s.MONTANT_TOTAL*'.$versement.' > s.MONTANT_PAYE')->fetch();
   $upDate=$obj->Requete('SELECT COUNT(*) as upToDate FROM scolarite s, inscription i WHERE i.ID_ANNEE='.getAnnee(0)["ID_ANNEE"].' AND i.ID_INSCRIPTION=s.ID_INSCRIPTION AND s.MONTANT_TOTAL*'.$versement.' <= s.MONTANT_PAYE')->fetch();

   $school_fees_payment_overview[] = $upDate['upToDate'];
   $school_fees_payment_overview[] = $outDate['outToDate'];

?>

<div class="container-fluid" id="fenetre">

<!-- ============================================================================================================================================== -->
    <div class="row mt-3 mt-md-4 mt-lg-5">
        <div class="col-xl-3 col-md-6 mb-4 wow bounceIn">
            <div class="card border-top-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="mb-1">
                                Number of students
                            </div>
                            <div class=" mb-0 text-success font-weight-bold"><?=$student_number['total']?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>   

        <div class="col-xl-3 col-md-6 mb-4 wow bounceIn">
            <div class="card border-top-warning shadow h-100 pt-2">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="mb-1">
                                In meeting
                            </div>
                            <div class=" mb-0 text-warning font-weight-bold"><?=$all_new_student["total"]?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-handshake text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>  

        <div class="col-xl-3 col-md-6 mb-4 wow bounceIn">
            <div class="card border-top-dark shadow h-100 py-2">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="mb-1">
                                Scholarship owners
                            </div>
                            <div class=" mb-0 text-dark font-weight-bold"><?= $scholarships["total"] ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill text-dark"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>  

        <div class="col-xl-3 col-md-6 mb-4 wow bounceIn">
            <div class="card border-top-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="mb-1">
                                Number of teachers
                            </div>
                            <div class=" mb-0 text-primary font-weight-bold"><?= $teacher["total"] ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chalkboard-teacher text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
    </div>
<!-- ============================================================================================================================================== -->
    
    <div class="row mt-3 mt-md-4 mt-lg-5">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col">Students Overview <span class="badge badge-primary ml-2"><?= $student_number["total"] ?></span></div>
                        <div class="col text-right">
                            <a href="student.php" role="button" class="btn btn-outline-dark rounded-pill">See more <i class="fas fa-plus"></i></a>
                        </div>
                    </div>
                    
                </div>

                <div class="card-body">
                    <?php
                        $students = $obj->Requete("SELECT i.MATRICULE , e.PRENOM , e.NOM , c.NOM_CLASSE , b.TAUX from inscription i , etudiant e , classe c, bourse b WHERE i.MATRICULE = e.MATRICULE AND i.ID_CLASSE = c.ID_CLASSE AND i.ID_BOURSE = b.ID_BOURSE AND i.ID_ANNEE = '".getAnnee(0)["ID_ANNEE"]."' LIMIT 5");
                    ?>
                    <table class="table table-bordered table-hover table-responsive-lg table-responsive-md table-responsive-sm w-100">
                        <thead>
                            <tr>
                                <th class="text-truncate">Matricule</th>
                                <th class="text-truncate">First Name</th>
                                <th class="text-truncate">Last Name</th>
                                <th class="text-truncate">Classe</th>
                                <th class="text-truncate">Scholarship</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                while($student = $students->fetch()):
                            ?>
                            <tr>
                                <td class="text-truncate"><?= $student["MATRICULE"] ?></td>
                                <td class="text-truncate"><?= $student["NOM"] ?></td>
                                <td class="text-truncate"><?= $student["PRENOM"] ?></td>
                                <td class="text-truncate"><?= $student["NOM_CLASSE"] ?></td>
                                <td class="text-truncate"><?= $student["TAUX"] . " %" ?></td>
                            </tr>
                            <?php
                                endwhile;
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mt-3 mt-lg-0">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col">
                            School fees overview
                        </div>
                        <div class="col text-right">
                            <a href="list-payment.php" role="button" class="btn btn-dark rounded-pill">See more</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="school_fees_pie"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- ============================================================================================================================================== -->

    <div class="row mt-3 mt-md-4 mt-lg-5">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col">Teachers Overview <span class="badge badge-primary ml-2"><?= $teacher["total"] ?></span></div>
                        <div class="col text-right">
                            <a href="./edit_teacher.php" role="button" class="btn btn-outline-dark rounded-pill">See more <i class="fas fa-plus"></i></a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover table-responsive-lg table-responsive-md table-responsive-sm w-100">
                        <thead>
                            <tr>
                                <th class="text-truncate">Last Name</th>
                                <th class="text-truncate">Fist Name</th>
                                <th class="text-truncate">CNIB</th>
                                <th class="text-truncate">Grade</th>
                                <th class="text-truncate">Contact</th>
                            </tr>
                        </thead>
                        <?php
                            $teacher_infos = $obj->Requete("SELECT * FROM `professeur` LIMIT 5");
                            while ($teacher_info = $teacher_infos->fetch()):
                        ?>
                            <tbody>
                                <tr>
                                    <td class="text-truncate"><?= $teacher_info["NOM_PROF"] ?></td>
                                    <td class="text-truncate"><?= $teacher_info["PRENOM_PROF"] ?></td>
                                    <td class="text-truncate"><?= $teacher_info["CNIB_PROF"] ?></td>
                                    <td class="text-truncate"><?= $teacher_info["GRADE"] ?></td>
                                    <td class="text-truncate">
                                        <div class="btn-group" role="group">
                                            <a class="btn btn-dark rounded-circle mx-1 " href=<?= "mailto:".$teacher_info["EMAIL_PROF"] ?>><i class="text-white fab fa-google-plus-g"></i></a> 
                                            <a class="btn btn-dark rounded-circle mx-1 " href=<?= "tel:".$teacher_info["TELEPHONE_PROF"] ?>><i class="text-white fas fa-phone"></i></a> 
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        <?php
                            endwhile;
                        ?>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="row">
                <div class="col-md-6 col-lg-12 my-2 my-lg-0 mb-0 mb-lg-2">
                    <div class="card h-100 bg-primary">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-auto text-right">
                                    <i class="fas fa-male text-white"></i>
                                </div>
                                <div class="col">
                                    <div class="text-white">Male Students</div>
                                    <div class="font-weight-bold text-white"><?= $male_student['male'] ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-12 my-2">
                    <div class="card h-100 bg-warning">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-auto text-right">
                                    <i class="fas fa-female text-dark"></i>
                                </div>
                                <div class="col">
                                    <div class="text-dark">Female Students</div>
                                    <div class="font-weight-bold text-dark"><?= $female_student['female'] ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-12 my-2">
                    <div class="card h-100 bg-dark">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-auto text-right">
                                    <i class="fas fa-money-bill text-white"></i>
                                </div>
                                <div class="col">
                                    <div class="text-white">Male owning scholarship</div>
                                    <div class="font-weight-bold text-white"><?= $scholarships_female["total"] ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-12 my-2">
                    <div class="card h-100 bg-success">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-auto text-right">
                                    <i class="fas fa-money-bill text-white"></i>
                                </div>
                                <div class="col">
                                    <div class="text-white">Female owning scholarship</div>
                                    <div class="font-weight-bold text-white"><?= $scholarships_female["total"] ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
<!-- ============================================================================================================================================== -->
    

</div>


<?php
    include('../footer.php');
?>

<script>
    chart_pie("school_fees_pie", ["Up to date" , "Out to date"] , <?= json_encode($school_fees_payment_overview) ?> , 'pie');
</script>