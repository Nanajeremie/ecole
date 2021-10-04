<?php
    include '../../../utilities/QueryBuilder.php';
    $obj = new QueryBuilder();
    $title = 'Fees Payment ';
    $breadcrumb = 'Receipts';
    include('header.php');
    if (isset($_GET['id']) AND !empty($_GET['id'])) 
    {
        $id = $_GET['id'];
        $old_student = $obj->Requete('SELECT * FROM etudiant etu, inscription insc, scolarite sco WHERE etu.MATRICULE = insc.MATRICULE AND sco.ID_INSCRIPTION = insc.ID_INSCRIPTION AND insc.ID_INSCRIPTION = "'.$id.'" ORDER BY insc.ID_INSCRIPTION DESC LIMIT 1')->fetch();

        $class_info = $obj->Requete('SELECT * FROM classe c, inscription i, niveau n WHERE i.ID_CLASSE = c.ID_CLASSE AND n.ID_NIVEAU = c.ID_NIVEAU AND i.ID_INSCRIPTION = "'.$id.'" ')->fetch();

        $date_paiement = $obj->Requete('SELECT * FROM historique_payement h, inscription i WHERE h.ID_INSCRIPTION = i.ID_INSCRIPTION AND i.ID_INSCRIPTION = "'.$id.'" ORDER BY ID_PAYEMENT DESC LIMIT 1')->fetch();


        $secretaire = $obj->Requete("SELECT * FROM administration WHERE ID_USER = '".$date_paiement['USER']."'")->fetch();
    }
    elseif (isset($_GET['id_inscription']) AND !empty($_GET['id_inscription']) AND isset($_GET['dates']) AND !empty($_GET['dates'])) {
        extract($_GET);
        $date_paiement = substr($dates , 0 , 10);
        $matri = $obj->Requete("SELECT MATRICULE FROM inscription WHERE ID_INSCRIPTION = '".$id_inscription."'")->fetch();
        $old_student = $obj->Requete('SELECT * FROM etudiant etu, inscription insc, scolarite sco WHERE etu.MATRICULE = insc.MATRICULE AND sco.ID_INSCRIPTION = insc.ID_INSCRIPTION AND etu.MATRICULE = "'.$matri['MATRICULE'].'"')->fetch();
        $scolarship_rate = $obj->Requete('SELECT * FROM bourse b, historique_bourse h WHERE b.ID_BOURSE = h.ID_BOURSE AND h.MATRICULE = "'.$matri['MATRICULE'].'"')->fetch();
        $date_paiement = $obj->Requete('SELECT * FROM historique_payement h, inscription i WHERE h.ID_INSCRIPTION = i.ID_INSCRIPTION AND i.MATRICULE = "'.$matri['MATRICULE'].'" AND h.DATE_PAYEMENT = "'.$dates.'"')->fetch();
        $class_info = $obj->Requete('SELECT * FROM classe c, inscription i, filieres f WHERE i.ID_CLASSE = c.ID_CLASSE AND f.ID_FILIERE = c.ID_FILIERE AND i.MATRICULE = "'.$matri['MATRICULE'].'" ')->fetch();
        $montant_payer = $obj->Requete('SELECT SUM(MONTANT) FROM `historique_payement` WHERE ID_INSCRIPTION ='.$id_inscription.' AND DATE_PAYEMENT <="'.$date_paiement['DATE_PAYEMENT'].'"')->fetch();
        $secretaire = $obj->Requete("SELECT * FROM administration WHERE ID_USER = '".$date_paiement['USER']."'")->fetch();
    }

  

    if(isset($_SESSION['first_pay']) && $_SESSION['first_pay'] == 1)
    {
        alert('success', 'Payment Completed Successfully.');
        unset($_SESSION['first_pay']);
    }
    
    if(isset($_SESSION['other_pay']) && $_SESSION['other_pay'] == 1)
    {
        alert('success', 'Payment Completed Successfully.');
        unset($_SESSION['other_pay']);
    }
    
    if(isset($_SESSION['repay']) && $_SESSION['repay'] == 1)
    {
        alert('success', 'Payment Completed Successfully.');
        unset($_SESSION['repay']);
    }

?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-10 my-2 mx-auto" >
                <button class="btn btn-dark my-2" onclick="print" id="print_me"><i class="fas fa-print"></i> Imprimer</button>

                <div class="card" id="student_receipts">
                    <div class="card-hearder bg-gradient-primary py-3 px-4">
                        <div class="row">

                            <div class="col-6 ml-auto">
                                <!-- <img src="http://localhost/Opensch_final_version/Web-Application-Coding/assets/media/logo_bit.png" width="150px" height="70px" alt="" srcset=""> -->
                            </div>

                            <div class="col-6">
                                <p class="text-capitalize text-white h4 font-weight-light">Recut de paiement : <?= $old_student["NOM"].' '.$old_student['PRENOM'] ?></p>
                                <p class="text-white">[ Date : <?= $date_paiement["DATE_PAYEMENT"] ?> ]</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-5">
                        <table class="table table-bordered">
                        <tbody>
                                <tr>                                    
                                    <td class="h6 font-weight-bolder" colspan="2">Frais de scolarite</td>
                                    <td class="h6 font-weight-bolder">Methode de paiement : </td>
                                    <td>Espece</td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="border-0 py-4"></td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="h6 font-weight-bolder">Somme Totale : </td>
                                    <td colspan="2"><?= $old_student["MONTANT_TOTAL"]. " fcfa" ?></td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="py-4"></td>
                                </tr>
                                <tr>
                                    <td class="h6 font-weight-bolder bg-light">Montant paye : </td>
                                    <td class="bg-light"><?= intval($date_paiement['MONTANT']). " fcfa" ?></td>

                                    <td class="h6 font-weight-bolder">Somme restante : </td>
                                    <td colspan="1"><?= $old_student["MONTANT_TOTAL"] - $old_student["MONTANT_PAYE"]." fcfa" ?></td> 
                                </tr>
                            </tbody>
                        </table>
                        <div class="row ">

                            <div class="col-6">
                                <p class="py-3 text-bluesky font-weight-bolder h5">Detail de l'eleve</p>
                                <div class="border border-black py-2 px-2">
                                    <p><span class="h6"> Nom : </span><span class="text-uppercase"><?= $old_student["NOM"] ?></span></p>
                                    <p><span class="h6">Prenom : </span><span class="text-capitalize"><?= $old_student["PRENOM"] ?></span></p>
                                    <p><span class="h6">Niveau : </span><span class=""><?= $class_info['NOM_NIVEAU'] ?></span></p>
                                    <p><span class="h6">Classe : </span><span class=""><?= $class_info['NOM_CLASSE'] ?></span></p>
                                    <p><span class="h6">Tel : </span><span class=""><?= $old_student['TELEPHONE'] ?></span></p>
                                    <p><span class="h6">Email : </span><span class=""><?= $old_student['EMAIL'] ?></span></p>
                                </div>
                            </div>

                            <div class="col-6">
                                <p class="py-3 text-bluesky font-weight-bolder h5">Detail de l'institut</p>
                                <div class="border border-black py-2 px-2">
                                    <p><span class="h6">Agent : </span><span class=""><?= $secretaire['NOM']. ' ' .$secretaire['PRENOM'] ?></span></p>
                                    <p><span class="h6">Postal : </span><span class="">322 Koudougou</span></p>
                                    <p><span class="h6">Site web : </span><span class="">www.bit.bf</span></p>
                                    <p><span class="h6">Tel : </span><span class="">(+226) 53 11 11 10 / (+226) 67 44 42 29</span></p>
                                    <p><span class="h6">Email : </span><span class="">admissions@bit.bf</span></p>
                                </div>
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
<script>        
    print('print_me', 'student_receipts');
</script>
