<?php
// creation de l'objet QueryBuilder
include('utilities/QueryBuilder.php');
include('assets/pages/functions.php');

$obj = new QueryBuilder();
// insertion controle php insertion classe du modale addClasse
if (isset($_POST['submit'])) {
    $getClassMsg = "";
    extract($_POST);
    if ($filiere != "") {
        //appel de la fonction insert de queryBuilder
        // on verifie si la classe n'est pas deja enregistrer
        $checkClass = $obj->Select('classe', [], array('NOM_CLASSE' => $classe_nom), $orderBy = '', $order = 1);
        if (is_object($checkClass) == false) {
            $insertClass = $obj->Insert('classe', ['NOM_CLASSE', 'MONTANT_SCOLARITE','ID_NIVEAU', ], [$classe_nom,$class_fees,$filiere]);

            if ($insertClass == 1) {
                echo 1;
            }
        } else {
            echo 2;
        }
    } else {
        echo 3;
    }

}
// fin insertion

//controle recuperation des infos de la classes cliquee pour la modification
if (isset($_POST['upd'])) {
    extract($_POST);
    $string = '<div class="row">';
    $selectClass = $obj->Requete("SELECT * FROM classe c, niveau n WHERE c.ID_NIVEAU = n.ID_NIVEAU AND ID_CLASSE='" . $matricule . "'");
    $selectFillier = $obj->Select('niveau', [], [], $orderBy = '', $order = 1);
    if (is_object($selectClass)) {
        if ($getSelect = $selectClass->fetch()) {
            $string .= '<div class="col-lg-4">
                    <input type="text" value="' . $matricule . '" hidden id="idClasse">
                   <label for="filiere">Niveau</label>
                   <div class="input-group">
                       <select class="form-control" name="filiere" id="up_filiere" oninput="EnableDecimal(this)" >
                       <option value="' . $getSelect['ID_NIVEAU'] . '">' . $getSelect['NOM_NIVEAU'] . '</option>';

            if (is_object($selectFillier)) {
                while ($repListe = $selectFillier->fetch()) {

                    $string .= "<option value='" . $repListe['ID_NIVEAU'] . "'>" . $repListe['NOM_NIVEAU'] . "</option>";
                }
            }
            $string .= '</select>
                               <div class="input-group-append">
                                   <span class=" input-group-text fas fa-school"></span>
                               </div>
                           </div>
                       </div>

                       <div class="col-lg-4">
                           <label for="up_classe_nom">Nom de la classe</label>
                           <div class="input-group">
                               <input type="text" class="form-control" name="classe_nom" id="up_classe_nom" placeholder="Saisir le nom de la classe" oninput="EnableDecimal(this)" value="' . $getSelect['NOM_CLASSE'] . '" required>

                               <div class="input-group-append">
                                   <span class="input-group-text fas fa-school"></span>
                               </div>
                           </div>
                       </div>

                       <div class="col-lg-4">
                           <label for="up_class_fees">Scolarite</label>
                           <div class="input-group">
                               <input type="number" class="form-control" name="class_fees" id="up_class_fees" placeholder="Saissir le montant de la scolarite" oninput="EnableDecimal(this)" value="' . $getSelect['MONTANT_SCOLARITE'] . '" required>

                               <div class="input-group-append">
                                   <span class="input-group-text fas fa-money-bill-wave-alt"></span>
                               </div>
                           </div>
                       </div>

                       <div class="col-lg-12 text-center my-5">
                           <input class="btn btn-primary w-50" type="button" name="submit_class" id="update" value="Modifier" onclick="Update()"> 
                       </div> </div>';

        }


    }
    echo $string;
}
//fin recuperation

//debut modification de la classe 
if (isset($_POST['Update'])) {
    extract($_POST);

    // on verifie si l'utilisateur a renseigne tous les champs
    if ($up_classe_nom == "" || $up_class_fees == "") {
        echo "empty";
    } else {
        // on verifie si la classe n'est pas deja enregistrer
        $checkClass = $obj->Select('classe', [], array('NOM_CLASSE' => $up_classe_nom), $orderBy = '', $order = 1);
        if (is_object($checkClass) == true) {
            if ($checkClass->rowCount() == 1) {
                $getObj = $checkClass->fetch();
                $getIdClasse = $getObj['ID_CLASSE'];
                $getFillieres = $getObj['ID_NIVEAU'];
                if ($getIdClasse != $idClasse) {
                    echo "exit";
                } else { 
                    $updateClass1 = $obj->Update('classe', ['NOM_CLASSE','MONTANT_SCOLARITE','ID_NIVEAU'], [$up_classe_nom, $up_class_fees,$up_filiere], array('ID_CLASSE' => $idClasse));
                    if ($updateClass1 == 1) {
                        echo "okk";
                    }
                }
            }
        } else {
            $updateClass2 = $obj->Update('classe', ['NOM_CLASSE', 'MONTANT_SCOLARITE','ID_NIVEAU'], [$up_classe_nom, $up_class_fees,$up_filiere], array('ID_CLASSE' => $idClasse));
            if ($updateClass2 == 1) {
                echo "ok";
            }
        }
    }
}
//fin modification

//debut insertion de departement
if (isset($_POST['submit_department'])) {
    $getClassMsg = "";
    extract($_POST);
    //appel de la fonction insert de queryBuilder
    // on verifie si le departement n'est pas deja enregistrer
    $checkClass = $obj->Select('department', [], array('NOM_DEPARTEMENT' => $department_name), $orderBy = '', $order = 1);
    if (is_object($checkClass) == false) {
        $insertClass = $obj->Insert('department', ['NOM_DEPARTEMENT', 'CHEF_DEPARTEMENT', 'DESCRIPTION'], [$department_name, $department_chief, $description]);

        if ($insertClass == 1) {
            echo 1;
        }
    } else {
        echo 2;
    }

}

//controle recuperation des infos du department 
if (isset($_POST['depUpd'])) {
    extract($_POST);
    $string = '<div class="row">';
    $selectDept = $obj->Requete("SELECT * FROM department  WHERE ID_DEPARTEMENT ='" . $depId . "'");
    if (is_object($selectDept)) {
        if ($getSelect = $selectDept->fetch()) {
            $string .= '<div class="col-lg-6">
                <input type="text" value="' . $depId . '" hidden id="idDepart">
                    <label for="up_department_name">Department Name<span class="text-danger"> * </span></label>
                          <div class="input-group">
                             <input type="text" class="form-control" name="up_department_name" id="up_department_name" placeholder="Enter Department Name" value="' . $getSelect['NOM_DEPARTEMENT'] . '" oninput="EnableDecimal(this)" required>
                                 <div class="input-group-append">
                                  <span class=" input-group-text fas fa-university"></span>
                              </div>
                          </div>
                      </div>

                      <div class="col-lg-6">
                          <label for="up_department_chief">Department Chief <span class="text-danger"> * </span></label>
                          <div class="input-group">
                              <input type="text" class="form-control" name="department_chief" id="up_department_chief" placeholder="Enter Department Chief" value="' . $getSelect['CHEF_DEPARTEMENT'] . '" oninput="EnableDecimal(this)" required>
                                      
                              <div class="input-group-append">
                                  <span class="input-group-text fas fa-user-tie"></span>
                              </div>
                          </div>
                      </div>

                      <div class="col-lg-12 mt-5">
                          <label for="up_description">Description <span class="text-danger"> * </span></label>
                          <div class="form-group">
                              <textarea name="up_description" id="up_description" class="form-control" rows="10" style="resize:none" placeholder="Enter Description Here" value="" required oninput="EnableDecimal(this)">' . $getSelect['DESCRIPTION'] . '</textarea>
                          </div>
                      </div>';

        }
    }
    $string .= '<div class="col-lg-12 text-center my-5">
                    <button class="btn btn-danger w-25 my-3" data-dismiss="modal" aria-label="Close">Reset</button>
                        <input class="btn btn-success w-25 my-3" name="update_department" type="button" id="Up_Dep" value="Update" onclick="DeprtUpdate()">
                            </div></div>';
    echo $string;


}
//fin recuperation

//debut modification de la classe 
if (isset($_POST['Up_Dep'])) {
    extract($_POST);


    // on verifie si l'utilisateur a renseigne tous les champs
    if ($up_department_chief == "" || $up_description == "" || $up_department_name == "") {
        echo "empty";
    } else {
        // on verifie si la classe n'est pas deja enregistrer
        $checkDepart = $obj->Select('department', [], array('NOM_DEPARTEMENT' => $up_department_name), $orderBy = '', $order = 1);
        if (is_object($checkDepart) == true) {
            if ($checkDepart->rowCount() == 1) {
                $getDepart = $checkDepart->fetch();
                $getIdDepart = $getDepart['ID_DEPARTEMENT'];
                if ($getIdDepart != $idDepart) {
                    echo "exit";
                } else {
                    $updateDeparte = $obj->Update('department', ['NOM_DEPARTEMENT', 'CHEF_DEPARTEMENT', 'DESCRIPTION'], [$up_department_name, $up_department_chief, $up_description], array('ID_DEPARTEMENT' => $idDepart));
                    if ($updateDeparte == 1) {
                        echo "okk";
                    }
                }
            }
        } else {
            $updateDeparte2 = $obj->Update('department', ['NOM_DEPARTEMENT', 'CHEF_DEPARTEMENT', 'DESCRIPTION'], [$up_department_name, $up_department_chief, $up_description], array('ID_DEPARTEMENT' => $idDepart));
            if ($updateDeparte2 == 1) {
                echo "ok";
            }
        }
    }
}
//fin modification

//debut insertion de Filiere
if (isset($_POST['submit_field'])) {
    extract($_POST);
    //appel de la fonction insert de queryBuilder
    // on verifie si le departement n'est pas deja enregistrer
    if ($department != 'Select') {
        $checkField = $obj->Select('filieres', [], array('NOM_FILIERE' => $field_nom), $orderBy = '', $order = 1);
        if (is_object($checkField) == false) {
            $insertField = $obj->Insert('filieres', ['ID_DEPARTEMENT', 'NOM_FILIERE', 'DESCRIPTION'], [$department, $field_nom, $description]);

            if ($insertField == 1) {
                echo 1;
            } else {
                echo 2;
            }
        } else {
            echo 2;
        }
    } else {
        echo 3;
    }

}
//Fin Insertion

//controle recuperation des infos de la filiere cliquee pour la modification
if (isset($_POST['fieldUpd'])) {
    extract($_POST);

    $string = '<div class="row">';
    $selectFillier1 = $obj->Requete("SELECT * FROM  department d, filieres f WHERE f.ID_DEPARTEMENT = d.ID_DEPARTEMENT AND ID_FILIERE='" . $fieldId . "' ORDER BY f.ID_FILIERE DESC");
    $selectFillier = $obj->Select('department', [], [], $orderBy = '', $order = 1);

    if ($getSelect = $selectFillier1->fetch()) {
        $string .= '<div class="col-lg-6">
          <label for="up_department">Department <span class="text-danger"> * </span></label>
          <div class="input-group">
          <input type="text" value="' . $fieldId . '" hidden id="idField">
              <select class="form-control" name="up_department" id="up_department" required>
                  <option value="' . $getSelect['ID_DEPARTEMENT'] . '">' . $getSelect['NOM_DEPARTEMENT'] . '</option>';


        if (is_object($selectFillier)) {
            while ($repListe = $selectFillier->fetch()) {
                $string .= "<option value='" . $repListe['ID_DEPARTEMENT'] . "'>" . $repListe['NOM_DEPARTEMENT'] . "</option>";
            }
        }

        $string .= '</select>
              <div class="input-group-append">
                  <span class=" input-group-text fas fa-school"></span>
              </div>
          </div>
      </div>
      <div class="col-lg-6">
           <label for="up_field_nom">Field Name <span class="text-danger"> * </span></label>
           <div class="input-group">
               <input type="text" class="form-control" name="up_field_nom" id="up_field_nom" placeholder="Enter Field Name" value="' . $getSelect['NOM_FILIERE'] . '" oninput="EnableDecimal(this)"  required>
                       
               <div class="input-group-append">
                   <span class="input-group-text fas fa-school"></span>
               </div>
           </div>
       </div>

       <div class="col-lg-12 mt-5">
           <label for="up_description">Description <span class="text-danger"> * </span></label>
           <div class="form-group">
               <textarea name="up_description" id="up_description" class="form-control" rows="10" style="resize:none;"   oninput="EnableDecimal(this)" required>' . $getSelect['DESCRIPTION'] . '</textarea>
           </div>
       </div>
                                                
       <div class="col-lg-12 text-center my-5">
           <button class="btn btn-danger w-25 my-3" data-dismiss="modal" aria-label="Close">Reset</button>
           <input class="btn btn-success w-25 my-3" name="update_field" type="button" onclick="FieldtUpdate()" id="upField" value="Update">
       </div>';


    }


    echo $string;
}
//fin recuperation
//debut modification de la classe 
if (isset($_POST['upField'])) {
    extract($_POST);

    // on verifie si l'utilisateur a renseigne tous les champs
    if ($up_field_nom == "" || $up_description == "") {
        echo 0;
    } else {
        // on verifie si la classe n'est pas deja enregistrer
        $checkClass = $obj->Select('filieres', [], array('NOM_FILIERE' => $up_field_nom), $orderBy = '', $order = 1);
        if (is_object($checkClass) == true) {
            if ($checkClass->rowCount() == 1) {
                $getObj = $checkClass->fetch();
                $getFillieres = $getObj['ID_FILIERE'];
                $getDepart = $getObj['ID_DEPARTEMENT'];
                if ($getFillieres != $idField) {
                    echo 1;
                } else {
                    if ($getDepart != $up_department) {
                        echo 1;
                    } else {
                        $updateFiel1 = $obj->Update('filieres', ['ID_DEPARTEMENT', 'NOM_FILIERE', 'DESCRIPTION'], [$up_department, $up_field_nom, $up_description], array('ID_FILIERE' => $idField));
                        if ($updateFiel1 == 1) {
                            echo 2;
                        }
                    }

                }
            }
        } else {
            $updateFiel2 = $obj->Update('filieres', ['ID_DEPARTEMENT', 'NOM_FILIERE', 'DESCRIPTION'], [$up_department, $up_field_nom, $up_description], array('ID_FILIERE' => $idField));
            if ($updateFiel2 == 1) {
                echo 2;
            }
        }
    }
}
//fin modification


//recherche par annee
if (isset($_POST['shr'])){
    $obj = new QueryBuilder();
    extract($_POST);
    $temps = $obj->Requete('SELECT NOW() as dat')->fetch();
    $upcorps = '';
    $oucorps = '';

    // Dans le cas ou nous sommes dans la page de liste paiement
    if ($page == 'list-payment') {
        $all_new_payment = $obj->Requete('SELECT * FROM etudiant etu,inscription insc, scolarite sco WHERE etu.MATRICULE = insc.MATRICULE AND sco.ID_INSCRIPTION = insc.ID_INSCRIPTION AND insc.ID_ANNEE="'. getAnnee($annee)['ID_ANNEE'].'"');

        while ($the_new_payment = $all_new_payment->fetch()) {
            if ($the_new_payment['MONTANT_TOTAL']!=0){
                $tauxPayer = ($the_new_payment['MONTANT_PAYE'] / $the_new_payment['MONTANT_TOTAL']) * 100;
                $tauxPayer = intval($tauxPayer);
            }else{
                
                $tauxPayer=100;
            }

            if ($tauxPayer>=100){

            $update = $the_new_payment['MATRICULE'];
            $classe = $obj->Select('classe', array(), array('ID_CLASSE' => $the_new_payment['ID_CLASSE']))->fetch();
            $filiere = $obj->Select('filieres', array(), array('ID_FILIERE' => $classe['ID_FILIERE']))->fetch();

            $stud_naes = $the_new_payment['NOM']." ".$the_new_payment['PRENOM']." : ".$classe['NOM_CLASSE'];
            $upcorps .= '<tr>
                                                <td>
                                                    <button class="btn btn-outline-primary" data-toggle="modal" data-target="#updates" onclick="getStudentInfo('.htmlspecialchars(json_encode($the_new_payment['ID_INSCRIPTION'])).','.htmlspecialchars(json_encode($stud_naes)).')">
                                                        Details
                                                    </button>
                                                </td>
                                            <td>' . $the_new_payment['MATRICULE'] . '</td>
                                            <td>' . $the_new_payment['NOM'] . '</td>
                                            <td>' . $the_new_payment['PRENOM'] . '</td>
                                            <td>' . $the_new_payment['SEXE'] . '</td>
                                            <td>' . $classe['NOM_CLASSE'] . '</td>
                                            <td>' . ((int)$the_new_payment['MONTANT_TOTAL'] - (int)$the_new_payment['MONTANT_PAYE']) . '</td>
                                            <td>' . $the_new_payment['MONTANT_TOTAL'] . '</td>';

            if ($tauxPayer < 100) {

                $upcorps .= '<td class="text-warning">' . $tauxPayer . '%</td> ';
            } else {

                $upcorps .= '<td class="text-success">' . $tauxPayer . '%</td>';
            }
            $upcorps .= '</tr>';
        }

        }



        //echo $upcorps;


        //pour out of date

        $yaro = $obj->Requete('SELECT * FROM etudiant etu,inscription insc, scolarite sco WHERE etu.MATRICULE = insc.MATRICULE AND sco.ID_INSCRIPTION = insc.ID_INSCRIPTION AND insc.ID_ANNEE="' . getAnnee($annee)['ID_ANNEE'] .'"');

        while ($thepayment = $yaro->fetch()) {
            $classe = $obj->Select('classe', array(), array('ID_CLASSE' => $thepayment['ID_CLASSE']))->fetch();
            $filiere = $obj->Select('filieres', array(), array('ID_FILIERE' => $classe['ID_FILIERE']))->fetch();
            $update = 'see';
            $update = $thepayment['MATRICULE'];


            if ($thepayment['MONTANT_TOTAL']!=0){
                $tauxPayer = ($thepayment['MONTANT_PAYE'] / $thepayment['MONTANT_TOTAL']) * 100;
                $tauxPayer = floatval($tauxPayer);
            }else{
                $tauxPayer=100;
            }


            if ($tauxPayer<100 ) {
                $stud_names = $thepayment['NOM']." ".$thepayment['PRENOM']." : ".$classe['NOM_CLASSE'];

                $oucorps .= '<tr>
                                                <td>
                                                        <button class="btn btn-outline-primary" data-toggle="modal" data-target="#updates" onclick="getStudentInfo('.htmlspecialchars(json_encode($thepayment['ID_INSCRIPTION'])).','.htmlspecialchars(json_encode($stud_names)).')">

                                                        Details
                                                    </button>
                                                </td>
                                            <td>' . $thepayment['MATRICULE'] . '</td>
                                            <td>' . $thepayment['NOM'] . '</td>
                                            <td>' . $thepayment['PRENOM'] . '</td>
                                            <td>' . $thepayment['SEXE'] . '</td>
                                            <td>' . $classe['NOM_CLASSE'] . '</td>
                                            <td>' . ((int)$thepayment['MONTANT_TOTAL'] - (int)$thepayment['MONTANT_PAYE']) . '</td>
                                            <td>' . $thepayment['MONTANT_TOTAL'] . '</td>';
                $showTaux = explode('.',strval($tauxPayer))[0].'.'.substr(explode('.',strval($tauxPayer))[1], 0,2);
                if ($tauxPayer < 100) {

                    $oucorps .= '<td class="text-warning">'.$showTaux.'%</td> ';
                } else {


                    $oucorps .= ' <td class="text-success">' . $showTaux . '%</td>';
                }
                $oucorps .= '  </tr>';


            }
        }

        //echo $oucorps;
        $retour = $upcorps . "*" . $oucorps;
        echo $retour;

    //exit(json_decode([$upcorps,$oucorps]));

    }

    //Dans le cas ou nous sommes dans la page internship_owners
    if ($page == 'scholarship_owners_inscrit') {

        ///$all_new_payment = $obj->Requete('SELECT * FROM etudiant etu,inscription insc, scolarite sco WHERE etu.MATRICULE = insc.MATRICULE AND sco.ID_INSCRIPTION = insc.ID_INSCRIPTION AND insc.DATE_INSCRIPTION>="' . getAnnee($annee)['DATE_INI'] . '"AND insc.DATE_INSCRIPTION<="' . getAnnee($annee)['DATE_FIN'] . '"');
        $scholar = $obj->Requete('SELECT * FROM etudiant e, classe c, inscription i, filieres f, bourse b WHERE  f.ID_FILIERE = c.ID_FILIERE AND i.MATRICULE = e.MATRICULE AND i.ID_ClASSE = c.ID_CLASSE AND b.ID_BOURSE = i.ID_BOURSE AND  i.ID_ANNEE="' . getAnnee($annee)['ID_ANNEE'] .'"');
        $string_scholar ='<thead>
        <tr>
           <th data-field="Action" >Action</th>
           <th data-field="first_name" >First Name</th>
           <th data-field="name" >Last Name</th>
           <th data-field="gender" >Gender</th>
           <th data-field="class" >Class</th>
           <th data-field="field" >Field</th>
           <th data-field="rate" >Rate</th>
           <th data-field="rate-amount" >Rate Amount</th>
           <th data-field="total-amount" >Total Amount</th>
        </tr>
        </thead><tbody>';
                        while($back_scholar = $scholar->fetch())
                        { 

                           $string_scholar.='<tr>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Button group">
                                        <a href="scholarship_owners.php?matricule='. $back_scholar['MATRICULE'].'"
                                                                data-toggle="modal"
                                                                data-target="#' .'$update' .'">
                                            <i class="fas fa-edit text-warning"></i>
                                        </a>

                                </div>
                                </td>
                                <td>'. $back_scholar['PRENOM'].'</td>
                                <td>'. $back_scholar['NOM'].'</td>
                                <td>'. $back_scholar['SEXE'].'</td>
                                <td>'. $back_scholar['NOM_CLASSE'].'</td>
                                <td>'. $back_scholar['NOM_FILIERE'].'</td>
                                <td>'. $back_scholar['TAUX']. '%</td>
                                <td>'.$back_scholar['MONTANT_SCOLARITE'] * (1 - ($back_scholar['TAUX'])/100).'</td>
                                <td>'. $back_scholar['MONTANT_SCOLARITE'].'</td>
                            </tr>';
                        }
        $string_scholar .='</tbody>';

        echo  $string_scholar;
    }
    //Dans le cas ou nous sommes dans la page valide_other_paiement
    if ($page == 'valide_other_paiement') {

        ///$all_new_payment = $obj->Requete('SELECT * FROM etudiant etu,inscription insc, scolarite sco WHERE etu.MATRICULE = insc.MATRICULE AND sco.ID_INSCRIPTION = insc.ID_INSCRIPTION AND insc.DATE_INSCRIPTION>="' . getAnnee($annee)['DATE_INI'] . '"AND insc.DATE_INSCRIPTION<="' . getAnnee($annee)['DATE_FIN'] . '"');
        $all_new_payment = $obj->Requete('SELECT * FROM etudiant etu, inscription insc, scolarite sco WHERE sco.MONTANT_TOTAL>sco.MONTANT_PAYE AND etu.MATRICULE = insc.MATRICULE AND sco.ID_INSCRIPTION = insc.ID_INSCRIPTION AND insc.ID_ANNEE="'.getAnnee($annee)['ID_ANNEE'].'"');
        $string_tuition ="";
        while($back_payment = $all_new_payment->fetch())
        { 
            $classe = $obj->Select('classe', array(), array('ID_CLASSE' => $back_payment['ID_CLASSE']))->fetch();
            $filiere = $obj->Select('filieres', array(), array('ID_FILIERE' => $classe['ID_FILIERE']))->fetch();
            $string_tuition.='<tr>
            
                <td>
                    <input class="btn btn-primary" name="set_matricule" type="submit"  value="Pay Tuition" title="'. $back_payment['MATRICULE'].'" onclick="Attrib_matricule_to_input(this.title)">
                </td>
                <td>'. $back_payment['MATRICULE'].'</td>
                <td>'.$back_payment['NOM'].'</td>
                <td>'.$back_payment['PRENOM'].'</td>
                <td>'.$back_payment['SEXE'].'</td>
                <td>'.$classe['NOM_CLASSE'].'</td>
                <td>'.$filiere['NOM_FILIERE'].'</td>
                <td>'.$back_payment['MONTANT_PAYE'].' F cfa</td>
                <td>'.intval($back_payment['MONTANT_TOTAL'] - $back_payment['MONTANT_PAYE']).' F cfa</td>
                <td>'.$back_payment['MONTANT_TOTAL'].' F cfa</td>
            </tr>';
        }
        echo  $string_tuition;
    }
}


// code traitement des paiements
if(isset($_POST['submit_paye'])){
    extract($_POST);
    $historiquePay = $obj->Requete('SELECT * FROM historique_payement hpay,  scolarite sco WHERE hpay.ID_INSCRIPTION='.$id_inscription.' AND sco.ID_INSCRIPTION =hpay.ID_INSCRIPTION');
    $string="";
    while ($back_horiq = $historiquePay->fetch()){
        $montant_payer = $obj->Requete('SELECT SUM(MONTANT) FROM `historique_payement` WHERE ID_INSCRIPTION ='.$id_inscription.' AND DATE_PAYEMENT <="'.$back_horiq['DATE_PAYEMENT'].'"')->fetch();
        $string .=' <div class="container-fluid border my-5 py-2">
                        <div class="row pb-3 border-bottom">
                            <div class="col">
                                <h6 class="mt-3 text-bluesky"> Payment Date : '.$back_horiq['DATE_PAYEMENT'].'</h6>
                            </div>
                            <div class="col text-right">
                                <a class="mt-3 btn btn-dark" href="recu.php?id_inscription='.$id_inscription.'&dates='.$back_horiq['DATE_PAYEMENT'].'">Generate receipt</a>
                            </div>
                        </div>
                        
                        <div class="row pt-3">
                            <div class="col">
                                <label>Payed amount</label>
                                <p>'.$back_horiq['MONTANT']. ' F FCFA'.'</p>
                            </div>
                            <div class="col">
                                <label>Remaining amount</label>
                                <p>'.(intval($back_horiq['MONTANT_TOTAL'] )- intval($montant_payer["SUM(MONTANT)"])).' F FCFA'.'</p>
                            </div>
                            <div class="col">
                                <label>Total amount</label>
                                <p>'.$back_horiq['MONTANT_TOTAL'].' F FCFA'.'</p>
                            </div>
                        </div>
                    </div>';
     }

     echo $string;

}

if (isset($_POST['rate'])){
    $selc="";
    extract($_POST);
    $cla=$obj->Requete('SELECT * FROM bourse WHERE ID_BOURSE !="'.$id_c.'"');
    $selc.='<option  value="'.$id_c.'">'.$id_t.'</option>';

    while ($cl=$cla->fetch()){
        $selc.='<option value="'.$cl['ID_BOURSE'].'">'.$cl['TAUX'].'</option>';
    }
    echo $selc;

}

// mise a jour de la bourse des etudiants deja inscrient
if(isset($_POST['end_all_upd'])){
    extract($_POST);
    if(empty($motivation) AND !empty($observation)){
        echo 'motivation';
    }else if(empty($observation) AND !empty($motivation)){
        echo 'observation';
    }else if(empty($observation) AND empty($motivation)){
        echo 'motivation*observation';
    }
    else{
        $id_Inscri = $obj->Select('inscription',[],array("MATRICULE"=>$id_student,"ID_ANNEE"=>getAnnee(-1)['ID_ANNEE']))->fetch();
        $update_Hist = $obj->Insert('historique_bourse',['MATRICULE', 'ID_BOURSE', 'MOTIVATION', 'OBSERVATION', 'ID_ANNEE'],[$id_student,$selected_taux,$motivation,$observation,getAnnee(0)['ID_ANNEE']]);
        if($update_Hist==1){
            echo 1;
        }else{
            echo 0;
        }
    }
   
}

// selection de la bourse du nouveau etudiant
if(isset($_POST['submit_new_Scholar'])){
//    declartion des variables
    $taux_sring="";
    extract($_POST);
    // on selectionne la bourse courant de l'etudiant et la liste global des bourses disponible
    $student_Bourse = $obj->Requete("SELECT * FROM newetudiant n, bourse b WHERE b.ID_BOURSE=n.BOURSE AND n.ID_NEW_ETUDIANT='".$id_new_student."'");
    $all_Bourse = $obj->Requete("SELECT * FROM bourse ");
    // on verifie si on trouve au moins un resultat correspondant a la premere requete 
    if(is_object($student_Bourse)){
        if($back_bourse = $student_Bourse->fetch()){
            $taux_sring = '<option value ="'.$back_bourse['BOURSE'].'">'.$back_bourse['TAUX'].'</option>';
        }
    }
    if(is_object($all_Bourse)){
        while($back_all_bourse = $all_Bourse->fetch()){
            if($back_all_bourse['ID_BOURSE'] != $back_bourse['BOURSE']){
                $taux_sring .= '<option value ="'.$back_all_bourse['ID_BOURSE'].'">'.$back_all_bourse['TAUX'].' </option>';
            }
        }
            
    }
    
    echo  $taux_sring; 
}
// mise a jour de la bourse d'un nouveau etudiant
if(isset($_POST['end_update'])){
    extract($_POST);
    $update = $obj->Update('newetudiant',['BOURSE'],[$selected_taux],array("ID_NEW_ETUDIANT"=>$id_student));
    if($update==1){
        // 
        echo 1;
    }else{
        echo 0;
    }
}


//pour avoir les modules de la classe

if (isset($_POST['getMd'])){
    //$module=$obj->Select('module',[],array('ID_CLASSE'=>$_POST['id_classe']));
    // if($_POST['id_classe'] !=0){
    //     // $module=$obj->Requete("SELECT * FROM module m, enseigner e WHERE m.ID_CLASSE=".$_POST['id_classe']." AND e.ID_MODULE=m.ID_MODULE AND e.ID_PROFESSEUR=".$_POST["id_prof"]);
    //     $module=$obj->Requete("SELECT * FROM module m, enseigner e WHERE m.ID_CLASSE=".$_POST['id_classe']." AND e.ID_MODULE=m.ID_MODULE");
    // }else{
    //     $module=$obj->Requete("SELECT * FROM module m WHERE m.ID_CLASSE=".$_POST['id_classe']);
    // }
    // $all_mod="";
    // $all_mod.='<option value="" disabled selected>Select Modulus</option>';
    // while ($modu=$module->fetch()){
    //     $all_mod.='<option value="'.$modu['ID_MODULE'].'">'.$modu['NOM_MODULE'].'</option>';
    // }

    $module=$obj->Requete("SELECT * FROM module m, enseigner e WHERE m.ID_CLASSE=".$_POST['id_classe']." AND e.ID_MODULE=m.ID_MODULE AND e.ANNEE ='".getAnnee(0)['ID_ANNEE']."'");
    $all_mod='<option value="" disabled selected>Select Modulus</option>';
    while ($modu=$module->fetch()){
            $all_mod.='<option value="'.$modu['ID_MODULE'].'">'.$modu['NOM_MODULE'].'</option>';
            $exist_mod = 1;
        }
    if(isset($exist_mod)):
        echo $all_mod;
    else:
        echo 'none';
    endif;
}

// pour avoir les modules pour admine mark
if (isset($_POST['adm_modul'])){
    //$module=$obj->Select('module',[],array('ID_CLASSE'=>$_POST['id_classe']));
    $module=$obj->Requete("SELECT * FROM module m, enseigner e WHERE m.ID_CLASSE=".$_POST['id_classe']." AND e.ID_MODULE=m.ID_MODULE ");
    $all_mod="";
    $all_mod.='<option value="" disabled selected>Select Modulus</option>';
    while ($modu=$module->fetch()){
        $all_mod.='<option value="'.$modu['ID_MODULE'].'">'.$modu['NOM_MODULE'].'</option>';
    }
    echo $all_mod;
}

//Adding books

//Updating the books

if (isset($_POST["book"])){
    $the_books=$obj->Select('documents',[],array("CODE_LIVRE"=>$_POST['id_book']))->fetch();

    if($the_books['DEPARTMENT']!=0){
        $the_dep=$obj->Select("department",[],array('ID_DEPARTEMENT'=>$the_books['DEPARTMENT']))->fetch();
    } else{
        $the_dep['NOM_DEPARTEMENT']='both';
    }

    $content="";
    $content.='<div class="row">
                                    <div class="col-lg-6 py-lg-3 py-2">
                                        <img src="../../Bookcover/' .$the_books["PICTURE"].'"  alt="../../Bookcover/'.$the_books["PICTURE"].'" class="py-1" width="100px" , height="100px">
                                    </div>

                                    <!-------------------------------------- Cover Picture --------------------------------------->
                                    <div class="col-lg-6 py-lg-3 py-2">
                                        <div class="form-group">
                                            <label for="cover_pic">Cover Picture <span class="text-danger"> * </span></label>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="cover_pic" name="file">
                                                <label class="custom-file-label" for="cover_pic">Choose file</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-12 text-left mt-2">
                                        <hr>
                                    </div>

                                    <!-------------------------------------- Book Code --------------------------------------->
                                    <div class="col-lg-6 py-lg-3 py-2">
                                        <div class="form-group">
                                            <label for="code">Book Code <span class="text-danger"> * </span></label>
                                            <input readonly class="form-control" type="text" name="code" id="code" value="'.$the_books['CODE_LIVRE'].'">
                                        </div>
                                    </div>

                                    <!-------------------------------------- Book Title --------------------------------------->
                                    <div class="col-lg-6 py-lg-3 py-2">
                                        <div class="form-group">
                                            <label for="book_title">Book Title  <span class="text-danger"> * </span></label>
                                            <input type="text" name="book_title" id="book_title" class="form-control" value="'.$the_books['TITRE'].'"/>
                                        </div>
                                    </div>

                                    <!-------------------------------------- department --------------------------------------->
                                    <div class="col-lg-6 py-lg-3 py-2">
                                        <div class="form-group">
                                            <label for="department">Department  <span class="text-danger"> * </span></label>
                                            <select type="number" name="department" id="department" class="form-control" required>
                                                <option value="'.$the_books['DEPARTMENT'].'" selected>'.$the_dep['NOM_DEPARTEMENT'].'</option>';

    $depat=$obj->Select("department",[],[])->fetchAll();
    foreach ($depat as $dep):
        $content.='<option value="'.$dep["ID_DEPARTEMENT"].'" >'.$dep["NOM_DEPARTEMENT"].'</option>';
    endforeach;
    $content.='<option value=0 >Both</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-------------------------------------- Book Status --------------------------------------->
                                    <div class="col-lg-6 py-lg-3 py-2">
                                        <div class="form-group">
                                            <label for="initial_state">Book Status <span class="text-danger"> * </span></label>
                                            <select name="initial_state" id="initial_state" class="custom-select">
                                                <option value="0">Select the status
                                                 <option selected value="'.$the_books['BOOK_STATUS'].'">'.$the_books['BOOK_STATUS'].'</option>
                                                <option value="new">New</option>
                                                <option value="good">Good</option>
                                                <option value="old">Old</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-------------------------------------- Author --------------------------------------->
                                    <div class="col-lg-6 py-lg-3 py-2">
                                        <div class="form-group">
                                            <label for="author">Author  <span class="text-danger"> * </span></label>
                                            <input type="text" name="author" id="author" class="form-control" value="'.$the_books['AUTHEUR'].'">
                                        </div>
                                    </div>

                                    <!-------------------------------------- Edition Date --------------------------------------->
                                    <div class="col-lg-6 py-lg-3 py-2">
                                        <div class="form-group">
                                            <label for="edition_date">Edition Date  <span class="text-danger"> * </span></label>
                                            <input type="date" name="edition_date" id="edition_date" class="form-control" value="'.$the_books['DATE_EDITION'].'">
                                        </div>
                                    </div>

                                    <div class="col-lg-12 text-center my-3">
                                        <button type="reset" class="btn btn-outline-dark px-4 rounded-pill" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-outline-primary px-4 rounded-pill" name="updat_book" id="updat_book">Update Book</button>
                                    </div>
                                </div>

                                <script>
                                    document.querySelector(".custom-file-input").addEventListener("change", function() 
                                    {
                                        var fileName = document.querySelector(this).value.split("\").pop();
                                        document.querySelector(this).siblings(".custom-file-label").classList.add("selected").html(fileName);
                                    });
                                </script>
                                ';

    echo $content;
}

if (isset($_POST['veri'])){

        $var=$obj->Select('documents',["CODE_LIVRE"],array("CODE_LIVRE"=>$_POST['id']));
        if (is_object($var)){
            echo $var->fetch()["CODE_LIVRE"];
        }else{
            echo '';
        }
}

if (isset($_POST['ver'])){

    $var=$obj->Select($_POST['table'],[$_POST['colonne']],array($_POST['colonne']=>$_POST['ide']));
    if (is_object($var)){
        echo $var->fetch()["CODE_LIVRE"];
    }else{
        echo '';
    }
}


// recuperation des pourcentages de notes
if(isset($_POST['showeight'])){
    extract($_POST);
    $percentage = 0;
    $i = 0;
    $urls = '<input type="text" class="form-control w-50" id = "module_id" value ="'.$module.'" hidden>';
    $getWeight = $obj->Requete("SELECT * FROM devoirs WHERE ID_MODULE = ".$module."");
    while( $weight = $getWeight->fetch()){
        $urls .='<div class="input-group col-12 mb-2">
                <label class="col-12"> Weight of Test'.($i+1).'</label>
                <input type="text" class="form-control w-50" id ='.$i.' value ="'.$weight['POURCENTAGE'].'">
                <input type="button" class="btn btn-danger form-control mx-2 w-25" value = "Update" onclick="update_weight('.$weight['ID_DEVOIR'].','.$i.')"> 
            </div>
        ';
        $i++;
        $percentage += intval($weight['POURCENTAGE']);
    }
    $urls.='<label class="col-12 mt-4 "> Weight of Admin and participation</label><input type="text" class="form-control w-100 mx-3" id = "percent1" value ="10" disabled><h5 class = "text-center text-danger col-12 mt-4"> <span class = "text-primary"> Total Weights: '.($percentage+10).' / 100</span></h5>';
    
    echo $urls.'`'.($percentage+10);
}

// mise a jour des poids des notes
if(isset($_POST['upWeight'])){
    extract($_POST);
    $compareWeight = $obj->Requete("SELECT SUM(POURCENTAGE) as somVale FROM devoirs WHERE ID_MODULE = ".$id_module." AND ID_DEVOIR !=".$id_devoir." ORDER BY ID_DEVOIR ASC")->fetch();
    if($compareWeight['somVale']+$module_val<=90){
        $update_weight = $obj->Requete("UPDATE devoirs set POURCENTAGE=".$module_val." WHERE ID_DEVOIR = ".$id_devoir."");
        $percentage = 0;
        $url = '<input type="text" class="form-control w-50" id = "module_id" value ="'.$id_module.'" hidden>';
        $getWeights = $obj->Requete("SELECT * FROM devoirs WHERE ID_MODULE = ".$id_module."");
        $i =0;
        while( $weights = $getWeights->fetch()){
            $url .='<div class="input-group col-12 mb-2">
                        <label class="col-12"> Weight of Test'.($i+1).'</label>
                        <input type="text" class="form-control w-50" id ='.$i.' value ="'.$weights['POURCENTAGE'].'">
                        <input type="button" class="btn btn-danger form-control mx-2 w-25" value = "Update" onclick="update_weight('.$weights['ID_DEVOIR'].','.$i.')"> 
                    </div>
            ';
            $i++;
            $percentage += intval($weights['POURCENTAGE']);
        }
        $url.='<label class="col-12 mt-4 "> Weight of Admin and participation</label><input type="text" class="form-control w-100 mx-3" id = "percent1" value ="10" disabled><h5 class = "text-center text-danger col-12 mt-4"> <span class = "text-primary"> Total Percentage: '.($percentage+10).' / 100</span></h5>';

        echo $url.'`'.($percentage+10);
    }else{
        echo 1;
    }
       
}

// genertion des moyennes des etudiants

if(isset($_POST['mean'])){
    extract($_POST);
    $getWeight = array();
    
    // on recupere la liste des devoirs lies au module selectionne 
    $select_test = $obj->Requete("SELECT * FROM devoirs WHERE ID_MODULE=".$module_id." ORDER BY ID_DEVOIR ASC");
    while($getElem = $select_test->fetch()){
        array_push($getWeight,$getElem['POURCENTAGE']);
    }
    $select_note = $obj->Requete("SELECT * FROM notes WHERE ID_MODULE=".$module_id." ");
    while($get_note =$select_note->fetch()){
        $moyenne=0;
        $total_note=0;
        for ($cpt = 0; $cpt<count($getWeight); $cpt++){
            $moyenne += floatval((($getWeight[$cpt]*$get_note['NOTE'.($cpt+1)]))/100);
            
        } 
        $pain = 0;
        if(!empty($get_note["SANCTION"])){$pain = $get_note["SANCTION"];}
        $id_note = $get_note['ID_NOTE'];
        $moyenne+= floatval(($get_note['NOTE_ADMINISTRATION']+$get_note['NOTE_PARTICIPATION']-$pain)*0.1);
        $add_moyenne = $obj->Update('notes',['MOYENNE'],[$moyenne],array("ID_NOTE"=>$id_note));
        
    }
    if($add_moyenne ==1){
        $requetes = $obj->Requete("SELECT n.MATRICULE, n.ID_MODULE , e.NOM , e.PRENOM , n.NOTE1 , n.NOTE2 , n.NOTE3 , n.NOTE_PARTICIPATION, n.NOTE_ADMINISTRATION, n.SANCTION, n.MOYENNE FROM notes n , etudiant e WHERE e.MATRICULE = n.MATRICULE AND n.ID_MODULE='".$module_id."' AND n.ANNEE_SCOLAIRE=".getAnnee(0)['ID_ANNEE']." ORDER BY n.MATRICULE ASC");
        $infos = '';
        while($show_info = $requetes->fetch()){
            
            if(!empty($show_info["SANCTION"]))
                {
                    $sanction = $show_info["SANCTION"];
                }
            else
                {
                    $sanction = 0;
                }
           $infos.=' 
            <tr> 
                <td>'.$show_info['MATRICULE'].'</td>
                <td data-name="PRENOM" class="PRENOM" data-type="text" data-pk="' .$show_info['MATRICULE']. '">' .$show_info['PRENOM'].'</td>
                <td data-name="NOM" class="NOM" data-type="text" data-pk="' .$show_info['MATRICULE']. '">' .$show_info['NOM'].'</td>
                <td data-name="NOTE1" class="NOTE1" data-type="number" data-pk="' .$show_info['MATRICULE']. '">'.$show_info['NOTE1'].'</td>
                <td data-name="NOTE2" class="NOTE2" data-type="number" data-pk="' .$show_info['MATRICULE']. '">'.$show_info['NOTE2'].'</td>
                <td data-name="NOTE3" class="NOTE3" data-type="number" data-pk="' .$show_info['MATRICULE']. '">'.$show_info['NOTE3'].'</td>
                <td data-name="NOTE_PARTICIPATION" class="NOTE_PARTICIPATION" data-type="number" data-pk="' .$show_info['MATRICULE']. '">'.$show_info['NOTE_PARTICIPATION'].'</td>
                <td data-name="NOTE_ADMINISTRATION" class="NOTE_ADMINISTRATION" data-type="number" data-pk="' .$show_info['MATRICULE'].'">'.$show_info['NOTE_ADMINISTRATION'].'</td>
                <td data-name="SANCTION" class="SANCTION" data-type="number" data-pk="' .$show_info['MATRICULE']. '">'.$sanction.'</td> 
                <td data-name="MOYENNE" class="MOYENNE" data-type="number" data-pk="' .$show_info['MATRICULE']. '">' .$show_info['MOYENNE'].'</td>
            </tr>';
        }
        
        echo $infos;
        
    }
    
   
}
if(isset($_POST['get_mod_id'])){
    extract($_POST);
    $note1 = $obj->Requete("SELECT * FROM module m, devoirs d WHERE m.ID_MODULE = d.ID_MODULE AND m.ID_MODULE=".$get_mod_val."");
    $dev_list = 0;
    $dev_column=array();

    while($rep = $note1->fetch()){
        $dev_list++;
        array_push($dev_column,'NOTE'.$dev_list);
        
    }
    $head = '
            <th>Matricule</th>
            <th>Firt Name</th>
            <th>Last Name</th>';
    
        for($i=0;$i<count($dev_column);$i++){

            $head .='<th>Mark '.($i+1).'</th>';
        }
        $head .='<th>Participation Mark</th>';
    
    echo $head;
}

if (isset($_POST['book_ret'])){
    $book_retur=$obj->Select('louer',[],array("ID_LOUER"=>$_POST['id_louer']))->fetch();
    $booking='';

    $booking.='  
    <input style="display: none" name="ide" value="'.$_POST['id_louer'].'"/>
    <div class="row">
    <!-- ------------------------------------------------------------------------------------------------------- -->
        <div class="col-lg-12 text-left">
            <h6 class="font-weight-bold text-bluesky">Student Information</h6>
            <hr class="bg-gradient-primary">
        </div>

        <!-------------------------------------- MATRICULE --------------------------------------->
        <div class="col-lg-6 py-lg-3 py-2">
            <div class="form-group">
                <label for="matricule">Matricule <span class="text-danger"> </span></label>
                <input class="form-control" type="text" name="matricule" id="matricule" value="'.$book_retur['MATRICULE'].' " readonly>
            </div>
        </div>

        <!-------------------------------------- BOOK CODE --------------------------------------->
        <div class="col-lg-6 py-lg-3 py-2">
            <div class="form-group">
                <label for="code">Book Code <span class="text-danger"> </span></label>
                <input class="form-control" type="text" name="code" id="code" value="'.$book_retur['CODE_LIVRE'].'" readonly>
            </div>
        </div>


    <!-- ------------------------------------------------------------------------------------------------------- -->
        <div class="col-lg-12 text-left mt-2">
            <h6 class="font-weight-bold text-bluesky">Booking Period</h6>
            <hr class="bg-gradient-primary">
        </div>

        <!-------------------------------------- LOAN DATE --------------------------------------->
        <div class="col-lg-6 py-lg-3 py-2">
            <div class="form-group">
                <label for="loan_date">Start Date <span class="text-danger"> </span></label>
                <input class="form-control" type="text" name="loan_date" id="loan_date" value="'.$book_retur['DATE_EMPRUNT'].'" readonly>
            </div>
        </div>

        <!-------------------------------------- Return DATE --------------------------------------->
        <div class="col-lg-6 py-lg-3 py-2">
            <div class="form-group">
                <label for="return_date">Return Date <span class="text-danger">  </span></label>
                <input class="form-control" type="text"  value="'.$book_retur['DATE_REMISE'].'" readonly>
            </div>
        </div>

        <div class="col-lg-12 py-lg-3 py-2">
            <button type="button" class="btn btn-secondary dropdown-toggle w-100" data-toggle="dropdown" aria-expanded="false">
                Do you need more time ? <span class="sr-only"></span>
            </button>
            <div class="dropdown-menu w-100 px-2">
                <label for="return_date">Return Date <span class="text-danger"> * </span></label>
                <input onchange="time_book(this.value)" class="form-control" type="date" name="return_date" id="return_date"  name="vide">
            </div>
        </div>


        <!-- ------------------------------------------------------------------------------------------------------- -->
        <div class="col-lg-12 text-left mt-2">
            <h6 class="font-weight-bold text-bluesky">Book Status</h6>
            <hr class="bg-gradient-primary">
        </div>

        <!-------------------------------------- Book Initial State --------------------------------------->
        <div class="col-lg-6 py-lg-3 py-2">
            <div class="form-group">
                <label for="initial_state">Book Initial Status <span class="text-danger"> </span></label>
                <input class="form-control" type="text" name="initial_state" id="initial_state" value="'.$book_retur['ETAT_EMPRUNT'].'" readonly>
            </div>
        </div>

        <!-------------------------------------- Book Return State --------------------------------------->
        <div class="col-lg-6 py-lg-3 py-2" >
            <div class="form-group" id="back_book">
                <label for="return_state">Book Return Status <span class="text-danger"> * </span></label>
                <select  name="return_state" id="return_state" class="form-control">
                    <option value="0" selected disabled>Select the status</option>
                    <option value="New">New</option>
                    <option value="Good">Good</option>
                    <option value="Old">Old</option>
                </select>
            </div>
        </div>

        

        <div class="col-lg-12 text-right my-3">
            <button type="reset" class="btn btn-outline-dark px-4 rounded-pill" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-outline-primary px-4 rounded-pill" name="update_booking" id="update_booking">Update Booking</button>
        </div>
    </div>';
    echo $booking;
}


// Modal for survey upating
if (isset($_POST['surv'])){
    //$surv_retur=$obj->Select('survey_set',[],array('ID_SURVEYS'=$_POST['id_surv']))->fetch();
    $surv_retur = $obj->Requete("SELECT s.ID_SURVEYS, c.NOM_CLASSE,m.NOM_MODULE,s.SURVEY_DESCRIPTION,s.START_DATE,s.END_DATE FROM survey_set s , module m , classe c WHERE s.ID_MODULE = m.ID_MODULE AND m.ID_CLASSE = c.ID_CLASSE AND s.ID_ANNEE='".getAnnee(0)['ID_ANNEE']."' AND ID_SURVEYS=".$_POST['id_surv'])->fetch();
    $survey='';

    $survey.='
        <input name="sid" style="display: none"  value="'.$_POST['id_surv'].'"/>
        <div class="col-lg-6">
            <div class="row border-right border-border-left-dark">

                <!-- Modulus -->
                <div class="col-lg-6 py-3">
                    <div class="group-form">
                        <label for="class" class="font-weight-bold">Class <span class="text-danger"> *</span></label>
                        <select type="text" class="form-control" name="class" id="class" disabled>
                            <option value="">'.$surv_retur['NOM_CLASSE'].'</option>
                        </select>
                    </div>
                </div>

                <div class="col-lg-6 py-3">
                    <div class="group-form">
                        <label for="modulus" class="font-weight-bold">Modulus <span class="text-danger"> *</span></label>
                        <select type="text" class="form-control" name="modulus" id="modulus" disabled>
                            <option value="">'.$surv_retur['NOM_MODULE'].'</option>
                        </select>
                    </div>
                </div>

                <!-- Start Date -->
                <div class="col-lg-12 py-3">
                    <div class="group-form">
                        <label for="start_date" class="font-weight-bold">Start Date <span class="text-danger"> *</span></label>
                        <input type="date" class="form-control" name="start_date" id="start_date" value="'.$surv_retur['START_DATE'].'">
                    </div>
                </div>

                <!-- End Date -->
                <div class="col-lg-12 py-3">
                    <div class="group-form">
                        <label for="end_date" class="font-weight-bold">End Date <span class="text-danger"> *</span></label>
                        <input type="date" class="form-control" name="end_date" id="end_date" value="'.$surv_retur['END_DATE'].'">
                    </div>
                </div>
            </div>
        </div>
    
        <div class="col-lg-6">
            <div class="row">
                <div class="col-lg-12 py-3">
                    <div class="group-form">
                        <label for="description" class="font-weight-bold">Description <span class="text-danger"> *</span></label>
                        <textarea class="form-control" name="description" id="description" style="height:240px;">'.$surv_retur['SURVEY_DESCRIPTION'].'</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12 text-center py-5">
            <button type="reset" class="btn btn-outline-danger rounded-pill px-4" data-dismiss="modal">Reset</button>
            <button type="submit" class="btn btn-outline-primary rounded-pill" name="update_survey" id="update_survey">Update the survey</button>
        </div>';
        echo $survey;
}


#--------------------------------------------- GESTION DES SURVEYS -------------------------------------------------
extract($_GET);

#-------------------------------------------- Add new question -----------------------------------------------------
if(isset($action) && $action == 'add_question')
{
    extract($_POST);
    
    $data = " SURVEY_ID= '$id' ";
    $data .= ", QUESTION='$question' ";
    $data .= ", TYPE='$type' ";
    $data .= ", ID_ANNEE=".getAnnee(0)['ID_ANNEE'];

    if($type != 'textfield_s')
    {
        $arr = array();
        foreach ($label as $k => $v) 
        {
            $i = 0 ;
            while($i == 0)
            {
                $k = substr(str_shuffle(str_repeat($x='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(5/strlen($x)) )),1,5);
                if(!isset($arr[$k]))
                    $i = 1;
            }
            $arr[$k] = $v;
        }
        $data .= ", FRM_OPTION='".json_encode($arr)."' ";
    }
    else
    {
        $data .= ", FRM_OPTION='' ";
    }

    $save = $obj->Requete("INSERT INTO questions set $data");

    if($save) {
        echo("success");
    }
    
}

#-------------------------------------------- Delete question -----------------------------------------------------

if(isset($action) && $action == 'delete_question')
{
    extract($_POST);
    $delete = $obj->Requete("DELETE FROM questions where ID_QUESTIONS = '".$question_id."'");
    if($delete)
    {
        echo('success');
    }
}

#-------------------------------------------- Edit question -----------------------------------------------------

if(isset($action) && $action == 'edit_question')
{
    extract($_POST);
    $data = " SURVEY_ID= '$id' ";
    $data .= ", QUESTION='$edit_question_name' ";
    $data .= ", TYPE='$edit_type' ";
    $data .= ", ID_ANNEE=".getAnnee(0)['ID_ANNEE'];

    if($edit_type != 'textfield_s')
    {
        $arr = array();
        foreach ($label as $k => $v) 
        {
            $i = 0 ;
            while($i == 0)
            {
                $k = substr(str_shuffle(str_repeat($x='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(5/strlen($x)) )),1,5);
                if(!isset($arr[$k]))
                    $i = 1;
            }
            $arr[$k] = $v;
        }
        $data .= ", FRM_OPTION='".json_encode($arr)."' ";
    }
    else
    {
        $data .= ", FRM_OPTION='' ";
    }

    $save = $obj->Requete("UPDATE questions SET $data WHERE ID_QUESTIONS = '" .$qid. "'");

    if($save) {
        echo("success");
    }
}

if(isset($action) && $action == 'get_editable_data' && isset($qid))
{
    $query = $obj->Requete("SELECT * FROM questions where ID_QUESTIONS = '".$qid. "'")->fetch();
    
    print(json_encode($query));

}


#------------------------------------------ Allow student to answer survey questions -------------------------------

if(isset($action) && $action == 'answer_questions')
{
    extract($_POST);

    foreach($qid as $k => $v)
    {
        $data = " SURVEY_ID=$survey_id ";
        $data .= ", QUESTION_ID='$qid[$k]' ";
        $data .= ", ID_USER='{$_SESSION['ID_USER']}' ";
        $data .= ", ID_ANNEE=".getAnnee(0)['ID_ANNEE'];
        if($type[$k] == 'check_opt')
        {
            $data .= ", answer='[".implode("],[",$answer[$k])."]' ";
        }
        else
        {
            $data .= ", answer='$answer[$k]' ";
        }

        $save = $obj->Requete("INSERT INTO answers set $data");
    }

    if($save)
    {
        echo('success');
    }  
}
#-------------------------------------------- Department chief submitting timetable -----------------------------------------------------

if(isset($_POST['submit_timetable']))
{
    extract($_POST);
    extract($_FILES);
    
    $ds = DIRECTORY_SEPARATOR;  //1

    $storeFolder = 'Timetable';   //2
    
    if (!empty($_FILES)) 
    {
    
        $tempFile = $_FILES['file']['tmp_name'];          //3             
        $targetPath = 'assets' . $ds. $storeFolder . $ds;  //4
        $link = $_FILES['file']['name'];
        $targetFile =  $targetPath. $link;  //5
        $files_name = move_uploaded_file($tempFile,$targetFile); //6
        
        $requete = $obj -> Requete("INSERT INTO `timetable`(`ID_CLASSE`, `WEEK_NUMBER`, `START_DATE`,`TIMETABLE_FILE`, `ID_ANNEE`, `DATE_INSERTION`) VALUES ('$classroom' ,$week_number,'$week_range', '$link', '". getAnnee(0)['ID_ANNEE']. "',NOW())");

    }
    if($requete)
    {
        echo('success');
    }
    
}

if(isset($action) && $action == 'update_test')
{
    extract($_POST);
    $max_date = $obj->Requete('SELECT NOW() + INTERVAL 1 DAY as dat')->fetch();
    $max_date = $max_date['dat'];
    $query = $obj->Requete("SELECT d.ID_DEVOIR, c.NOM_CLASSE , m.NOM_MODULE , d.DATE_DEV , d.DEVOIR , d.POURCENTAGE , d.STATUT FROM devoirs d , professeur pr , enseigner e , classe c , module m WHERE d.ID_MODULE = m.ID_MODULE AND d.ID_MODULE = e.ID_MODULE AND e.ID_PROFESSEUR =  pr.ID_PROFESSEUR AND m.ID_CLASSE = c.ID_CLASSE AND d.ID_ANNEE = '". getAnnee(0)['ID_ANNEE']."' AND d.ID_DEVOIR = '".$id_test."'")->fetch();

    $modal = '<div class="row mx-lg-5 py-2"> 
                <input type="hidden" name="id_dev" value="'.$query['ID_DEVOIR'].'">
                <div class="col-12 col-md-6 col-lg-6 pb-3 pb-lg-1">
                    <div class="form-group">
                        <label>Classroom</label>
                        <input type="text" class="form-control" value="'.$query['NOM_CLASSE'].'" readonly>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-6 pb-3 pb-lg-1">
                    <div class="form-group">
                        <label>Modulus</label>
                        <input type="text" class="form-control" value="'.$query['NOM_MODULE'].'" readonly>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-6 pb-3 pb-lg-1">
                    <div class="form-group">
                        <label>Percentage</label>
                        <input type="text" class="form-control" name="pourcentage" value="'.$query['POURCENTAGE'].'" required>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-6 pb-3 pb-lg-1">
                    <div class="form-group">
                        <label>Test Date</label>
                        <input type="text" name="date_dev" onfocus="(this.type=\'datetime-local\') , (this.min=\''. substr($max_date, 0, 10) .'T07:59\')"  class="form-control" value="'.$query['DATE_DEV'].'" required>
                    </div>
                </div>
                
                <div class="col-lg-12 text-center my-3">
                    <button type="reset" class="btn btn-outline-dark px-4 rounded-pill" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-outline-primary px-4 rounded-pill" name="update_test" id="update_test">Update Test</button>
                </div>

            </div>';

    echo($modal);
    
}

if(isset($action) && $action == 'del_test')
{
    extract($_POST);

    $modal = '<div class="row py-2"> 
                <input type="hidden" name="id_dev" value="'.$id_test.'">
                <div class="col-12 pb-3 pb-lg-1">
                    <p>Do you really want to delete this test. Notice that this action is irreversible</p>
                </div>

                
                <div class="col-lg-12 text-center my-3">
                    <button type="reset" class="btn btn-outline-dark px-4 rounded-pill" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger px-4 rounded-pill" name="del_test" id="del_test">Update Test</button>
                </div>

            </div>';

    echo($modal);
    
}

if(isset($action) && $action == 'activate_test')
{
    extract($_POST);
    $valide=$obj->Requete("SELECT NOW() AS now, DATE_DEV FROM devoirs WHERE ID_DEVOIR=".$id_test." AND ID_ANNEE=".getAnnee(0)["ID_ANNEE"])->fetch();
    $message=' <p>Do you really want to activate this test.</p>
                    <p class="text-bluesky">Notice that this action will allow student to see the test file.</p>';
    $etat="";
    if ($valide['now']<$valide['DATE_DEV']){
        $message=' <p>Ooooooth !!!</p>
                    <p class="text-warning">This test can \'t be activated for the moment.</p>';
        $etat="disabled";
    }

    $modal = '<div class="row py-2"> 
                <input type="hidden" name="id_dev" value="'.$id_test.'">
                <div class="col-12 pb-3 pb-lg-1">
                   '.$message.'
                </div>

                
                <div class="col-lg-12 text-center my-3">
                    <button type="submit" class="btn btn-outline-danger px-4 rounded-pill" name="deactivate_test" id="deactivate_test">Deactivate</button>
                    <button '.$etat.' type="submit" class="btn btn-outline-primary px-4 rounded-pill" name="activate_test" id="activate_test"> Activate </button>
                </div>

            </div>';

    echo($modal);
    
}
#-------------------------------------------- Update profile pic -----------------------------------------------------

if(isset($_POST['update_pic']))
{
    extract($_POST);
    extract($_FILES);
    
    $ds = DIRECTORY_SEPARATOR;  //1

    $storeFolder = 'media';   //2
    
    if (!empty($_FILES)) 
    {
    
        $tempFile = $_FILES['file']['tmp_name'];          //3             
        $targetPath = 'assets' . $ds. $storeFolder . $ds;  //4
        $link = $_FILES['file']['name'];
        $targetFile =  $targetPath. $link;  //5
        $files_name = move_uploaded_file($tempFile,$targetFile); //6
        
        $requete = $obj -> Requete("UPDATE `user` SET `PROFILE_PIC` = '".$link."' WHERE `ID_USER` = '".$id_user."'");

    }
    if($requete)
    {
        echo('success');
    }
    
}
#-------------------------------------------- Secretary Canteen Fees Management -----------------------------------------------------

if(isset($action) && $action == 'get_editable_canteen_fee' && isset($canteen_fee_id))
{
    $query = $obj->Requete("SELECT * FROM cantine where ID_MOIS = '".$canteen_fee_id. "'")->fetch();

    $modal = '<div class="row mx-lg-5 py-2"> 
                <div class="col-12">
                    <label>'.$query['MOIS'].' <span class="text-danger"> * </span></label>
                </div>

                <div class="col-12 col-md-6 col-lg-6">
                    <div class="form-group">
                        <input type="number" name="month_fees" id="month_fees" class="form-control" value="'.$query['PRIX'].'">
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-6">
                    <div class="form-group">
                        <input type="text" onfocus="(this.type=\'datetime-local\')" name="payment_limit" id="payment_limit" class="form-control" value="'.date("M d, Y",strtotime($query['DATE_LIMITE_PAIEMENT'])).'">
                        <input type="hidden" name="canteen_fee_id" value="'.$query['ID_MOIS'].'">
                    </div>
                </div>


                <div class="col-lg-12 text-center my-3">
                    <button type="reset" class="btn btn-outline-dark px-4 rounded-pill" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-outline-primary px-4 rounded-pill" name="update_month_fees" id="update_month_fees">Update Month Fees</button>
                </div>
            </div>';
    
    echo($modal);

}

if(isset($action) && $action == 'book_canteen')
{
    extract($_POST);

    $requete = $obj->Requete("INSERT INTO `abonnements`(`ID_MOIS`, `MATRICULE`, `ID_ANNEE`, `DATE_ABONNEMENT`, `DATE_FIN_ABONNEMENT`, `NUMBER_DAYS`, `COST_FEES`, `STATUT`) VALUES ('$month', '$matricule', '".getAnnee(0)["ID_ANNEE"]."' , NOW() , DATE_ADD(NOW(), INTERVAL '$nb_days' DAY) ,'$nb_days', '$cost_fees', 'actif')");
    
    if($requete)
    {
        echo("success");
    }
}

// ---------------------------------------------------- Student Booking Canteen -----------------------------------------------------------------------------------

if(isset($action) && $action == 'get_canteen_fee' && isset($fee_id))
{
    $query = $obj->Requete("SELECT * FROM cantine where ID_MOIS = '".$fee_id. "'")->fetch();

    $modal = '<div class="row mx-lg-5 py-2"> 

                <div class="col-12 col-md-6 col-lg-6 pb-3 pb-lg-1">
                    <div class="form-group">
                        <label>Month</label>
                        <input type="text" class="form-control" value="'.$query['MOIS'].'" readonly>
                        <input type="hidden" name="canteen_fee_id" value="'.$query['ID_MOIS'].'">
                        <input type="hidden" name="start_date" value="'.$query['DATE_LIMITE_PAIEMENT'].'">
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-6 pb-3 pb-lg-1">
                    <div class="form-group">
                        <label>Price</label>
                        <input type="text" class="form-control" value="'.$query['PRIX'].'" readonly>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-6 pb-3 pb-lg-1">
                    <div class="form-group">
                        <label>Payment Deadline</label>
                        <input type="text" class="form-control" value="'.date("M d, Y",strtotime($query['DATE_LIMITE_PAIEMENT'])).'" readonly>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-6 pb-3 pb-lg-1">
                    <div class="form-group">
                        <label>Cost Per Day (fcfa) </label>
                        <input type="text" name="cost_per_day" id="cost_per_day" class="form-control" readonly value="'. $query['COST_PER_DAY'] .'">
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-6 pb-3 pb-lg-1">
                    <div class="form-group">
                        <label>How much days do you need ? <span class="text-danger"> * </span></label>
                        <input type="number" name="nb_days" id="nb_days" min="1" max="'.$query['PRIX']/$query['COST_PER_DAY'].'" placeholder="All the month : '.$query['PRIX']/$query['COST_PER_DAY'].' days" class="form-control" required>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-6 pb-3 pb-lg-1">
                    <div class="form-group">
                        <label for="cost_fees">Cost Fees</label>
                        <input type="number" class="form-control" name="cost_fees" id="cost_fees" readonly>
                    </div>
                </div>
                
                <div class="col-lg-12 text-center my-3">
                    <button type="reset" class="btn btn-outline-dark px-4 rounded-pill" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-outline-primary px-4 rounded-pill" name="book_this_month" id="book_this_month">Book Now</button>
                </div>
            </div>
            <script>

                document.getElementById("nb_days").addEventListener("keyup", function(){ 

                    var nb_de_jr = Number(document.getElementById("nb_days").value);
                    var cost_fees = document.getElementById("cost_fees");
                    var cost_per_day = Number(document.getElementById("cost_per_day").value) ;

                    if (nb_de_jr != "" && nb_de_jr != 0) {
                        cost_fees.value = nb_de_jr * cost_per_day;
                    }
                });
            </script>
            ';
    
    echo($modal);

}

#-------------------------------------------- Secretary Canteen Bookings Management -----------------------------------------------------


if(isset($action) && $action == 'get_pending_booking_editable_data' && isset($id_abonnement))
{
    $query = $obj->Requete("SELECT * FROM abonnements a, cantine c where a.ID_MOIS = c.ID_MOIS AND a.ID_ABONNEMENT = '".$id_abonnement. "'")->fetch();

    $modal = '<div class="row mx-lg-5 py-2"> 
                <div class="col-12 col-md-6 col-lg-6  pb-3 pb-lg-1">
                    <div class="form-group">
                        <label for="'.$query['MATRICULE'].'">Matricule</label>
                        <input class="form-control" type="text" id="'.$query['MATRICULE'].'" name="matricule" value="'.$query['MATRICULE'].'" readonly>
                    </div>
                </div>

                <input type="hidden" name="id_abonnements" value="'.$query['ID_ABONNEMENT'].'">
                <input type="hidden" name="start_date" value="'.$query['DATE_DEBUT_ABONNEMENT'].'">

                <div class="col-12 col-md-6 col-lg-6  pb-3 pb-lg-1">
                    <div class="form-group">
                        <label for="month">Month</label>
                        <input class="form-control" type="text" id="month" name="matricule" value="'.$query['MOIS'].'" readonly>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-6 pb-3 pb-lg-1">
                    <div class="form-group">
                        <label for="cost_per_day">Cost Per Day (fcfa) </label>
                        <input type="text" name="cost_per_day" id="cost_per_day" class="form-control" readonly value="'. $query['COST_PER_DAY'] .'">
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-6 pb-3 pb-lg-1">
                    <div class="form-group">
                        <label for="cost_fees">Cost Fees (fcfa)</label>
                        <input type="number" class="form-control" name="cost_fees" id="cost_fees" value="'.$query['COST_PER_DAY']*$query['NUMBER_DAYS'].'" readonly>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-6 pb-3 pb-lg-1">
                    <div class="form-group">
                        <label for="nb_days">Number of days</label>
                        <input class="form-control" type="number" min="1" max="'.$query['PRIX']/$query['COST_PER_DAY'].'" id="nb_days" name="nb_days" value="'.$query['NUMBER_DAYS'].'" required>
                    </div>
                </div>

                <div class="col-lg-12 text-center my-3">
                    <button type="reset" class="btn btn-outline-dark px-4 rounded-pill" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-outline-primary px-4 rounded-pill" name="validate_book" id="validate_book">Validate Booking</button>
                </div>

              </div>
              <script>

                document.getElementById("nb_days").addEventListener("keyup", function(){ 

                    var nb_de_jr = Number(document.getElementById("nb_days").value);
                    var cost_fees = document.getElementById("cost_fees");
                    var cost_per_day = Number(document.getElementById("cost_per_day").value) ;

                    if (nb_de_jr != "" && nb_de_jr != 0) {
                        cost_fees.value = nb_de_jr * cost_per_day;
                    }
                });
              </script>';
    
    echo($modal);

}

if(isset($action) && $action == 'delete_pending_booking')
{
    extract($_POST);
    $delete = $obj->Requete("DELETE FROM abonnements where ID_ABONNEMENT = '".$id_abonnement."'");
    if($delete)
    {
        echo('success');
    }
}

#-------------------------------------------- Student Book Loan  -----------------------------------------------------

if(isset($action) && $action == 'get_booking_book_id' && isset($book_id))
{
    $can_book = $obj->Requete("SELECT count(DISTINCT(l.ID_LOUER)) as nb_location FROM louer l , documents d WHERE d.CODE_LIVRE = l.CODE_LIVRE AND l.MATRICULE = (SELECT MATRICULE FROM etudiant WHERE ID_USER = '".$_SESSION["ID_USER"]."') AND (d.STATUT='pending_for_confirmation' OR d.STATUT='in_location')")->fetch();
    if($can_book["nb_location"] > 0)
    {
        $modal = '<div class="col-md-12">
                    <div class="alert alert-danger">Sorry but you can not hold two (2) books at the same time. <br> Returned the previous book before being able to loan an other book.</div>
                  </div>';
    }
    else
    {
        $modal = '
                    <div class="col-md-12 text-center">
                        <p>Do you really want to loan this book.</p>
                        <input type="hidden" name="code_livre" id="code_livre" value="'.$book_id.'">
                        <button  data-dismiss="modal" class="btn btn-outline-danger rounded-pill" aria-label="Close">I don\'t</button>
                        <button type="submit" class="btn btn-outline-primary rounded-pill" name="btn_loan">Loan the book</button>
                    </div>
                ';
    }
    
    echo($modal);

}

# -------------------------------------------- Admin Booking Deletion ------------------------------------------------

if(isset($action) && $action == 'delete_book_pending_booking')
{
    extract($_POST);
    $delete = $obj->Requete("DELETE FROM louer where ID_LOUER = '".$id_louer."'");
    if($delete)
    {
        echo('success');
    }
}

if(isset($action) && $action == 'validate_booking_book_id' && isset($id_louer))
{
    $requete = $obj->Requete("SELECT * FROM louer l , documents d WHERE l.CODE_LIVRE = d.CODE_LIVRE AND l.ID_LOUER = '$id_louer'")->fetch();
    $etudiant = $obj->Requete("SELECT NOM,PRENOM FROM etudiant WHERE MATRICULE='".$requete['MATRICULE']."'")->fetch();
    $date = $obj->Requete("SELECT NOW() as current_dates , DATE_ADD(NOW() , INTERVAL 2 WEEK) as return_date")->fetch();

    $modal = '  <div class="col-12 col-md-6 col-lg-6  pb-3 pb-lg-1">
                    <div class="form-group">
                        <label for="">Matricule</label>
                        <input class="form-control" name="matricule" type="text" value="'.$requete["MATRICULE"].'" readonly>
                    </div>
                </div>

                <input type="hidden" name="id_louer" value="'.$requete["ID_LOUER"].'">

                <div class="col-12 col-md-6 col-lg-6  pb-3 pb-lg-1">
                    <div class="form-group">
                        <label for="">Full Name</label>
                        <input class="form-control" name="names" type="text" value="'.$etudiant["PRENOM"].' '.$etudiant["NOM"].'" readonly>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-6  pb-3 pb-lg-1">
                    <div class="form-group">
                        <label for="">Book Code</label>
                        <input class="form-control" name="code_livre" type="text" value="'.$requete["CODE_LIVRE"].'" readonly>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-6  pb-3 pb-lg-1">
                    <div class="form-group">
                        <label for="">Book Title</label>
                        <input class="form-control" name="book_title" type="text" value="'.$requete["TITRE"].'" readonly>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-6  pb-3 pb-lg-1">
                    <div class="form-group">
                        <label for="">Start Date</label>
                        <input class="form-control" name="start_date" type="text" value="'.date("M d, Y",strtotime($date["current_dates"])).'" readonly>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-6  pb-3 pb-lg-1">
                    <div class="form-group">
                        <label for="">Return Date</label>
                        <input class="form-control" name="return_date" type="text" value="'.date("M d, Y",strtotime($date["return_date"])).'" readonly>
                    </div>
                </div>

                <div class="col-md-12 text-center my-3">
                    <input type="hidden" name="id_louer" id="id_louer" value="'.$id_louer.'">
                    <button  data-dismiss="modal" class="btn btn-outline-danger rounded-pill" aria-label="Close">Close Modal</button>
                    <button type="submit" class="btn btn-outline-primary rounded-pill" name="validate_loan">Validate The Booking</button>
                </div>
            ';
    
    echo($modal);

}

# -------------------------------------------- Get Chart Modules ------------------------------------------------

if(isset($action) && $action == 'get_chart_mark' && isset($module))
{
    if($module != '0'):
        $query = $obj->Requete("SELECT n.NOTE1 , n.NOTE2 , n.NOTE3 FROM notes n , module m  WHERE n.ID_MODULE = m.ID_MODULE AND n.ID_MODULE = '".$module."' AND n.MATRICULE = (SELECT e.MATRICULE FROM etudiant e WHERE e.ID_USER = '".$_SESSION["ID_USER"]."') AND n.ANNEE_SCOLAIRE = '".getAnnee(0)["ID_ANNEE"]."'")->fetch();
        foreach ($query as $key => $value) {
            if(!empty($value))
            {
                $marks[] = (float)$value;
                $matieres[] = ucfirst($key);
            }
        }
    else:
        $query = $obj->Requete("SELECT NOTE1 , NOTE2 , NOTE3 FROM `notes` WHERE MATRICULE = (SELECT e.MATRICULE FROM etudiant e WHERE e.ID_USER = '".$_SESSION["ID_USER"]."') AND ANNEE_SCOLAIRE = '".getAnnee(0)["ID_ANNEE"]."'");
        $matieres = []; $marks = [];
        while($note = $query->fetch()):
            foreach ($note as $key => $value) {
                if(!empty($value))
                {
                    $marks[] = (float)$value;
                    $matieres[] = ucfirst($key);
                }
            }
        endwhile;
    endif;

    $set = [
        'marks'=>$marks,
        'matieres'=>$matieres
    ];

    print(json_encode($set));

}

# -------------------------------------------- Load Students ------------------------------------------------

if(isset($action) && $action == 'load_student' && isset($classroom))
{
    if($classroom != 'all'):
        $query = $obj->Requete("SELECT e.MATRICULE , e.PRENOM , e.NOM , e.SEXE , u.ID_USER , u.PROFILE_PIC FROM inscription i , etudiant e , user u WHERE i.MATRICULE = e.MATRICULE AND e.ID_USER = u.ID_USER AND i.ID_CLASSE = '".$classroom."' AND i.ID_ANNEE = '".getAnnee(0)["ID_ANNEE"]."'")->fetchAll();
        
    else:
        $query = $obj->Requete("SELECT e.MATRICULE , e.PRENOM , e.NOM , e.SEXE , u.ID_USER , u.PROFILE_PIC FROM inscription i , etudiant e , user u WHERE i.MATRICULE = e.MATRICULE AND e.ID_USER = u.ID_USER AND i.ID_ANNEE = '".getAnnee(0)["ID_ANNEE"]."'")->fetchAll();
        
    endif;

    $student_info = '<style>
                        .notify_badge{
                            position: absolute;
                            top: 0px;
                            right: 2%;
                            opacity: 0.8;
                            text-align: center;
                        }

                        .notify_badge{
                            overflow: hidden;
                        }

                        .notify_badge i{
                            transition: all 1.5s ease;
                        } 

                        .notify_badge:hover i{
                            transform: scale(1.5);

                        }
                    </style>';

    if(!is_object($query)):

        foreach ($query as $student):
            $student_info .= '<div class="col-12 col-md-6 col-lg-4 col-xl-3 mt-3 mt-md-2 mt-lg-2 wow fadeInDown card_box">
                                <div class="card shadow">
                                    <button class="notify_badge btn btn-dark rounded-circle" disabled>';
            if($student["SEXE"] == "Male"):  
                $student_info .= '<i class="fas fa-mars"></i>';
            else: 
                $student_info .= '<i class="fas  fa-venus"></i>';
            endif; 

            $student_info .= '</button>
                                <img class="card-img-top rounded" src="..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.$student['PROFILE_PIC'] .'" height="250px">
                                
                                <div class="card-body text-center">
                                    <h6 class="card-title font-weight-bold student_unique_matricule">'.$student["MATRICULE"].'</h6>
                                    <span class="card-text text-justify"><strong>Name : </strong>'.$student["NOM"].' '.$student["PRENOM"].'</span>
                                    <a href="student_info.php?matricule='.$student["MATRICULE"].'" role="button" class="btn btn-outline-primary rounded-pill mt-2">See more</a>
                                </div>
                            </div>
                        </div>';
        endforeach;

    else:

        $student_info = '<div class="col-12 font-weight-bold">No student for this classroom for the moment</div>';

    endif;

    echo($student_info);
}

# -------------------------------------------- Load Surveys classroom ------------------------------------------------

if(isset($action) && $action == 'load_class' && isset($classroom))
{
    if($classroom != 'all'):
        $query = $obj->Requete("SELECT * FROM survey_set s, module m , classe c WHERE m.ID_MODULE = s.ID_MODULE AND c.ID_CLASSE = m.ID_CLASSE AND c.ID_CLASSE = '".$classroom."' AND '". date('Y-m-d') ."' between date(START_DATE) and date(END_DATE) order by rand() AND s.ID_ANNEE = '".getAnnee(0)['ID_ANNEE']."'")->fetchAll();
        
    else:
        $query = $obj->Requete("SELECT * FROM survey_set s, module m , classe c WHERE m.ID_MODULE = s.ID_MODULE AND c.ID_CLASSE = m.ID_CLASSE AND'". date('Y-m-d') ."' between date(START_DATE) and date(END_DATE) order by rand() AND s.ID_ANNEE = '".getAnnee(0)['ID_ANNEE']."'")->fetchAll();
        
    endif;

    $survey_info = '';

    if(!is_object($query)):

        foreach ($query as $survey):
            $survey_info .= '<div class="col-12 col-md-6 col-lg-4 col-xl-4 mt-3 mt-md-2 mt-lg-2 wow fadeInDown card_box">
                                <div class="card">
                                    <div class="card-header">'. $survey['NOM_MODULE'] .' </div>
                                        <div class="card-body">
                                            <p class="text-justify">'. $survey['SURVEY_DESCRIPTION'] .' </p>
                                        
                                            <div class="row">
                                                <div class="col-lg-12 text-center my-3">
                                                    <a href="surveys_report.php?id='. $survey['ID_SURVEYS'] .'" class="btn btn-dark">View report</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>';
        endforeach;

    else:

        $survey_info = '<div class="col-12 font-weight-bold">No student for this classroom for the moment</div>';

    endif;



    echo($survey_info);
}

// //////
if (isset($_POST['vveri'])){
    
    $var=$obj->Select('user',["ID_USER"],array("USERNAME"=>$_POST['valeur']));
    if (is_object($var)){
        echo $var->fetch()["ID_USER"];
    }else{
        echo '';
    }
}
    
if(isset($_POST['file_rem'])){
   extract($_POST);
   $delete_file = $obj->Delete('data_bank',array('ID_DATA_BANK'=>$file_id));
   if($delete_file==1){
       echo "1";
   }
   else{
       echo "0";
   }
}

// @jeremie=> modification des informations d'un fichier deja upload
if (isset($_POST['file_change'])){
    extract($_POST);
    //recuperation des infos du fichier a modifier
    $get_file_infos = $obj->Requete("SELECT * FROM data_bank WHERE ID_DATA_BANK='".$file_id."'");
    $select_module = $obj->Requete("SELECT * FROM module");
    if($ans = $get_file_infos->fetch()){
        
        $strings = '
        <form action="" method="post">
                    <div class="row">
                        <div class="form-group col-lg-12">
                            <label for="module_data_bank">Modules<span class="text-danger"> * </span></label>
                            <div class="input-group">
                                <select class="form-control" name="module_data_bank" id="module_update_bank">
                                <option value="'.$ans['MODULE'].'"> '.$ans['MODULE'].'</option>
                                ';

                                    while($get_module=$select_module->fetch()){
                                        $strings .= '<option value="'.$get_module['NOM_MODULE'].'">'.$get_module['NOM_MODULE'].'</option>';
                                 } 
                                 
                               $strings .=' </select>
                                <div class="input-group-append">
                                <span class="input-group-text fas fa-school"></span>
                            </div>
                            </div>
                        </div>

                     <div class="form-group col-lg-12">
                            <label for="titre_data_bank">Title<span class="text-danger"> * </span></label>
                                <div class="input-group">
                                <input type="text" class="form-control" value="'.$ans['TITRE'].'" name="titre_data_bank" id="titre_update_bank">
                                <div class="input-group-append">
                                        <span class="input-group-text fas fa-book-reader"></span>
                                </div>
                            </div>  
                        </div>
                    
                    <div class="form-group col-lg-12">
                            <label for="upload_data_bank">Upload file<span class="text-danger"> * </span></label>
                            <div class="form-group">
                                <div class="custom-file">
                                    <input type="file" name="upload_data_bank" id="file_update_bank" class="form-control custom-file-input">
                                    <label class="custom-file-label" for="upload_data_bank">'.$ans['FILE_NAME'].'</label>
                                </div>
                            </div>  
                    </div>
                    <!-- Description du fichier a uploader -->
                    <div class="form-group col-lg-12">
                            <label for="desc_data_bank">Description<span class="text-danger"> * </span></label>
                            <div class="input-group">
                                <textarea name="desc_data_bank" id="desc_update_bank" class="form-control" >
                                '.$ans['DESCRIPTION'].'
                                </textarea>
                                    <div class="input-group-append">
                                        <span class="input-group-text fas fa-book-reader"></span>
                                    </div>
                            </div>  
                        </div>
                    <!-- Espace pour sortir/ submit le formulaire -->
                        <div class="col text-right">
                            <button class="btn btn-danger" data-dismiss="modal" aria-label="Close">Cancel</button>
                            <button class="btn btn-success" type="button" name="confirm_data_bank_edit" id="confirm_data_bank_edit" onclick="modify_file('.$file_id.')">Update</button>
                        </div>
                    </div>
                </form>
                ';

    }
    echo $strings;
    
}

if(isset($_POST['idClass']))
{
    extract($_POST);
    if($idClass != 0)
    {
        $teachers = $obj->Requete('SELECT * FROM professeur p, user u, classe c, enseigner e, module m WHERE u.ID_USER= p.ID_USER AND c.ID_CLASSE='.$idClass.' AND e.ID_MODULE= m.ID_MODULE AND m.ID_CLASSE = C.ID_CLASSE AND p.ID_PROFESSEUR = e.ID_PROFESSEUR GROUP BY u.ID_USER');
    }
    else
    {
        $teachers = $obj->Select('professeur p, user u', [], ['u.ID_USER'=>'p.ID_USER']);
    }
    $toShow = '<div class="row">';
    if($teachers->rowCount()>0)
    {
        while($teacher = $teachers->fetch())
        {  
            $toShow .= '<div class="col-lg-3 py-3">
                <div class="card shadow">
                    <div class="inner">
                        <img class="card-img-top rounded" src="../../media/';
            $toShow .= empty($teacher['PROFILE_PIC']) ? 'user_24px.png': $teacher['PROFILE_PIC'];
            $toShow .='" height="250px">
                    </div>
                    <div class="card-body text-center">
                        <h5 class="card-title font-weight-bold">'. $teacher['NOM_PROF']." ".$teacher['PRENOM_PROF'].'</h5>
                        <p class="card-text text-justify"><strong>Grade : </strong>'. $teacher['GRADE'].'</p>
                        <a href="teacher_info.php?prof='. $teacher['ID_PROFESSEUR'].'" role="button" class="btn btn-outline-primary rounded-pill">See more</a>
                    </div>
                </div>
            </div>';
        }
    }
    else
    {
        $toShow .= '<div class="container"><div class="text-center"><span class = "">NO DATA FOUND !!!</span></div></div>';
    }
    $toShow .= '</div>';
        
    echo $toShow;
}
// @Jeremie gestion de l'affichage du body du modale de semestre
if(isset($_POST['modal_cont'])){
    // @Jeremie=> Recuperation de la des semestres
    $recu_sem = $obj->Requete("SELECT * FROM semestre");
    $id_sem=1;
   
    $view ='';
        while($get_semest = $recu_sem->fetch()){ 
            $view .='<div class="row mb-4">
                <div class="semest_name col-md-6 col-sm-12 mb-3 input-group">
                    <input class="form-control" type="text" name="sem_name" id="'.$id_sem.'" value="'.$get_semest['NOM_SEMESTRE'].'" readonly>
                    <div class="input-group-append">
                        <span class=" input-group-text bg-dark" onclick="edit('.$id_sem.')">
                        <input type="checkbox" name="" id="chk'.$id_sem.'">
                        </span>
                    </div>
                </div>
                <div class="semest_actions col-md-6 col-sm-12">
                    <div class="row">
                        <button id="upd'.$id_sem.'" class="btn btn-warning col-6 text-white font-weight-bold mr-2" onclick="semest_up('.$get_semest['ID_SEMESTRE'].')" disabled>Update</button>
                        <button id="'."del".$id_sem.'" class="btn btn-danger col-5 font-weight-bold" ondblclick="semest_del('.$get_semest['ID_SEMESTRE'].')" disabled >Delete</button>
                    </div>
                </div>
            </div>
            <hr class=" my-3">';
        $id_sem++;}
    echo $view;
}


// @Jeremie => Cette fonction est charge d'ajouter une semestre 
if(isset($_POST['action'])){
    extract($_POST);
    if($sem_names==""){
        echo -1;
    }
    else{
        $check_semester = $obj->Select('semestre',['NOM_SEMESTRE'],array('NOM_SEMESTRE'=>$sem_names));
        if(is_object($check_semester)){
            echo 0;
        }else{
            $add_semester = $obj->Insert('semestre', ['NOM_SEMESTRE'], [$sem_names]);
            echo 1;  
        }
    }  

}
// @Jeremie Fonction ajax pour la suppression d'une semestre

if(isset($_POST['sem_del'])){
    extract($_POST);
    $del = $obj->Delete('semestre',array('ID_SEMESTRE'=>$sem_id));
    if($del==1){
        echo 1;
    }
}

// @Jeremie Fonction ajax pour la modification d'une semestre

if(isset($_POST['sem_upd'])){
    extract($_POST);
    $select_sem = $obj->Select('semestre', ['NOM_SEMESTRE'],array('NOM_SEMESTRE'=>$sem_name_upd));

    if($sem_name_upd!=""){
        if(is_object($select_sem)){
            echo 0;
        }else{
            $upd = $obj->Update('semestre',['NOM_SEMESTRE'],[$sem_name_upd],array('ID_SEMESTRE'=>$sem_id_upd));
            if($upd==1){
                echo 1;
            }
        }
    }else{
        echo -1;
    }
}

// @Jeremie=> Cette fonction permet de d'ajouter un module

if(isset($_POST['add_mod'])){
    extract($_POST);
    $selc_mod = $obj->Select('module',[],array('NOM_MODULE'=>$mod_name));
    if(is_object($selc_mod)){
        echo 0;
    }else{
        $inser_mod = $obj->Insert('module',['ID_CLASSE', 'ID_SEMESTRE', 'NOM_MODULE', 'VOLUME_HORAIRE', 'DESCRIPTION'],[$mod_class,$mod_sem,$mod_name,$mod_credi,$description]);
        if($inser_mod==1){echo 1;}
    }
}
// @Jeremie=>Cette fonction permet de confirmer la suppression d'un module
if(isset($_POST['remove_act'])){
   extract($_POST);
   $del_module = $obj->Delete('module',array('ID_MODULE'=>$mod_id));
   if($del_module==1){
       echo 1;
   }
}
// @Jeremie => Cette fonction permet de modifier un module
if(isset($_POST['upd_module'])){
    extract($_POST);
    $select_modules =  $obj->Requete("SELECT * FROM module m, classe c, filieres f WHERE m.ID_CLASSE = c.ID_CLASSE AND c.ID_FILIERE = f.ID_FILIERE AND m.ID_MODULE ='".$module_id."'");
    $select_semester = $obj->Requete("SELECT * FROM semestre");
    $select_classe = $obj->Requete("SELECT * FROM classe");

    if(is_object($select_modules)){
        $mod_string='';

        if($get_module_infos = $select_modules->fetch()){
           $mod_string.='
            <form action="#" class="row" id="mod_form">
            <input type="text" value="'.$module_id.'" id="mod_val" hidden>
                <div class="form-group col-sm-12 col-md-6">
                <label for="mod_name">Module<span class="text-danger"> * </span></label>
                <div class="input-group" onclick="alert("Salut")">
                    <input type="text" class="form-control" id="upd_mod_name" value="'.$get_module_infos['NOM_MODULE'].'">
                    <div class="input-group-append">
                        <span class="input-group-text fas fa-book-reader"></span>
                </div>
                </div>
            </div>
            <div class="form-group col-sm-12 col-md-6">
                    <label for="mod_sem">Semester<span class="text-danger"> * </span></label>
                    <div class="input-group">
                    <select class="form-control" name="mod_sem" id="upd_mod_sem" value="'.$get_module_infos['NOM_MODULE'].'">';

                   $current_semester = $obj->Select('semestre',[],array('ID_SEMESTRE'=>$get_module_infos['ID_SEMESTRE']));
                   if(is_object($current_semester)){
                       $get_semestre = $current_semester->fetch();
                    $mod_string .='<option value="'.$get_semestre['ID_SEMESTRE'].'">'.$get_semestre['NOM_SEMESTRE'].'</option>';
                   }
                    while($get_sem =$select_semester->fetch()){
                       $mod_string .=' <option value="'.$get_sem['ID_SEMESTRE'].'">'.$get_sem['NOM_SEMESTRE'].'</option>';
                        } 
                    $mod_string .= '</select>
                    <div class="input-group-append">
                    <span class="input-group-text fas fa-school"></span>
                </div>
                </div>
                </div>
                
                <div class="form-group col-sm-12 col-md-6 ">
                <label for="mod_class">Classe<span class="text-danger"> * </span></label>
                <div class="input-group">
                    <select class="form-control" name="mod_class" id="upd_mod_class">';
                    $current_classe = $obj->Select('classe',[],array('ID_CLASSE'=>$get_module_infos['ID_CLASSE']));
                    if(is_object($current_classe)){
                        $get_classe = $current_classe->fetch();
                        $mod_string .= '<option value="'.$get_classe['ID_CLASSE'].'">'.$get_classe['NOM_CLASSE'].'</option>';
                    }
                    
                    while($get_class =$select_classe->fetch()){
                        $mod_string .= '<option value="'.$get_class['ID_CLASSE'].'">'.$get_class['NOM_CLASSE'].'</option>';
                    } 

                   $mod_string .= ' </select>
                    <div class="input-group-append">
                        <span class="input-group-text fas fa-school"></span>
                    </div>
                </div>
                </div>
                
                <div class="form-group col-sm-12 col-md-6">
                    <label for="mod_cred">Credits<span class="text-danger"> * </span></label>
                    <div class="input-group">
                        <input type="number" class="form-control" value="'.$get_module_infos['VOLUME_HORAIRE'].'" name="mod_cred" id="upd_mod_cred">
                        
                        <div class="input-group-append">
                            <span class="input-group-text fas fa-school"></span>
                        </div>
                    </div>
                </div>
                
                <div class="form-group col-lg-12">
                    <label class="text-center" for="description">Description<span class="text-danger"> * </span></label>
                    <textarea rows="5" class="form-control" name="description" id="upd_description" >'.$get_module_infos['DESCRIPTION'].'</textarea>
                </div>
                
                <div class="col-lg-12 form-group text-center my-4">
                    <input class="btn btn-primary col-lg-4" type="button" value="Update" onclick="updat_module()">
                </div>
                
        </form> ';
        }
    }
    echo $mod_string;
}

// @Jeremie => cette fonction permet de valider la mise a jour du module
if(isset($_POST['upd_mod'])){
    extract($_POST);
    $select_modul = $obj->Select('module', ['NOM_MODULE'],array('NOM_MODULE'=>$upd_mod_name));

    if($upd_mod_name!=""){
        if(is_object($select_modul)){
            echo 0;
        }else{
            $upd = $obj->Update('module',['ID_CLASSE','ID_SEMESTRE','NOM_MODULE','VOLUME_HORAIRE','DESCRIPTION'],[$upd_mod_class,$upd_mod_sem,$upd_mod_name,$upd_mod_credi,$upd_description],array('ID_MODULE'=>$mod_val));
            if($upd==1){
                echo 1;
            }
        }
    }else{
        echo -1;
    }
}

//@lougoudoro => pour metre a jour le prix unitaire
if(isset($_POST['canteen'])){
    $identifier=$obj->Select('user',array('PASSWORD'),array('ID_USER'=>$_POST['userID']));
    if(is_object($identifier)){
        $identifier=$identifier->fetch()['PASSWORD'];
        if($identifier==$_POST['password']) {
            $obj->Requete("UPDATE cantine SET COST_PER_DAY=" . $_POST['valeur']);
            echo 'ok';
        }
    }
    else{
        echo 'error';
    }
}