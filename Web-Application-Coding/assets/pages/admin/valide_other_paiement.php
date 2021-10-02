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
                        <div id="toolbar">
                            <select class="form-control dt-tb">
                                <option value="">Export Basic</option>
                                <option value="all">Export All</option>
                                <option value="selected">Export Selected</option>
                            </select>
                        </div>
                   
                        <form action="" method="post">
                            <!--input that keps the matricule of the clicked buttton-->
                            <input type="text" name="matricule" id="matricule" style = "display: none">
                            <table  id="table" data-toggle="table" data-show-columns="true" data-show-export="true" data-toolbar="#toolbar">
                                <thead>
                                    <tr>
                                        <th  data-field="action">Action</th>
                                        <th  data-field="Matricule">Matricule</th>
                                        <th  data-field="first-name">First Name</th>
                                        <th  data-field="last-name">Last Name</th>
                                        <th  data-field="gender">Gender</th>
                                        <th  data-field="class">Class</th>
                                        <th  data-field="field">Field</th>
                                        <th  data-field="payed-amount">Payed amount</th>
                                        <th  data-field="remain-amount">Remaining amount</th>
                                        <th  data-field="total-amount">Total amount</th>
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
                                                <td><input class="btn btn-primary" name="set_matricule" type="submit"  value="Pay Tuition" title="<?= $the_new_payment['MATRICULE']?>" onclick="Attrib_matricule_to_input(this.title)"></td>
                                                <td><?= $the_new_payment['MATRICULE']?></td>
                                                <td><?=$the_new_payment['NOM']?></td>
                                                <td><?=$the_new_payment['PRENOM']?></td>
                                                <td><?=$the_new_payment['SEXE']?></td>
                                                <td><?=$classe['NOM_CLASSE']?></td>
                                                <td><?=$filiere['NOM_FILIERE']?></td>
                                                <td><?=$the_new_payment['MONTANT_PAYE']?> F cfa</td>
                                                <td><?=$the_new_payment['MONTANT_TOTAL'] - $the_new_payment['MONTANT_PAYE']?> F cfa</td>
                                                <td><?=$the_new_payment['MONTANT_TOTAL']?> F cfa</td>
                                            </tr>
                                    <?php 
                                        }
                                    ?>
                                </tbody>
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