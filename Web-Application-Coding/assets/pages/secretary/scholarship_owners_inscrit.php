<?php
    include '../../../utilities/QueryBuilder.php';
    $title = 'Scholarship';
    $breadcrumb = 'Owners';
    include('header.php');
    
    $obj = new QueryBuilder();
    $scholarship_owners_current = $obj->Requete('SELECT * FROM etudiant e, classe c, historique_bourse h, inscription i, filieres f, bourse b WHERE b.ID_BOURSE = h.ID_BOURSE AND f.ID_FILIERE = c.ID_FILIERE AND i.MATRICULE = e.MATRICULE AND e.MATRICULE = h.MATRICULE AND i.ID_ClASSE = c.ID_CLASSE AND i.ID_ANNEE="'.getAnnee(0)["ID_ANNEE"].'" ORDER BY h.ID_HISTORIQUE_BOURSE DESC');
    if(getAnnee(-1)){
        $scholarship_owners_last= $obj->Requete('SELECT * FROM etudiant e, classe c, historique_bourse h, inscription i, filieres f, bourse b WHERE b.ID_BOURSE = h.ID_BOURSE AND f.ID_FILIERE = c.ID_FILIERE AND i.MATRICULE = e.MATRICULE AND e.MATRICULE = h.MATRICULE AND i.ID_ClASSE = c.ID_CLASSE AND i.ID_ANNEE="'.getAnnee(-1)["ID_ANNEE"].'" ORDER BY h.ID_HISTORIQUE_BOURSE DESC');
        $scholarship_owners = fill_histor_brouse($scholarship_owners_current,$scholarship_owners_last);
    }
    else
    {
        $scholarship_owners = $scholarship_owners_current->fetchAll();
    }
?>

<div class="container-fluid" id="fenetre">
    <div class="row"> 
        <div class="col-lg-12">
            <div class="card">

                <div class="card-header">
                    <div class="row">
                        <div class="col-6">
                            <h4 class="text-bluesky">Scholarships Owners</h4>
                        </div>
                        <div class="col-6 text-right">
                            <a class="btn btn-dark" role="button" href="scholarship.php">List all scholarships</a>
                        </div>
                    </div>
                </div>

                <div class="card-body py-2">
                    
                    <?php 
                        if(!is_object($scholarship_owners))
                        {
                    ?>
                        <table id="table" class="table table-bordered table-hover table-responsive-lg table-responsive-md table-responsive-sm">
                            <thead>
                                <tr>
                                    <th class="text-truncate">First Name</th>
                                    <th class="text-truncate">Last Name</th>
                                    <th class="text-truncate">Gender</th>
                                    <th class="text-truncate">Class</th>
                                    <th class="text-truncate">Field</th>
                                    <th class="text-truncate">Rate</th>
                                    <th class="text-truncate">Rate Amount</th>
                                    <th class="text-truncate">Total Amount</th>
                                </tr>
                            </thead>
                                
                            <tbody id="scholar">
                                <?php
                                $i = 0;
                                while(  $i<count($scholarship_owners) and $scholarship_owner = $scholarship_owners[$i] )

                                    { 
                                        $i++;
                                ?>  
                                    <tr>
                                        <td class="text-truncate"><?= $scholarship_owner['PRENOM']?></td>
                                        <td class="text-truncate"><?= $scholarship_owner['NOM']?></td>
                                        <td class="text-truncate"><?= $scholarship_owner['SEXE']?></td>
                                        <td class="text-truncate"><?= $scholarship_owner['NOM_CLASSE']?></td>
                                        <td class="text-truncate"><?= $scholarship_owner['NOM_FILIERE']?></td>
                                        <td class="text-truncate"><?= $scholarship_owner['TAUX']. '%';?></td>
                                        <td class="text-truncate"><?=($scholarship_owner['MONTANT_SCOLARITE'] * (100 - $scholarship_owner['TAUX'])/100)?></td>
                                        <td class="text-truncate"><?= $scholarship_owner['MONTANT_SCOLARITE']?></td>
                                    </tr>
                                <?php 
                                    } 
                                ?>
                            </tbody>

                            <tfoot>
                                <tr>
                                    <th class="text-truncate">First Name</th>
                                    <th class="text-truncate">Last Name</th>
                                    <th class="text-truncate">Gender</th>
                                    <th class="text-truncate">Class</th>
                                    <th class="text-truncate">Field</th>
                                    <th class="text-truncate">Rate</th>
                                    <th class="text-truncate">Rate Amount</th>
                                    <th class="text-truncate">Total Amount</th>
                                </tr>
                            </tfoot>
                        </table>
                    <?php
                        }
                        else
                        {
                            echo "No record found";
                        }
                    ?>
                    <!-- Scholarship Update -->
                    <div class="modal fade" id="scholar_modal" data-backdrop="static"
                        data-keyboard="false" role="dialog" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">

                                <div class="modal-header bg-primary">
                                    <h5 class="modal-title text-white" id="student_data"></h5>
                                    <button type="button" class="close" data-dismiss="modal"
                                            aria-label="Close" onclick="loads()">
                                        <span aria-hidden="true" class="text-white">&times;</span>
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <div class="container-fluid ">
                                    <div id="return" class="text-center"></div>
                                        <form action="" method="post" id="scholar_content" class="mt-2">
                                            <div class="row">
                                            <input type="text" value="" id="hide_id" hidden>
                                                <!--student class -->
                                                <div class="col-6">
                                                    <label for="nom">
                                                        Class
                                                    </label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control"
                                                            name="<?= 'scholarship' . '$scholarship_owner[];' ?>"
                                                            id="scholar_classe"
                                                            value="" readonly>

                                                        <div class="input-group-append">
                                                            <span class=" input-group-text fas fa-school"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--  Student Scholarship -->
                                                <div class="col-6">
                                                    <label for="scholarship">
                                                        Scholarship
                                                    </label>
                                                    <div class="input-group">
                                                    <select  class="form-control"
                                                            name=""
                                                            id="taux">
                                                    </select>
                                                    <div class="input-group-append">
                                                        <span class=" input-group-text fas fa-percent"></span>
                                                    </div>
                                                    </div>
                                                </div>
                                                <!-- Motivation Area -->
                                                <div class="col-lg-6 p-3">
                                                        <label for="motivation" class="">Motivations: </label>
                                                        <textarea name="motivation" id="motivation" class="form-control" rows="10"  style="resize:none;" oninput="format(this.id)">
                                                            
                                                        </textarea>
                                                    </div>

                                                    <!-- Observation Area -->
                                                    <div class="col-lg-6 p-3">
                                                        <label for="observation" class="">Observations: </label>
                                                        <textarea name="observation" id="observation" class="form-control" rows="10"  style="resize:none;" oninput="format(this.id)">
                                                            
                                                        </textarea>
                                                    </div>
                                                <div class="col-lg-12 text-right mt-2">
                                                    <button class="btn btn-success w-25 "
                                                            name="update_scholarship" type="button" onclick="end_all_update()">Modify
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
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

    <script type="text/javascript"></script>
    <!-- End Scholarship Update -->
    <script>
        function loads() {
            window.location.replace("scholarship_owners_inscrit.php?page=scholarship_owners_inscrit");
        }
        $('#year').css('display', 'block');
    </script>
<?php
    include('../footer.php');
?>