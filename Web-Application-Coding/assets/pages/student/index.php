<?php 
    include('../../../utilities/QueryBuilder.php');
    $title = 'Student Dashboard';
    $breadcrumb = 'Home';
    include('header.php');

    $obj = new QueryBuilder();

    $current_time = $obj->Requete("SELECT NOW() as temps")->fetch();

    $test = $obj->Requete("SELECT d.DATE_DEV , m.NOM_MODULE FROM devoirs d , module m , semestre s , classe c , professeur p , enseigner en WHERE d.ID_MODULE = m.ID_MODULE AND c.ID_CLASSE = m.ID_CLASSE AND p.ID_PROFESSEUR = en.ID_PROFESSEUR AND en.ID_MODULE = m.ID_MODULE AND m.ID_SEMESTRE = s.ID_SEMESTRE AND d.STATUT = 'Inactive' AND d.DATE_DEV > NOW()  AND c.ID_CLASSE = (SELECT i.ID_CLASSE FROM inscription i WHERE i.ID_ANNEE = '".getAnnee(0)["ID_ANNEE"]."' AND i.MATRICULE = (SELECT et.MATRICULE FROM etudiant et WHERE et.ID_USER = '".$_SESSION["ID_USER"]."')) AND d.ID_ANNEE = '".getAnnee(0)["ID_ANNEE"]."' ORDER BY d.DATE_DEV ASC LIMIT 1")->fetch();
    $book_booking = $obj->Requete("SELECT d.TITRE, l.DATE_REMISE, DATEDIFF(l.DATE_REMISE , l.DATE_EMPRUNT) as diff_initial , DATEDIFF(l.DATE_REMISE , NOW()) as diff_actu FROM louer l , documents d WHERE l.CODE_LIVRE = d.CODE_LIVRE AND l.STATUT = 'active' AND l.ETAT_RETOUR IS NULL AND d.STATUT = 'in_location' AND l.MATRICULE = (SELECT MATRICULE FROM etudiant WHERE ID_USER = '".$_SESSION["ID_USER"]."')")->fetch();
    $canteen = $obj->Requete("SELECT c.MOIS, DATEDIFF(a.DATE_FIN_ABONNEMENT , a.DATE_DEBUT_ABONNEMENT) as diff_initial , DATEDIFF(a.DATE_FIN_ABONNEMENT , NOW()) as diff_actu FROM abonnements a , cantine c WHERE c.ID_MOIS = a.ID_MOIS AND MONTHNAME(NOW()) = c.MOIS AND STATUT = 'actif' AND a.MATRICULE = (SELECT e.MATRICULE FROM etudiant e WHERE e.ID_USER = '".$_SESSION["ID_USER"]."') AND a.ID_ANNEE = '".getAnnee(0)["ID_ANNEE"]."'")->fetch();
    $remaining_fee = $obj->Requete("SELECT MONTANT_TOTAL - MONTANT_PAYE as total FROM scolarite s, inscription i WHERE s.ID_INSCRIPTION = i.ID_INSCRIPTION AND s.ID_INSCRIPTION = (SELECT i.ID_INSCRIPTION FROM inscription i WHERE i.ID_ANNEE = '".getAnnee(0)["ID_ANNEE"]."' AND i.MATRICULE = (SELECT e.MATRICULE FROM etudiant e WHERE e.ID_USER = '".$_SESSION["ID_USER"]."')) AND i.ID_ANNEE = '".getAnnee(0)["ID_ANNEE"]."'")->fetch();
    

    $timetable = $obj->Requete("SELECT t.TIMETABLE_FILE , t.START_DATE FROM timetable t WHERE NOW() BETWEEN t.START_DATE AND (SELECT t.START_DATE + INTERVAL 1 WEEK) AND t.ID_ANNEE = '". getAnnee(0)['ID_ANNEE'] ."' AND t.ID_CLASSE = (SELECT i.ID_CLASSE FROM inscription i WHERE i.ID_ANNEE = '". getAnnee(0)['ID_ANNEE'] ."' AND i.MATRICULE = (SELECT e.MATRICULE FROM etudiant e WHERE e.ID_USER = '".$_SESSION['ID_USER']."'))")->fetch();
    $payed_fee = $obj->Requete("SELECT MONTANT_PAYE FROM scolarite s , inscription i WHERE s.ID_INSCRIPTION = i.ID_INSCRIPTION AND s.ID_INSCRIPTION = (SELECT i.ID_INSCRIPTION FROM inscription i WHERE i.ID_ANNEE = '".getAnnee(0)["ID_ANNEE"]."' AND i.MATRICULE = (SELECT e.MATRICULE FROM etudiant e WHERE e.ID_USER = '".$_SESSION["ID_USER"]."')) AND i.ID_ANNEE = '".getAnnee(0)["ID_ANNEE"]."'")->fetch();
    $scholarship = $obj->Requete("SELECT b.TAUX FROM inscription i , bourse b WHERE i.ID_BOURSE = b.ID_BOURSE AND i.MATRICULE = (SELECT e.MATRICULE FROM etudiant e WHERE e.ID_USER = '".$_SESSION["ID_USER"]."') AND i.ID_ANNEE = '".getAnnee(0)["ID_ANNEE"]."'")->fetch();

    $answers = $obj->Requete("SELECT distinct(SURVEY_ID) from answers where ID_USER = '". $_SESSION['ID_USER'] ."' AND ID_ANNEE = '".getAnnee(0)['ID_ANNEE']."'");
    $ans = array();

    while($row=$answers->fetch())
    {
        $ans[$row['SURVEY_ID']] = 1;
    }

    $nb_surveys = 0;

    $survey = $obj->Requete("SELECT * FROM survey_set s, module m , classe c, inscription i, etudiant e where e.ID_USER = '" .$_SESSION["ID_USER"]. "' AND i.MATRICULE = e.MATRICULE AND m.ID_MODULE = s.ID_MODULE AND c.ID_CLASSE = m.ID_CLASSE AND i.ID_CLASSE = m.ID_CLASSE AND '".date('Y-m-d')."' between date(start_date) and date(end_date) order by rand() AND s.ID_ANNEE = '".getAnnee(0)['ID_ANNEE']."'");
    while($row=$survey->fetch()):
        if(!isset($ans[$row['ID_SURVEYS']])):
            $nb_surveys += 1;
        endif;
    endwhile;

    $modules = $obj->Requete("SELECT m.ID_MODULE , m.NOM_MODULE FROM classe c , module m WHERE c.ID_CLASSE = m.ID_CLASSE AND c.ID_CLASSE = (SELECT i.ID_CLASSE FROM inscription i WHERE i.ID_ANNEE = '".getAnnee(0)['ID_ANNEE']."' AND i.MATRICULE = (SELECT e.MATRICULE FROM etudiant e WHERE e.ID_USER = '".$_SESSION["ID_USER"]."'))");
    $notes = $obj->Requete("SELECT NOTE1 , NOTE2 , NOTE3 FROM `notes` WHERE MATRICULE = (SELECT e.MATRICULE FROM etudiant e WHERE e.ID_USER = '".$_SESSION["ID_USER"]."') AND ANNEE_SCOLAIRE = '".getAnnee(0)["ID_ANNEE"]."'");
    
    $matieres = []; $marks = [];
    while($note = $notes->fetch()):
        foreach ($note as $key => $value) {
            if(!empty($value))
            {
                $marks[] = (float)$value;
                $matieres[] = ucfirst($key);
            }
        }
    endwhile;
?>

<div class="container-fluid">
    <div class="row mt-4 mt-lg-0">
        <div class="col-xl-3 col-md-6 mb-4 wow bounceIn">
            <div class="card border-top-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="mb-2 font-weight-bold">
                                Next Test
                            </div>
                        </div>

                        <div class="col-auto">
                            <i class="fas fa-pen-alt text-warning"></i>
                        </div>
                        <?php
                            if($test != false):
                        ?>
                            <div class="col-12 my-2">
                                <div class="mb-0">Module : <span class="text-warning pr-4"><?= $test["NOM_MODULE"] ?></span><br> Date : <span class="text-warning pr-4"><?= date("M d, Y | h:i:a",strtotime($test["DATE_DEV"]))?></span></div>
                            </div>
                        <?php
                            else:
                        ?>
                            <div class="col-12 my-2">
                                <div class="mb-0">No upcoming test</div>
                            </div>
                        <?php
                            endif
                        ?>
                    </div>
                </div>
            </div>
        </div>   

        <div class="col-xl-3 col-md-6 mb-4 wow bounceIn">
            <div class="card border-top-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="mb-2 font-weight-bold">
                                Library
                            </div>
                        </div>

                        <div class="col-auto">
                            <i class="fas fa-book-open text-success"></i>
                        </div>

                        <div class="col-12 my-2">
                            <div class="mb-0"><?= isset($book_booking["TITRE"]) ? 'Currently reading : '.$book_booking["TITRE"] : "Currently reading no book" ?></div>
                            <?php
                                if($book_booking != false):
                                    $pourcentage = ($book_booking["diff_actu"] * 100)/$book_booking["diff_initial"];
                                    if ($pourcentage > 50 && $pourcentage <= 100) 
                                    {
                                        $progression_color = 'bg-success';
                                    }
                                    elseif($pourcentage > 3 && $pourcentage <= 50)
                                    {
                                        $progression_color = 'bg-warning';
                                    }
                                    elseif($pourcentage <= 3)
                                    {
                                        $progression_color = 'bg-danger';
                                    }

                                    if ($pourcentage >= 0) :
                                        $style = 'style="width:'. $pourcentage . '%" aria-valuenow="'.$pourcentage.'" ';
                                        $progress_text = $book_booking["diff_actu"].' day'. ($book_booking["diff_actu"] > 1 ? 's ' : ' ') .'remaining' ;
                                    else: 
                                        $style = 'style="width:100%" aria-valuenow="0"'; 
                                        $progress_text = 'You should give this book back ' . abs($book_booking["diff_actu"]).' day'. (abs($book_booking["diff_actu"]) > 1 ? 's ' : ' ') .'ago' ;
                                    endif;

                            ?>
                                <div class="progress mt-2" style="height:20px">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated <?= $progression_color ?>" aria-valuemin="0" aria-valuemax="100" <?= $style ?>> <?= $progress_text ?></div>
                                </div>
                            <?php
                                endif;
                            ?>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>  

        <div class="col-xl-3 col-md-6 mb-4 wow bounceIn">
            <div class="card border-top-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="mb-2 font-weight-bold">
                                Canteen Booking
                            </div>
                        </div>
                        
                        <div class="col-auto">
                            <i class="fas fa-utensils text-primary"></i>
                        </div>

                        <div class="col-12 my-2">
                            <?php
                                if(isset($canteen['diff_actu']) && $canteen['diff_actu'] > 0):
                            ?>
                                <div class=" mb-0"><?= isset($canteen["MOIS"]) ? 'Currently month : '.$canteen["MOIS"] : "No booking for the current month" ?></div>
                                <?php
                                    if($canteen != false):
                                        $pourcentage_canteen = ($canteen["diff_actu"] * 100)/$canteen["diff_initial"];
    
                                        if ($pourcentage_canteen > 50 && $pourcentage_canteen <= 100) 
                                        {
                                            $progression_canteen_color = 'bg-primary';
                                        }
                                        elseif($pourcentage_canteen > 0 && $pourcentage_canteen <= 50)
                                        {
                                            $progression_canteen_color = 'bg-warning';
                                        }
                                        elseif($pourcentage_canteen < 0)
                                        {
                                            $progression_canteen_color = 'bg-danger';
                                        }
                                ?>
                                    <div class="progress mt-2" style="height:20px">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated <?= $progression_canteen_color ?>" style="<?='width:'.$pourcentage_canteen.'%'?>"  aria-valuenow="<?= $pourcentage_canteen ?>" aria-valuemin="0" aria-valuemax="100"><?= $canteen['diff_actu'].' day'. ($canteen['diff_actu'] > 1 ? 's ' : ' ') .'remaining' ?></div>
                                    </div>
                                <?php
                                    endif;
                                        else:
                                ?>
                                    <div>No booking for the current month</div>
                            <?php
                                endif;
                            ?>  
                        </div>
                    </div>
                </div>
            </div>
        </div>  

        <div class="col-xl-3 col-md-6 mb-4 wow bounceIn">
            <div class="card border-top-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="mb-2 font-weight-bold">
                                Remaining School Fees
                            </div>
                        </div>

                        <div class="col-auto">
                            <i class="fas fa-money-bill text-danger"></i>
                        </div>
                        <?php
                            if($remaining_fee != false):
                        ?>
                            <div class="col-12 my-2">
                                <div class="mb-0 text-danger"><?= $remaining_fee["total"] . ' cfa'?></div>
                            </div>
                        <?php
                            endif;
                        ?>
                    </div>
                </div>
            </div>
        </div>      
    </div>

    <div class="row">
        <div class="col-lg-8 my-2">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col">
                            Mark Evolution
                        </div>
                        <div class="col text-right">
                            <form action="" method="POST">
                                <div class="form-group">
                                    <select name="modules" id="modules" class="form-control" onchange="chart_module(this.value)">
                                        <option value="0">All</option>
                                        <?php
                                            while($module = $modules->fetch()):
                                        ?>
                                            <option value="<?= $module["ID_MODULE"] ?>"><?= $module["NOM_MODULE"] ?></option>
                                        <?php
                                            endwhile;
                                        ?>
                                    </select>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="chart-area table-responsive">
                        <canvas id="marks"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="row">

                <div class="col-lg-12 my-2">
                    <div class="card bg-primary">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-auto text-right">
                                    <i class="fas fa-calendar-check text-white"></i>
                                </div>

                                <div class="col">
                                    <div class="text-white my-3">
                                        <span class="font-weight-bold">Current TimeTable</span>
                                        <span class="font-italic"><?= $timetable != false ? " | ".date("M d, Y",strtotime($timetable["START_DATE"])) : "" ?></span>
                                    </div>
                                    <div class="text-white">
                                        <?= $timetable != false ? "<a class='btn btn-outline-dark btn-sm btn-md btn-lg text-truncate' target='_blank' href= '"."..".DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."Timetable".DIRECTORY_SEPARATOR.$timetable['TIMETABLE_FILE']."'>Check timetable</a>" : "No timetable for the current week for the moment." ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12 my-2">
                    <div class="card bg-warning">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-auto text-right">
                                    <i class="fas fa-money-bill-wave text-white"></i>
                                </div>

                                <div class="col">
                                    <div class="text-white my-3">
                                        <span class="font-weight-bold">Paid School Fees</span><br>
                                        <span><?= $payed_fee != false ? $payed_fee["MONTANT_PAYE"]." fcfa" : "" ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12 my-2">
                    <div class="card bg-success">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-auto text-right">
                                    <i class="fas fa-calendar-check text-white"></i>
                                </div>

                                <div class="col">
                                    <div class="text-white my-3">
                                        <span class="font-weight-bold">Scholarship</span><br>
                                        <span><?= $scholarship != false ? $scholarship["TAUX"]." %" : "" ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12 my-2">
                    <div class="card bg-danger">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-auto text-right">
                                    <i class="fas fa-poll text-white"></i>
                                </div>

                                <div class="col">
                                    <div class="text-white my-3">
                                        <span class="font-weight-bold">Pending Surveys</span><br>
                                        <span><?= $nb_surveys > 0 ? $nb_surveys : "No survey to answer" ?></span>
                                    </div>
                                </div>
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

    function chart_module(param) 
    {  
        $.ajax({
            url: "../../../ajax.php?action=get_chart_mark&module="+param,
            method: "post",
            dataType: "json",
            success: function (response) {
                if(Object.keys(response["marks"]) == 0)
                {
                    
                }
                else
                {
                    chart("marks" , response['matieres'] , "Mark" , response["marks"] , " " , 'line');
                }
            }
        })
    }

    window.onload(chart_module(0));

</script>
