<?php
    include '../../../utilities/QueryBuilder.php';
    $title = 'Inscription';
    $breadcrumb = 'Primaire';

    $obj = new QueryBuilder();
    // $status = array();
    // $the_new_student = $obj->Select('newetudiant', array(), array('ID_NEW_ETUDIANT' => $_GET['id']))->fetch();
    // $class_data = $obj->Select('classe', array(), array('ID_CLASSE'=>$the_new_student['CLASSE']))->fetch();

    // //*************meeting info****************/
    // if (isset($_POST['submit_meeting_info'])) {
    //     $obj = new QueryBuilder();
    //     extract($_POST);
    //     $z=$obj->Update('newetudiant', array('NOM_PERE','PERE_PROFESSION','NOM_MERE','MERE_PROFESSION','MOTIVATION','OBSERVATION','STATUT','RECOMMENDATION'), array($inscription_fath_name,$inscription_father_job,$inscription_moth_name,$inscription_moth_job,$motivation,$observation,'bourse_waiting',$inscription_recommend), array('ID_NEW_ETUDIANT'=>$_GET['id']));
    //     if($z == 1)
    //     {
    //         $_SESSION['validate_meeting'] = 1;
    //     }
    //     Redirect('allmeeting.php');
    // }
    include('header.php');

   
?>

<div class="container-fluid" id="fenetre">
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow">
                <div class="card-header">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a href="#inscription" class="nav-link active" id="inscription_tab" data-toggle="tab">Secondaire</a>
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
                                        <h5 class="text-bluesky mt-3">Information personnelle</h5>
                                        <hr class="bg-gradient-primary">
                                    </div>
                                    <div class="col-12">
                                        <div class="row">
                                            <!-- First Name -->
                                            <div class="col-12 col-md-4 col-lg-3 p-4">
                                                <label for="inscription_nom" class="pb-3">Nom<span class="text-danger"> * </span></label>
                                                <div class="input-group">
                                                    <input type="text" name="inscription_nom" id="inscription_nom" class="form-control" value="" >
                                                    <div class="input-group-append">
                                                        <span class=" input-group-text fas fa-portrait  bg-light"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Last Name -->
                                            <div class="col-12 col-md-4 col-lg-3 p-4">
                                                <label for="inscription_prenom" class="pb-3">Prénom(s)<span class="text-danger"> * </span></label>
                                                <div class=" input-group">
                                                    <input type="text" name="inscription_prenom" id="inscription_prenom" class="form-control"  value="" >
                                                    <div class="input-group-append">
                                                        <span class=" input-group-text fas fa-portrait  bg-light"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Gender Area -->
                                            <div class="col-12 col-md-4 col-lg-3 p-4">
                                                <label for="inscription_sexe" class="pb-3">Genre<span class="text-danger"> * </span></label>
                                                <div class="input-group">
                                                    <input type="text" name="inscription_sexe" id="inscription_sexe" class="form-control"  value="" >
                                                    <div class="input-group-append">
                                                        <span class=" input-group-text fas fa-venus-mars  bg-light"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Birthday Area-->
                                            <div class=" col-12 col-md-4 col-lg-3 p-4">
                                                <label for="inscription_dnaiss" class="pb-3">Date de naissance<span class="text-danger"> * </span></label>
                                                <div class="form-group">
                                                    <input type="date" name="inscription_dnaiss" id="inscription_dnaiss" class="form-control" value="" >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                <!-- Parents Information -->
                                    <div class="col-lg-12 text-left">
                                        <h5 class="text-bluesky mt-2">Information des parents</h5>
                                        <hr class="bg-gradient-primary">
                                    </div>

                                    <div class="col-12">
                                        <div class="row">
                                             <!-- Parent Name -->
                                            <div class="col-12 col-md-6 p-4">
                                                <label for="inscription_fath_name" class="pb-3">Nom du père<span class="text-danger"> * </span></label>
                                                <div class="input-group">
                                                    <input type="text" name="inscription_fath_name" id="inscription_fath_name" placeholder="Ex : Yanogo Patrick" class="form-control" placeholder="" required>
                                                    <div class="input-group-append">
                                                        <span class=" input-group-text fas fa-user bg-light"></span>
                                                    </div>
                                                </div>
                                            </div> 
                                            <!-- Mother Name -->
                                            <div class="col-12 col-md-6 p-4">
                                                <label for="inscription_moth_name" class="pb-3">Nom de la mère<span class="text-danger"> * </span></label>
                                                <div class="input-group">
                                                    <input type="text" name="inscription_moth_name" id="inscription_moth_name" placeholder="Ex : Yanogo Patricia" class="form-control" placeholder="" required>
                                                    <div class="input-group-append">
                                                        <span class=" input-group-text fas fa-user bg-light"></span>
                                                    </div>
                                                </div>
                                            </div> 
                                            <!-- Father Job -->
                                            <div class="col-12 col-md-6 p-4">
                                                <label for="inscription_father_job" class="pb-3">Profession du père<span class="text-danger"> * </span></label>
                                                <div class="input-group">
                                                    <input type="text" name="inscription_father_job" id="inscription_father_job" placeholder="Ex : Fullstack Developer" class="form-control" value="" required>
                                                    <div class="input-group-append">
                                                        <span class=" input-group-text fas fa-user bg-light"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Mother Job -->
                                            <div class="col-12 col-md-6 p-4">
                                                <label for="inscription_moth_job" class="pb-3">Profession de la mère<span class="text-danger"> * </span></label>
                                                <div class="input-group">
                                                    <input type="text" name="inscription_moth_job" id="inscription_moth_job" placeholder="Ex : Lawyer" class="form-control" value="" required>
                                                    <div class="input-group-append">
                                                        <span class=" input-group-text fas fa-user bg-light"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                   
                                     <!-- Contact Information -->
                                     <div class="col-lg-12 text-left mt-5">
                                        <h5 class="text-bluesky">Contacts du tuteur</h5>
                                        <hr class="bg-gradient-primary">
                                    </div>

                                    <!-- Email Area -->
                                    <div class="col-md-4 p-4">
                                        <label for="inscription_email" class="pb-3">Email<span class="text-danger"> * </span></label>
                                        <div class="input-group">
                                            <input type="email" name="inscription_email" id="inscription_email" class="form-control" value="" >
                                            <div class="input-group-append">
                                                <span class=" input-group-text fas fa-envelope  bg-light"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Phone Number -->
                                    <div class="col-md-4 p-4">
                                        <label for="inscription_tel" class="pb-3">téléphone<span class="text-danger"> * </span></label>
                                        <div class="input-group">
                                            <input type="text" name="inscription_tel" id="inscription_tel" class="form-control" value="" >
                                            <div class="input-group-append">
                                                <span class=" input-group-text fas fa-mobile bg-light"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Emergency Number -->
                                    <div class="col-md-4 p-4">
                                        <label for="inscription_tel_urg" class="pb-3">Numéro d'urgence<span class="text-danger"> * </span></label>
                                        <div class="input-group">
                                            <input type="text" name="inscription_tel_urg" id="inscription_tel_urg" class="form-control" value="" >
                                            <div class="input-group-append">
                                                <span class=" input-group-text fas fa-phone-alt  bg-light"></span>
                                            </div>
                                        </div>
                                    </div> 

                                    <div class="col-lg-12 text-center my-5 ">
                                        <button class="btn btn-primary w-50 my-3" type="submit" name="submit_meeting_info">Valider l'inscription</button>
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
    include('../footer.php'); 
?>