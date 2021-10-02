<?php
    $title = 'School fees';
    $breadcrumb = 'Overview';

    include('../../../utilities/QueryBuilder.php');
    include('header.php');
    
    $obj= new QueryBuilder();

    $historiquePay = $obj->Requete("SELECT * FROM historique_payement hpay,  scolarite sco WHERE hpay.ID_INSCRIPTION = (SELECT i.ID_INSCRIPTION FROM inscription i WHERE i.ID_ANNEE = '".getAnnee(0)["ID_ANNEE"]."' AND MATRICULE = (SELECT MATRICULE FROM etudiant WHERE ID_USER = '".$_SESSION["ID_USER"]."')) AND sco.ID_INSCRIPTION =hpay.ID_INSCRIPTION");
    
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover table-responsive-lg table-responsive-md table-responsive-sm" id="table">
                        <thead>
                            <tr>
                                <th class="text-truncate">Action</th>
                                <th class="text-truncate">Payment Date</th>
                                <th class="text-truncate">Payed Amount</th>
                                <th class="text-truncate">Total Amount</th>
                                <th class="text-truncate">Remaining Amount</th>
                            </tr>
                        </thead>

                        <tbody>
                        <?php
                            while ($back_horiq = $historiquePay->fetch()):
                                $montant_payer = $obj->Requete("SELECT SUM(MONTANT) FROM `historique_payement` WHERE ID_INSCRIPTION = (SELECT ID_INSCRIPTION FROM inscription WHERE MATRICULE = (SELECT MATRICULE FROM etudiant WHERE ID_USER = '".$_SESSION["ID_USER"]."')) AND DATE_PAYEMENT <='".$back_horiq['DATE_PAYEMENT']."'")->fetch();
                        ?>
                            <tr>
                                <td class="text-truncate">
                                    <a class="mt-3 btn btn-dark text-truncate" href="<?= 'recu.php?id_inscription='.$back_horiq['ID_INSCRIPTION'].'&dates='.$back_horiq['DATE_PAYEMENT'] ?>">Generate receipt</a>
                                </td>
                                <td class="text-truncate"><?= date("M d, Y | H:i:s",strtotime($back_horiq['DATE_PAYEMENT'])) ?></td>
                                <td class="text-truncate"><?= $back_horiq['MONTANT'].' fcfa' ?></td>
                                <td class="text-truncate"><?= $back_horiq['MONTANT_TOTAL'] ?></td>
                                <td class="text-truncate"><?= (intval($back_horiq['MONTANT_TOTAL'] )- intval($montant_payer["SUM(MONTANT)"])). ' fcfa' ?></td>
                            </tr>
                        <?php
                            endwhile;
                        ?>
                        </tbody>
                        
                        <tfoot>
                            <tr>
                                <th class="text-truncate">Action</th>
                                <th class="text-truncate">Payment Date</th>
                                <th class="text-truncate">Payed Amount</th>
                                <th class="text-truncate">Total Amount</th>
                                <th class="text-truncate">Remaining Amount</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
    include("../footer.php");
?>