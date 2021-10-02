<?php
    include '../../../utilities/QueryBuilder.php';
    $title = 'Scholarship';
    $breadcrumb = 'Pre - Inscripted Owners';
    include('header.php');
    
    $obj = new QueryBuilder();

    $scholarship_owners = $obj->Select(
        'newetudiant e, classe c, filieres f, bourse b', 
        array(), 
        array("e.BOURSE"=>"b.ID_BOURSE","f.ID_FILIERE"=>"c.ID_FILIERE", "c.ID_CLASSE"=>"e.CLASSE")
    );

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
                <?php if(is_object($scholarship_owners)){?> 
                    <div id="toolbar">
                        <select class="form-control dt-tb">
                            <option value="">Export Basic</option>
                            <option value="all">Export All</option>
                            <option value="selected">Export Selected</option>
                        </select>
                    </div>
                </div>
                
                <table id="table" 
                        data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" 
                        data-show-pagination-switch="true"
                        data-show-refresh="true" data-key-events="true" data-show-toggle="true" data-resizable="false" data-cookie="true"
                        data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar">
                        <thead>
                            <tr>
                                <th data-field="action" >Action</th>
                                <th data-field="first_name" >First Name</th>
                                <th data-field="name" >Last Name</th>
                                <th data-field="gender" >Gender</th>
                                <th data-field="class" >Class</th>
                                <th data-field="field" >Field</th>
                                <th data-field="rate" >Rate</th>
                                <th data-field="total-amount" >School fees</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                        <?php 

                        
                       while($scholarship_owner = $scholarship_owners->fetch())
                        { ?>
                            <tr>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Button group">
                                        <button onclick="get_New_StudentId(<?= htmlspecialchars(json_encode( $scholarship_owner['ID_NEW_ETUDIANT'].'~'.$scholarship_owner['NOM'].'~'.$scholarship_owner['PRENOM'].'~'.$scholarship_owner['NOM_CLASSE'].'~'.$scholarship_owner['TAUX']))?>)"
                                                               type="button"
                                                               class="btn"
                                                                data-toggle="modal"
                                                                data-target="#update_new_Scolarship">
                                            <i class="fas fa-edit text-warning"></i>
                                        </button>
                                                        
                                </div>
                                </td>
                                <td><?= $scholarship_owner['PRENOM']?></td>
                                <td><?= $scholarship_owner['NOM']?></td>
                                <td><?= $scholarship_owner['SEXE']?></td>
                                <td><?= $scholarship_owner['NOM_CLASSE']?></td>
                                <td><?= $scholarship_owner['NOM_FILIERE']?></td>
                                <td><?= $scholarship_owner['TAUX']. '%';?></td>
                                <td><?= $scholarship_owner['MONTANT_SCOLARITE']?></td>
                                <td>2019-2020</td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                    <?php
                         }
                        else
                        {
                            echo "<h2 class='text-center'>No record found</h2>";
                        }?>

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
                                    
               
                        </div>
                </div>
            </div>
        </div>
<script type="text/javascript">
    function loads() {
            window.location.replace("scholarship_owners.php?page=scholarship_owners");
        }
</script>
<?php
include('../footer.php');
?>