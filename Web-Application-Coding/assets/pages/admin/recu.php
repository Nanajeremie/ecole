<?php
    include '../../../utilities/QueryBuilder.php';
    $obj = new QueryBuilder();
    $title = 'Old Student Payment ';
    $breadcrumb = 'Receipts';

    $_SESSION['current_paiement'] = 10000;
    
    if (isset($_SESSION['id']) AND !empty($_SESSION['id'])) 
    {
       
        extract($_SESSION);
        $old_student = $obj->Requete('SELECT * FROM etudiant etu, inscription insc, scolarite sco WHERE etu.MATRICULE = insc.MATRICULE AND sco.ID_INSCRIPTION = insc.ID_INSCRIPTION AND etu.MATRICULE = "'.$id.'" ORDER BY insc.ID_INSCRIPTION DESC LIMIT 1')->fetch();
        $scolarship_rate = $obj->Requete('SELECT * FROM bourse b, historique_bourse h WHERE b.ID_BOURSE = h.ID_BOURSE AND h.MATRICULE = "'.$id.'"')->fetch();
        $class_info = $obj->Requete('SELECT * FROM classe c, inscription i, filieres f WHERE i.ID_CLASSE = c.ID_CLASSE AND f.ID_FILIERE = c.ID_FILIERE AND i.MATRICULE = "'.$id.'" ')->fetch();
        $date_paiement = $obj->Requete('SELECT * FROM historique_payement h, inscription i WHERE h.ID_INSCRIPTION = i.ID_INSCRIPTION AND i.MATRICULE = "'.$id.'" ORDER BY ID_PAYEMENT DESC LIMIT 1')->fetch();
    }
    else if (isset($_SESSION['old_matricule']) AND !empty($_SESSION['old_matricule'])) 
    {
        extract($_SESSION);
        $old_student = $obj->Requete('SELECT * FROM etudiant etu, inscription insc, scolarite sco WHERE etu.MATRICULE = insc.MATRICULE AND sco.ID_INSCRIPTION = insc.ID_INSCRIPTION AND etu.MATRICULE = "'.$old_matricule.'" ORDER BY insc.ID_INSCRIPTION DESC LIMIT 1')->fetch();
        $scolarship_rate = $obj->Requete('SELECT * FROM bourse b, historique_bourse h WHERE b.ID_BOURSE = h.ID_BOURSE AND h.MATRICULE = "'.$old_matricule.'"')->fetch();
        $class_info = $obj->Requete('SELECT * FROM classe c, inscription i, filieres f WHERE i.ID_CLASSE = c.ID_CLASSE AND f.ID_FILIERE = c.ID_FILIERE AND i.MATRICULE = "'.$old_matricule.'" ')->fetch();
        $date_paiement = $obj->Requete('SELECT * FROM historique_payement h, inscription i WHERE h.ID_INSCRIPTION = i.ID_INSCRIPTION AND i.MATRICULE = "'.$old_matricule.'" ORDER BY ID_PAYEMENT DESC LIMIT 1')->fetch();
    
    }

    ?>
<style type="text/css">
    .container
    {
        /*border: 1px solid black;*/
        padding: 0%;
        margin:0%;
    }

    .banner
    {
        background-color: #18bcfd;
    }
    .logo
    {
        position: absolute;
        margin-top: 3%;
        margin-left: 25px;
    }
    .info-student-payment-date
    {
        position: relative;
        margin-left: 50%;
        margin-top: -11%;
        color: white;
    }
    .fees-info
    {
        margin: 20px 5px 20px 5px;
    }
    .fees-info table
    {
        border-collapse : collapse ;
        width: 100%;
    }
    td
    {
        border: 1px solid #dee2e6;
        padding-top: 3px;
        padding-left: 3px;
        padding-bottom: 3px;
    }
    .to_bold
    {
        font-weight: bold;
        font-family: 'Agency FB';
    }
    .info-title
    {
        color: #18bcfd;
    }
    .student-info
    {
        position: relative;
        margin-left: 10px;
        width: 45%;
        padding: 3px;
        background-color: #ffffff;
        opacity: 0.5;
        border: 1px dashed #dee2e6;
    }
    .school-info
    {
        position: relative;
        margin-left: 50%;
        margin-top: -46%;
        margin-right:10px; 
        padding: 3px;
        background-color: #ffffff;
        opacity: 0.5;
        border: 1px dashed #dee2e6;
    }
    .other-info
    {
        /*border: 1px solid black;*/
        /*margin: 5px 10px 20px 10px;*/
        height: 35%;

    }
    .spacement
    {
        height: 30px;
    }
</style>

<div class="container">
    <div class="banner">
        <div class="logo">
            <img src="http://localhost/Opensch_final_version/Web-Application-Coding/assets/media/logo_bit.png" width="150px" height="70px" alt="" srcset="">
        </div>

        <div class="info-student-payment-date">
            <p >Payment receipt : <?= $old_student["NOM"].' '.$old_student['PRENOM'] ?></p>
            <p >[ Date : <?= $date_paiement["DATE_PAYEMENT"] ?> ]</p>
        </div>
    </div>

    <div class="fees-info">
        <table class="table table-bordered">
            <tbody>
                <tr>                                    
                    <td  colspan="2" class="to_bold">Tuition Fees</td>
                    <td class="to_bold">Payment Method : </td>
                    <td>Espece</td>
                </tr>
                <tr>
                    <td colspan="5" class="spacement"></td>
                </tr>
                <tr>
                    <td class="to_bold">Normal Amount : </td>
                    <td><?= $class_info["MONTANT_SCOLARITE"] .' fcfa' ?></td>
                    <td class="to_bold">Loan : </td>
                    <td><?= "25". ' %' ?></td>
                </tr>
                <tr>
                    <td class="to_bold">Total Amount : </td>
                    <td><?= $old_student["MONTANT_TOTAL"]. " fcfa" ?></td>
                    <td class="to_bold">Reduction : </td>
                    <td><?= ($scolarship_rate["TAUX"] - 25).'%' ?></td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td class="to_bold">Total : </td>
                    <td><?= $scolarship_rate["TAUX"].'%' ?></td>
                </tr>
                <tr>
                    <td colspan="4" class="spacement"></td>
                </tr>
                <tr>
                    <td class="to_bold">Payed Amount : </td>
                    <td ><?= $_SESSION['current_paiement']. " fcfa" ?></td>
                    <td class="to_bold">Remaining amount : </td>
                    <td colspan="1"><?= $old_student["MONTANT_TOTAL"] - $old_student["MONTANT_PAYE"]." fcfa" ?></td> 
                </tr>
            </tbody>
        </table>
    </div>

        <div class="other-info">
             <div class="student-info">
                <p class="info-title">Student information</p>
               
                    <p><span> First Name : </span><span class="text-uppercase"><?= $old_student["NOM"] ?></span></p>
                    <p><span>Last Name : </span><span class="text-capitalize"><?= $old_student["PRENOM"] ?></span></p>
                    <p><span>Matricule : </span><span class=""><?= $old_student["MATRICULE"] ?></span></p>
                    <p><span>Field : </span><span class=""><?= $class_info['NOM_FILIERE'] ?></span></p>
                    <p><span>Class : </span><span class=""><?= $class_info['NOM_CLASSE'] ?></span></p>
                    <p><span>Tel : </span><span class=""><?= $old_student['TELEPHONE'] ?></span></p>
                    <p><span>Email : </span><span class=""><?= $old_student['EMAIL'] ?></span></p>
            </div>

            <div class="school-info">
                <p class="info-title">School information</p>
                    <p><span class="h6">Agent : </span><span class=""><?= $_SESSION['USERNAME'] ?></span></p>
                    <p><span class="h6">Postal : </span><span class="">322 Koudougou</span></p>
                    <p><span class="h6">Website : </span><span class="">www.bit.bf</span></p>
                    <p><span class="h6">Tel : </span><span class="">(+226) 53 11 11 10 / (+226) 67 44 42 29</span></p>
                    <p><span class="h6">Email : </span><span class="">admissions@bit.bf</span></p>
            </div>
</div>
                            


<?php
    /*unset($_SESSION['old_matricule']);
    unset($_SESSION['id']);
    unset($_SESSION['current_paiement']);*/
?>