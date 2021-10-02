<?php 
    include('../../../utilities/QueryBuilder.php');
    $title = 'Admin Dashboard';
    $breadcrumb = 'Home';
    include_once ('header.php');

    //droit_acces();
    $obj = new QueryBuilder();

    $academic_years_exist = $obj->Select('annee_scolaire', array(), array());
    //of any line of academic year exists in the database
    if (is_object($academic_years_exist))
    {
        $academic_years_exist = $academic_years_exist->rowCount();
    }
    //if there is no records in the database
    else
    {
        $academic_years_exist = 0;
    }

    if(array_key_exists('confirm_new_year_creation' , $_POST))
    {
        echo "<script>window.open('new_year.php' , '_self') </script>";
    }

    if(array_key_exists('confirm_end_year_creation' , $_POST))
    {
        echo "<script>window.open('end_year.php' , '_self') </script>";
    }

    $student_number = $obj->Requete("SELECT COUNT(ID_INSCRIPTION) as total FROM `inscription` WHERE ID_ANNEE = '".getAnnee(0)["ID_ANNEE"]."'")->fetch();
    $attribute_scholarship = $obj->Select('newetudiant', array("COUNT(ID_NEW_ETUDIANT) as total"), array('STATUT' => 'bourse_waiting'))->fetch();

    $result_table_mois = ['January' , 'February' , 'March' , 'April' , 'May' , 'June' , 'July' , 'August' , 'September' , 'October' , 'November' , 'December'];
    $school_fees = $obj ->Requete("SELECT MONTH, MAX(somme) as moneys FROM 
                                        ( SELECT MONTH(h.DATE_PAYEMENT) AS MONTH, SUM(h.MONTANT) AS somme FROM inscription i , historique_payement h WHERE h.ID_INSCRIPTION = i.ID_INSCRIPTION AND i.ID_ANNEE = '".getAnnee(0)["ID_ANNEE"]."' GROUP BY MONTH(h.DATE_PAYEMENT) 
                                            UNION SELECT 1 , 0 UNION SELECT 2 , 0 UNION SELECT 3 , 0 UNION SELECT 4 , 0 UNION SELECT 5 , 0 UNION SELECT 6 , 0 UNION SELECT 7 , 0 UNION SELECT 8 , 0 UNION SELECT 9 , 0 UNION SELECT 10, 0 UNION SELECT 11, 0 UNION SELECT 12, 0
                                        ) AS X GROUP BY MONTH")->fetchAll();
    foreach ($school_fees as $item) 
    {
      $school_fees_table[] = (int)$item['moneys'];
    }

    $male_student = $obj->Requete("SELECT COUNT(e.MATRICULE) as male FROM inscription i , etudiant e WHERE e.MATRICULE = i.MATRICULE AND e.SEXE = 'Male' AND i.ID_ANNEE = '".getAnnee(0)["ID_ANNEE"]."'")->fetch();
    $female_student = $obj->Requete("SELECT COUNT(e.MATRICULE) as female FROM inscription i , etudiant e WHERE e.MATRICULE = i.MATRICULE AND e.SEXE = 'Female' AND i.ID_ANNEE = '".getAnnee(0)["ID_ANNEE"]."'")->fetch();
    $scholarships = $obj->Requete("SELECT COUNT(i.MATRICULE) as total FROM inscription i , bourse b WHERE i.ID_BOURSE = b.ID_BOURSE AND b.TAUX > 0 AND i.ID_ANNEE = '".getAnnee(0)['ID_ANNEE']."'")->fetch();

    $total_pending_bookings = $obj->Requete("SELECT COUNT(l.ID_LOUER) as total FROM louer l , documents d WHERE d.CODE_LIVRE = l.CODE_LIVRE AND l.STATUT = 'unactive' AND l.ID_ANNEE = '".getAnnee(0)["ID_ANNEE"]."' LIMIT 3")->fetch();
    $total_bookings = $obj->Requete("SELECT COUNT(ID_LOUER) as total FROM `louer` WHERE STATUT = 'active' AND ID_ANNEE = '".getAnnee(0)["ID_ANNEE"]."'")->fetch();
    $total_books = $obj->Requete("SELECT COUNT(CODE_LIVRE) as total FROM `documents` ")->fetch();

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
    <div class="row mt-3 mt-md-4 mt-lg-5">
        <div class="col-lg-6 ml-auto text-right">
            <?php
                //Verifier dans la base de donnee qu'il existe deja ou non une annee scolaire
                $annee_exists = 'vrai';
                //Si il existe pas d'abord une annee scolaire, on permet a l'utilisateur de commencer une nouvelle annee 
                if($academic_years_exist ==0) :
            ?>
                <button type="submit" data-toggle="modal" data-target="#confirmation_new_year" class="btn btn-dark text-capitalize" name="create_new_year_button" id="create_new_year_button">Start new year</button> 
            <?php
                else :
            ?>
                <button type="submit" data-toggle="modal" data-target="#confirmation_end_year" class="btn btn-dark text-capitalize" name="finish_current_year_button" id="finish_current_year_button">Jump to the next year</button> 
            <?php
                endif;
            ?>
        </div>
    </div>

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
                                Male Students
                            </div>
                            <div class=" mb-0 text-warning font-weight-bold"><?=$male_student['male']?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-male text-warning"></i>
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
                                Female Students
                            </div>
                            <div class=" mb-0 text-primary font-weight-bold"><?= $female_student['female'] ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-female text-primary"></i>
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
                                Attribute scholarship
                            </div>
                            <div class=" mb-0 text-dark font-weight-bold"><?= $attribute_scholarship["total"] ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill text-dark"></i>
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

    <div class="row my-3 my-md-4 my-lg-5">
        <div class="col-lg-8 my-3 my-md-3 my-lg-0">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col">Books Booking Pending confirmation <span class="badge badge-primary ml-2"><?= $total_pending_bookings["total"] ?></span> </div>
                        <div class="col text-right">
                            <a href="booking_pending_confirmation.php" role="button" class="btn btn-outline-dark rounded-pill">See more <i class="fas fa-plus"></i></a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover table-responsive-lg table-responsive-md table-responsive-sm w-100">
                        <thead>
                            <tr>
                                <th class="text-truncate">Matricule</th>
                                <th class="text-truncate">First Name</th>
                                <th class="text-truncate">Last Name</th>
                                <th class="text-truncate">Book Title</th>
                                <th class="text-truncate">Loan Date</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                                $pending_bookings = $obj->Requete("SELECT * FROM louer l , documents d WHERE d.CODE_LIVRE = l.CODE_LIVRE AND l.STATUT = 'unactive' AND l.ID_ANNEE = '".getAnnee(0)["ID_ANNEE"]."' LIMIT 3");
                                while ($pending_booking = $pending_bookings->fetch()):
                                    $etudiant = $obj->Requete("SELECT NOM , PRENOM FROM etudiant WHERE MATRICULE = '".$pending_booking["MATRICULE"]."'")->fetch();
                            ?>
                            <tr>
                                <td class="text-truncate"><?= $pending_booking["MATRICULE"] ?></td>
                                <td class="text-truncate"><?= $etudiant["PRENOM"] ?></td>
                                <td class="text-truncate"><?= $etudiant["NOM"] ?></td>
                                <td class="text-truncate"><?= $pending_booking["TITRE"] ?></td>
                                <td class="text-truncate"><?= $pending_booking["DATE_EMPRUNT"] ?></td>
                            </tr>
                            <?php
                                endwhile;
                            ?>
                        </tbody>
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
                                    <i class="fas fa-book-open text-white"></i>
                                </div>
                                <div class="col">
                                    <div class="text-white">Number of books</div>
                                    <div class="font-weight-bold text-white"><?= $total_books['total'] ?></div>
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
                                    <i class="fas fa-hourglass-start text-dark"></i>
                                </div>
                                <div class="col">
                                    <div class="text-dark">Pending bookings</div>
                                    <div class="font-weight-bold text-dark"><?= $total_pending_bookings['total'] ?></div>
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
                                    <i class="fas fa-calendar-week text-white"></i>
                                </div>
                                <div class="col">
                                    <div class="text-white">Total bookings</div>
                                    <div class="font-weight-bold text-white"><?= $total_bookings['total'] ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row my-3 my-md-4 my-lg-5">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    School Fees Incomes overview
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="school_fees"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Modal de confirmation creation d'une nouvelle annee -->
<div id="confirmation_new_year" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title text-warning" id="my-modal-title"><span class="fas fa-bell text-warning"></span> Welcome On Opensch</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
    
            <div class="modal-body">
                <p>
                    You are about to create your first academic year. We will guide you to design it simply. This step is irreversible.<br>
                    Are you ready to start ??
                </p>
                <form action="" method="post">
                    <div class="col text-right">
                        <button class="btn btn-danger" data-dismiss="modal" aria-label="Close">Cancel</button>
                        <button class="btn btn-success" type="submit" name="confirm_new_year_creation" id="confirm_new_year_creation">Confirm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Fin Modal de confirmation de changement d'annee -->


<!-- Modal de confirmation du passage a une nouvelle annee -->
<div id="confirmation_end_year" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title text-danger" id="my-modal-title"><span class="fas fa-bell text-danger"></span> Alert Warning</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
    
            <div class="modal-body">
                <p>
                    You are about to jump to a new academic. All the information are going to be out date. This step is irreversible.<br>
                    Are you sure to continue ??
                </p>
                <form action="" method="post">
                    <div class="col text-right">
                        <button class="btn btn-danger" data-dismiss="modal" aria-label="Close">Cancel</button>
                        <button class="btn btn-success" type="submit" name="confirm_end_year_creation" id="confirm_end_year_creation">Confirm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Fin Modal de confirmation de changement d'annee -->

<?php
    include('../footer.php');
?>

<script>
    chart("school_fees" , <?= json_encode($result_table_mois) ?> , "School fees incomes" , <?=json_encode($school_fees_table)?> , "cfa" , 'line');
    chart_pie("school_fees_pie", ["Up to date" , "Out to date"] , <?= json_encode($school_fees_payment_overview) ?> , 'pie');
</script>