<?php
    include '../../../utilities/QueryBuilder.php';
    $title = 'Meeting';
    $breadcrumb = 'All Meetings';

    $obj = new QueryBuilder();
    $status = array();
    $all_new_student = $obj->Select('newetudiant', array(), array('STATUT' => 'in_meeting'));

    $min_date = $obj->Requete('SELECT NOW()+INTERVAL 1 DAY as dat')->fetch();
    $min_date=$min_date['dat'];

    //update of the meeting
    if (isset($_POST['update_student_infos']))
    {
        $obj = new QueryBuilder();
        extract($_POST);
        //update of the planned meeting
        $requete = $obj->Update('entretien', array('DATE_ENTRETIEN','HEURE'), array($_POST['interview_date'.$id_user_meet_updt], $_POST['interview_hour'.$id_user_meet_updt]), array('ID_NEW_ETUDIANT'=>$id_user_meet_updt));
        
        if($requete == 1)
        {
            $_SESSION['update_meet'] = 1;
        }
        //we refresh the page in 3s
        Refresh();
    }

    //deletion of the planned meeting
    if (isset($_POST['delete_student']))
    {
        extract($_POST);
        //update of the status of the student to keep it for further changes
        $obj->Update('newetudiant', array('STATUT'), array('in_progress'), array('ID_NEW_ETUDIANT'=>$id_user_meet_del));
        //deletion of the planned meeting linked to the student
        
        $requete = $obj->Delete('entretien', array('ID_NEW_ETUDIANT'=>$id_user_meet_del));
        if($requete == 1)
        {
            $_SESSION['del_meet'] = 1;
        }
        //we refresh the page in 3s
        Refresh();
    }

    include('header.php');

    if(isset($_SESSION['update_meet']) && $_SESSION['update_meet'] == 1)
    {
        alert('success', 'Meeting Updated Successfully.');
        $_SESSION['update_meet'] = 0;
    }

    if(isset($_SESSION['del_meet']) && $_SESSION['del_meet'] == 1)
    {
        alert('danger', 'Meeting Deleted Successfully.');
        $_SESSION['del_meet'] = 0;
    }
?>
    <div class="container-fluid" id="fenetre">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">

                    <div class="card-header">
                        <div class="row">
                            <div class="col-6">
                                <h4 class="text-bluesky">All Plan Meeting</h4>
                            </div>
                            <div class="col-6 text-right">
                                <a class="btn btn-dark" role="button" href="plan_metting.php">Plan new meeting</a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body py-2">
                        <?php 
                            if (is_object($all_new_student)) {
                        ?>
                        <table id="table" class="table table-bordered table-hover table-responsive table-responsive-md table-responsive-lg">
                            <thead>
                                <tr>
                                    <th class="text-truncate">Action</th>
                                    <th class="text-truncate">Meeting Date</th>
                                    <th class="text-truncate">Meeting Time</th>
                                    <th class="text-truncate">First Name</th>
                                    <th class="text-truncate">Last Name</th>
                                    <th class="text-truncate">Gender</th>
                                    <th class="text-truncate">Birthday</th>
                                    <th class="text-truncate">Coming School</th>
                                    <th class="text-truncate">Degree</th>
                                    <th class="text-truncate">Desired Domain</th>
                                    <th class="text-truncate">Pre-inscription Date</th>
                                    <th class="text-truncate">Email</th>
                                    <th class="text-truncate">Phone Number</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                    while ($new_student = $all_new_student->fetch()) {
                                        //the class of the student
                                        $bgr='';
                                        $class_data = $obj->Select('classe', array(), array('ID_CLASSE' => $new_student['CLASSE']))->fetch();

                                        //******seletion of date and time of the interview
                                        $date_time = $obj->Select('entretien', array(), array('ID_NEW_ETUDIANT' => $new_student['ID_NEW_ETUDIANT']))->fetch();
                                        $date_time_interview = $date_time['DATE_ENTRETIEN'].' '.$date_time['HEURE'];
                                        $now = date('Y-m-d H:i:s');
                                    
                                        if($date_time_interview < $now)
                                        {
                                            $bgr='alert-danger';
                                        }

                                        $update = 'update';
                                        $delete = 'delete';

                                        $update .= $new_student['ID_NEW_ETUDIANT'];
                                        $delete .= $new_student['ID_NEW_ETUDIANT'];
                                ?>

                                <tr class="<?=$bgr;?>">
                                    <td class="text-truncate">
                                        <div class="btn-group" role="group" aria-label="Button group">
                                            <button onclick="rename_input_updt_plan_meeting(this.title)" title='<?= $new_student['ID_NEW_ETUDIANT'];?>' class="btn text-truncate" data-toggle="modal" data-target="<?= '#' . $update ?>">
                                                <i class="fas fa-edit text-warning" ></i></button>
                                            <button title='<?= $new_student['ID_NEW_ETUDIANT'];?>' onclick="rename_input_del_plan_meeting(this.title)" class="btn text-truncate" data-toggle="modal" data-target="<?= '#' . $delete ?>">
                                                <i class="fas fa-trash text-danger"></i></button>
                                        </div>
                                    </td>
                                    <td class="text-truncate"><?= $date_time['DATE_ENTRETIEN']; ?></td>
                                    <td class="text-truncate"><?= $date_time['HEURE']; ?></td>
                                    <td class="text-uppercase"><?= $new_student['NOM']; ?></td>
                                    <td class="text-truncate"><?= $new_student['PRENOM']; ?></td>
                                    <td class="text-truncate"><?= $new_student['SEXE']; ?></td>
                                    <td class="text-truncate"><?= $new_student['DATE_NAISSANCE']; ?></td>
                                    <td class="text-truncate"><?= $new_student['ECOLE_ORIGINE']; ?></td>
                                    <td class="text-truncate"><?= $new_student['DIPLOME']; ?></td>
                                    <td class="text-truncate"><?= $class_data['NOM_CLASSE']; ?></td>
                                    <td class="text-truncate"><?= $new_student['D_INSCRIPTION']; ?></td>
                                    <td class="text-truncate"><?= $new_student['EMAIL']; ?></td>
                                    <td class="text-truncate"><?= $new_student['TELEPHONE']; ?></td>
                                </tr>


                                <!--Update Modal -->
                                <div class="modal fade" id="<?= $update ?>" data-backdrop="static" data-keyboard="false" role="dialog"
                                    tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-xl" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary">
                                                <h5 class="modal-title text-white">Update Student Information</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true" class="text-white">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="container-fluid">
                                                    <form action="" method="post">
                                                        <!-- hidden input to get the id of the user -->
                                                        <input type="text" name=""
                                                            id="id_updt_meet_<?= $new_student['ID_NEW_ETUDIANT']; ?>"
                                                            value="<?= $new_student['ID_NEW_ETUDIANT']; ?>"
                                                            style="display: none">
                                                        <div class="row">
                                                            <!-- Personnal Information -->
                                                            <div class="col-lg-12 text-left">
                                                                <h5 class="text-bluesky mt-3">Personal Information</h5>
                                                                <hr class="bg-gradient-primary">
                                                            </div>

                                                            <!-- First Name -->
                                                            <div class="col-lg-4 p-4">
                                                                <label for="<?= 'inscription_nom' . $new_student['ID_NEW_ETUDIANT'] ?>" class="pb-3">First Name<span class="text-danger">   </span></label>
                                                                <div class="input-group">
                                                                    <input type="text"
                                                                        name="<?= 'inscription_nom' . $new_student['ID_NEW_ETUDIANT'] ?>"
                                                                        id="<?= 'inscription_nom' . $new_student['ID_NEW_ETUDIANT'] ?>"
                                                                        class="form-control" value="<?= $new_student['NOM'] ?>" readonly>
                                                                    <div class="input-group-append">
                                                                        <span class=" input-group-text fas fa-portrait  bg-light"></span>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Last Name -->
                                                            <div class="col-lg-4 p-4">
                                                                <label for="<?= 'inscription_prenom' . $new_student['ID_NEW_ETUDIANT'] ?>" class="pb-3">Last Name(s)<span class="text-danger">   </span></label>
                                                                <div class=" input-group">
                                                                    <input type="text"
                                                                        name="<?= 'inscription_prenom' . $new_student['ID_NEW_ETUDIANT'] ?>"
                                                                        id="<?= 'inscription_prenom' . $new_student['ID_NEW_ETUDIANT'] ?>"
                                                                        class="form-control" value="<?= $new_student['PRENOM'] ?>" readonly>
                                                                    <div class="input-group-append">
                                                                        <span class=" input-group-text fas fa-portrait  bg-light"></span>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Gender Area -->
                                                            <div class="col-lg-4 p-4">
                                                                <label for="<?= 'inscription_sexe' . $new_student['ID_NEW_ETUDIANT'] ?>" class="pb-3">Gender<span class="text-danger">   </span></label>
                                                                <div class="input-group">
                                                                    <select class="form-control" readonly
                                                                            name="<?= 'inscription_sexe' . $new_student['ID_NEW_ETUDIANT'] ?>"
                                                                            id="<?= 'inscription_sexe' . $new_student['ID_NEW_ETUDIANT'] ?>">
                                                                        <option><?= $new_student['SEXE']?></option>
                                                                    </select>
                                                                    <div class="input-group-append">
                                                                        <span class=" input-group-text fas fa-venus-mars  bg-light"></span>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Birthday Area-->
                                                            <div class="col-lg-4 p-4">
                                                                <label for="<?= 'inscription_dnaiss' . $new_student['ID_NEW_ETUDIANT'] ?>" class="pb-3">Birthday<span class="text-danger">   </span></label>
                                                                <div class="form-group">
                                                                    <input type="date"
                                                                        name="<?= 'inscription_dnaiss' . $new_student['ID_NEW_ETUDIANT'] ?>"
                                                                        id="<?= 'inscription_dnaiss' . $new_student['ID_NEW_ETUDIANT'] ?>"
                                                                        class="form-control" value="<?= $new_student['DATE_NAISSANCE'] ?>" readonly>
                                                                </div>
                                                            </div>

                                                            <!-- CNIB -->
                                                            <div class="col-lg-4 p-4">
                                                                <label for="<?= 'inscription_cnib' . $new_student['ID_NEW_ETUDIANT'] ?>" class="pb-3">CNIB<span class="text-danger">   </span></label>
                                                                <div class="input-group">
                                                                    <input type="text"
                                                                        name="<?= 'inscription_cnib' . $new_student['ID_NEW_ETUDIANT'] ?>"
                                                                        id="<?= 'inscription_cnib' . $new_student['ID_NEW_ETUDIANT'] ?>"
                                                                        class="form-control" value="<?= $new_student['CNIB'] ?>" readonly>
                                                                    <div class="input-group-append">
                                                                        <span class=" input-group-text fas fa-id-card  bg-light"></span>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Interview Area-->
                                                            <div class="col-lg-12 text-left">
                                                                <h5 class="text-bluesky mt-3"> Student's Interview Information</h5>
                                                                <hr class="bg-gradient-primary">
                                                            </div>

                                                            <div class="col-lg-4 p-4">
                                                                <label for="<?= 'interview_date' . $new_student['ID_NEW_ETUDIANT'] ?>" class="pb-3">Interview date<span class="text-danger"> * </span></label>
                                                                <div class="form-group">
                                                                    <input type="date" min="<?=substr($min_date, 0,10) ?>"
                                                                        name="<?= 'interview_date' . $new_student['ID_NEW_ETUDIANT'] ?>"
                                                                        id="<?= 'interview_date' . $new_student['ID_NEW_ETUDIANT'] ?>"
                                                                        class="form-control" value="<?= $date_time['DATE_ENTRETIEN'] ?>" required>
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-4 p-4">
                                                                <label for="<?= 'interview_hour' . $new_student['ID_NEW_ETUDIANT'] ?>" class="pb-3">Interview Hour <span class="text-danger"> * </span></label>
                                                                <div class="form-group">
                                                                    <input type="time" name="<?= 'interview_hour' . $new_student['ID_NEW_ETUDIANT'] ?>" id="<?= 'interview_hour' . $new_student['ID_NEW_ETUDIANT'] ?>" class="form-control" value="<?= $date_time['HEURE'] ?>" required>
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-12 text-center my-5">
                                                                <button class="btn btn-danger w-25 my-3" data-dismiss="modal" aria-label="Close">Reset </button>
                                                                <button class="btn btn-success w-25 my-3" name="update_student_infos">Plan </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Delete Modal -->
                                <div class="modal fade" id="<?= $delete ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                    <form action="" method = 'post'>
                                        <!-- hidden input to get the id of the user -->
                                        <input  type="text" name="" id="id_del_meet_<?= $new_student['ID_NEW_ETUDIANT']; ?>" value="<?= $new_student['ID_NEW_ETUDIANT']; ?>" style="display: none">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Delete Meeting</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>

                                                <div class="modal-body">
                                                    <div class="container-fluid">
                                                        <span class="text-uppercase"> do You really want to delete the student's meeting??<br> <?= 'Nom et Prenoms de l\'etudiant' ?></span>
                                                    </div>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-warning" data-dismiss="modal">Reset</button>
                                                    <button class="btn btn-danger" name="delete_student">Delete</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <?php
                                    };
                                ?>
                            </tbody>

                            <tfoot>
                                <tr>
                                    <th class="text-truncate">Action</th>
                                    <th class="text-truncate">Meeting Date</th>
                                    <th class="text-truncate">Meeting Time</th>
                                    <th class="text-truncate">First Name</th>
                                    <th class="text-truncate">Last Name</th>
                                    <th class="text-truncate">Gender</th>
                                    <th class="text-truncate">Birthday</th>
                                    <th class="text-truncate">Coming School</th>
                                    <th class="text-truncate">Degree</th>
                                    <th class="text-truncate">Desired Domain</th>
                                    <th class="text-truncate">Pre-inscription Date</th>
                                    <th class="text-truncate">Email</th>
                                    <th class="text-truncate">Phone Number</th>
                                </tr>
                            </tfoot>
                        </table>
                        <?php
                            } 
                            else 
                            {
                        ?>
                            <h2 class="text-center">No student for the moment!!!</h2>
                        <?php
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
    include('../footer.php');
?>