<?php
    include('../../../utilities/QueryBuilder.php');
    $title = 'Liste des paiement';
    $breadcrumb = 'Liste complete';
    $obj = new QueryBuilder();
    $all_new_payment = $obj->Requete('SELECT * FROM etudiant etu, classe c, niveau n,inscription insc, scolarite sco, annee_scolaire a WHERE c.ID_CLASSE=insc.ID_CLASSE AND n.ID_NIVEAU = c.ID_NIVEAU AND a.ID_ANNEE = insc.ID_ANNEE AND etu.MATRICULE = insc.MATRICULE  AND sco.ID_INSCRIPTION = insc.ID_INSCRIPTION AND sco.MONTANT_TOTAL>sco.MONTANT_PAYE AND insc.ID_ANNEE=(SELECT ID_ANNEE FROM annee_scolaire ORDER BY ID_ANNEE DESC LIMIT 1)');
    $temps = $obj->Requete('SELECT NOW() as dat')->fetch();

    include_once('header.php');
?>

    <div class="container-fluid" id="fenetre">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            
                        </div>
                    </div>

                    <div class="card-body py-2">
                        <div class="tab-content">

                            <!-- Up to date student -->
                            <div class="tab-pane fade show active" id="up_to_date">
                                
                                <table class="table table-bordered table-hover " id="table">
                                    <thead >
                                        <tr>
                                            <th class="text-truncate h6">Action</th>
                                            <th class="text-truncate h6">Nom</th>
                                            <th class="text-truncate h6">Prenom</th>
                                            <th class="text-truncate h6">Sexe</th>
                                            <th class="text-truncate h6">Classe</th>
                                            <th class="text-truncate h6">Somme restante</th>
                                            <th class="text-truncate h6">Montant total</th>
                                            <th class="text-truncate h6">Etat</th>
                                        </tr>
                                    </thead>

                                    <tbody id="cont">
                                        <?php

                                            while ($the_new_payment = $all_new_payment->fetch()) {
                                                $tauxPayer=0;
                                                $temoin = 0;

                                                $tauxPayer = ($the_new_payment['MONTANT_PAYE'] / $the_new_payment['MONTANT_TOTAL']) * 100;
                                                $tauxPayer = floatval($tauxPayer);

                                                if ($temps['dat'] <= $the_new_payment['FIN_VERSEMENT_1']) {
                                                    //vrai
                                                    $temoin = 1;
                                                } else {

                                                    if ($temps['dat'] >= $the_new_payment['FIN_VERSEMENT_1'] and $temps['dat'] < $the_new_payment['FIN_VERSEMENT_2'] and $tauxPayer >= 50) {
                                                        $temoin = 1;
                                                        //vrai

                                                    } elseif ($temps['dat'] >= $the_new_payment['FIN_VERSEMENT_2'] and $temps['dat'] < $the_new_payment['FIN_VERSEMENT_3'] and $tauxPayer >= 75) {
                                                        //vrai
                                                        $temoin = 1;
                                                    }
                                                    elseif ($temps['dat'] >=$the_new_payment['FIN_VERSEMENT_3'] and $tauxPayer >= 100) {
                                                        //vrai
                                                        $temoin = 1;
                                                    }
                                                }

                                                $update = 'see';
                                                $update = $the_new_payment['MATRICULE'];
                                               
                                                    $student_name = $the_new_payment['NOM']." ".$the_new_payment['PRENOM']." : ".$the_new_payment['NOM_CLASSE'];
                                                    ?>
                                                    <tr class="<?=$temoin==1?'':''?>">
                                                        <td class="text-truncate">
                                                            <button class="btn btn-outline-primary" data-toggle="modal"
                                                                    data-target="#updates" onclick="getStudentInfo(<?=htmlspecialchars(json_encode($the_new_payment['ID_INSCRIPTION'])) ?>,<?= htmlspecialchars(json_encode($student_name)) ?>)">
                                                                Details
                                                            </button>
                                                        </td>
                                                        <td class="text-truncate"><?= $the_new_payment['NOM'] ?></td>
                                                        <td class="text-truncate"><?= $the_new_payment['PRENOM'] ?></td>
                                                        <td class="text-truncate"><?= $the_new_payment['SEXE'] ?></td>
                                                        <td class="text-truncate"><?= $the_new_payment['NOM_CLASSE'] ?></td>
                                                        <td class="text-truncate"><?= ($the_new_payment['MONTANT_TOTAL'] - $the_new_payment['MONTANT_PAYE'] == 0 ) ? '<span class="text-primary">Completed</span>' : $the_new_payment['MONTANT_TOTAL'] - $the_new_payment['MONTANT_PAYE'] ?></td>
                                                        <td class="text-truncate"><?= $the_new_payment['MONTANT_TOTAL'] ?></td>
                                                        <?php
                                                        if ($tauxPayer < 100 AND $tauxPayer >0) {
                                                            ?>
                                                        <td class="text-warning text-truncate"><?php printf("%.2f",$tauxPayer) ;?> %</td>
                                                        <?php } else {
                                                            ?>
                                                            <td class="text-success text-truncate"><?php printf("%.2f",$tauxPayer) ;?> %</td>
                                                        <?php } ?>
                                                    </tr>

                                                    <!--Details Modal -->

                                                    <?php
                                            };
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th class="text-truncate">Action</th>
                                            <th class="text-truncate">Nom</th>
                                            <th class="text-truncate">Prenom</th>
                                            <th class="text-truncate">Sexe</th>
                                            <th class="text-truncate">Classe</th>
                                            <th class="text-truncate">Somme restante</th>
                                            <th class="text-truncate">Montant total</th>
                                            <th class="text-truncate">Etat</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

       <!--Modalls update-->
    <div class="modal fade" id="updates" data-backdrop="static"
         data-keyboard="false" role="dialog" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                        <div class="col-12 col-md-6">
                            <h5 class="modal-title text-white"><?='Historique de paiement / ' ?><span id="students_head" class="text-dark"></span></h5>
                        </div>
                        <div class="col-12 col-md-4 text-left">
                            
                        </div>
                        <div class="col-12 col-md-2 text-right">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true" class="text-white">&times;</span>
                            </button>
                        </div>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <!-- Personnal Information -->
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="card-text" id="payement_content"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--    Fin modal Update-->

   <!-- Modal Out to date-->
    <div class="modal fade" id="o<?= $update ?>" data-backdrop="static"
         data-keyboard="false" role="dialog" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white"><?= $thepayment['MATRICULE'] . ' Information' ?></h5>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true" class="text-white">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <!-- Personnal Information -->

                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-8">
                                                <span class="card-title text-capitalize h4"><?= $thepayment['NOM'] . ' ' . $the_new_payment['PRENOM'] . ' : ' . $classe['NOM_CLASSE'] ?></span>
                                            </div>
                                            <div class="col-lg-4">
                                                <span>Academic Year : </span>
                                            </div>
                                        </div>
                                        <div class="card-text">
                                            <?php
                                            $i = 1;
                                            for ($i = 0; $i < 5; $i++) :
                                                ?>
                                                <div class="row py-5">
                                                    <div class="col-lg-8">
                                                        <h6 class="mt-3">Versement
                                                            : <?= ''?></h6>
                                                        <hr>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col">
                                                        <label>Payment Date</label>
                                                        <p><?= '' ?></p>
                                                    </div>
                                                    <div class="col">
                                                        <label>Payed amount</label>
                                                        <p><?= '' ?></p>
                                                    </div>
                                                    <div class="col">
                                                        <label>Remaining
                                                            amount</label>
                                                        <p><?= '' ?></p>
                                                    </div>
                                                    <div class="col">
                                                        <label>Total amount</label>
                                                        <p><?= ''?></p>
                                                    </div>
                                                </div>
                                            <?php
                                            endfor;
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    

<!--Fin Out To Date-->
<script type="text/javascript">
    $('#year').css('display','block');

</script>
<?php
    include('../footer.php');
?>




