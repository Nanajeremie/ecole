<?php
include '../../../utilities/QueryBuilder.php';
$title = 'Meeting';
$breadcrumb = 'All Meetings';
include('header.php');

$obj = new QueryBuilder();
$status = array();
$all_new_student = $obj->Select('newetudiant', array(), array('STATUT' => 'in_meeting'));

if(isset($_SESSION['validate_meeting']) && $_SESSION['validate_meeting'] == 1)
{
    alert('success','Student Interview Validated Successfully.');
    $_SESSION['validate_meeting'] = 0;
}
?>
    <div class="container-fluid" id="fenetre">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6">
                                <h4 class="text-bluesky">All Meetings</h4>
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
                    </div>

                <?php if (is_object($all_new_student)) {
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
                                while ($new_student=$all_new_student->fetch()){
                                    //the class of the student
                                    $class_data = $obj->Select('classe', array(), array('ID_CLASSE'=>$new_student['CLASSE']))->fetch();

                                    //******seletion of date and time of the interview
                                    $date_time=$obj->Select('entretien',array(),array('ID_NEW_ETUDIANT'=>$new_student['ID_NEW_ETUDIANT']))->fetch();
                                    $date_time_interview = $date_time['DATE_ENTRETIEN'].' '.$date_time['HEURE'];
                                    $now = date('Y-m-d H:i:s');

                                    if($date_time_interview > $now)
                                    {
                            ?>
                                    <tr>
                                        <td><a href="meeting.php?id=<?= $new_student['ID_NEW_ETUDIANT'] ?>" class="btn btn-primary">Start Meeting</a>
                                        </td>
                                        <td class="text-truncate"><?= $date_time['DATE_ENTRETIEN']; ?></td>
                                        <td class="text-truncate"><?= $date_time['HEURE'];  ?></td>
                                        <td class="text-uppercase"><?=$new_student['NOM']; ?></td>
                                        <td class="text-truncate"><?= $new_student['PRENOM']; ?></td>
                                        <td class="text-truncate"><?= $new_student['SEXE']; ?></td>
                                        <td class="text-truncate"><?= $new_student['DATE_NAISSANCE'];?></td>
                                        <td class="text-truncate"><?= $new_student['ECOLE_ORIGINE']; ?></td>
                                        <td class="text-truncate"><?= $new_student['DIPLOME']; ?></td>
                                        <td class="text-truncate"><?= $class_data['NOM_CLASSE'];?></td> 
                                        <td class="text-truncate"><?= $new_student['D_INSCRIPTION'];  ?></td>
                                        <td class="text-truncate"><?= $new_student['EMAIL']; ?></td>
                                        <td class="text-truncate"><?= $new_student['TELEPHONE']; ?></td>
                                    </tr>
                            <?php
                                    }
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
                        } else {
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