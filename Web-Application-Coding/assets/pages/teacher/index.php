<?php
    include('../../../utilities/QueryBuilder.php');
    $title = 'Teacher Dashboard';
    $breadcrumb = 'Home';
    include('header.php');

    $requetes = $obj ->Requete("SELECT c.NOM_CLASSE , m.NOM_MODULE , d.DATE_DEV , d.DEVOIR , d.POURCENTAGE , d.STATUT FROM devoirs d , enseigner e , classe c , module m WHERE d.ID_MODULE = m.ID_MODULE AND d.ID_MODULE = e.ID_MODULE AND e.ID_PROFESSEUR = (SELECT pr.ID_PROFESSEUR FROM professeur pr WHERE pr.ID_USER = '". $_SESSION['ID_USER'] ."') AND m.ID_CLASSE = c.ID_CLASSE AND d.DATE_DEV > NOW() AND d.ID_ANNEE = '". getAnnee(0)['ID_ANNEE']."' LIMIT 5")->fetchAll();
    $requete_outdate = $obj ->Requete("SELECT c.NOM_CLASSE , m.NOM_MODULE , d.DATE_DEV , d.DEVOIR , d.POURCENTAGE , d.STATUT FROM devoirs d , enseigner e , classe c , module m WHERE d.ID_MODULE = m.ID_MODULE AND d.ID_MODULE = e.ID_MODULE AND e.ID_PROFESSEUR = (SELECT pr.ID_PROFESSEUR FROM professeur pr WHERE pr.ID_USER = '". $_SESSION['ID_USER'] ."') AND m.ID_CLASSE = c.ID_CLASSE AND d.DATE_DEV < NOW() AND d.ID_ANNEE = '". getAnnee(0)['ID_ANNEE']."' LIMIT 5")->fetchAll();

    $reDate=$obj->Requete("SELECT d.DATE_DEV FROM devoirs d, enseigner e ,professeur p WHERE e.ID_PROFESSEUR=p.ID_PROFESSEUR AND p.ID_USER=".$_SESSION['ID_USER']." AND e.ID_MODULE=d.ID_MODULE AND d.DATE_DEV >= NOW() AND d.ID_ANNEE=".getAnnee(0)['ID_ANNEE']." ORDER BY d.DATE_DEV ASC LIMIT 1");
    $mesModulesEtClasses=$obj->Requete("SELECT COUNT(DISTINCT m.ID_CLASSE) as mesClasses,  COUNT(DISTINCT e.ID_MODULE) as mesModules FROM enseigner e, professeur p,module m WHERE m.ID_MODULE=e.ID_MODULE AND  p.ID_USER=".$_SESSION['ID_USER']." AND e.ID_PROFESSEUR=p.ID_PROFESSEUR AND e.ANNEE=".getAnnee(0)['ID_ANNEE'])->fetch();
    
?>

<div class="container-fluid">
    <div class="row"> 
        <div class="col-xl-4 col-md-6 mb-4 wow bounceIn">
            <div class="card border-top-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="mb-2 font-weight-bold">
                                Coming test
                            </div>
                        </div>

                        <div class="col-auto">
                            <i class="fas fa-pen-alt text-success"></i>
                        </div>

                        <div class="col-12 my-2">
                        <?php
                            if ($reDate = $reDate->fetch()) {
                                echo $reDate["DATE_DEV"];
                            }
                            else
                            {
                                echo "There is no upcoming test";
                            }
                        ?>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>  

        <div class="col-xl-4 col-md-6 mb-4 wow bounceIn">
            <div class="card border-top-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="mb-2 font-weight-bold">
                                Number of modulus
                            </div>
                        </div>
                        
                        <div class="col-auto">
                            <i class="fas fa-book-reader text-primary"></i>
                        </div>

                        <div class="col-12 my-2">                           
                           <?=$mesModulesEtClasses['mesModules']?>
                        </div>
                    </div>
                </div>
            </div>
        </div> 

        <div class="col-xl-4 col-md-6 mb-4 wow bounceIn">
            <div class="card border-top-dark shadow h-100 py-2">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="mb-2 font-weight-bold">
                                Number of classrooms
                            </div>
                        </div>

                        <div class="col-auto">
                            <i class="fas fa-school text-dark"></i>
                        </div>

                        <div class="col-12 my-2">
                            <?=$mesModulesEtClasses['mesClasses']?>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>  
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col">
                            <ul class="nav nav-tabs">
                                <li class="nav-item">
                                    <a href="#up_to_date" class="nav-link active" data-toggle="tab"
                                       data-target="#up_to_date">Upcoming tests</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#out_to_date" class="nav-link text-danger" data-toggle="tab"
                                       data-target="#out_to_date">Passed tests</a>
                                </li>
                            </ul>
                        </div>
                        <div class="col text-right">
                            <a class="btn btn-dark" href="planned_test.php">See more</a> 
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <!-- Up to date student -->
                        <div class="tab-pane fade show active" id="up_to_date">
                            <table class="table table-bordered">
                                <thead>
                                    <th class="text-truncate">Classroom</th>
                                    <th class="text-truncate">Modulus</th>
                                    <th class="text-truncate">Test Date</th>
                                    <th class="text-truncate">Test</th>
                                    <th class="text-truncate">Percentage</th>
                                    <th class="text-truncate">Status</th>
                                </thead>
                                <tbody>
                                    <?php
                                        foreach ($requetes as $requete):
                                    ?>
                                    <tr>
                                        <td class="text-truncate"><?= $requete["NOM_CLASSE"]?></td>
                                        <td class="text-truncate"><?= $requete["NOM_MODULE"]?></td>
                                        <td class="text-truncate"><?= $requete["DATE_DEV"] ?> </td>
                                        <td class="text-truncate"><a class="btn btn-dark text-truncate" href="<?= 'devoir_preview.php?title='.$requete['DEVOIR'] ?>" >Check the file</a></td>
                                        <td class="text-truncate"><?= $requete["POURCENTAGE"].' %' ?></td>
                                        <?php
                                                if ($requete["STATUT"] == 'Active') :
                                                    $actif = 'badge-success';
                                                else:
                                                    $actif = 'badge-danger';
                                                endif;
                                        ?>
                                        <td class="text-truncate">
                                            <span class="py-2 px-4 badge <?= $actif ?>"><?= $requete["STATUT"] ?></span>
                                        </td>
                                    </tr>
                                    <?php
                                        endforeach;
                                    ?>
                                    
                                </tbody>
                               
                            </table>
                        </div>
                        <div class="tab-pane fade" id="out_to_date">
                            <table class="table table-bordered">
                                <thead>
                                    <th class="text-truncate">Classroom</th>
                                    <th class="text-truncate">Modulus</th>
                                    <th class="text-truncate">Test Date</th>
                                    <th class="text-truncate">Test</th>
                                    <th class="text-truncate">Percentage</th>
                                    <th class="text-truncate">Status</th>
                                </thead>
                                <tbody>
                                    <?php
                                        foreach ($requete_outdate as $requete):
                                    ?>
                                    <tr>
                                        <td class="text-truncate"><?= $requete["NOM_CLASSE"]?></td>
                                        <td class="text-truncate"><?= $requete["NOM_MODULE"]?></td>
                                        <td class="text-truncate"><?= $requete["DATE_DEV"] ?> </td>
                                        <td class="text-truncate"><a class="btn btn-dark text-truncate" href="<?= 'devoir_preview.php?title='.$requete['DEVOIR'] ?>" >Check the file</a></td>
                                        <td class="text-truncate"><?= $requete["POURCENTAGE"].' %' ?></td>
                                        <?php
                                                if ($requete["STATUT"] == 'Active') :
                                                    $actif = 'badge-success';
                                                else:
                                                    $actif = 'badge-danger';
                                                endif;
                                        ?>
                                        <td class="text-truncate">
                                            <span class="py-2 px-4 badge <?= $actif ?>"><?= $requete["STATUT"] ?></span>
                                        </td>
                                    </tr>
                                    <?php
                                        endforeach;
                                    ?>
                                    
                                </tbody>
                               
                            </table>
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