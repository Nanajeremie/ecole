<?php 
    include '../../../utilities/QueryBuilder.php';
    $title = 'Other Payment';
    $breadcrumb = 'Pay Tuition';
    $obj = new QueryBuilder();
    if (isset($_SESSION['id']) AND !empty($_SESSION['id'])) 
    {
        extract($_SESSION);
        $old_student = $obj->Requete('SELECT * FROM etudiant etu, inscription insc, scolarite sco WHERE etu.MATRICULE = insc.MATRICULE AND sco.ID_INSCRIPTION = insc.ID_INSCRIPTION AND etu.MATRICULE = "'.$id.'" ORDER BY insc.ID_INSCRIPTION DESC LIMIT 1')->fetch();
        $scolarship_rate = $obj->Requete('SELECT * FROM bourse b, historique_bourse h WHERE b.ID_BOURSE = h.ID_BOURSE AND h.MATRICULE = "'.$id.'"')->fetch();
        $class_info = $obj->Requete('SELECT * FROM classe c, inscription i WHERE i.ID_CLASSE = c.ID_CLASSE AND i.MATRICULE = "'.$id.'" ')->fetch();
    }
    else{
        Redirect('valide_other_paiement.php');
    }
    //when we click on the PAY button
    if (isset($_POST['submit_student_fees']))
    {
        extract($_POST);
        $paid_amount = floatval($inscription_payment)  + floatval($inscription_actu_fees) ;
        $obj->Update('scolarite', array('MONTANT_PAYE'), array($paid_amount), array('ID_SCOLARITE'=>$old_student['ID_SCOLARITE']));
        $obj->Insert('historique_payement', array('ID_INSCRIPTION', 'MONTANT', 'DATE_PAYEMENT','USER'), array($old_student['ID_INSCRIPTION'], $inscription_payment, 'NOW()',$_SESSION['ID_USER']));
        $_SESSION['other_pay'] = 1;

        Redirect('recu.php?id=' .$old_student["MATRICULE"] .'&firstname='.$old_student["NOM"] .'&lastname='.$old_student["PRENOM"]);
        //Redirect('valide_other_paiement.php');
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
                                            <div class="col-6"><h5 class="text-dark text-text-uppercase mt-3"><?='| ' .$old_student["NOM"] .' '.$old_student["PRENOM"]?></h5></div>
                                        </div>
                                        <hr class="bg-gradient-primary">
                                    </div>
                                    <div class="col-lg-6 p-4">
                                        <label for="inscription_matricule" class="pb-3">Matricule<span class="text-danger"> * </span></label>
                                        <div class="input-group">
                                            <input type="text" name="inscription_matricule" id="inscription_matricule" class="form-control" value="<?= $old_student["MATRICULE"] ?>" readonly>
                                            <div class="input-group-append">
                                                <span class="input-group-text fas fa-portrait text-primary">
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 p-4">
                                        <label for="inscription_total" class="pb-3">Normal School fees<span class="text-danger"> * </span></label>
                                        <div class="input-group">
                                            <input type="text" name="inscription_total" id="inscription_total" class="form-control" value="<?= $class_info["MONTANT_SCOLARITE"] .' F CFA'?>" disabled>
                                            <div class="input-group-append">
                                                <span class="input-group-text fas fa-money-bill text-primary"></span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-6 p-4">
                                        <label for="inscription_fees_rat" class="pb-3">Scholarship rate<span class="text-danger"> * </span></label>
                                        <div class="input-group">
                                            <input type="text" name="inscription_fees_rat" id="inscription_fees_rat" class="form-control" value="<?= $scolarship_rate["TAUX"].'%' ?>" disabled >
                                            <div class="input-group-append">
                                                <span class="input-group-text fas  text-primary">
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 p-4">
                                        <label for="inscription_actu_fees" class="pb-3">Actual School fees amounts<span class="text-danger"> * </span></label>
                                        <div class="input-group">
                                            <input type="text" name="inscription_actu_fees" id="inscription_actu_fees" class="form-control" value="<?= $old_student["MONTANT_TOTAL"]. 'F CFA' ?>" readonly >
                                            <div class="input-group-append">
                                                <span class="input-group-text fas fa-portrait text-primary">
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                
                                    <div class="col-lg-6 p-4">
                                        <label for="inscription_actu_fees" class="pb-3">Already Paid<span class="text-danger"> * </span></label>
                                        <div class="input-group">
                                            <input type="text" name="inscription_actu_fees" id="inscription_actu_fees" class="form-control" value="<?= $old_student["MONTANT_PAYE"].' F CFA' ?>" readonly >
                                            <div class="input-group-append">
                                                <span class="input-group-text fas fa-portrait text-primary">
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 p-4">
                                        <label for="inscription_actu_fees" class="pb-3">Remained fees<span class="text-danger"> * </span></label>
                                        <div class="input-group">
                                            <input type="text" name="inscription_actu_fees" id="inscription_actu_fees" class="form-control" value="<?= $old_student["MONTANT_TOTAL"] - $old_student["MONTANT_PAYE"].' F CFA' ?>" disabled >
                                            <div class="input-group-append">
                                                <span class="input-group-text fas fa-portrait text-primary">
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                
                                    <div class="col-lg-6 p-4">
                                        <label for="inscription_payment" class="pb-3">Payment Amount<span class="text-danger"> * </span></label>
                                        <div class="input-group">
                                            <input type="number" name="inscription_payment" id="inscription_payment" class="form-control" placeholder="Enter the paiement amount" required min=0 max=<?= $old_student["MONTANT_TOTAL"] - $old_student["MONTANT_PAYE"]?>>
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

<?php
    include('../footer.php');
?>