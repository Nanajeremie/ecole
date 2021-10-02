<?php
    include '../../../utilities/QueryBuilder.php';
    $title = 'New Student Payments';
    $breadcrumb = 'First Paiement';
    include('header.php');

    $obj = new QueryBuilder();
    $filieres = $obj->Select('filieres', array());
    $all_new_student = $obj->Select('newetudiant', array(), array('STATUT' => 'enabled'));
?>
    <div class="container-fluid" id="fenetre">
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
                    <?php 
                        if (is_object($all_new_student)) 
                        {
                    ?>
                        <table class="table table-bordered table-hover table-responsive table-responsive-md table-responsive-lg" id="table">
                            <thead>
                                <tr>
                                    <th class="text-truncate">Meetings</th>
                                    <th class="text-truncate">First Name</th>
                                    <th class="text-truncate">Last Name</th>
                                    <th class="text-truncate">Gender</th>
                                    <th class="text-truncate">Birthday</th>
                                    <th class="text-truncate">CNIB</th>
                                    <th class="text-truncate">Recommendation</th>
                                    <th class="text-truncate">Coming School</th>
                                    <th class="text-truncate">Degree</th>
                                    <th class="text-truncate">Desired Domain</th>
                                    <th class="text-truncate">Father Name</th>
                                    <th class="text-truncate">Father Job</th>
                                    <th class="text-truncate">Mother Name</th>
                                    <th class="text-truncate">Mother Job</th>
                                    <th class="text-truncate">Email</th>
                                    <th class="text-truncate">Phone Number</th>
                                    <th class="text-truncate">Emergency Number</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                    while ($new_student = $all_new_student->fetch()) 
                                    {
                                        $class_data = $obj->Select('classe', array(), array('ID_CLASSE' => $new_student['CLASSE']))->fetch();
                                ?>
                                    <tr>
                                        <td class="text-truncate">
                                            <a href="pay_tuition.php?id=<?= $new_student['ID_NEW_ETUDIANT'] ?>" class="btn btn-primary text-truncate">Pay Tuition</a>
                                        </td>
                                        <td class="text-uppercase text-truncate"><?= $new_student['NOM'] ?></td>
                                        <td class="text-truncate"><?= $new_student['PRENOM'] ?></td>
                                        <td class="text-truncate"><?= $new_student['SEXE'] ?></td>
                                        <td class="text-truncate"><?= $new_student['DATE_NAISSANCE'] ?></td>
                                        <td class="text-truncate"><?= $new_student['CNIB'] ?></td>
                                        <td class="text-truncate"><?= $new_student['RECOMMENDATION'] ?></td>
                                        <td class="text-truncate"><?= $new_student['ECOLE_ORIGINE'] ?></td>
                                        <td class="text-truncate"><?= $new_student['DIPLOME'] ?></td>
                                        <td class="text-truncate"><?= $class_data['NOM_CLASSE'] ?></td>
                                        <td class="text-truncate"><?= $new_student['NOM_PERE'] ?></td>
                                        <td class="text-truncate"><?= $new_student['PERE_PROFESSION'] ?></td>
                                        <td class="text-truncate"><?= $new_student['NOM_MERE'] ?></td>
                                        <td class="text-truncate"><?= $new_student['MERE_PROFESSION'] ?></td>
                                        <td class="text-truncate"><?= $new_student['EMAIL'] ?></td>
                                        <td class="text-truncate"><?= $new_student['TELEPHONE'] ?></td>
                                        <td class="text-truncate"><?= $new_student['NUM_URGENCE'] ?></td>
                                    </tr>
                                <?php
                                    }
                                ?>
                            </tbody>

                            <tfoot>
                                <tr>
                                    <th class="text-truncate">Meetings</th>
                                    <th class="text-truncate">First Name</th>
                                    <th class="text-truncate">Last Name</th>
                                    <th class="text-truncate">Gender</th>
                                    <th class="text-truncate">Birthday</th>
                                    <th class="text-truncate">CNIB</th>
                                    <th class="text-truncate">Recommendation</th>
                                    <th class="text-truncate">Coming School</th>
                                    <th class="text-truncate">Degree</th>
                                    <th class="text-truncate">Desired Domain</th>
                                    <th class="text-truncate">Father Name</th>
                                    <th class="text-truncate">Father Job</th>
                                    <th class="text-truncate">Mother Name</th>
                                    <th class="text-truncate">Mother Job</th>
                                    <th class="text-truncate">Email</th>
                                    <th class="text-truncate">Phone Number</th>
                                    <th class="text-truncate">Emergency Number</th>
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
   
<?php
include('../footer.php');
?>