<?php
    include '../../../utilities/QueryBuilder.php';
    $title = 'Pre-Inscription';
    $breadcrumb = 'Add new student';
    include('header.php');

    //selection of the study field and classes
    $obj = new QueryBuilder();
    $status = array();
    $filieres = $obj->Select('filieres', $status);
    $max_date = $obj->Requete('SELECT NOW()-INTERVAL 16 YEAR as dat')->fetch();
    $max_date = $max_date['dat'];
    $error = array();
    //requet sur les types de bac
    $type_bac = $obj->Select('type_bac', array(), array());
    //when the user hits the register button, we insert the data in the factice table of students
    if (isset($_POST['submit_student_infos'])) {
        extract($_POST);
        if (is_object($obj->Select('newetudiant', array(), array('CNIB' => $inscription_cnib)))) {
            //erreur pour cnib
            $error['cnib_exist'] = 'This cnib already exist';
        }
        
        if ($obj->Requete('SELECT * FROM newetudiant WHERE EMAIL ="' . $inscription_email . '"')->rowCount() != 0) {
            //erreur pour
            $error['email_exist'] = 'This email address already exist';


        }

        if (empty($error)) {
            $columns = array('NOM', 'PRENOM', 'SEXE', 'DATE_NAISSANCE', 'EMAIL', 'CLASSE', 'CNIB', 'TELEPHONE', 'NUM_URGENCE', 'ECOLE_ORIGINE', 'DIPLOME', 'MOYENNE', 'D_INSCRIPTION');
            $values = array($inscription_nom, $inscription_prenom, $inscription_sexe, $inscription_dnaiss, $inscription_email, $inscription_classe, $inscription_cnib, $inscription_tel, $inscription_tel_urg, $inscription_school, $inscription_bac, $inscription_ave, 'NOW()');
            /*$inscription_classe, $submit_student_infos, , $inscription_total, $inscription_payment, $submit_student_fees*/
            $status = array('CNIB' => $inscription_cnib, 'EMAIL' => $inscription_email);
            $table = 'newetudiant';
            //creation of object for requests
            $obj = new QueryBuilder();

            $ne_student = $obj->Inscription($table, $columns, $values, $status);
            $inscription_nom = "";
            $inscription_prenom = "";
            $inscription_sexe = "";
            $inscription_dnaiss = "";
            $inscription_email = "";
            $inscription_classe = "";
            $inscription_cnib = "";
            $inscription_tel = "";
            $inscription_tel_urg = "";
            $inscription_school = "";
            $inscription_bac = "";
            $inscription_ave = "";

            alert('success', 'Student inserted successfully !!');
        }


    }

?>

    <div class="container-fluid" id="fenetre">
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow">

                    <div class="card-header">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a href="#inscription" class="nav-link active" id="inscription_tab" data-toggle="tab">Step : Pre - Inscription</a>
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
                                            <label for="inscription_nom" class="pb-3">Last Name<span
                                                        class="text-danger"> * </span></label>
                                            <div class="input-group">
                                                <input type="text" name="inscription_nom" id="inscription_nom"
                                                       class="form-control" placeholder="Enter First Name" required
                                                       value="<?php if (isset($inscription_nom)) {
                                                           echo $inscription_nom;
                                                       } ?>">
                                                <div class="input-group-append">
                                                    <span class=" input-group-text fas fa-portrait  bg-light"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Last Name -->
                                        <div class="col-lg-4 p-4">
                                            <label for="inscription_prenom" class="pb-3">First Name(s)<span
                                                        class="text-danger"> * </span></label>
                                            <div class=" input-group">
                                                <input type="text" name="inscription_prenom" id="inscription_prenom"
                                                       class="form-control" placeholder="Enter Last Name(s)" required
                                                       value="<?php if (isset($inscription_prenom)) {
                                                           echo $inscription_prenom;
                                                       } ?>">
                                                <div class="input-group-append">
                                                    <span class=" input-group-text fas fa-portrait  bg-light"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Gender Area -->
                                        <div class="col-lg-4 p-4">
                                            <label for="inscription_sexe" class="pb-3">Gender<span class="text-danger"> * </span></label>
                                            <div class="input-group">
                                                <select class="form-control" name="inscription_sexe"
                                                        id="inscription_sexe" required>
                                                    <option value="" disabled selected>Select Gender</option>
                                                    <option value="Male">Male</option>
                                                    <option value="Female">Female</option>
                                                </select>
                                                <div class="input-group-append">
                                                    <span class=" input-group-text fas fa-venus-mars  bg-light"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Birthday Area-->
                                        <div class="col-lg-4 p-4">
                                            <label for="inscription_dnaiss" class="pb-3">Birthday<span
                                                        class="text-danger"> * </span></label>
                                            <div class="form-group">
                                                <input max="<?= substr($max_date, 0, 10) ?>" type="date"
                                                       name="inscription_dnaiss" id="inscription_dnaiss"
                                                       class="form-control" placeholder="" required
                                                       value="<?php if (isset($inscription_dnaiss)) {
                                                           echo $inscription_dnaiss;
                                                       } ?>">
                                            </div>
                                        </div>

                                        <!-- CNIB -->
                                        <div class="col-lg-4 p-4">
                                            <label for="inscription_cnib" class="pb-3">CNIB<span
                                                        class="text-danger"> * </span></label>
                                            <div class="input-group">
                                                <input oninput="cacher(this.id)" type="text" name="inscription_cnib"
                                                       id="inscription_cnib"
                                                       class="form-control <?php if (isset($error['cnib_exist'])): ?> border border-danger <?php endif; ?>"
                                                       placeholder="" required
                                                       value="<?php if (isset($inscription_cnib)) {
                                                           echo $inscription_cnib;
                                                       } ?>">
                                                <div class="input-group-append">
                                                    <span class=" input-group-text fas fa-id-card  bg-light"></span>
                                                </div>
                                            </div>
                                            <?php
                                                if (isset($error['cnib_exist'])):
                                            ?>
                                                <div style="display: block" class="alert alert-danger mt-1" id="dinscription_cnib"><?= $error['cnib_exist'] ?></div>
                                            <?php
                                                endif;
                                            ?>
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
                                                <input type="text" name="inscription_school" id="inscription_school"
                                                       class="form-control" placeholder="Coming school" required
                                                       value="<?php if (isset($inscription_school)) {
                                                           echo $inscription_school;
                                                       } ?>">
                                                <div class="input-group-append">
                                                    <span class=" input-group-text fas fa-school  bg-light"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Degree Area -->
                                        <div class="col-lg-4 p-4">
                                            <label for="inscription_bac" class="pb-3">Degree<span class="text-danger"> * </span></label>
                                            <div class="input-group">
                                                <select class="form-control" name="inscription_bac" id="inscription_bac"
                                                        required>
                                                    <option value="" disabled selected>Select Degree</option>
                                                    <?php 
                                                        while ($bac = $type_bac->fetch()) 
                                                        {
                                                    ?>
                                                        <option value="<?= $bac['NOM_TYPE_BAC'] ?>"><?= $bac['NOM_TYPE_BAC'] ?></option>
                                                    <?php 
                                                        } 
                                                    ?>
                                                </select>
                                                <div class="input-group-append">
                                                    <span class=" input-group-text fas fa-user-graduate bg-light"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Average Area -->
                                        <div class="col-lg-4 p-4">
                                            <label for="inscription_ave" class="pb-3">Average<span class="text-danger"> * </span></label>
                                            <div class="input-group">
                                                <input type="number" name="inscription_ave" id="inscription_ave"
                                                       class="form-control" placeholder="ex. 15.00" required min=10
                                                       max=20 step=0.01 value="<?php if (isset($inscription_ave)) {
                                                    echo $inscription_ave;
                                                } ?>">
                                                <div class="input-group-append">
                                                    <span class=" input-group-text fas fa-user-graduate bg-light"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Desired Degree -->
                                        <div class="col-lg-4 p-4">
                                            <label for="inscription_classe" class="pb-3">Interested Domain<span class="text-danger"> * </span></label>
                                            <div class="input-group">
                                                <select class="form-control" name="inscription_classe"
                                                        id="inscription_classe" required>
                                                    <option value="" disabled>Select Domain</option>
                                                    <?php 
                                                        while ($filiere = $filieres->fetch()) 
                                                        {
                                                            $status = array('ID_FILIERE' => $filiere['ID_FILIERE']);
                                                            $classes = $obj->Select('classe', array('NOM_CLASSE', 'ID_CLASSE', 'MONTANT_SCOLARITE'), $status);
                                                    ?>
                                                        <optgroup label="<?= $filiere['NOM_FILIERE'] ?>">
                                                            <?php
                                                                while ($classe = $classes->fetch()) 
                                                                {
                                                            ?>
                                                                    <option class="<?= $classe['MONTANT_SCOLARITE'] ?>"
                                                                            id="<?= $classe['ID_CLASSE'] ?>"
                                                                            value="<?= $classe['ID_CLASSE'] ?>"><?= $classe['NOM_CLASSE'] ?></option>
                                                            <?php 
                                                                } 
                                                            ?>
                                                        </optgroup>
                                                    <?php 
                                                        } 
                                                    ?>
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

                                        <!-- Email Area -->
                                        <div class="col-lg-4 p-4">
                                            <label for="inscription_email" class="pb-3">Email<span class="text-danger"> * </span></label>
                                            <div class="input-group">
                                                <input oninput="cacher(this.id)" type="email" name="inscription_email"
                                                       id="inscription_email" class="form-control" placeholder="example@example.com"
                                                       required value="<?php if (isset($inscription_email)) {
                                                    echo $inscription_email;
                                                } ?>">
                                                <div class="input-group-append">
                                                    <span class=" input-group-text fas fa-envelope  bg-light"></span>
                                                </div>
                                            </div>

                                            <?php
                                                if (isset($error['email_exist'])):
                                            ?>
                                                <div style="display: block" class="alert alert-danger mt-1" id="dinscription_email"><?= $error['email_exist'] ?></div>
                                            <?php
                                                endif;
                                            ?>
                                        </div>

                                        <!-- Phone Number -->
                                        <div class="col-lg-4 p-4">
                                            <label for="inscription_tel" class="pb-3">Phone Number<span class="text-danger"> * </span></label>
                                            <div class="input-group">
                                                <input type="text" name="inscription_tel" id="inscription_tel"
                                                       class="form-control" placeholder="00 226 00 00 00 00" required
                                                       value="<?php if (isset($inscription_tel)) {
                                                           echo $inscription_tel;
                                                       } ?>">
                                                <div class="input-group-append">
                                                    <span class=" input-group-text fas fa-mobile bg-light"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Emergency Number -->
                                        <div class="col-lg-4 p-4">
                                            <label for="inscription_tel_urg" class="pb-3">Emergency Number<span class="text-danger"> * </span></label>
                                            <div class="input-group">
                                                <input type="text" name="inscription_tel_urg" id="inscription_tel_urg"
                                                       class="form-control" placeholder="00 226 00 00 00 00" required
                                                       value="<?php if (isset($inscription_tel_urg)) {
                                                           echo $inscription_tel_urg;
                                                       } ?>">
                                                <div class="input-group-append">
                                                    <span class=" input-group-text fas fa-phone-alt  bg-light"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-12 text-center my-5">
                                            <button class="btn btn-primary w-75 my-3" type="submit"
                                                    name="submit_student_infos" id="submit">Register
                                            </button>
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
    <!-- Script to disable the register button if the gender of the student is not seleted -->
    <script type="text/javascript">
        /* var submit = document.getElementById('submit');
         submit.addEventListener('click', disableregister());
         onload = disableregister()
         function disableregister()
         {

         }*/
        function cacher(id) {
            document.getElementById('d' + id).style.display = 'none';
        }
    </script>
<?php
    include('../footer.php');
?>