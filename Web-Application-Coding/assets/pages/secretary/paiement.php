<?php
    include '../../../utilities/QueryBuilder.php';
    $title = 'Reinscription';
    $breadcrumb = 'First Payment';

    
    $obj = new QueryBuilder();
    $actual_year= $obj->Requete('SELECT * FROM annee_scolaire WHERE DATE_FIN IS NULL ORDER BY ID_ANNEE DESC LIMIT 1 ')->fetch();

    if (isset($_SESSION['old_matricule']) AND !empty($_SESSION['old_matricule'])) {
        extract($_SESSION);
        $old_student_tuition = $obj->Requete('SELECT * FROM etudiant etu, inscription insc, scolarite sco WHERE etu.MATRICULE = insc.MATRICULE AND sco.ID_INSCRIPTION = insc.ID_INSCRIPTION AND etu.MATRICULE = "'.$old_matricule.'"')->fetch();
        $scolarship_rate = $obj->Requete('SELECT * FROM bourse b, historique_bourse h WHERE b.ID_BOURSE = h.ID_BOURSE AND h.MATRICULE = "'.$old_matricule.'"')->fetch();
        $class_info = $obj->Requete('SELECT * FROM classe c, inscription i WHERE i.ID_CLASSE = c.ID_CLASSE AND i.MATRICULE = "'.$old_matricule.'" ')->fetch();
        $all_classe = $obj->Select('classe',[],array('ID_CLASSE !'=>$class_info['ID_CLASSE']));
        $all_bourse = $obj->Select('bourse',[],array('ID_BOURSE !'=>$scolarship_rate['ID_BOURSE']));
    }
    else{
        Redirect('old_student_tuition.php');
    }

    if (isset($_POST['submit_old_student_fees'])) {
        extract($_POST);
        $subs_new_student = $obj->Insert('inscription', array('ID_BOURSE', 'MATRICULE', 'ID_CLASSE', 'DATE_INSCRIPTION','ID_ANNEE'), array(explode('*',$inscription_fees_rat)[0], $scolarship_rate['MATRICULE'], explode('*',$inscription_classe)[0] , 'NOW()',$actual_year['ID_ANNEE']));
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
                    $ne_his_scolarite=$obj->Insert('historique_payement', array('ID_INSCRIPTION',  'MONTANT', 'DATE_PAYEMENT','USER'), array($last_ins_scolr_id,$inscription_payment,'NOW()',$_SESSION['ID_USER']));
                    $_SESSION['repay'] = 1;
                    /*===================redirect to student list page===========================**/
                    Redirect('recu.php?id=' .$old_student_tuition['MATRICULE'] .'&firstname='.$old_student_tuition["PRENOM"] .'&lastname='.$old_student_tuition["NOM"]);
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
                            <a href="#school_fees" class="nav-link active" id="fees_tab" data-toggle="tab">Step : School fees</a>
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
                                            <div class="col-6"><h5 class="text-dark text-text-uppercase mt-3"><?='| ' .$old_student_tuition['NOM']. ' ' .$old_student_tuition["PRENOM"] ?></h5></div>
                                        </div>
                                        <hr class="bg-gradient-primary">
                                    </div>

                                    <div class="col-lg-6 p-4">
                                        <label for="inscription_matricule" class="pb-3">Matricule<span class="text-danger"> * </span></label>
                                        <div class="input-group">
                                            <input type="text" name="inscription_matricule" id="inscription_matricule" class="form-control" value="<?= $old_student_tuition['MATRICULE'] ?>" readonly>
                                            <div class="input-group-append">
                                                <span class="input-group-text fas fa-portrait text-primary"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 p-4">
                                        <label for="inscription_classe" class="pb-3">Class <span class="text-danger"> * </span></label>
                                        <div class="input-group">
                                            <select class="form-control" name="inscription_classe" id="inscription_classe" oninput="get_Montant(this.value.split('*')[1])">
                                                <option selected value="<?= $class_info['ID_CLASSE'].'*'.$class_info['MONTANT_SCOLARITE']?>"><?= $class_info['NOM_CLASSE'] ?></option>
                                                <?php
                                                    while ($back_classe=$all_classe->fetch()) {
                                                ?>
                                                    <option value="<?= $back_classe['ID_CLASSE'].'*'.$back_classe['MONTANT_SCOLARITE']?>" ><?= $back_classe['NOM_CLASSE'] ?></option>
                                                <?php
                                                    }
                                                ?>
                                            </select>
                                            <div class="input-group-append">
                                                <span class="input-group-text fas fa-school text-primary"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 p-4">
                                        <label for="inscription_total" class="pb-3">Normal School fees<span class="text-danger"> * </span></label>
                                        <div class="input-group">
                                            <input type="text" name="inscription_total" id="inscription_total" class="form-control" value="<?=$class_info['MONTANT_SCOLARITE']?>" readonly>
                                            <div class="input-group-append">
                                                <div class="input-group-text">
                                                    <span class="text-primary pr-5">Fcfa</span>
                                                    <span class="fas fa-money-bill text-primary"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 p-4">
                                        <label for="inscription_fees_rat" class="pb-3">Scholarship rate<span class="text-danger"> * </span></label>
                                        <div class="input-group">
                                            <select class="form-control" name="inscription_fees_rat" id="inscription_fees_rat" oninput="get_Bourse(this.value.split('*')[1])">
                                                <option selected value="<?=$scolarship_rate['ID_BOURSE'].'*'.$scolarship_rate['TAUX'] ?>"><?= $scolarship_rate['TAUX'].' %' ?></option>
                                                <?php
                                                    while ($back_bourse =$all_bourse->fetch()) {
                                                ?>
                                                    <option value="<?=$back_bourse['ID_BOURSE'].'*'.$back_bourse['TAUX'] ?>"><?= $back_bourse['TAUX'].' %' ?></option>
                                                <?php
                                                    }
                                                ?>
                                            </select>
                                            <div class="input-group-append">
                                                <span class="input-group-text fas fa-percent text-primary"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 p-4">
                                        <label for="inscription_actu_fees" class="pb-3">Actual School fees amounts<span class="text-danger"> * </span></label>
                                        <div class="input-group">
                                            <input type="text" name="inscription_actu_fees" id="inscription_actu_fees" class="form-control" value="<?= $old_student_tuition['MONTANT_TOTAL'] ?>" readonly >
                                            <div class="input-group-append">
                                                <span class="input-group-text fas fa-portrait text-primary">
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                
                                    <div class="col-lg-6 p-4">
                                        <label for="inscription_payment" class="pb-3">Payment Amount<span class="text-danger"> * </span></label>
                                        <div class="input-group">
                                            <input type="number" name="inscription_payment" id="inscription_payment" class="form-control" placeholder="Enter the paiement amount" required>
                                            <div class="input-group-append">
                                                <span class="input-group-text fas fa-money-bill text-primary"></span>
                                            </div>
                                        </div>
                                    </div> 

                                    
                                    <div class="col-lg-12 text-center my-5">
                                        <button class="btn btn-primary w-75 my-3" type="submit" name="submit_old_student_fees">Pay</button>
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