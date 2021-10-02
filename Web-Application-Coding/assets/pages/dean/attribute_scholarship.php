<?php
include '../../../utilities/QueryBuilder.php';
$title = 'Meeting';
$breadcrumb = 'All Meetings';
include('header.php');

$obj = new QueryBuilder();
$status = array();
$all_new_student = $obj->Select('newetudiant', array(), array('STATUT' => 'bourse_waiting'));

?>
    <div class="container-fluid">
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
                        <table id="table"
                               data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true"
                               data-show-pagination-switch="true"
                               data-show-refresh="true" data-key-events="true" data-show-toggle="true"
                               data-resizable="false" data-cookie="true"
                               data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true"
                               data-toolbar="#toolbar">
                            <thead>
                            <tr>
                                <th data-field="action">Action</th>
                                <th data-field="date">Meeting Date</th>
                                <th data-field="time">Meeting Time</th>
                                <th data-field="field">First Name</th>
                                <th data-field="name">Last Name</th>
                                <th data-field="gender">Gender</th>
                                <th data-field="birth">Birthday</th>
                                <th data-field="recommendation">Recommendation</th>
                                <th data-field="school">Coming School</th>
                                <th data-field="degree">Degree</th>
                                <th data-field="domain">Desired Domain</th>
                                <th data-field="pre_inscription_date">Pre-inscription Date</th>
                                <th data-field="email">Email</th>
                                <th data-field="phone">Phone Number</th>
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
                                    <td><a href="meeting_bourse.php?id=<?= $new_student['ID_NEW_ETUDIANT'] ?>" class="btn btn-primary">Start Meeting</a>
                                    </td>
                                    <td><?= $date_time['DATE_ENTRETIEN']; ?></td>
                                    <td><?= $date_time['HEURE'];  ?></td>
                                    <td class="text-uppercase"><?=$new_student['NOM']; ?></td>
                                    <td><?= $new_student['PRENOM']; ?></td>
                                    <td><?= $new_student['SEXE']; ?></td>
                                    <td><?= $new_student['DATE_NAISSANCE'];?></td>
                                    <td><?=$new_student['RECOMMENDATION'];?></td>
                                    <td><?= $new_student['ECOLE_ORIGINE']; ?></td>
                                    <td><?= $new_student['DIPLOME']; ?></td>
                                    <td><?= $class_data['NOM_CLASSE'];?></td> 
                                    <td><?= $new_student['D_INSCRIPTION'];  ?></td>
                                    <td><?= $new_student['EMAIL']; ?></td>
                                    <td><?= $new_student['TELEPHONE']; ?></td>
                                </tr>
                            <?php
                            }
                            };
                            ?>
                            </tbody>
                        </table>
                        <?php
                        } else {
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
include('footer.php');
?>