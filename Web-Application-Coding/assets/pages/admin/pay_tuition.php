<?php 
    include '../../../utilities/QueryBuilder.php';
    $title = 'New Student';
    $breadcrumb = 'First Payment';

    $obj = new QueryBuilder();
    //the global session is well-defined
    $actual_year= $obj->Requete('SELECT * FROM annee_scolaire WHERE DATE_FIN IS NULL ORDER BY ID_ANNEE DESC LIMIT 1 ')->fetch();


    if (isset($_GET['id']) AND !empty($_GET['id']) AND is_numeric($_GET['id']))
    {
        $new_student_paying = $obj->Select('newetudiant', array(), array('ID_NEW_ETUDIANT'=>$_GET['id']));
        if (is_object($new_student_paying))
        {
           $new_student_paying = $new_student_paying->fetch();
           //selection of the normal scholar fees amount
            $normal_scholar_fees = $obj->Select('classe', array(), array('ID_CLASSE'=>$new_student_paying['CLASSE']));
            $normal_scholar_fees = $normal_scholar_fees->fetch();
            $normal_scholar_fees = $normal_scholar_fees['MONTANT_SCOLARITE'];
            //selection of the scholarship rate
            $scholar_rate = $obj->Select('bourse', array(), array('ID_BOURSE'=>$new_student_paying['BOURSE']));
            if (is_object($scholar_rate))
            {
                $scholar_rate = $scholar_rate->fetch();
                $scholar_rate = $scholar_rate['TAUX'];
            }
        }
    }
    //otherwise, we target th e use to the precedent page
    else
    {
        Redirect('inscription_valide.php');
    }


    //*******************Incription of new student*********************/

        if (isset($_POST['submit_student_fees']))
        {
            extract($_POST);
            $columns = array('MATRICULE', 'PRENOM', 'NOM', 'SEXE', 'DATE_NAISSANCE', 'CNIB', 'TELEPHONE', 'EMAIL', 'NUM_URGENCE','DIPLOME','MOYENNE','NOM_PERE','PROFESSION_PERE','NOM_MERE','PROFESSION_MERE','ECOLE_ORIGINE','RECOMMENDATION','STATUT','ID_USER');
            $values = array($inscription_matricule, $new_student_paying['PRENOM'],
                $new_student_paying['NOM'],$new_student_paying['SEXE'],
                $new_student_paying['DATE_NAISSANCE'],$new_student_paying['CNIB'],$new_student_paying['TELEPHONE'],
                $new_student_paying['EMAIL'],$new_student_paying['NUM_URGENCE'],$new_student_paying['DIPLOME'],$new_student_paying['MOYENNE'],
                $new_student_paying['NOM_PERE'],$new_student_paying['PERE_PROFESSION'],$new_student_paying['NOM_MERE'],
                $new_student_paying['MERE_PROFESSION'],$new_student_paying['ECOLE_ORIGINE'],$new_student_paying['RECOMMENDATION'],$new_student_paying['STATUT'],
                $new_student_paying['ID_NEW_ETUDIANT']);
            /*$inscription_classe, $submit_student_infos, , $inscription_total, $inscription_payment, $submit_student_fees*/
            $status = array('CNIB'=>$new_student_paying['CNIB']);
            $table = 'etudiant';


            $obj = new QueryBuilder();
            /***********************creation of user type student********************************/
            $ne_student = $obj->Inscription($table, $columns, $values, $status);
            if ($ne_student == 0)
            {
                $er_msg = SetMessage('This student already exists.', 'warning');
            }
            else
            {
               //die("tttttttttttt");

                $subs_new_student = $obj->Insert('inscription', array('ID_BOURSE', 'MATRICULE', 'ID_CLASSE', 'DATE_INSCRIPTION','ID_ANNEE'), array($new_student_paying['BOURSE'], $inscription_matricule, $new_student_paying['CLASSE'], 'NOW()',$actual_year['ID_ANNEE']));
                $user=$obj->Insert('user',array('PASSWORD','USERNAME','DROITS'),array($inscription_matricule, $inscription_matricule, 'student'));
                $ne_his_bourse=$obj->Insert('historique_bourse', array('MATRICULE',  'ID_BOURSE', 'MOTIVATION','OBSERVATION','ID_ANNEE'), array($inscription_matricule,$new_student_paying['BOURSE'],$new_student_paying['MOTIVATION'],$new_student_paying['OBSERVATION'],$actual_year['ID_ANNEE']));

                if ($subs_new_student == 1)
                {
                    $last_ins_id = $obj->Requete('SELECT ID_INSCRIPTION FROM inscription ORDER BY ID_INSCRIPTION DESC LIMIT 1');
                    // var_dump($last_ins_id->fetch());
                    if($last_ins_id->rowCount()>0){
                        $son_id=$last_ins_id->fetch();
                        $son_id = $son_id['ID_INSCRIPTION'];
                        $ne_scolarite=$obj->Insert('scolarite', array('ID_INSCRIPTION', 'MONTANT_TOTAL', 'MONTANT_PAYE', 'DATE_LIMITE'), array($son_id,$inscription_actu_fees,$inscription_payment,'NOW()'));

                        $last_ins_scolr = $obj->Requete('SELECT ID_SCOLARITE FROM scolarite ORDER BY ID_SCOLARITE DESC LIMIT 1');
                        $last_ins_scolr_id = $last_ins_scolr->fetch();
                        $last_ins_scolr_id = $last_ins_scolr_id['ID_SCOLARITE'];

                        if($last_ins_scolr->rowCount()>0)
                        {
                            $ne_his_scolarite=$obj->Insert('historique_payement', array('ID_INSCRIPTION',  'MONTANT', 'DATE_PAYEMENT'), array($last_ins_scolr_id,$inscription_payment,'NOW()'));
                            $obj->Delete('newetudiant',array('ID_NEW_ETUDIANT'=>$new_student_paying['ID_NEW_ETUDIANT']));
                            
                            /*===================redirect to student list page===========================**/
                            Redirect('inscription_valide.php');
                        }

                    }
                }
            }
        }
    /*************************** end of student inscription*****************************/
    include('header.php');
?>

<div class="container-fluid" id="fenetre">
    <div class="row" >
        <div class="col-lg-12">
            <div class="card shadow">
                <div class="card-header">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a href="#school_fees" class="nav-link active" id="fees_tab" data-toggle="tab">Step 2 : School fees</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="school_fees">
                            <form action="" method="post" >
                                <div class="row">
                                    <div class="col-lg-12 text-left">
                                        <div class="row">
                                            <div class="col-6"><h5 class="text-bluesky mt-3">School Fees : </h5></div>
                                            <div class="col-6"><h5 class="text-dark text-text-uppercase mt-3"><?='| ' .$new_student_paying['NOM']. ' ' .$new_student_paying["PRENOM"] ?></h5></div>
                                        </div>
                                        
                                        <hr class="bg-gradient-primary">
                                    </div>
                                    <div class="col-lg-6 p-4">
                                        <label for="inscription_matricule" class="pb-3">Matricule<span class="text-danger"> * </span></label>
                                        <div class="input-group">
                                            <input type="text" name="inscription_matricule" id="inscription_matricule" class="form-control" value="<?= MatriculAttrib() ?>" readonly>
                                            <div class="input-group-append">
                                                <span class="input-group-text fas fa-portrait text-primary">
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 p-4">
                                        <label for="inscription_total" class="pb-3">Normal School fees<span class="text-danger"> * </span></label>
                                        <div class="input-group">
                                            <input type="text" name="inscription_total" id="inscription_total" class="form-control" value="<?= $normal_scholar_fees.' F CFA'?>" disabled>
                                            <div class="input-group-append">
                                                <span class="input-group-text fas fa-money-bill text-primary"></span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-6 p-4">
                                        <label for="inscription_fees_rat" class="pb-3">Scholarship rate<span class="text-danger"> * </span></label>
                                        <div class="input-group">
                                            <input type="text" name="inscription_fees_rat" id="inscription_fees_rat" class="form-control" value="<?= $scholar_rate.'%' ?>" disabled >
                                            <div class="input-group-append">
                                                <span class="input-group-text fas  text-primary">
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 p-4">
                                        <label for="inscription_actu_fees" class="pb-3">Actual School fees amounts<span class="text-danger"> * </span></label>
                                        <div class="input-group">
                                            <input type="text" name="inscription_actu_fees" id="inscription_actu_fees" class="form-control" value="<?= $normal_scholar_fees - ($normal_scholar_fees * $scholar_rate/100) ?>" readonly >
                                            <div class="input-group-append">
                                                <span class="input-group-text fas fa-portrait text-primary">
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 p-4">
                                        <label  for="inscription_payment" class="pb-3">Payment Amount<span class="text-danger"> * </span></label>
                                        <div class="input-group">
                                            <input type="number" name="inscription_payment" id="inscription_payment" class="form-control" placeholder="Enter the paiement amount" required max="<?= $normal_scholar_fees - ($normal_scholar_fees * $scholar_rate/100) ?>">
                                            <div class="input-group-append">
                                                <span class="input-group-text fas fa-money-bill text-primary"></span>

                                            </div>
                                        </div>
                                    </div> 

                                    
                                    <div class="col-lg-12 text-center my-5">
                                        <button class="btn btn-primary w-75 my-3" type="submit" name="submit_student_fees">Pay</button>
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
    <script>
        //document.getElementsByTagName('body')[0].addEventListener("load", zeroToPay);
        var donner=document.getElementById('inscription_actu_fees');
        if (donner.value==0){
            document.getElementById('inscription_payment').setAttribute('readonly','');
            document.getElementById('inscription_payment').value=0;

        }
        </script>
<?php
    include('../footer.php');
?>