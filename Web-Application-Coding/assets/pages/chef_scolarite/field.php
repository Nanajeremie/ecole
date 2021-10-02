<?php 
    include('../../../utilities/QueryBuilder.php');
    $title = 'Departments';
    $breadcrumb = 'List';
    //suppression d'une Filiere

$obj = new QueryBuilder();
// Recuperation de la liste des departements 
    $listeDepart1 =$obj->Requete("SELECT * FROM  department d, filieres f WHERE f.ID_DEPARTEMENT = d.ID_DEPARTEMENT ORDER BY f.ID_FILIERE DESC");


    include('header.php');
?>
<script src="../../library/jquery/jquery.js" type="text/javascript"></script>
<div class="container-fluid" id="fenetre">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6">
                            <h6 class="text-bluesky">All Fields</h6>
                        </div>
                    </div>
                </div>
                <div class="card-body py-2">
                   
                    <table class="table table-bordered table-hover table-responsive-lg table-responsive-md table-responsive-sm" id="table">
                        <thead>
                            <tr>
                                <th class="text-truncate">Departement</th>
                                <th class="text-truncate">Field Name</th>
                                <th class="text-truncate">Description</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                                while($backListeField=$listeDepart1->fetch()){
                            ?>
                                <tr>
                                    <td class="text-truncate"><?= $backListeField['NOM_DEPARTEMENT'] ?></td>
                                    <td class="text-truncate"><?= $backListeField['NOM_FILIERE'] ?></td>
                                    <td><?= $backListeField['DESCRIPTION'] ?></td>
                                </tr>
                            <?php
                                  }
                            ?>
                        </tbody>

                         <tfoot>
                            <tr>
                                <th>Departement</th>
                                <th>Field Name</th>
                                <th>Description</th>
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
