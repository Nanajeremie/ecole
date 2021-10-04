<?php
    include '../../../utilities/QueryBuilder.php';
    $title = 'Liste des paiements';
    $breadcrumb = 'Liste des eleves';
    if (isset($_POST['set_matricule']))
    {
        extract($_POST);
        setSession('id', $matricule);
        Redirect('valide_other_paiement_form.php');
    }
    include('header.php');

    $obj = new QueryBuilder();
    $status = array();
    $all_new_payment = $obj->Requete('SELECT * FROM etudiant etu, classe c, niveau n, inscription insc, scolarite sco WHERE n.ID_NIVEAU = c.ID_NIVEAU AND c.ID_CLASSE = insc.ID_CLASSE AND etu.MATRICULE = insc.MATRICULE AND sco.ID_INSCRIPTION = insc.ID_INSCRIPTION AND insc.ID_ANNEE=(SELECT ID_ANNEE FROM annee_scolaire ORDER BY ID_ANNEE DESC LIMIT 1)');
    
?>
    <div class="container-fluid" id="fenetre">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6">
                                <h4 class="text-bluesky">Liste complete des eleves</h4>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body py-2">
                        
                        <form action="" method="post">
                            <!--input that keps the matricule of the clicked buttton-->
                            <input type="text" name="matricule" id="matricule" hidden>

                            <table class="table table-bordered table-hover table-responsive table-responsive-md table-responsive-lg" id="table">
                                <thead>
                                    <tr>
                                        <th class="text-truncate">Action</th>
                                        <th class="text-truncate">Matricule</th>
                                        <th class="text-truncate">NOM</th>
                                        <th class="text-truncate">PRENOM</th>
                                        <th class="text-truncate">SEXE</th>
                                        <th class="text-truncate">Classe</th>
                                        <th class="text-truncate">NIVEAU</th>
                                        <th class="text-truncate">PAYEE</th>
                                        <th class="text-truncate">RESTANTE</th>
                                        <th class="text-truncate">TOTAL</th>
                                    </tr>
                                </thead>
                                
                                <tbody id="payment">
                                    <?php
                                        while ($the_new_payment=$all_new_payment->fetch()) {?>
                                        <tr>
                                            <td class="text-truncate">
                                                <input class="btn btn-primary" name="set_matricule" type="submit"  value="Payer" title="<?= $the_new_payment['ID_INSCRIPTION']?>" onclick="Attrib_matricule_to_input(this.title)">
                                            </td>
                                            <td class="text-truncate"><?= $the_new_payment['MATRICULE']?></td>
                                            <td class="text-truncate"><?=$the_new_payment['NOM']?></td>
                                            <td class="text-truncate"><?=$the_new_payment['PRENOM']?></td>
                                            <td class="text-truncate"><?=$the_new_payment['SEXE']?></td>
                                            <td class="text-truncate"><?=$the_new_payment['NOM_CLASSE']?></td>
                                            <td class="text-truncate"><?=$the_new_payment['NOM_NIVEAU']?></td>
                                            <td class="text-truncate"><?=$the_new_payment['MONTANT_PAYE']?> F cfa</td>
                                            <td class="text-truncate"><?=$the_new_payment['MONTANT_TOTAL'] - $the_new_payment['MONTANT_PAYE']?> F cfa</td>
                                            <td class="text-truncate"><?=$the_new_payment['MONTANT_TOTAL']?> F cfa</td>
                                        </tr>
                                    <?php 
                                        } 
                                    ?>
                                </tbody>

                                <tfoot>
                                    <tr>
                                        <th class="text-truncate">Action</th>
                                        <th class="text-truncate">Matricule</th>
                                        <th class="text-truncate">NOM</th>
                                        <th class="text-truncate">PRENOM</th>
                                        <th class="text-truncate">SEXE</th>
                                        <th class="text-truncate">Classe</th>
                                        <th class="text-truncate">NIVEAU</th>
                                        <th class="text-truncate">PAYEE</th>
                                        <th class="text-truncate">RESTANTE</th>
                                        <th class="text-truncate">TOTAL</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script type="text/javascript">
    $('#year').css('display','block');

</script>
<?php
    include('../footer.php');
?>