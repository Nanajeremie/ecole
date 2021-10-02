<?php
    include '../../../utilities/QueryBuilder.php';
    $title = 'List Registered Students';
    $breadcrumb = 'All students';

    $obj = new QueryBuilder();
    $status = array();
    $filieres = $obj->Select('filieres', $status);
    $all_new_student = $obj->Select('newetudiant', array(), array());

    //if the user hits the update button
    if (isset($_POST['update_student_infos'])) {
        $obj = new QueryBuilder();
        extract($_POST);
        $requete = $obj->Update('newetudiant', array('NOM', 'PRENOM', 'SEXE', 'DATE_NAISSANCE', 'EMAIL', 'CLASSE', 'CNIB', 'TELEPHONE', 'NUM_URGENCE', 'ECOLE_ORIGINE', 'DIPLOME', 'MOYENNE'), array($_POST['inscription_nom' . $id_user], $_POST['inscription_prenom' . $id_user], $_POST['inscription_sexe' . $id_user], $_POST['inscription_dnaiss' . $id_user], $_POST['inscription_email' . $id_user], $_POST['inscription_classe' . $id_user], $_POST['inscription_cnib' . $id_user], $_POST['inscription_tel' . $id_user], $_POST['inscription_tel_urg' . $id_user], $_POST['inscription_school' . $id_user], $_POST['inscription_bac' . $id_user], $_POST['inscription_ave' . $id_user]), array('ID_NEW_ETUDIANT' => $id_user));
        if($requete == 1)
        {
            $_SESSION['update_msg'] = 1;
            $_SESSION['nom'] = $_POST['inscription_nom' . $id_user]; 
            $_SESSION['prenom'] = $_POST['inscription_prenom' . $id_user];
        }

        Refresh();
    }
    //when the user clicks the delete button
    if (isset($_POST['delete_student'])) {
        extract($_POST);
        $requet = $obj->Delete('newetudiant', array('ID_NEW_ETUDIANT' => $id_user_del));
        if($requet == 1)
        {
            $_SESSION['del_msg'] = 1;
        }
        Refresh();
    }

    include('header.php');

    if(isset($_SESSION['update_msg']) && $_SESSION['update_msg'] == 1)
    {
        alert('success', $_SESSION['nom'].' '.$_SESSION['prenom'].' information updated successfully.');
        $_SESSION['update_msg'] = 0;
    }

    if(isset($_SESSION['del_msg']) && $_SESSION['del_msg'] == 1)
    {
        alert('danger','Student deleted successfully.');
        $_SESSION['del_msg'] = 0;
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
                                <a class="btn btn-dark" role="button" href="inscription.php">Add new student</a>
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
                                            <th class="text-truncate">Action</th>
                                            <th class="text-truncate">First Name</th>
                                            <th class="text-truncate">Last Name</th>
                                            <th class="text-truncate">Gender</th>
                                            <th class="text-truncate">Birthday</th>
                                            <th class="text-truncate">CNIB</th>
                                            <th class="text-truncate">Coming School</th>
                                            <th class="text-truncate">Degree</th>
                                            <th class="text-truncate">Average</th>
                                            <th class="text-truncate">Desired Domain</th>
                                            <th class="text-truncate">Email</th>
                                            <th class="text-truncate">Phone Number</th>
                                            <th class="text-truncate">Emergency Number</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php
                                            $update = 'update';
                                            $delete = 'delete';
                                            while ($new_student = $all_new_student->fetch()) 
                                            {
                                                $class_data = $obj->Select('classe', array(), array('ID_CLASSE' => $new_student['CLASSE']))->fetch();
                                                $update .= $new_student['ID_NEW_ETUDIANT'];
                                                $delete .= $new_student['ID_NEW_ETUDIANT'];
                                        ?>

                                                <tr>
                                                    <td>
                                                        <div class="btn-group" role="group" aria-label="Button group">
                                                            <button onclick="rename_input_update(this.title)" class="btn text-truncate" title="<?= $new_student['ID_NEW_ETUDIANT']; ?>" data-toggle="modal" data-target="<?= '#' . $update ?>">
                                                                <i class="fas fa-edit text-warning"></i>
                                                            </button>
                                                            <button onclick="rename_input_delete(this.title)" title="<?= $new_student['ID_NEW_ETUDIANT']; ?>" class="btn text-truncate" data-toggle="modal" data-target="<?= '#' . $delete ?>">
                                                                <i class="fas fa-trash text-danger"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                    <td class="text-uppercase text-truncate"><?= $new_student['NOM']; ?></td>
                                                    <td class="text-truncate"><?= $new_student['PRENOM']; ?></td>
                                                    <td class="text-truncate"><?= $new_student['SEXE']; ?></td>
                                                    <td class="text-truncate"><?= $new_student['DATE_NAISSANCE']; ?></td>
                                                    <td class="text-truncate"><?= $new_student['CNIB']; ?></td>
                                                    <td class="text-truncate"><?= $new_student['ECOLE_ORIGINE']; ?></td>
                                                    <td class="text-truncate"><?= $new_student['DIPLOME']; ?></td>
                                                    <td class="text-truncate"><?= $new_student['MOYENNE']; ?></td>
                                                    <td class="text-truncate"><?= $class_data['NOM_CLASSE']; ?></td>
                                                    <td class="text-truncate"><?= $new_student['EMAIL']; ?></td>
                                                    <td class="text-truncate"><?= $new_student['TELEPHONE']; ?></td>
                                                    <td class="text-truncate"><?= $new_student['NUM_URGENCE']; ?></td>
                                                </tr>

                                                <!--Update Modal -->
                                                <div class="modal fade" id="<?= $update ?>" data-backdrop="static" data-keyboard="false" role="dialog" tabindex="-1" aria-hidden="true">
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
                                                                        <input type="" name="id_user"
                                                                            id="id<?= $new_student['ID_NEW_ETUDIANT']; ?>"
                                                                            value="<?= $new_student['ID_NEW_ETUDIANT']; ?>"
                                                                            style="" hidden>
                                                                        <div class="row">
                                                                            <!-- Personnal Information  -->
                                                                            <div class="col-lg-12 text-left">
                                                                                <h5 class="text-bluesky mt-3">Personal Information</h5>
                                                                                <hr class="bg-gradient-primary">
                                                                            </div>

                                                                            <!-- First Name  -->
                                                                            <div class="col-lg-4 p-4">
                                                                                <label for="<?= 'inscription_nom' . $new_student['ID_NEW_ETUDIANT'] ?>" class="pb-3">First Name<span class="text-danger"> * </span></label>
                                                                                <div class="input-group">
                                                                                    <input type="text"
                                                                                        name="<?= 'inscription_nom' . $new_student['ID_NEW_ETUDIANT'] ?>"
                                                                                        id="<?= 'inscription_nom' . $new_student['ID_NEW_ETUDIANT'] ?>"
                                                                                        class="form-control"
                                                                                        value="<?= $new_student['NOM']; ?>" required>
                                                                                    <div class="input-group-append">
                                                                                        <span class=" input-group-text fas fa-portrait  bg-light"></span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>


                                                                            <!-- Last Name -->
                                                                            <div class="col-lg-4 p-4">
                                                                                <label for="<?= 'inscription_prenom' . $new_student['ID_NEW_ETUDIANT'] ?>" class="pb-3">Last Name(s)<span class="text-danger"> * </span></label>
                                                                                <div class=" input-group">
                                                                                    <input type="text"
                                                                                        name="<?= 'inscription_prenom' . $new_student['ID_NEW_ETUDIANT'] ?>"
                                                                                        id="<?= 'inscription_prenom' . $new_student['ID_NEW_ETUDIANT'] ?>"
                                                                                        class="form-control"
                                                                                        value="<?= $new_student['PRENOM']; ?>"
                                                                                        required>
                                                                                    <div class="input-group-append">
                                                                                        <span class=" input-group-text fas fa-portrait  bg-light"></span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <!-- Gender Area -->
                                                                            <div class="col-lg-4 p-4">
                                                                                <label for="<?= 'inscription_sexe' . $new_student['ID_NEW_ETUDIANT'] ?>" class="pb-3">Gender<span class="text-danger"> * </span></label>
                                                                                <div class="input-group">
                                                                                    <select class="form-control"
                                                                                            name="<?= 'inscription_sexe' . $new_student['ID_NEW_ETUDIANT'] ?>"
                                                                                            id="<?= 'inscription_sexe' . $new_student['ID_NEW_ETUDIANT'] ?>">
                                                                                        <option value="<?= $new_student['SEXE'] ?>"><?= $new_student['SEXE'] ?></option>
                                                                                        <?php if ($new_student['SEXE'] == 'Male') { ?>
                                                                                            <option value="Female">Female</option>
                                                                                        <?php } else { ?>
                                                                                            <option value="Male">Male</option>
                                                                                        <?php } ?>
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
                                                                                        value="<?= $new_student['DATE_NAISSANCE']; ?>"
                                                                                        required>
                                                                                </div>
                                                                            </div>

                                                                            <!-- CNIB -->
                                                                            <div class="col-lg-4 p-4">
                                                                                <label for="<?= 'inscription_cnib' . $new_student['ID_NEW_ETUDIANT'] ?>" class="pb-3">CNIB<span class="text-danger"> * </span></label>
                                                                                <div class="input-group">
                                                                                    <input type="text"
                                                                                        name="<?= 'inscription_cnib' . $new_student['ID_NEW_ETUDIANT'] ?>"
                                                                                        id="<?= 'inscription_cnib' . $new_student['ID_NEW_ETUDIANT'] ?>"
                                                                                        class="form-control"
                                                                                        value="<?= $new_student['CNIB']; ?>"
                                                                                        required>
                                                                                    <div class="input-group-append">
                                                                                        <span class=" input-group-text fas fa-id-card  bg-light"></span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <!-- Education Background -->
                                                                            <div class="col-lg-12 text-left mt-5">
                                                                                <h5 class="text-bluesky">Education Background</h5>
                                                                                <hr class="bg-gradient-primary">
                                                                            </div>

                                                                            <!-- Coming School -->
                                                                            <div class="col-lg-4 p-4">
                                                                                <label for="<?= 'inscription_school' . $new_student['ID_NEW_ETUDIANT'] ?>"
                                                                                    class="pb-3">Coming School<span
                                                                                            class="text-danger"> * </span></label>
                                                                                <div class="input-group">
                                                                                    <input type="text"
                                                                                        name="<?= 'inscription_school' . $new_student['ID_NEW_ETUDIANT'] ?>"
                                                                                        id="<?= 'inscription_school' . $new_student['ID_NEW_ETUDIANT'] ?>"
                                                                                        class="form-control"
                                                                                        value="<?= $new_student['ECOLE_ORIGINE']; ?>"
                                                                                        required>
                                                                                    <div class="input-group-append">
                                                                                        <span class=" input-group-text fas fa-school  bg-light"></span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <!-- Degree Area-->
                                                                            <div class="col-lg-4 p-4">
                                                                                <label for="<?= 'inscription_bac' . $new_student['ID_NEW_ETUDIANT'] ?>" class="pb-3">Degree<span class="text-danger"> * </span></label>
                                                                                <div class="input-group">
                                                                                    <input type="text"
                                                                                        name="<?= 'inscription_bac' . $new_student['ID_NEW_ETUDIANT'] ?>"
                                                                                        id="<?= 'inscription_bac' . $new_student['ID_NEW_ETUDIANT'] ?>"
                                                                                        class="form-control" value="<?= $new_student['DIPLOME']; ?>" required>
                                                                                    <div class="input-group-append">
                                                                                        <span class=" input-group-text fas fa-user-graduate bg-light"></span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <!-- Average Area -->
                                                                            <div class="col-lg-4 p-4">
                                                                                <label for="<?= 'inscription_ave' . $new_student['ID_NEW_ETUDIANT'] ?>"
                                                                                    class="pb-3">Average<span
                                                                                            class="text-danger"> * </span></label>
                                                                                <div class="input-group">
                                                                                    <input type="number"
                                                                                        name="<?= 'inscription_ave' . $new_student['ID_NEW_ETUDIANT'] ?>"
                                                                                        id="<?= 'inscription_ave' . $new_student['ID_NEW_ETUDIANT'] ?>"
                                                                                        class="form-control"
                                                                                        value="<?= $new_student['MOYENNE']; ?>"
                                                                                        required>
                                                                                    <div class="input-group-append">
                                                                                        <span class=" input-group-text fas fa-user-graduate bg-light"></span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>


                                                                            <!-- Desired Degree-->
                                                                            <div class="col-lg-4 p-4">
                                                                                <label for="<?= 'inscription_classe' . $new_student['ID_NEW_ETUDIANT'] ?>" class="pb-3">Interested Domain<span class="text-danger"> * </span></label>
                                                                                <div class="input-group">
                                                                                    <select class="form-control"
                                                                                            name="<?= 'inscription_classe' . $new_student['ID_NEW_ETUDIANT'] ?>"
                                                                                            id="<?= 'inscription_classe' . $new_student['ID_NEW_ETUDIANT'] ?>">
                                                                                        <option value="<?= $new_student['CLASSE']; ?>"><?= $class_data['NOM_CLASSE']; ?></option>
                                                                                        <?php while ($filiere = $filieres->fetch()) {
                                                                                            $status = array('ID_FILIERE' => $filiere['ID_FILIERE']);
                                                                                            $classes = $obj->Select('classe', array('NOM_CLASSE', 'ID_CLASSE', 'MONTANT_SCOLARITE'), $status);
                                                                                            ?>
                                                                                            <optgroup
                                                                                                    label="<?= $filiere['NOM_FILIERE'] ?>">
                                                                                                <?php
                                                                                                while ($classe = $classes->fetch()) {
                                                                                                    if ($classe['NOM_CLASSE'] != $class_data['NOM_CLASSE']) {
                                                                                                        ?>
                                                                                                        <option class="<?= $classe['MONTANT_SCOLARITE'] ?>"
                                                                                                                id="<?= $classe['ID_CLASSE'] ?>"
                                                                                                                value="<?= $classe['ID_CLASSE'] ?>"><?= $classe['NOM_CLASSE'] ?></option>
                                                                                                        <?php
                                                                                                    }
                                                                                                } ?>
                                                                                            </optgroup>
                                                                                        <?php } ?>
                                                                                    </select>
                                                                                    <div class="input-group-append">
                                                                                        <span class=" input-group-text fas fa-id-badge  bg-light"></span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>


                                                                            <!-- Contact Information -->
                                                                            <div class="col-lg-12 text-left mt-5">
                                                                                <h5 class="text-bluesky">Contacts</h5>
                                                                                <hr class="bg-gradient-primary">
                                                                            </div>

                                                                            <!-- Email Area --->

                                                                            <div class="col-lg-4 p-4">
                                                                                <label for="<?= 'inscription_email' . $new_student['ID_NEW_ETUDIANT'] ?>" class="pb-3">Email<span class="text-danger"> * </span></label>
                                                                                <div class="input-group">
                                                                                    <input type="email"
                                                                                        name="<?= 'inscription_email' . $new_student['ID_NEW_ETUDIANT'] ?>"
                                                                                        id="<?= 'inscription_email' . $new_student['ID_NEW_ETUDIANT'] ?>"
                                                                                        class="form-control" value=" <?= $new_student['EMAIL']; ?>" required>
                                                                                    <div class="input-group-append">
                                                                                        <span class=" input-group-text fas fa-envelope  bg-light"></span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <!-- Phone Number-->
                                                                            <div class="col-lg-4 p-4">
                                                                                <label for="<?= 'inscription_tel' . $new_student['ID_NEW_ETUDIANT'] ?>" class="pb-3">Phone Number<span class="text-danger"> * </span></label>
                                                                                <div class="input-group">
                                                                                    <input type="number"
                                                                                        name="<?= 'inscription_tel' . $new_student['ID_NEW_ETUDIANT'] ?>"
                                                                                        id="<?= 'inscription_tel' . $new_student['ID_NEW_ETUDIANT'] ?>"
                                                                                        class="form-control" value="<?= $new_student['TELEPHONE']; ?>" required>
                                                                                    <div class="input-group-append">
                                                                                        <span class=" input-group-text fas fa-mobile bg-light"></span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>


                                                                            <!-- Emergency Number  -->
                                                                            <div class="col-lg-4 p-4">
                                                                                <label for="<?= 'inscription_tel_urg' . $new_student['ID_NEW_ETUDIANT'] ?>" class="pb-3">Emergency Number<span class="text-danger"> * </span></label>
                                                                                <div class="input-group">
                                                                                    <input type="number"
                                                                                        name="<?= 'inscription_tel_urg' . $new_student['ID_NEW_ETUDIANT'] ?>"
                                                                                        id="<?= 'inscription_tel_urg' . $new_student['ID_NEW_ETUDIANT'] ?>"
                                                                                        class="form-control" value="<?= $new_student['NUM_URGENCE']; ?>" required>
                                                                                    <div class="input-group-append">
                                                                                        <span class=" input-group-text fas fa-phone-alt  bg-light"></span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-lg-12 text-center my-5">
                                                                                <button class="btn btn-danger w-25 my-3" data-dismiss="modal" aria-label="Close">Reset </button>
                                                                                <button class="btn btn-success w-25 my-3" name="update_student_infos">update </button>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Delete Modal-->
                                                <div class="modal fade" id="<?= $delete ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                                    <form action="" method="post">
                                                        <input type="" name="id_user_del" id="id_del_<?= $new_student['ID_NEW_ETUDIANT']; ?>"
                                                            value="<?= $new_student['ID_NEW_ETUDIANT']; ?>" style="display: none">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header bg-danger">
                                                                    <h5 class="modal-title">Delete Student</h5>
                                                                    <button class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>

                                                                <div class="modal-body">
                                                                    <div class="container-fluid">
                                                                        You really want to delete the student : <?= $new_student["NOM"]." ".$new_student["PRENOM"] ?>
                                                                    </div>
                                                                </div>

                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-warning" data-dismiss="modal">Reset </button>
                                                                    <button type="submit" class="btn btn-danger" name="delete_student">Delete </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
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
                                            <th class="text-truncate">CNIB</th>
                                            <th class="text-truncate">Coming School</th>
                                            <th class="text-truncate">Degree</th>
                                            <th class="text-truncate">Average</th>
                                            <th class="text-truncate">Desired Domain</th>
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
    </div>

<?php
    include('../footer.php');
?>