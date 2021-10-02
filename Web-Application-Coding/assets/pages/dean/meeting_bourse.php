<?php
    include '../../../utilities/QueryBuilder.php';
    $title = 'Meeting';
    $breadcrumb = 'Student';

    $obj = new QueryBuilder();
    $status = array();
    $the_new_student = $obj->Select('newetudiant', array(), array('ID_NEW_ETUDIANT' => $_GET['id']))->fetch();
    $class_data = $obj->Select('classe', array(), array('ID_CLASSE'=>$the_new_student['CLASSE']))->fetch();

    //*************meeting info****************/
    if (isset($_POST['submit_meeting_info'])) {
        $obj = new QueryBuilder();
        extract($_POST);
        $z = $obj->Update('newetudiant', array('BOURSE','MOTIVATION','OBSERVATION','STATUT'), array($inscription_scholarship,$motivation,$observation,'enabled'), array('ID_NEW_ETUDIANT'=>$_GET['id']));
        if($z == 1)
        {
            $_SESSION['upd_std'] = 1;
        }
        Redirect('allmeeting.php');
    }
    include('header.php');

    if(isset($_SESSION['upd_std']) && $_SESSION['upd_std'] == 1)
    {
        alert('success', 'Scholarship Attributed Successfully.');
        $_SESSION['upd_std'] = 0;
    }
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow">
                <div class="card-header">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a href="#inscription" class="nav-link active" id="inscription_tab" data-toggle="tab">Step 2 : Meeting</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="inscription">
                            <form action="" method="post">
                                <div class="row">
                                <!-- Personnal Information -->
                                    <div class="col-lg-12 text-left">
                                        <h5 class="text-bluesky mt-3">Personal Information</h5>
                                        <hr class="bg-gradient-primary">
                                    </div>

                                    <!-- First Name -->
                                    <div class="col-lg-4 p-4">
                                        <label for="inscription_nom" class="pb-3">First Name<span class="text-danger"> * </span></label>
                                        <div class="input-group">
                                            <input type="text" name="inscription_nom" id="inscription_nom" class="form-control" value="<?= $the_new_student['NOM']; ?>" readonly>
                                            <div class="input-group-append">
                                                <span class=" input-group-text fas fa-portrait  bg-light"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Last Name -->
                                    <div class="col-lg-4 p-4">
                                        <label for="inscription_prenom" class="pb-3">Last Name(s)<span class="text-danger"> * </span></label>
                                        <div class=" input-group">
                                            <input type="text" name="inscription_prenom" id="inscription_prenom" class="form-control"  value="<?=  $the_new_student['PRENOM'];?>" readonly>
                                            <div class="input-group-append">
                                                <span class=" input-group-text fas fa-portrait  bg-light"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Gender Area -->
                                    <div class="col-lg-4 p-4">
                                        <label for="inscription_sexe" class="pb-3">Gender<span class="text-danger"> * </span></label>
                                        <div class="input-group">
                                            <input type="text" name="inscription_sexe" id="inscription_sexe" class="form-control"  value="<?=  $the_new_student['SEXE']; ?>" readonly>
                                            <div class="input-group-append">
                                                <span class=" input-group-text fas fa-venus-mars  bg-light"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Birthday Area-->
                                    <div class="col-lg-4 p-4">
                                        <label for="inscription_dnaiss" class="pb-3">Birthday<span class="text-danger"> * </span></label>
                                        <div class="form-group">
                                            <input type="date" name="inscription_dnaiss" id="inscription_dnaiss" class="form-control" value="<?= $the_new_student['DATE_NAISSANCE']; ?>" readonly>
                                        </div>
                                    </div>

                                    <!-- CNIB -->
                                    <div class="col-lg-4 p-4">
                                        <label for="inscription_cnib" class="pb-3">CNIB<span class="text-danger"> * </span></label>
                                        <div class="input-group">
                                            <input type="text" name="inscription_cnib" id="inscription_cnib" class="form-control" value="<?= $the_new_student['CNIB']; ?>" readonly>
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
                                        <label for="inscription_school" class="pb-3">Coming School<span class="text-danger"> * </span></label>
                                        <div class="input-group">
                                            <input type="email" name="inscription_school" id="inscription_school" class="form-control" value="<?=$the_new_student['ECOLE_ORIGINE']; ?>" readonly>
                                            <div class="input-group-append">
                                                <span class=" input-group-text fas fa-school  bg-light"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Degree Area -->
                                    <div class="col-lg-4 p-4">
                                        <label for="inscription_bac" class="pb-3">Degree<span class="text-danger"> * </span></label>
                                        <div class="input-group">
                                            <input type="text" name="inscription_bac" id="inscription_bac" class="form-control" value="<?=$the_new_student['DIPLOME'];?>" readonly>
                                            <div class="input-group-append">
                                                <span class=" input-group-text fas fa-user-graduate bg-light"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Desired Degree -->
                                    <div class="col-lg-4 p-4">
                                        <label for="inscription_classe" class="pb-3">Interested Domain<span class="text-danger"> * </span></label>
                                        <div class="input-group">
                                            <input type="text" name="inscription_classe" id="inscription_classe" class="form-control" value="<?= $class_data['NOM_CLASSE']; ?>" readonly>
                                            <div class="input-group-append">
                                                <span class=" input-group-text fas fa-id-badge  bg-light"></span>
                                            </div>
                                        </div>
                                    </div>
                                <!-- Parents Information -->
                                    <div class="col-lg-12 text-left">
                                        <h5 class="text-bluesky mt-5">Parents Information</h5>
                                        <hr class="bg-gradient-primary">
                                    </div>

                                    <!-- Father Job -->
                                    <div class="col-lg-6 p-4">
                                        <label for="inscription_father_job" class="pb-3">Father Job<span class="text-danger"> * </span></label>
                                        <div class="input-group">
                                            <input type="text" name="inscription_father_job" id="inscription_father_job" class="form-control" value="<?= $the_new_student['PERE_PROFESSION']; ?>" readonly>
                                            <div class="input-group-append">
                                                <span class=" input-group-text fas fa-user bg-light"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Mother Job -->
                                    <div class="col-lg-6 p-4">
                                        <label for="inscription_moth_job" class="pb-3">Mother Job<span class="text-danger"> * </span></label>
                                        <div class="input-group">
                                            <input type="text" name="inscription_moth_job" id="inscription_moth_job" class="form-control" value="<?= $the_new_student['MERE_PROFESSION'];?>" readonly>
                                            <div class="input-group-append">
                                                <span class=" input-group-text fas fa-user bg-light"></span>
                                            </div>
                                        </div>
                                    </div>

                                <!-- Contact Information -->
                                    <div class="col-lg-12 text-left mt-5">
                                        <h5 class="text-bluesky">Contacts</h5>
                                        <hr class="bg-gradient-primary">
                                    </div>

                                    <!-- Email Area -->
                                    <div class="col-lg-4 p-4">
                                        <label for="inscription_email" class="pb-3">Email<span class="text-danger"> * </span></label>
                                        <div class="input-group">
                                            <input type="email" name="inscription_email" id="inscription_email" class="form-control" value="<?= $the_new_student['EMAIL']; ?>" readonly>
                                            <div class="input-group-append">
                                                <span class=" input-group-text fas fa-envelope  bg-light"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Phone Number -->
                                    <div class="col-lg-4 p-4">
                                        <label for="inscription_tel" class="pb-3">Phone Number<span class="text-danger"> * </span></label>
                                        <div class="input-group">
                                            <input type="number" name="inscription_tel" id="inscription_tel" class="form-control" value="<?= $the_new_student['TELEPHONE']; ?>" readonly>
                                            <div class="input-group-append">
                                                <span class=" input-group-text fas fa-mobile bg-light"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Emergency Number -->
                                    <div class="col-lg-4 p-4">
                                        <label for="inscription_tel_urg" class="pb-3">Emergency Number<span class="text-danger"> * </span></label>
                                        <div class="input-group">
                                            <input type="number" name="inscription_tel_urg" id="inscription_tel_urg" class="form-control" value="<?=$the_new_student['NUM_URGENCE']; ?>" readonly>
                                            <div class="input-group-append">
                                                <span class=" input-group-text fas fa-phone-alt  bg-light"></span>
                                            </div>
                                        </div>
                                    </div> 

                                <!-- Meeting Information -->
                                    <div class="col-lg-12 text-left mt-5">
                                        <h5 class="text-bluesky">Meeting Information</h5>
                                        <hr class="bg-gradient-primary">
                                    </div>
                            
                                    
                                    <div class="col-12"></div>
                                    <!-- Motivation Area -->
                                    <div class="col-lg-6 p-4">
                                        <label for="motivation" class="pb-3">Motivations</label>
                                        <textarea name="motivation" id="motivation" class="form-control" rows="10"  style="resize:none;" readonly="">
                                            <?= $the_new_student['MOTIVATION']; ?>
                                        </textarea>
                                    </div>

                                    <!-- Observation Area -->
                                    <div class="col-lg-6 p-4">
                                        <label for="observation" class="pb-3">Observations</label>
                                        <textarea name="observation" id="observation" class="form-control" rows="10"  style="resize:none;" readonly="">
                                            <?= $the_new_student['MOTIVATION']; ?>
                                        </textarea>
                                    </div>


                                    <!-- Scholarship rate Area -->
                                   <div class="col-lg-6 p-3">
                                        <label for="inscription_sexe" class="pb-3">Scholarship rate<span class="text-danger"> * </span></label>
                                        <div class="input-group">
                                            <select class="form-control" name="inscription_scholarship" id="inscription_scholarship" required>
                                                <option disabled selected>Select rate</option>
                                            <?php
                                            $all_bourse = $obj->Select('bourse', array(), array());
                                            if(is_object($all_bourse)){
                                                while ($the_bourse=$all_bourse->fetch()){
                                            ?>
                                                <option value="
                                                <?php
                                                    echo ($the_bourse['ID_BOURSE']);
                                                ?>">
                                                 <?php  echo ($the_bourse['TAUX'].' %');?> 
                                                 </option>
                                            <?php
                                                }
                                            }
                                            ?>
                                            </select>
                                            <div class="input-group-append">
                                                <span class=" input-group-text fas fa-venus-mars  bg-light"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 text-center my-5">
                                        <button class="btn btn-primary w-75 my-3" type="submit" name="submit_meeting_info">Validate Meeting</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
    include('footer.php');
?>