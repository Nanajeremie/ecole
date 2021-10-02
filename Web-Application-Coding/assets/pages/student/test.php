<?php
     $title = 'Tests Program';
     $breadcrumb = 'Tests';
 
     include('../../../utilities/QueryBuilder.php');
     include('header.php');
     
     $obj= new QueryBuilder();

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <!--  Titre de la liste des fichiers deja uploader  -->
            <div class="card">
                <div class="card-header">
                    <div class="row pd-3">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <h4 class="text-bluesky">Tests  Program</h4>
                        </div>
                    </div>
                </div>
                <div class="card-body py-2">
                    <table id="table" class="table table-bordered table-hover table-responsive-lg table-responsive-md table-responsive-sm">
                        <!-- En tete de la table -->
                        <thead>
                            <tr>
                                <th class="text-truncate">Classe</th>
                                <th class="text-truncate">Module</th>
                                <th class="text-truncate">Teacher Name</th>
                                <th class="text-truncate">Tests</th>
                                <th class="text-truncate">Tests Date</th>
                                <th class="text-truncate">Semester</th>
                                <th class="text-truncate"> Tests Status</th>
                            </tr>
                        </thead>
                        <!-- Contenue de la table -->
                        <tbody>
                                <?php
                                    $elements = $obj->Requete("SELECT * FROM devoirs d , module m , semestre s , classe c , professeur p , enseigner en WHERE d.ID_MODULE = m.ID_MODULE AND c.ID_CLASSE = m.ID_CLASSE AND p.ID_PROFESSEUR = en.ID_PROFESSEUR AND en.ID_MODULE = m.ID_MODULE AND m.ID_SEMESTRE = s.ID_SEMESTRE AND c.ID_CLASSE = (SELECT i.ID_CLASSE FROM inscription i WHERE i.ID_ANNEE = '".getAnnee(0)["ID_ANNEE"]."' AND i.MATRICULE = (SELECT et.MATRICULE FROM etudiant et WHERE et.ID_USER = '".$_SESSION["ID_USER"]."') AND i.ID_ANNEE = '".getAnnee(0)["ID_ANNEE"]."' ) AND d.ID_ANNEE = '".getAnnee(0)["ID_ANNEE"]."'");
                                    while($element = $elements->fetch()):
                                ?>
                                <tr>
                                    <td class="text-truncate"><?= $element["NOM_CLASSE"]?></td>
                                    <td class="text-truncate"><?= $element["NOM_MODULE"]?></td>
                                    <td class="text-truncate"><?= $element["NOM_PROF"]?></td>
                                    <td class="text-truncate">
                                        <?php
                                            if($element["STATUT"] == 'Active'):
                                            
                                        ?>
                                            <a id="devoir" role="button" class="btn btn-primary btn-sm btn-md btn-lg text-truncate" href="<?= 'viewtext.php?title='.$element['DEVOIR'] ?>">Chech The test</a>
                                        <?php
                                            else:  
                                                echo"Pending test";
                                            endif;
                                        ?>
                                    </td>

                                    <td class="text-truncate"><?= $element["DATE_DEV"] ?> </td>
                                    <td class="text-truncate"><?= $element["NOM_SEMESTRE"]?></td>
                                    <td class="text-truncate">
                                        <?php
                                            if($element["STATUT"] == "Active"):
                                                $actif = ' badge-success';   
                                        ?>
                                                <span class="py-2 px-4 badge <?= $actif ?>">Passed Test</span>
                                        <?php
                                            else: 
                                                $actif = ' badge-warning';
                                        ?>
                                                <span class="py-2 px-4 badge <?= $actif ?>">Upcoming Test</span>
                                        <?php
                                            endif;
                                        ?> 
                                    </td>
                                </tr>
                                <?php
                                    endwhile;
                                ?>
                                
                        </tbody>

                        <!-- Pied de la table -->
                        <tfoot>
                            <tr>
                                <th data-file='classe'>Classe</th>
                                <th data-field="module">Module</th>
                                <th data-field="name">Teacher Name</th>
                                <th data-field="devoirs">Test</th>
                                <th data-field="date">Test Date</th>
                                <th data-field="semestre">Semester</th>
                                <th data-field="status">Tests Stattus</th>
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

<script type="text/javascript"> document.getElementById('year').style.display='block';</script>
