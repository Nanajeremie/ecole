<?php
    include '../../../utilities/QueryBuilder.php';
    $title = 'New Student Payments';
    $breadcrumb = 'First Paiement';
    include('header.php');

    $obj = new QueryBuilder();
    $filieres = $obj->Select('filieres', array());
    $all_new_student = $obj->Select('newetudiant', array(), array('STATUT' => 'enabled'));
?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6">
                                <h4 class="text-bluesky">New Students</h4>
                            </div>
                            <div class="col-6 text-right">
                                <a class="btn btn-dark" role="button" href="old-student-tuition.php">Old Student
                                    Payment</a>
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
                    <?php 
                        if (is_object($all_new_student)) 
                        {
                    ?>
                        <table id="table" data-toggle="table" data-show-columns="true" data-show-export="true" data-toolbar="#toolbar">
                            <thead>
                                <tr>
                                    <th data-field="id">Meetings</th>
                                    <th data-field="field">First Name</th>
                                    <th data-field="name">Last Name</th>
                                    <th data-field="gender">Gender</th>
                                    <th data-field="birth">Birthday</th>
                                    <th data-field="cnib">CNIB</th>
                                    <th data-field="recommendation">Recommendation</th>
                                    <th data-field="school">Coming School</th>
                                    <th data-field="degree">Degree</th>
                                    <th data-field="domain">Desired Domain</th>
                                    <th data-field="father">Father Name</th>
                                    <th data-field="father_job">Father Job</th>
                                    <th data-field="mother">Mother Name</th>
                                    <th data-field="mother_job">Mother Job</th>
                                    <th data-field="email">Email</th>
                                    <th data-field="phone">Phone Number</th>
                                    <th data-field="emergency_number">Emergency Number</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                    while ($new_student = $all_new_student->fetch()) {
                                        $class_data = $obj->Select('classe', array(), array('ID_CLASSE' => $new_student['CLASSE']))->fetch();
                                ?>
                                        <tr>
                                            <td><a href="pay_tuition.php?id=<?= $new_student['ID_NEW_ETUDIANT'] ?>" class="btn btn-primary">Pay Tuition</a></td>
                                            <td class="text-uppercase"><?= $new_student['NOM'] ?></td>
                                            <td><?= $new_student['PRENOM'] ?></td>
                                            <td><?= $new_student['SEXE'] ?></td>
                                            <td><?= $new_student['DATE_NAISSANCE'] ?></td>
                                            <td><?= $new_student['CNIB'] ?></td>
                                            <td><?= $new_student['RECOMMENDATION'] ?></td>
                                            <td><?= $new_student['ECOLE_ORIGINE'] ?></td>
                                            <td><?= $new_student['DIPLOME'] ?></td>
                                            <td><?= $class_data['NOM_CLASSE'] ?></td>
                                            <td><?= $new_student['NOM_PERE'] ?></td>
                                            <td><?= $new_student['PERE_PROFESSION'] ?></td>
                                            <td><?= $new_student['NOM_MERE'] ?></td>
                                            <td><?= $new_student['MERE_PROFESSION'] ?></td>
                                            <td><?= $new_student['EMAIL'] ?></td>
                                            <td><?= $new_student['TELEPHONE'] ?></td>
                                            <td><?= $new_student['NUM_URGENCE'] ?></td>
                                        </tr>
                                <?php
                                    }
                                ?>
                            </tbody>

                            <tfoot>
                                <tr>
                                    <th data-field="id">Meetings</th>
                                    <th data-field="field">First Name</th>
                                    <th data-field="name">Last Name</th>
                                    <th data-field="gender">Gender</th>
                                    <th data-field="birth">Birthday</th>
                                    <th data-field="cnib">CNIB</th>
                                    <th data-field="recommendation">Recommendation</th>
                                    <th data-field="school">Coming School</th>
                                    <th data-field="degree">Degree</th>
                                    <th data-field="domain">Desired Domain</th>
                                    <th data-field="father">Father Name</th>
                                    <th data-field="father_job">Father Job</th>
                                    <th data-field="mother">Mother Name</th>
                                    <th data-field="mother_job">Mother Job</th>
                                    <th data-field="email">Email</th>
                                    <th data-field="phone">Phone Number</th>
                                    <th data-field="emergency_number">Emergency Number</th>
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
include('footer.php');
?>