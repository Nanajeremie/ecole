<?php
    include '../../../utilities/QueryBuilder.php';
    $title = 'Reduction';
    $breadcrumb = 'All Reductions';

    $obj = new QueryBuilder();
    $all_bourse = $obj->Select('bourse', array(), array(), 'TAUX',1);
    
    if (isset($_POST['update_scholarship'])) {
        extract($_POST);
        $select_Scholar = $obj->Select('bourse',['TAUX'],array("TAUX"=>$_POST['scholarship' . $id_bourse]));
        if(!is_object($select_Scholar)){
            $q = $obj->Update('bourse', array('TAUX'), array($_POST['scholarship' . $id_bourse]), array('ID_BOURSE' => $id_bourse));
            if($q == 1)
            {
                $_SESSION['update_sch'] = 1;
            }
        }else{
            $_SESSION['update_sch'] = 2;
        }
        Refresh(0);
    }

    //when the user hits the 'create new reduction button' to create a reduction
    if (isset($_POST['submit_scholarship'])) 
    {
        extract($_POST);
        //insetion of the new reduction amount to the database if it doesnt already exist
        $q = $obj->Inscription('bourse', array('TAUX'), array($scholarship_rate), array('TAUX'=>$scholarship_rate));
        if($q == 1)
        {
            $_SESSION['new_sch'] = 1;
        }
        //reloading the page
        Refresh();
    }

    //if the user wants to delete the scholarship
    if (isset($_POST['delete_scholarship'])) 
    {
        extract($_POST);
        $e=$obj->Delete('bourse', array("ID_BOURSE"=>$del_scholarship));
        if($e == 1)
        {
            $_SESSION['del_sch'] = 1;
        }
        
        //reloading the page
        Refresh();
    }

    include('header.php');

    if(isset($_SESSION['new_sch']) && $_SESSION['new_sch'] == 1)
    {
        alert('success', 'Scholarship Created Successfully.');
        $_SESSION['new_sch'] = 0;
    }

    else if(isset($_SESSION['update_sch']) && $_SESSION['update_sch'] == 1)
    {
        alert('success', 'Scholarship Updated Successfully.');
        $_SESSION['update_sch'] = 0;
    }
    else if(isset($_SESSION['update_sch']) && $_SESSION['update_sch'] == 2)
    {
        alert('danger', 'This scholarship already existe.');
        $_SESSION['update_sch'] = 0;
    }

    else if(isset($_SESSION['del_sch']) && $_SESSION['del_sch'] == 1)
    {
        alert('danger', 'Scholarship Deleted Successfully.');
        $_SESSION['del_sch'] = 0;
    }
?>


    <div class="container-fluid" id="fenetre">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6">
                                <h4 class="text-bluesky">All Reduction's Rates</h4>
                            </div>
                            <div class="col-6 text-right">
                                <a class="btn btn-dark" data-toggle="modal" data-target="#new_scholarship"
                                   role="button">Add New Reduction</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body py-2">

                        <div id="toolbar">
                            <select class="form-control dt-tb">
                                <option value="">Export Basic</option>
                                <option value="all">Export All</option>
                                <option value="selected">Export Selected</option>
                            </select>
                        </div>
                        <script type="text/javascript">
                            sc = [];
                            //console.log(sc);
                        </script>
                        <?php

                        if (is_object($all_bourse)) {

                            ?>
                            <table id="table"
                                   data-toggle="table" data-pagination="true" data-search="true"
                                   data-show-columns="true"
                                   data-show-pagination-switch="true"
                                   data-show-refresh="true" data-key-events="true" data-show-toggle="true"
                                   data-resizable="false" data-cookie="true"
                                   data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true"
                                   data-toolbar="#toolbar">
                                <thead>
                                <tr>
                                    <th data-field="id">Action</th>
                                    <th data-field="rate" data-editable="true">Reduction Rate</th>
                                </tr>
                                </thead>
                                <tbody>


                                <?php
                                $update = 'update';
                                $delete = 'delete';
                                while ($the_bourse = $all_bourse->fetch()) {
                                    //  for($matricule=0 ; $matricule<10 ; $matricule++):
                                    $update .= $the_bourse['ID_BOURSE'];
                                    $delete .= $the_bourse['ID_BOURSE'];
                                    ?>
                                    <!-- script to append the value of the scholarship in js  -->
                                    <script type="text/javascript">
                                        sc.push(parseInt(<?= json_encode($the_bourse['TAUX']);?>));
                                        //console.log(sc);
                                    </script>
                                    <tr>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="Button group">
                                                <button onclick="getid_bourse(this.title)"
                                                        title="<?= $the_bourse['ID_BOURSE']; ?>" class="btn"
                                                        data-toggle="modal"
                                                        data-target="<?= '#' . $update ?>"><i
                                                            class="fas fa-edit text-warning"></i></button>
                                                <button onclick="getid_bourse_delete(this.title)"
                                                        title="<?= $the_bourse['ID_BOURSE']; ?>" class="btn" data-toggle="modal"
                                                        data-target="<?= '#' . $delete ?>"><i
                                                            class="fas fa-trash text-danger"></i></button>
                                            </div>
                                        </td>
                                        <td><?= $the_bourse['TAUX'] . ' %'; ?></td>
                                    </tr>

                                    <!-- Scholarship Update -->
                                    <div class="modal fade" id="<?= $update ?>" data-backdrop="static"
                                         data-keyboard="false" role="dialog" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">

                                                <div class="modal-header bg-primary">
                                                    <h5 class="modal-title text-white">Update Reduction
                                                        Information</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                        <span aria-hidden="true" class="text-white">&times;</span>
                                                    </button>
                                                </div>

                                                <div class="modal-body">
                                                    <div class="container-fluid my-5">
                                                        <form action="" method="post">
                                                            <!-- hidden input to get the id of the user -->
                                                            <input type="" name=""
                                                                   id="br<?= $the_bourse['ID_BOURSE']; ?>"
                                                                   value="<?= $the_bourse['ID_BOURSE']; ?>"
                                                                   style="display: none">
                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    <label for="<?= 'scholarship' . $the_bourse['ID_BOURSE']; ?>">Reduction
                                                                        Rate<span class="text-danger"> * </span></label>
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control"
                                                                               name="<?= 'scholarship' . $the_bourse['ID_BOURSE']; ?>"
                                                                               id="<?= 'scholarship' . $the_bourse['ID_BOURSE']; ?>"
                                                                               value="<?= $the_bourse['TAUX'] ?>" required>

                                                                        <div class="input-group-append">
                                                                            <span class=" input-group-text fas fa-percent"></span>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-lg-12 text-center my-5">
                                                                    <button class="btn btn-danger w-25 my-3"
                                                                            data-dismiss="modal" aria-label="Close">
                                                                        Reset
                                                                    </button>
                                                                    <button class="btn btn-success w-25 my-3"
                                                                            name="update_scholarship">Update
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


                                   
                                    <div class="modal fade" id="<?= $delete ?>" tabindex="-1" role="dialog"
                                         aria-hidden="true">
                                          <!-- Delete Scholarship Modal -->
                                    <form action="" method="post">
                                         <!-- hidden input to get the id of the user -->
                                        <input type="" name="" id="del_scholarship<?=$the_bourse['ID_BOURSE']; ?>"value="<?=$the_bourse['ID_BOURSE']; ?>" style="display: none">


                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Delete Reduction</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="container-fluid">
                                                        <span class="text-uppercase"> do You really want to delete the selected reduction??</span>
                                                    </div>

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-warning" data-dismiss="modal">
                                                        Reset
                                                    </button>
                                                   <!--  <input type="submit" class="btn btn-danger" name="delete_scholarship" value="Delete"/> -->
                                                   <!-- <input class="btn btn-danger" type="submit"
                                                    name="submit_scholarship" id="delete_scholarship<?= $the_bourse['ID_BOURSE'];?>" value="Delete"> -->

                                                    <button class="btn btn-danger"
                                                        name="delete_scholarship" id="delete_scholarship<?= $the_bourse['ID_BOURSE'];?>" type="submit">Delete
                                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        </form>
                                    </div>
                                
                                    <!-- End Delete Scholarship Modal -->
                                    <?php
                                }
                                ?>
                                </tbody>
                            </table>
                            <?php

                        }
                        //var_dump($all_bourse = $obj->Requete(strval($all_bourse)));
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Create Scholarship Field -->
                                <div class="modal fade" id="new_scholarship" data-backdrop="static"
                                     data-keyboard="false" role="dialog" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary">
                                                <h5 class="modal-title text-white">Add New Reduction Rate</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body my-3">
                                                <form action="" method="post">
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <label for="scholarship_rate">Reduction Rate<span
                                                                        class="text-danger"> * </span></label>
                                                            <div class="input-group">
                                                                <input type="number" class="form-control"
                                                                       name="scholarship_rate" id="scholarship_rate"
                                                                       placeholder="Enter Reduction Rate Here" required onkeyup="VerifyIfScholarship(this.value)">

                                                                <div class="input-group-append">
                                                                    <span class=" input-group-text fas fa-percent"></span>
                                                                </div>
                                                                <div  class="col-12 text-center text-danger">
                                                                    <span><i id="msg" style="display: none">NB: If the redution rate already exists, it wont be admitted !</i></span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-12 text-center my-5">
                                                            <input class="btn btn-primary w-50 disabled" type="submit"
                                                                   name="submit_scholarship" id="submit_scholarship"
                                                                   value="Create Reduction" >
                                                        </div>

                                                    </div>
                                                </form>

                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <!-- End Create New Scholarship -->
    
    
    
<?php
include('../footer.php');
?>
