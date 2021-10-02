<?php
include('../../../utilities/QueryBuilder.php');
$obj = new QueryBuilder();
$title = 'Classes List';
$breadcrumb = 'All classes';
include('header.php');

// Recuperation de la liste des fillieres
$listeFilieres = $obj->Requete("SELECT * FROM filieres");
$listeFiliere = $obj->Requete("SELECT * FROM classe c, department d, filieres f WHERE c.ID_FILIERE = f.ID_FILIERE AND f.ID_DEPARTEMENT = d.ID_DEPARTEMENT ORDER BY c.ID_CLASSE DESC");


?>
    <div class="container-fluid" id="fenetre">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6">
                                <h4 class="text-bluesky">All classes</h4>
                            </div>
                        </div>
                    </div>
                    <div class="card-body py-2">

                        <table class="table table-bordered table-hover table-responsive-lg table-responsive-md table-responsive-sm" id="table">
                            <thead>
                                <tr>
                                    <th class="text-truncate">Department</th>
                                    <th class="text-truncate">Field</th>
                                    <th class="text-truncate">Classe name</th>
                                    <th class="text-truncate">School Fees</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                    while ($backListe = $listeFiliere->fetch()) 
                                    {
                                ?>
                                    <tr> 
                                        <td class="text-truncate"><?= $backListe['NOM_DEPARTEMENT'] ?></td>
                                        <td class="text-truncate"><?= $backListe['NOM_FILIERE'] ?></td>
                                        <td class="text-truncate"><?= $backListe['NOM_CLASSE'] ?></td>
                                        <td class="text-truncate"><?= $backListe['MONTANT_SCOLARITE'] ?> Fcfa</td>
                                    </tr>
                                <?php 
                                    } 
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Department</th>
                                    <th>Field/Department</th>
                                    <th>Classe name</th>
                                    <th>School Fees</th>
                                </tr>
                            </tfoot>

                        </table>
                    </div>
                </div>
            </div>
 
        </div>
    </div>
<?php
include('../footer.php');
?>