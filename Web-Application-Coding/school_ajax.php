<?php
    // creation de l'objet QueryBuilder
    include('utilities/QueryBuilder.php');
    include('assets/pages/functions.php');
    $obj = new QueryBuilder();

    // Affichage et modification des donnees des eleves
    if(isset($_POST['std_upd_key']) and $_POST['std_upd_key'] =="std_upd_key"){
        extract($_POST);

        // recuperation des informations de l'eleve
        $SearchStdInfo = $obj->Requete("SELECT * FROM etudiant e, inscription i, niveau n, classe c,annee_scolaire a WHERE a.ID_ANNEE=i.ID_ANNEE  AND i.MATRICULE=e.MATRICULE AND c.ID_CLASSE = i.ID_CLASSE AND c.ID_NIVEAU = n.ID_NIVEAU AND i.ID_INSCRIPTION='".$std_inscription."'");

        // Recuperation de l'annee scolaire
        $schoolYear = $obj->Requete("SELECT ID_ANNEE, YEAR(DATE_INI) as yearStart FROM annee_scolaire");
        
        // Selection des classes du secondaire
        $listeClasses = $obj->Requete("SELECT * FROM classe c, niveau n WHERE n.ID_NIVEAU = c.ID_NIVEAU AND n.NOM_NIVEAU = 'Secondaire' ORDER BY c.ID_CLASSE DESC");

        $stdDetail = '
        <form action="" method="post">
        <div class="row">
            <div class="col-12 mt-4">
                <div class="row">
                    <div class="col-12 col-md-9">
                    <p class="text-danger">NB: <i class="text-dark font-weight-bold">Cliquer sur le bouton Modifier pour avoir la possibilite de modifier les informations</i></p>
                    </div>
                    <div class="col-12 col-md-3">
                        <button class="btn btn-secondary w-75 " id="enableModi" type="button" onclick="enableEditing()">Modifier</button>
                    </div>
                </div>
            </div>
        <!-- Personnal Information -->
            <div class="col-lg-12 text-left">
                <h5 class="text-bluesky mt-3">Information personnelle</h5>
                <hr class="bg-gradient-primary">
            </div>
            <div class="col-12">
                <div class="row">
                ';
            if($getStdInfo = $SearchStdInfo->fetch()){
            $stdDetail.='
            <!-- School year-->
            <div class=" col-12 col-md-4 col-lg-4 p-4">
                <label for="inscription_dnaiss" class="pb-3">Annee Scolaire<span class="text-danger"> * </span></label>
                <div class="form-group">
                    <select name="inscription_year" id="inscription_year" class="form-control" required >
                        <option value="'.$getStdInfo['ID_ANNEE'].'">'.(explode('-',$getStdInfo['DATE_INI'])[0]).'-'.(explode('-',$getStdInfo['DATE_INI'])[0]+1).'</option>';
                       while($getYear = $schoolYear->fetch()){
                        $stdDetail.='<option value="'.$getYear['ID_ANNEE'].'">'.$getYear['yearStart']."-".($getYear['yearStart']+1).'</option>';
                         } 
            $stdDetail.='</select>
                    </div>
                </div>
                <!-- Classe-->
                <div class=" col-12 col-md-4 col-lg-4 p-4">
                <label for="inscription_dnaiss" class="pb-3">Classe<span class="text-danger"> * </span></label>
                <div class="form-group">
                    <select name="inscription_class" id="inscription_class" class="form-control" onchange="validateInput(inscription_class, editInput)" required>
                        <option value="'.$getStdInfo['ID_CLASSE'].'">'.$getStdInfo['NOM_CLASSE'].'</option>';
                        while($getClassListe = $listeClasses->fetch()){
                            $stdDetail.='<option value="'.$getClassListe['ID_CLASSE'].'">'.$getClassListe['NOM_CLASSE'].'</option>';
                        } 
            
            $stdDetail.='</select>
                </div>
                </div>
                <!-- First Name -->
                <div class="col-12 col-md-4 col-lg-4 p-4">
                    <label for="inscription_nom" class="pb-3">Nom<span class="text-danger"> * </span></label>
                    <div class="input-group">
                        <input type="text" name="inscription_nom" id="inscription_nom" class="form-control" value="'.$getStdInfo['NOM'].'"                             placeholder="Saisir le nom" oninput="validateInput(inscription_nom, editInput)" required >
                        <div class="input-group-append">
                            <span class=" input-group-text fas fa-portrait  bg-light"></span>
                        </div>
                    </div>
                </div>
                <!-- Last Name -->
                <div class="col-12 col-md-4 col-lg-4 p-4">
                    <label for="inscription_prenom" class="pb-3">Prénom(s)<span class="text-danger"> * </span></label>
                    <div class=" input-group">
                        <input type="text" name="inscription_prenom" id="inscription_prenom" class="form-control"  value="'.$getStdInfo['PRENOM'].'" placeholder="Saisir le prenom" oninput="validateInput(inscription_prenom, editInput)" required>
                        <div class="input-group-append">
                            <span class=" input-group-text fas fa-portrait  bg-light"></span>
                        </div>
                    </div>
                </div
                <!-- Gender Area -->
                <div class="col-12 col-md-4 col-lg-4 p-4">
                    <label for="inscription_sexe" class="pb-3">Genre<span class="text-danger"> * </span></label>
                    <div class="input-group">
                    <select name="inscription_sexe" id="inscription_sexe" class="form-control" onchange="validateInput(inscription_sexe, editInput)"  required>
                            <option value="'.$getStdInfo['SEXE'].'">'.$getStdInfo['SEXE'].'</option>
                            <option value="Masculin">Masculin</option>
                            <option value="Feminin">Feminin</option>
                        </select>
                        <div class="input-group-append">
                            <span class=" input-group-text fas fa-venus-mars  bg-light"></span>
                        </div>
                    </div>
                </div>
                <!-- Birthday Area-->
                <div class=" col-12 col-md-4 col-lg-4 p-4">
                    <label for="inscription_dnaiss" class="pb-3">Date de naissance<span class="text-danger"> * </span></label>
                    <div class="form-group">
                        <input type="date" name="inscription_dnaiss" id="inscription_dnaiss" class="form-control" oninput="validateInput(inscription_dnaiss, editInput)" value="'.$getStdInfo['DATE_NAISSANCE'].'" required>
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
                            <input type="text" name="inscription_fath_name" id="inscription_fath_name" placeholder="Ex : Yanogo Patrick" class="form-control" placeholder="" value="'.$getStdInfo['NOM_PERE'].'"  oninput="validateInput(inscription_fath_name, editInput)" required>
                            <div class="input-group-append">
                                <span class=" input-group-text fas fa-user-edit bg-light"></span>
                            </div>
                        </div>
                    </div> 
                    <!-- Mother Name -->
                    <div class="col-12 col-md-6 p-4">
                        <label for="inscription_moth_name" class="pb-3">Nom de la mère<span class="text-danger"> * </span></label>
                        <div class="input-group">
                            <input type="text" name="inscription_moth_name" id="inscription_moth_name" placeholder="Ex : Yanogo Patricia" class="form-control" value="'.$getStdInfo['NOM_MERE'].'" placeholder="" oninput="validateInput(inscription_moth_name, editInput)" required>
                            <div class="input-group-append">
                                <span class=" input-group-text fas fa-user-edit bg-light"></span>
                            </div>
                        </div>
                    </div> 
                    <!-- Father Job -->
                    <div class="col-12 col-md-6 p-4">
                        <label for="inscription_father_job" class="pb-3">Profession du père<span class="text-danger"> * </span></label>
                        <div class="input-group">
                            <input type="text" name="inscription_father_job" id="inscription_father_job" placeholder="Ex : Fullstack Developer" class="form-control" value="'.$getStdInfo['PROFESSION_PERE'].'" oninput="validateInput(inscription_father_job, editInput)"  required>
                            <div class="input-group-append">
                                <span class=" input-group-text fas fa-user-edit bg-light"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Mother Job -->
                    <div class="col-12 col-md-6 p-4">
                        <label for="inscription_moth_job" class="pb-3">Profession de la mère<span class="text-danger"> * </span></label>
                        <div class="input-group">
                            <input type="text" name="inscription_moth_job" id="inscription_moth_job" placeholder="Ex : Lawyer" class="form-control" value="'.$getStdInfo['PROFESSION_MERE'].'" oninput="validateInput(inscription_moth_job, editInput)"  required>
                            <div class="input-group-append">
                                <span class=" input-group-text fas fa-user-edit bg-light"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="col-lg-12 text-left mt-3">
                <h5 class="text-bluesky">Contacts du tuteur</h5>
                <hr class="bg-gradient-primary">
            </div>

            <!-- Email Area -->
            <div class="col-md-6 p-4">
                <label for="inscription_email" class="pb-3">Email<span class="text-danger"> * </span></label>
                <div class="input-group">
                    <input type="email" name="inscription_email" id="inscription_email" class="form-control" value="'.$getStdInfo['EMAIL'].'" placeholder="Adresse e-mail" oninput="validateInput(inscription_email, editInput)" required>
                    <div class="input-group-append">
                        <span class=" input-group-text fas fa-envelope bg-light"></span>
                    </div>
                </div>
            </div>

            <!-- Phone Number -->
            <div class="col-md-6 p-4">
                <label for="inscription_tel" class="pb-3">téléphone<span class="text-danger"> * </span></label>
                <div class="input-group">
                    <input type="text" name="inscription_tel" id="inscription_tel" class="form-control" value="'.$getStdInfo['TELEPHONE'].'" placeholder="Le numero de telephone" oninput="validateInput(inscription_tel, editInput)" required >
                    <div class="input-group-append">
                        <span class=" input-group-text fas fa-mobile bg-light"></span>
                    </div>
                </div>
            </div>

            <!-- Phone Number -->
            <div class="col-md-6 p-4">
                <label for="inscription_tel" class="pb-3">CNIB<span class="text-danger"> * </span></label>
                <div class="input-group">
                    <input type="text" name="inscription_cnib" id="inscription_cnib" class="form-control" value="'.$getStdInfo['CNIB'].'" placeholder="Numero de la carte d\'identite" oninput="validateInput(inscription_cnib, editInput)" required >
                    <div class="input-group-append">
                        <span class=" input-group-text fas fa-id-card bg-light"></span>
                    </div>
                </div>
            </div>
            <!-- Emergency Number -->
            <div class="col-md-6 p-4">
                <label for="inscription_tel_urg" class="pb-3">Numéro d\'urgence<span class="text-danger"> * </span></label>
                <div class="input-group">
                    <input type="text" name="inscription_tel_urg" id="inscription_tel_urg" class="form-control" value="'.$getStdInfo['NUM_URGENCE'].'" placeholder="Numero en cas de besoin" oninput="validateInput(inscription_tel_urg, editInput)" required >
                    <div class="input-group-append">
                        <span class=" input-group-text fas fa-mobile-alt  bg-light"></span>
                    </div>
                </div>
            </div> 
            <div class="col-lg-12 d-flex justify-content-center my-3">
                <button class="btn btn-primary px-5" type="button" id="editInput" name="submit_meeting_info" style="text-align:center" onclick="updStdInfo()">Valider l\'inscription</button>
            </div>
        </div>
    </form> 
    ';
    }

    echo $stdDetail;
}

// Validattion de la modification des informations de l'eleve
if(isset($_POST['vali_std_upd_key']) AND $_POST['vali_std_upd_key'] =='vali_std_upd_key'){
    extract($_POST);

    $requete = $obj->Update('etudiant', array('PRENOM','NOM', 'SEXE', 'DATE_NAISSANCE','CNIB','TELEPHONE','EMAIL','NUM_URGENCE', 'NOM_PERE', 'PROFESSION_PERE', 'NOM_MERE','PROFESSION_MERE'), array($inscription_prenom,$inscription_nom, $inscription_sexe, $inscription_dnaiss,$inscription_cnib,$inscription_tel,$inscription_email,$inscription_tel_urg,$inscription_fath_name,$inscription_father_job,$inscription_moth_job,$inscription_moth_name),array("MATRICULE"=>$val_std_matricule));
    if($requete == 1)
    {
    $upd_student = $obj->Update('inscription', array('ID_CLASSE','ID_ANNEE'), array($inscription_class,$inscription_year),array("ID_INSCRIPTION"=>$val_id_inscription));
        if( $upd_student==1){
            // On recupere le montant de son scolarite
            $getStdAmount = $obj->Requete("SELECT * FROM classe c, inscription i,niveau n, annee_scolaire a,scolarite s WHERE n.ID_NIVEAU=c.ID_NIVEAU AND s.ID_INSCRIPTION = i.ID_INSCRIPTION AND c.ID_CLASSE = i.ID_CLASSE AND a.ID_ANNEE = i.ID_ANNEE AND i.ID_INSCRIPTION = '".$val_id_inscription."'");
            if($catchStdAmount = $getStdAmount->fetch()){
                $prepareStdPay = $obj->Update('scolarite',array('MONTANT_TOTAL','MONTANT_PAYE','DATE_LIMITE'),array($catchStdAmount['MONTANT_SCOLARITE'],$catchStdAmount['MONTANT_PAYE'],$catchStdAmount['FIN_VERSEMENT_3']),array('ID_INSCRIPTION'=>$val_id_inscription));
                if( $prepareStdPay==1){
                    echo 1;
                }
            }
        }
    }
}

// Supression d'un eleve
if(isset($_POST['vali_std_del_key']) AND $_POST['vali_std_del_key'] =='vali_std_del_key'){
    echo 0;
    extract($_POST);
    $requete = $obj->Update('etudiant', array('STATUT'), array(0),array("MATRICULE"=>$val_std_del_matricule));
    if($requete == 1){echo 1;}else{echo 0;}
}

?>



                                                      
                                                   
                                            
                                                    

                                            

                                            
                                            
                                    
                               