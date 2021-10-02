<?php
    include '../../../utilities/QueryBuilder.php';
    $title = 'Scholarship';
    $breadcrumb = 'Pre - Inscripted Owners';
    
    
    $obj = new QueryBuilder();

    $scholarship_owners = $obj->Select(
        'newetudiant e, classe c, filieres f, bourse b', 
        array(), 
        array("e.BOURSE"=>"b.ID_BOURSE","f.ID_FILIERE"=>"c.ID_FILIERE", "c.ID_CLASSE"=>"e.CLASSE")
    );
    include('header.php');
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
                    </div>
                </div>

                <div class="card-body py-2">
                    <?php 
                        if(is_object($scholarship_owners))
                        {
                    ?> 
                
                    <table id="table" class="table table-bordered table-hover table-responsive table-responsive-md table-responsive-lg">
                        <thead>
                            <tr>
                                <th class="text-truncate">First Name</th>
                                <th class="text-truncate">Last Name</th>
                                <th class="text-truncate">Gender</th>
                                <th class="text-truncate">Class</th>
                                <th class="text-truncate">Field</th>
                                <th class="text-truncate">Rate</th>
                                <th class="text-truncate">School fees</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            <?php 
                                while($scholarship_owner = $scholarship_owners->fetch())
                                { 
                            ?>
                                <tr>
                                    <td class="text-truncate"><?= $scholarship_owner['PRENOM']?></td>
                                    <td class="text-truncate"><?= $scholarship_owner['NOM']?></td>
                                    <td class="text-truncate"><?= $scholarship_owner['SEXE']?></td>
                                    <td class="text-truncate"><?= $scholarship_owner['NOM_CLASSE']?></td>
                                    <td class="text-truncate"><?= $scholarship_owner['NOM_FILIERE']?></td>
                                    <td class="text-truncate"><?= $scholarship_owner['TAUX']. '%';?></td>
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
                                <th class="text-truncate">School fees</th>
                            </tr>
                        </tfoot>
                    </table>
                    
                    <?php
                         }
                        else
                        {
                            echo "<p class='text-center'>No record found</p>";
                        }
                    ?>
                </div>
            </div>
        </div>   
    </div>
</div>
</div>

<!-- Scholarship Update -->
<div class="modal fade" id="update_new_Scolarship" data-backdrop="static"
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
                    <form action="" method="post" class="my-5">
                        <!-- hidden input to get the id of the user -->
                        <input type="" name=""
                               id="br"
                               value=""
                               style="display: none">
                        <div class="row">
                           <input type="text" value="" id="hide_id" hidden> 
                            <!--student class -->
                            <div class="col">
                                <label for="nom">
                                    Class
                                </label>
                                <div class="input-group">
                                    <input type="text" class="form-control"
                                          id="student_class"
                                           name=""
                                           value="" readonly>

                                    <div class="input-group-append">
                                        <span class=" input-group-text fas fa-school"></span>
                                    </div>
                                </div>
                            </div>
                             <!--  Student Scholarship -->
                            <div class="col">
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
                            <div class="col-lg-12 text-center my-5">
                                <button class="btn btn-success w-25 my-3"
                                        name="update_scholarship" onclick="end_update()" type="button">Modify
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- End Scholarship Update -->
<script type="text/javascript">
    function loads() {
            window.location.replace("scholarship_owners.php?page=scholarship_owners");
        }
</script>

<?php
    include('../footer.php');
?>
