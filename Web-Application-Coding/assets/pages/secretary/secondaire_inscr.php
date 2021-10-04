<?php
    include '../../../utilities/QueryBuilder.php';
    include('../functions.php');
    $title = 'Inscription';
    $breadcrumb = 'Secondaire';
    $obj = new QueryBuilder();

    // Recuperation de l'annee scolaire
    $schoolYear = $obj->Requete("SELECT ID_ANNEE, YEAR(DATE_INI) as yearStart FROM annee_scolaire");
    
    // Selection des classes du secondaire
     $listeClasses = $obj->Requete("SELECT * FROM classe c, niveau n  WHERE n.ID_NIVEAU = c.ID_NIVEAU AND n.NOM_NIVEAU = 'Secondaire' ORDER BY c.ID_CLASSE DESC");
        
     // insertion d'un eleve du secodaire 
    if (isset($_POST['submit_meeting_info'])) {
        extract($_POST);
        // Netoyage des donnees des champs
        $inscription_nom = filtreInput($inscription_nom);
        $inscription_prenom = filtreInput($inscription_prenom);
        $inscription_class = filtreInput($inscription_class);
        $inscription_sexe = filtreInput($inscription_sexe);
        $inscription_cnib = filtreInput($inscription_cnib);
        $inscription_tel = filtreInput($inscription_tel);
        $inscription_email = filtreInput($inscription_email);
        $inscription_tel_urg = filtreInput($inscription_tel_urg);
        $inscription_fath_name = filtreInput($inscription_fath_name);
        $inscription_father_job = filtreInput($inscription_father_job);
        $inscription_tel_urg = filtreInput($inscription_moth_job);
        $inscription_fath_name = filtreInput($inscription_moth_name);
        $matricule = MatriculAttrib() ;

        // on verifie si l'eleve n'est pas deja enregistre
        $checkStudent = $obj->Requete("SELECT * FROM etudiant e, inscription i WHERE e.MATRICULE = i.MATRICULE AND e.NOM='".$inscription_nom."' AND e.PRENOM='".$inscription_prenom."' AND i.ID_CLASSE='".$inscription_class."' AND DATE_NAISSANCE='". $inscription_dnaiss."'");
        if($checkStudent->rowCount()>=1){
            $error=1;
        }else{
            // On enregistre l'eleve
            $requete = $obj->Insert('etudiant', array('MATRICULE','PRENOM','NOM', 'SEXE', 'DATE_NAISSANCE','CNIB','TELEPHONE','EMAIL','NUM_URGENCE', 'NOM_PERE', 'PROFESSION_PERE', 'NOM_MERE','PROFESSION_MERE'), array($matricule,$inscription_prenom,$inscription_nom, $inscription_sexe, $inscription_dnaiss,$inscription_cnib,$inscription_tel,$inscription_email,$inscription_tel_urg,$inscription_fath_name,$inscription_father_job,$inscription_moth_job,$inscription_moth_name));
            if($requete == 1)
            {
                // Si l'enregistrement a est bien effectuee on effectue l'inscription
                $subs_new_student = $obj->Insert('inscription', array('ID_BOURSE', 'MATRICULE', 'ID_CLASSE', 'DATE_INSCRIPTION','ID_ANNEE'), array(1, $matricule, $inscription_class, 'NOW()',$inscription_year));
                // si l'incription est bien realisee, on prepare le paiement de son scolarite
                if($subs_new_student==1){
                    // On recupere le montant de son scolarite
                    $getStdAmount = $obj->Requete("SELECT * FROM classe c, inscription i,niveau n, annee_scolaire a WHERE n.ID_NIVEAU=c.ID_NIVEAU AND c.ID_CLASSE = i.ID_CLASSE AND a.ID_ANNEE = i.ID_ANNEE AND i.ID_CLASSE ='".$inscription_class."' AND i.ID_ANNEE = '".$inscription_year."' AND i.MATRICULE='".$matricule."'");
                    if($catchStdAmount = $getStdAmount->fetch()){
                        $prepareStdPay = $obj->Insert('scolarite',array('ID_INSCRIPTION','MONTANT_TOTAL','MONTANT_PAYE','DATE_LIMITE'),array($catchStdAmount['ID_INSCRIPTION'],$catchStdAmount['MONTANT_SCOLARITE'],0,$catchStdAmount['FIN_VERSEMENT_3']));
                        if( $prepareStdPay==1){
                            $_SESSION['update_msg'] = 1;
                            $_SESSION['nom'] = $_POST['inscription_nom']; 
                            $_SESSION['prenom'] = $_POST['inscription_prenom'];
                            Refresh();
                        }
                    }
                }
                
            }
        }
    }


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
                            <div class="col-12">
                                <div class="row justify-content-center">
                                    <div class="col-10">
                                    <?php if(isset($error)){ ?><div class="alert-danger py-2 text-center">Cet eleve est deja inscrit. <b class="text-danger">Veuillez verifier vos donnees</b></div><?php }elseif(isset($_SESSION['update_msg'])){ ?><div class="alert-success py-2 text-center text-success">Inscription validee avec success</div> <?php } ?>
                                    </div>
                                </div>
                            </div>
                            
                            <form action="" method="post">
                                <div class="row">
                                <!-- Personnal Information -->
                                    <div class="col-lg-12 text-left">
                                        <h5 class="text-bluesky mt-3">Information personnelle</h5>
                                        <hr class="bg-gradient-primary">
                                    </div>
                                    <div class="col-12">
                                        <div class="row">
                                            <!-- School year-->
                                            <div class=" col-12 col-md-4 col-lg-3 p-4">
                                                <label for="inscription_dnaiss" class="pb-3">Annee Scolaire<span class="text-danger"> * </span></label>
                                                <div class="form-group">
                                                    <select name="inscription_year" id="inscription_year" class="form-control" required >
                                                        <option value="">Choisir l'annee scolaire</option>
                                                       <?php while($getYear = $schoolYear->fetch()){?>
                                                        <option value="<?=$getYear['ID_ANNEE']?>"><?=$getYear['yearStart']."-".($getYear['yearStart']+1)?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                              <!-- Classe-->
                                              <div class=" col-12 col-md-4 col-lg-3 p-4">
                                                <label for="inscription_dnaiss" class="pb-3">Classe<span class="text-danger"> * </span></label>
                                                <div class="form-group">
                                                    <select name="inscription_class" id="inscription_class" class="form-control" required>
                                                        <option value="">Choisir la classe </option>
                                                        <?php
                                                        while($getClassListe = $listeClasses->fetch()){?>
                                                        <option value="<?=$getClassListe['ID_CLASSE']?>"><?=$getClassListe['NOM_CLASSE']?></option>
                                                       <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <!-- First Name -->
                                            <div class="col-12 col-md-4 col-lg-3 p-4">
                                                <label for="inscription_nom" class="pb-3">Nom<span class="text-danger"> * </span></label>
                                                <div class="input-group">
                                                    <input type="text" name="inscription_nom" id="inscription_nom" class="form-control" value="<?=isset($inscription_nom)?$inscription_nom:''?>" placeholder="Saisir le nom" required >
                                                    <div class="input-group-append">
                                                        <span class=" input-group-text fas fa-portrait  bg-light"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Last Name -->
                                            <div class="col-12 col-md-4 col-lg-3 p-4">
                                                <label for="inscription_prenom" class="pb-3">Prénom(s)<span class="text-danger"> * </span></label>
                                                <div class=" input-group">
                                                    <input type="text" name="inscription_prenom" id="inscription_prenom" class="form-control"  value="<?=isset($inscription_prenom)?$inscription_prenom:''?>" placeholder="Saisir le prenom" required>
                                                    <div class="input-group-append">
                                                        <span class=" input-group-text fas fa-portrait  bg-light"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Gender Area -->
                                            <div class="col-12 col-md-4 col-lg-3 p-4">
                                                <label for="inscription_sexe" class="pb-3">Genre<span class="text-danger"> * </span></label>
                                                <div class="input-group">
                                                <select name="inscription_sexe" id="inscription_sexe" class="form-control"  required>
                                                        <option value="<?=isset($inscription_sexe)?$inscription_sexe: ''?>"><?=isset($inscription_sexe)?$inscription_sexe: 'Choisir le sexe'?></option>
                                                        <option value="Masculin">Masculin</option>
                                                        <option value="Feminin">Feminin</option>
                                                    </select>
                                                    <div class="input-group-append">
                                                        <span class=" input-group-text fas fa-venus-mars  bg-light"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Birthday Area-->
                                            <div class=" col-12 col-md-4 col-lg-3 p-4">
                                                <label for="inscription_dnaiss" class="pb-3">Date de naissance<span class="text-danger"> * </span></label>
                                                <div class="form-group">
                                                    <input type="date" name="inscription_dnaiss" id="inscription_dnaiss" class="form-control" value="<?=isset($inscription_dnaiss)?$inscription_dnaiss:''?>" required>
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
                                                    <input type="text" name="inscription_fath_name" id="inscription_fath_name" placeholder="Ex : Yanogo Patrick" class="form-control" placeholder="" value="<?=isset($inscription_fath_name)?$inscription_fath_name:''?>" required>
                                                    <div class="input-group-append">
                                                        <span class=" input-group-text fas fa-user-edit bg-light"></span>
                                                    </div>
                                                </div>
                                            </div> 
                                            <!-- Mother Name -->
                                            <div class="col-12 col-md-6 p-4">
                                                <label for="inscription_moth_name" class="pb-3">Nom de la mère<span class="text-danger"> * </span></label>
                                                <div class="input-group">
                                                    <input type="text" name="inscription_moth_name" id="inscription_moth_name" placeholder="Ex : Yanogo Patricia" class="form-control" value="<?=isset($inscription_moth_name)?$inscription_moth_name:''?>" placeholder="" required>
                                                    <div class="input-group-append">
                                                        <span class=" input-group-text fas fa-user-edit bg-light"></span>
                                                    </div>
                                                </div>
                                            </div> 
                                            <!-- Father Job -->
                                            <div class="col-12 col-md-6 p-4">
                                                <label for="inscription_father_job" class="pb-3">Profession du père<span class="text-danger"> * </span></label>
                                                <div class="input-group">
                                                    <input type="text" name="inscription_father_job" id="inscription_father_job" placeholder="Ex : Fullstack Developer" class="form-control" value="<?=isset($inscription_father_job)?$inscription_father_job:''?>" required>
                                                    <div class="input-group-append">
                                                        <span class=" input-group-text fas fa-user-edit bg-light"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Mother Job -->
                                            <div class="col-12 col-md-6 p-4">
                                                <label for="inscription_moth_job" class="pb-3">Profession de la mère<span class="text-danger"> * </span></label>
                                                <div class="input-group">
                                                    <input type="text" name="inscription_moth_job" id="inscription_moth_job" placeholder="Ex : Lawyer" class="form-control" value="<?=isset($inscription_moth_job)?$inscription_moth_job:''?>" required>
                                                    <div class="input-group-append">
                                                        <span class=" input-group-text fas fa-user-edit bg-light"></span>
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
                                    <div class="col-md-3 p-4">
                                        <label for="inscription_email" class="pb-3">Email<span class="text-danger"> * </span></label>
                                        <div class="input-group">
                                            <input type="email" name="inscription_email" id="inscription_email" class="form-control" value="<?=isset($inscription_email)?$inscription_email:''?>" placeholder="Adresse e-mail" required>
                                            <div class="input-group-append">
                                                <span class=" input-group-text fas fa-envelope  bg-light"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Phone Number -->
                                    <div class="col-md-3 p-4">
                                        <label for="inscription_tel" class="pb-3">téléphone<span class="text-danger"> * </span></label>
                                        <div class="input-group">
                                            <input type="text" name="inscription_tel" id="inscription_tel" class="form-control" value="<?=isset($inscription_tel)?$inscription_tel:''?>" placeholder="Le numero de telephone" required >
                                            <div class="input-group-append">
                                                <span class=" input-group-text fas fa-mobile bg-light"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Phone Number -->
                                    <div class="col-md-3 p-4">
                                        <label for="inscription_tel" class="pb-3">CNIB<span class="text-danger"> * </span></label>
                                        <div class="input-group">
                                            <input type="text" name="inscription_cnib" id="inscription_cnib" class="form-control" value="<?=isset($inscription_cnib)?$inscription_cnib:''?>" placeholder="Numero de la carte d'identite" required >
                                            <div class="input-group-append">
                                                <span class=" input-group-text fas fa-id-card bg-light"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Emergency Number -->
                                    <div class="col-md-3 p-4">
                                        <label for="inscription_tel_urg" class="pb-3">Numéro d'urgence<span class="text-danger"> * </span></label>
                                        <div class="input-group">
                                            <input type="text" name="inscription_tel_urg" id="inscription_tel_urg" class="form-control" value="<?=isset($inscription_tel_urg)?$inscription_tel_urg:''?>" placeholder="Numero en cas de besoin" required >
                                            <div class="input-group-append">
                                                <span class=" input-group-text fas fa-mobile-alt  bg-light"></span>
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

