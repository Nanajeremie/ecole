<?php
    include '../../../utilities/QueryBuilder.php';
    $title = 'Payment\'s List';
    $breadcrumb = 'All Students';
    if (isset($_POST['set_matricule']))
    {
        extract($_POST);
        setSession('id', $matricule);
        Redirect('valide_other_paiement_form.php');
    }
    include('header.php');

    $obj = new QueryBuilder();
    $status = array();
    $all_new_payment = $obj->Requete('SELECT * FROM etudiant etu, inscription insc, scolarite sco WHERE sco.MONTANT_TOTAL>sco.MONTANT_PAYE AND etu.MATRICULE = insc.MATRICULE AND sco.ID_INSCRIPTION = insc.ID_INSCRIPTION AND insc.ID_ANNEE="'.getAnnee(0)['ID_ANNEE'].'"');
    
    
?>
    <div class="container-fluid" id="fenetre">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6">
                                <h4 class="text-bluesky">Student's List</h4>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body py-2">
                        
                        <form action="" method="post">
                            <!--input that keps the matricule of the clicked buttton-->
                            <input type="text" name="matricule" id="matricule" style = "display: none">

                            <table class="table table-bordered table-hover table-responsive table-responsive-md table-responsive-lg" id="table">
                                <thead>
                                    <tr>
                                        <th class="text-truncate">Action</th>
                                        <th class="text-truncate">Matricule</th>
                                        <th class="text-truncate">First Name</th>
                                        <th class="text-truncate">Last Name</th>
                                        <th class="text-truncate">Gender</th>
                                        <th class="text-truncate">Class</th>
                                        <th class="text-truncate">Field</th>
                                        <th class="text-truncate">Payed amount</th>
                                        <th class="text-truncate">Remaining amount</th>
                                        <th class="text-truncate">Total amount</th>
                                    </tr>
                                </thead>
                                
                                <tbody id="payment">
                                    <?php
                                        while ($the_new_payment=$all_new_payment->fetch())
                                        {
                                            $classe = $obj->Select('classe', array(), array('ID_CLASSE' => $the_new_payment['ID_CLASSE']))->fetch();
                                            $filiere = $obj->Select('filieres', array(), array('ID_FILIERE' => $classe['ID_FILIERE']))->fetch();
                                    ?>
                                        <tr>
                                            <td class="text-truncate">
                                                    <input class="btn btn-primary" name="set_matricule" type="submit"  value="Pay Tuition" title="<?= $the_new_payment['MATRICULE']?>" onclick="Attrib_matricule_to_input(this.title)">
                                            </td>
                                            <td class="text-truncate"><?= $the_new_payment['MATRICULE']?></td>
                                            <td class="text-truncate"><?=$the_new_payment['NOM']?></td>
                                            <td class="text-truncate"><?=$the_new_payment['PRENOM']?></td>
                                            <td class="text-truncate"><?=$the_new_payment['SEXE']?></td>
                                            <td class="text-truncate"><?=$classe['NOM_CLASSE']?></td>
                                            <td class="text-truncate"><?=$filiere['NOM_FILIERE']?></td>
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
                                        <th class="text-truncate">First Name</th>
                                        <th class="text-truncate">Last Name</th>
                                        <th class="text-truncate">Gender</th>
                                        <th class="text-truncate">Class</th>
                                        <th class="text-truncate">Field</th>
                                        <th class="text-truncate">Payed amount</th>
                                        <th class="text-truncate">Remaining amount</th>
                                        <th class="text-truncate">Total amount</th>
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