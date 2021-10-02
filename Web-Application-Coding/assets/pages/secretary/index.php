<?php 
    include('../../../utilities/QueryBuilder.php');
    $title = 'Secretary Dashboard';
    $breadcrumb = 'Home';
    include_once ('header.php');
    

    $obj = new QueryBuilder();

    $waiting_for_meeting = $obj->Requete("SELECT COUNT(ID_NEW_ETUDIANT) as total FROM `newetudiant` WHERE STATUT = 'pending'")->fetch();
    $student_number = $obj->Requete("SELECT COUNT(ID_INSCRIPTION) as total FROM `inscription` WHERE ID_ANNEE = '".getAnnee(0)["ID_ANNEE"]."'")->fetch();
    $active_canteen_suscriber = $obj->Requete("SELECT COUNT(ID_ABONNEMENT) as total FROM `abonnements` a , cantine c WHERE a.ID_MOIS = c.ID_MOIS AND a.STATUT = 'actif' AND c.MOIS = (SELECT MONTHNAME(NOW())) AND a.ID_ANNEE = '".getAnnee(0)["ID_ANNEE"]."'")->fetch();
    $canteen_pending_suscriber = $obj->Requete("SELECT COUNT(ID_ABONNEMENT) as total FROM `abonnements` a , cantine c WHERE a.ID_MOIS = c.ID_MOIS AND a.STATUT = 'pending' AND a.ID_ANNEE = '".getAnnee(0)["ID_ANNEE"]."'")->fetch();
    $current_canteen_pending = $obj->Requete("SELECT COUNT(ID_ABONNEMENT) as total FROM `abonnements` a , cantine c WHERE a.ID_MOIS = c.ID_MOIS AND a.STATUT = 'pending' AND c.MOIS = (SELECT MONTHNAME(NOW())) AND a.ID_ANNEE = '".getAnnee(0)["ID_ANNEE"]."'")->fetch();

    $result_table_mois = ['January' , 'February' , 'March' , 'April' , 'May' , 'June' , 'July' , 'August' , 'September' , 'October' , 'November' , 'December'];

    // ------------------------------------------------------------------------------------------------------------------------------

    $canteen_suscribers = $obj ->Requete("SELECT MONTH, MAX(CNT) as maxi FROM 
                                        ( SELECT MONTH(c.DATE_LIMITE_PAIEMENT) AS MONTH, COUNT(a.ID_ABONNEMENT) AS CNT FROM abonnements a, cantine c WHERE a.ID_MOIS = c.ID_MOIS AND a.STATUT = 'actif' AND a.ID_ANNEE = '".getAnnee(0)["ID_ANNEE"]."' GROUP BY MONTH(c.DATE_LIMITE_PAIEMENT) 
                                            UNION SELECT 1 , 0 UNION SELECT 2 , 0 UNION SELECT 3 , 0 UNION SELECT 4 , 0 UNION SELECT 5 , 0 UNION SELECT 6 , 0 UNION SELECT 7 , 0 UNION SELECT 8 , 0 UNION SELECT 9 , 0 UNION SELECT 10, 0 UNION SELECT 11, 0 UNION SELECT 12, 0
                                        ) AS X GROUP BY MONTH")->fetchAll();

    $canteen_suscribers_money = $obj ->Requete("SELECT MONTH, MAX(somme) as moneys FROM 
                                        ( SELECT MONTH(c.DATE_LIMITE_PAIEMENT) AS MONTH, SUM(a.COST_FEES) AS somme FROM abonnements a, cantine c WHERE a.ID_MOIS = c.ID_MOIS AND a.STATUT = 'actif' AND a.ID_ANNEE = '".getAnnee(0)["ID_ANNEE"]."' GROUP BY MONTH(c.DATE_LIMITE_PAIEMENT) 
                                            UNION SELECT 1 , 0 UNION SELECT 2 , 0 UNION SELECT 3 , 0 UNION SELECT 4 , 0 UNION SELECT 5 , 0 UNION SELECT 6 , 0 UNION SELECT 7 , 0 UNION SELECT 8 , 0 UNION SELECT 9 , 0 UNION SELECT 10, 0 UNION SELECT 11, 0 UNION SELECT 12, 0
                                        ) AS X GROUP BY MONTH")->fetchAll();

    $school_fees = $obj ->Requete("SELECT MONTH, MAX(somme) as moneys FROM 
                                        ( SELECT MONTH(h.DATE_PAYEMENT) AS MONTH, SUM(h.MONTANT) AS somme FROM inscription i , historique_payement h WHERE h.ID_INSCRIPTION = i.ID_INSCRIPTION AND i.ID_ANNEE = '".getAnnee(0)["ID_ANNEE"]."' GROUP BY MONTH(h.DATE_PAYEMENT) 
                                            UNION SELECT 1 , 0 UNION SELECT 2 , 0 UNION SELECT 3 , 0 UNION SELECT 4 , 0 UNION SELECT 5 , 0 UNION SELECT 6 , 0 UNION SELECT 7 , 0 UNION SELECT 8 , 0 UNION SELECT 9 , 0 UNION SELECT 10, 0 UNION SELECT 11, 0 UNION SELECT 12, 0
                                        ) AS X GROUP BY MONTH")->fetchAll();
    foreach ($canteen_suscribers as $item) 
    {
      $canteen_suscribers_table[] = (int)$item['maxi'];
    }

    foreach ($canteen_suscribers_money as $item) 
    {
      $canteen_suscribers_money_table[] = (int)$item['moneys'];
    }

    foreach ($school_fees as $item) 
    {
      $school_fees_table[] = (int)$item['moneys'];
    }

    // ------------------------------------------------------------------------------------------------------------------------------
    $pending_bookings = $obj->Requete("SELECT * FROM abonnements a , cantine c , etudiant e , classe cls, inscription i WHERE a.ID_MOIS = c.ID_MOIS  AND i.MATRICULE = e.MATRICULE AND a.MATRICULE = i.MATRICULE AND cls.ID_CLASSE = i.ID_CLASSE AND c.MOIS = (SELECT MONTHNAME(NOW())) AND a.ID_ANNEE = '".getAnnee(0)['ID_ANNEE']."' AND a.STATUT = 'pending' LIMIT 6")->fetchAll();

    $canteen_cost_per_day = $obj->Requete("SELECT COST_PER_DAY FROM `cantine` LIMIT 1")->fetch();

    $male_student = $obj->Requete("SELECT COUNT(e.MATRICULE) as male FROM inscription i , etudiant e WHERE e.MATRICULE = i.MATRICULE AND e.SEXE = 'Male' AND i.ID_ANNEE = '".getAnnee(0)["ID_ANNEE"]."'")->fetch();
    $female_student = $obj->Requete("SELECT COUNT(e.MATRICULE) as female FROM inscription i , etudiant e WHERE e.MATRICULE = i.MATRICULE AND e.SEXE = 'Female' AND i.ID_ANNEE = '".getAnnee(0)["ID_ANNEE"]."'")->fetch();
    $scholarships = $obj->Requete("SELECT COUNT(i.MATRICULE) as total FROM inscription i , bourse b WHERE i.ID_BOURSE = b.ID_BOURSE AND b.TAUX > 0 AND i.ID_ANNEE = '".getAnnee(0)['ID_ANNEE']."'")->fetch();


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

   $outDate= $obj->Requete("SELECT COUNT(*) as outToDate FROM scolarite s, inscription i WHERE i.ID_ANNEE='".getAnnee(0)["ID_ANNEE"]."' AND i.ID_INSCRIPTION=s.ID_INSCRIPTION AND s.MONTANT_TOTAL*'".$versement."' > s.MONTANT_PAYE")->fetch();
   $upDate=$obj->Requete('SELECT COUNT(*) as upToDate FROM scolarite s, inscription i WHERE i.ID_ANNEE='.getAnnee(0)["ID_ANNEE"].' AND i.ID_INSCRIPTION=s.ID_INSCRIPTION AND s.MONTANT_TOTAL*'.$versement.' <= s.MONTANT_PAYE')->fetch();

   $school_fees_payment_overview[] = $upDate['upToDate'];
   $school_fees_payment_overview[] = $outDate['outToDate'];

?>


<div class="container-fluid" id="fenetre">

    <div class="row mt-3 mt-md-4 mt-lg-5">
        <div class="col-xl-3 col-md-6 mb-4 wow bounceIn">
            <div class="card border-top-dark shadow h-100 pt-2">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="mb-1">
                                Waiting for meeting
                            </div>
                            <div class=" mb-0 text-black-50 font-weight-bold"><?= $waiting_for_meeting["total"]?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-handshake"></i>
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
                                Number of student
                            </div>
                            <div class=" mb-0 text-primary font-weight-bold"><?=$student_number['total']?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>   

        <div class="col-xl-3 col-md-6 mb-4 wow bounceIn">
            <div class="card border-top-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="mb-1">
                                Canteen active suscribers
                            </div>
                            <div class=" mb-0 text-success font-weight-bold"><?= $active_canteen_suscriber["total"] ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-utensils text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>  

        <div class="col-xl-3 col-md-6 mb-4 wow bounceIn">
            <div class="card border-top-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="mb-1">
                                Canteen pending validation
                            </div>
                            <div class=" mb-0 text-warning font-weight-bold"><?= $canteen_pending_suscriber["total"] ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-utensils text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>      
    </div>

    <div class="row mt-3 mt-md-4 mt-lg-5">

        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    Canteen overview
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="canteen_suscribers"></canvas>
                    </div>
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
                            <a href="list-payment.php" class="btn btn-dark rounded-pill">See more</a>
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

    <div class="row mt-3 mt-md-4 mt-lg-5">
        <div class="col-lg-8 my-3 my-md-3 my-lg-0">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col">Canteen Booking Pending confirmation <span class="badge badge-primary ml-2"><?= $current_canteen_pending["total"] ?></span> </div>
                        <div class="col text-right">
                            <a href="pending_bookings.php" role="button" class="btn btn-outline-dark rounded-pill">See more <i class="fas fa-plus"></i></a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <?php
                        if($pending_bookings == false):
                            echo"<p class='text-center'>No booking pendinf for validation</p>";
                        else:
                    ?>
                            <table class="table table-bordered table-hover table-responsive-lg table-responsive-md table-responsive-sm w-100">
                                <thead>
                                    <tr>
                                        <th class="text-truncate">Last Name</th>
                                        <th class="text-truncate">Fist Name</th>
                                        <th class="text-truncate">Matricule</th>
                                        <th class="text-truncate">Classroom</th>
                                        <th class="text-truncate">Month</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        
                                        foreach ($pending_bookings as $pending_booking):
                                    ?>
                                        <tr>
                                            <td class="text-truncate"><?= $pending_booking["PRENOM"] ?></td>
                                            <td class="text-truncate"><?= $pending_booking["NOM"] ?></td>
                                            <td class="text-truncate"><?= $pending_booking["MATRICULE"] ?></td>
                                            <td class="text-truncate"><?= $pending_booking["NOM_CLASSE"] ?></td>
                                            <td class="text-truncate"><?= $pending_booking["MOIS"] ?></td>
                                        </tr>
                                    <?php
                                        endforeach;
                                    ?>
                                </tbody>
                            </table>
                    <?php
                        endif;
                    ?>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            
            <div class="col-md-6 col-lg-12 my-2 my-lg-0 mb-0 mb-lg-2">
                <div class="card border-top-primary h-100 shadow">
                    <div class="card-body">
                        <form action="" method="POST">
                            <div class="form-group">
                                <label for="cost_per_day" class="font-weight-bold">Cost per day</label>
                                <div class="row my-3">
                                    <div class="col-7">
                                        <input type="text" onfocus="(this.type='number' , this.value= <?= $canteen_cost_per_day['COST_PER_DAY'] ?>)" class="form-control" name="cost_per_day" id="cost_per_day" value="<?= isset($canteen_cost_per_day["COST_PER_DAY"]) ? (float)$canteen_cost_per_day["COST_PER_DAY"]." fcfa" : ''  ?>">
                                    </div>
                                    <div class="col-5">
                                        <button type="button" class="btn btn-outline-primary rounded-pill w-100" data-toggle="modal" data-target="#update_cost">update</button>
                                    </div>
                                </div>
                            </div>


                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-12 my-2 my-lg-0 mb-0 mb-lg-2">
                <div class="card border-top-dark h-100 shadow">
                    <div class="card-body">
                        <p class="font-weight-bold">Male Students</p>
                        <div class="row">
                            <div class="col">
                                <p class="font-weight-bold"><?= $male_student['male'] ?></p>
                            </div>
                            <div class="col text-right">
                                <i class="fas fa-male fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-12 my-2 my-lg-0 mb-0 mb-lg-2">
                <div class="card border-top-success h-100 shadow">
                    <div class="card-body">
                        <p class="font-weight-bold">Female Students</p>
                        <div class="row">
                            <div class="col">
                                <p class="font-weight-bold"><?= $female_student['female'] ?></p>
                            </div>
                            <div class="col text-right">
                                <i class="fas fa-female fa-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-12 my-2 my-lg-0 mb-0 mb-lg-2">
                <div class="card border-top-warning h-100 shadow">
                    <div class="card-body">
                        <p class="font-weight-bold">Scholarship owners</p>
                        <div class="row">
                            <div class="col">
                                <p class="font-weight-bold"><?= $scholarships['total'] ?></p>
                            </div>
                            <div class="col text-right">
                                <i class="fas fa-money-bill fa-2x text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="col-md-6 col-lg-12 my-2 my-lg-0 mb-0 mb-lg-2">
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

<div class="modal fade mt-2" id="update_cost" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard='false' aria-hidden="true">
    <div class="modal-dialog modal-md mt-0" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary">
                <h6 class="modal-title text-white">Confirm Update</h6>
                <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="" method="post">
                    <div class="row my-2" id="update_confirmation">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="password">Enter password <span class="text-danger"> * </span></label>
                                <input type="password" class="form-control" name="password" id="password" required>
                            </div>
                        </div>
                        <div class="col-lg-12 py-2 text-center">
                            <input type="reset" class="btn btn-outline-danger rounded-pill px-4" value="Reset">
                            <button
                                    onclick="updateCost(document.getElementById('password').value, document.getElementById('cost_per_day').value, <?=htmlspecialchars(json_encode($_SESSION['ID_USER']))?>)"
                                    type="button" class="btn btn-outline-primary rounded-pill px-4">Update</button>
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



chart_multi("canteen_suscribers" , <?= json_encode($result_table_mois) ?> , "Number of students" , "Canteen incomes" , <?=json_encode($canteen_suscribers_table)?> , <?=json_encode($canteen_suscribers_money_table)?> , "cfa" , 'line' , 'line');

chart("school_fees" , <?= json_encode($result_table_mois) ?> , "School fees incomes" , <?=json_encode($school_fees_table)?> , "cfa" , 'line');

chart_pie("school_fees_pie", ["Up to date" , "Out to date"] , <?= json_encode($school_fees_payment_overview) ?> , 'pie');


function updateCost(password,valeur,userID) {

    $.post(
        '../../../ajax.php',{

            canteen:'ok',
            password:password,
            valeur: parseInt(valeur.split(" ")[0]),
            userID:userID,
        }, function (data){
            if(data=="ok"){
                toastr.success("The price is updated successfully");
            }else{
                toastr.error("Password incorect");
            }
        });



}



</script>