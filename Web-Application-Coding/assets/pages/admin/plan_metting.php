<?php
    include '../../../utilities/QueryBuilder.php';
    $title = 'Meetings';
    $breadcrumb = 'Plan Meeting';
    
    $obj = new QueryBuilder();
    $status = array();

    $min_date = $obj->Requete('SELECT NOW()+INTERVAL 1 DAY as dat')->fetch();
    $min_date=$min_date['dat'];

    //selection of all students with the status of 'in_progess'
    $all_new_student = $obj->Select('newetudiant', array(), array('STATUT' => 'in_progress'));
    //when the user hits the plan button
    if (isset($_POST['set_meeting'])) {
        $obj = new QueryBuilder();
        extract($_POST);
        //update of the status of the pre-subscrbed student
        $obj->Update('newetudiant', array('STATUT'), array('in_meeting'), array('ID_NEW_ETUDIANT' => $id_user_meet));
        //insertion of the planned meeting
        $requete = $obj->Insert('entretien', array('ID_NEW_ETUDIANT', 'DATE_ENTRETIEN', 'HEURE'), array($id_user_meet, $_POST['interview_date' . $id_user_meet], $_POST['interview_hour' . $id_user_meet]));
        if($requete == 1)
        {
            $_SESSION['new_meet'] = 1;
        }
        //we refresh the page
        Refresh();;
    }
    include('header.php');
    if(isset($_SESSION['new_meet']) && $_SESSION['new_meet'] == 1)
    {
        alert('success', 'Meeting Created Successfully.');
        $_SESSION['new_meet'] = 0;
    }
?>
    <div class="container-fluid" id="fenetre">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">

                    <div class="card-header">
                        <div class="row">
                            <div class="col-6">
                                <h4 class="text-bluesky">All Pre-Registered Student</h4>
                            </div>
                            <div class="col-6 text-right">
                                <a class="btn btn-dark" role="button" href="all_plan_meeting.php">List all meetings</a>
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

                        <?php 
                            if (is_object($all_new_student)) 
                            {
                        ?>
                            <table id="table" class="table table-bordered table-hover table-responsive table-responsive-md table-responsive-lg">
                                <thead>
                                    <tr>
                                        <th class="text-truncate">Action</th>
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
                                        $meeting = 'meeting';
                                        while ($new_student = $all_new_student->fetch()) 
                                        {
                                            $class_data = $obj->Select('classe', array(), array('ID_CLASSE' => $new_student['CLASSE']))->fetch();
                                            $meeting .= $new_student['ID_NEW_ETUDIANT'];
                                    ?>
                                        <tr>
                                            <td class="text-truncate">
                                                <div class="btn-group" role="group" aria-label="Button group">
                                                    <button class="btn btn-primary text-truncate" data-toggle="modal"
                                                            onclick="rename_input_plan_meeting(this.title)"
                                                            title="<?= $new_student['ID_NEW_ETUDIANT'] ?>"
                                                            data-target="<?= '#' . $meeting ?>">Plan meeting
                                                    </button>
                                                </div>
                                            </td>
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
                                        <div class="modal fade" id="<?= $meeting ?>" data-backdrop="static"
                                            data-keyboard="false" role="dialog" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-xl" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-primary">
                                                        <h5 class="modal-title text-white">Plan Meeting</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true" class="text-white">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="container-fluid">
                                                            <form action="" method="post">
                                                                <div class="row">
                                                                    <!-- Personnal Information -->
                                                                    <div class="col-lg-12 text-left">
                                                                        <h5 class="text-bluesky mt-3">Personal Information</h5>
                                                                        <hr class="bg-gradient-primary">
                                                                    </div>

                                                                    <!-- First Name -->
                                                                    <div class="col-lg-4 p-4">
                                                                        <label for="<?= 'inscription_nom' . $new_student['ID_NEW_ETUDIANT'] ?>"
                                                                            class="pb-3">First Name<span class="text-danger"> * </span></label>
                                                                        <div class="input-group">
                                                                            <input type="text"
                                                                                name="<?= 'inscription_nom' . $new_student['ID_NEW_ETUDIANT'] ?>"
                                                                                id="<?= 'inscription_nom' . $new_student['ID_NEW_ETUDIANT'] ?>"
                                                                                class="form-control"
                                                                                value="<?= $new_student['NOM']; ?>" readonly>
                                                                            <div class="input-group-append">
                                                                                <span class=" input-group-text fas fa-portrait  bg-light"></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Last Name -->
                                                                    <div class="col-lg-4 p-4">
                                                                        <label for="<?= 'inscription_prenom' . $new_student['ID_NEW_ETUDIANT'] ?>"
                                                                            class="pb-3">Last Name(s)<span
                                                                                    class="text-danger"> * </span></label>
                                                                        <div class=" input-group">
                                                                            <input type="text"
                                                                                name="<?= 'inscription_prenom' . $new_student['ID_NEW_ETUDIANT'] ?>"
                                                                                id="<?= 'inscription_prenom' . $new_student['ID_NEW_ETUDIANT'] ?>"
                                                                                class="form-control"
                                                                                value="<?= $new_student['PRENOM'] ?>"
                                                                                readonly>
                                                                            <div class="input-group-append">
                                                                                <span class=" input-group-text fas fa-portrait  bg-light"></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Gender Area -->
                                                                    <div class="col-lg-4 p-4">
                                                                        <label for="<?= 'inscription_sexe' . $new_student['ID_NEW_ETUDIANT'] ?>"
                                                                            class="pb-3">Gender<span
                                                                                    class="text-danger"> * </span></label>
                                                                        <div class="input-group">

                                                                            <select class="form-control" readonly

                                                                                    name="<?= 'inscription_sexe' . $new_student['ID_NEW_ETUDIANT'] ?>"
                                                                                    id="<?= 'inscription_sexe' . $new_student['ID_NEW_ETUDIANT'] ?>">
                                                                                <option>Female</option>
                                                                            </select>
                                                                            <div class="input-group-append">
                                                                                <span class=" input-group-text fas fa-venus-mars  bg-light"></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Birthday Area-->
                                                                    <div class="col-lg-4 p-4">
                                                                        <label for="<?= 'inscription_dnaiss' . $new_student['ID_NEW_ETUDIANT'] ?>"
                                                                            class="pb-3">Birthday<span
                                                                                    class="text-danger"> * </span></label>
                                                                        <div class="form-group">
                                                                            <input type="date"
                                                                                name="<?= 'inscription_dnaiss' . $new_student['ID_NEW_ETUDIANT'] ?>"
                                                                                id="<?= 'inscription_dnaiss' . $new_student['ID_NEW_ETUDIANT'] ?>"
                                                                                class="form-control"

                                                                                value="<?= $new_student['DATE_NAISSANCE'] ?>" readonly>

                                                                        </div>
                                                                    </div>

                                                                    <!-- CNIB -->
                                                                    <div class="col-lg-4 p-4">
                                                                        <label for="<?= 'inscription_cnib' . $new_student['ID_NEW_ETUDIANT'] ?>"
                                                                            class="pb-3">CNIB<span
                                                                                    class="text-danger"> * </span></label>
                                                                        <div class="input-group">
                                                                            <input type="text"
                                                                                name="<?= 'inscription_cnib' . $new_student['ID_NEW_ETUDIANT'] ?>"
                                                                                id="<?= 'inscription_cnib' . $new_student['ID_NEW_ETUDIANT'] ?>"
                                                                                class="form-control"
                                                                                value="<?= $new_student['CNIB'] ?>" readonly>
                                                                            <div class="input-group-append">
                                                                                <span class=" input-group-text fas fa-id-card  bg-light"></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Interview Area-->
                                                                    <div class="col-lg-12 text-left">
                                                                        <h5 class="text-bluesky mt-3"> Student's Interview
                                                                            Information</h5>
                                                                        <hr class="bg-gradient-primary">
                                                                    </div>

                                                                    <div class="col-lg-4 p-4">
                                                                        <label for="<?= 'interview_date' . $new_student['ID_NEW_ETUDIANT'] ?>"
                                                                            class="pb-3">Interview date<span
                                                                                    class="text-danger"> * </span></label>
                                                                        <div class="form-group">

                                                                            <input min="<?=substr($min_date, 0,10) ?>" type="date"

                                                                                name="<?= 'interview_date' . $new_student['ID_NEW_ETUDIANT'] ?>"
                                                                                id="<?= 'interview_date' . $new_student['ID_NEW_ETUDIANT'] ?>"
                                                                                class="form-control" value="" required>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-lg-4 p-4">
                                                                        <label for="<?= 'interview_hour' . $new_student['ID_NEW_ETUDIANT'] ?>"
                                                                            class="pb-3">Interview Hour<span
                                                                                    class="text-danger"> * </span></label>
                                                                        <div class="form-group">
                                                                            <input type="time"
                                                                                name="<?= 'interview_hour' . $new_student['ID_NEW_ETUDIANT'] ?>"
                                                                                id="<?= 'interview_hour' . $new_student['ID_NEW_ETUDIANT'] ?>"
                                                                                class="form-control" value="" required>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-lg-12 text-center my-5">
                                                                        <button class="btn btn-danger w-25 my-3"
                                                                                data-dismiss="modal" aria-label="Close">Reset
                                                                        </button>
                                                                        <button class="btn btn-success w-25 my-3"
                                                                                name="set_meeting">Plan
                                                                        </button>
                                                                    </div>
                                                                </div>

                                                                <input type="text" name=""
                                                                    id="id_plan_<?= $new_student['ID_NEW_ETUDIANT']; ?>"
                                                                    value="<?= $new_student['ID_NEW_ETUDIANT']; ?>"
                                                                    style="display: none">
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                        }
                                    ?>

                                </tbody>

                                <tfoot>
                                    <tr>
                                        <th class="text-truncate">Action</th>
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
                                <p class="text-center">No student for the moment!!!</p>
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