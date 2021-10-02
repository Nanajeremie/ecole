<?php
    include '../../../utilities/QueryBuilder.php';
    $title = 'Old Student Tuition';
    $breadcrumb = 'First Payment';

if (isset($_POST['set_old_matricule']))
    {
        extract($_POST);
        setSession('old_matricule', $old_matricule);

        Redirect('paiement.php');
    }
    include('header.php');

    $obj = new QueryBuilder();
    $status = array();
    //definition of global variable
    $all_old_student_tuition = 0;
    //$all_old_student_tuition = $obj->Requete('SELECT * FROM etudiant etu, inscription insc WHERE etu.MATRICULE = insc.MATRICULE  AND insc.DATE_INSCRIPTION>="' . getAnnee(-1)['DATE_INI'] . '" AND insc.DATE_INSCRIPTION<="' . getAnnee(-1)['DATE_FIN'] . '"');
    if(is_array(getAnnee(-1)))
    {

        $all_old_student_tuition = $obj->Requete('SELECT * FROM etudiant etu, inscription insc, scolarite sco WHERE insc.MATRICULE NOT IN(SELECT MATRICULE FROM inscription WHERE ID_ANNEE="'.getAnnee(0)['ID_ANNEE'].'") AND sco.ID_INSCRIPTION=insc.ID_INSCRIPTION AND  etu.MATRICULE = insc.MATRICULE  AND insc.ID_ANNEE="' . getAnnee(-1)['ID_ANNEE'] .'"');

    }
?>
    <div class="container-fluid" id="fenetre">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6">
                                <h4 class="text-bluesky">Old students</h4>
                            </div>
                            <div class="col-6 text-right">
                                <a class="btn btn-dark" role="button" href="inscription_valide.php">New Student
                                    Payment</a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-2">
                        <div id="toolbar">
                            <select class="form-control dt-tb">
                                <option value="">Export Basic</option>
                                <option value="all">Export All</option>
                                <option value="selected">Export Selected</option>
                            </select>
                        </div>
                    </div>
                    <form action="" method="post">
                        <!--input that keps the matricule of the clicked buttton-->
                        <input type="text" name="old_matricule" id="old_matricule" style="display: none">
                        <?php if(is_array(getAnnee(-1)))
                            { ?>
                        <table id="table"
                               data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true"
                               data-show-pagination-switch="true"
                               data-show-refresh="true" data-key-events="true" data-show-toggle="true"
                               data-resizable="false" data-cookie="true"
                               data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true"
                               data-toolbar="#toolbar">
                            <thead>
                            <tr>
                                <th data-field="id">Action</th>
                                <th data-field="matricule">Matricule</th>
                                <th data-field="field">First Name</th>
                                <th data-field="name">Last Name</th>
                                <th data-field="gender">Gender</th>
                                <th data-field="birth">Birthday</th>
                                <th data-field="cnib">CNIB</th>
                                <th data-field="email">Email</th>
                                <th data-field="phone">Phone Number</th>
                                <th data-field="emergency_number">Emergency Number</th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php
                             while ($the_old_payment = $all_old_student_tuition->fetch()) {
                                extract($the_old_payment);
                                $classe = $obj->Select('classe', array(), array('ID_CLASSE' => $the_old_payment['ID_CLASSE']))->fetch();
                                $filiere = $obj->Select('filieres', array(), array('ID_FILIERE' => $classe['ID_FILIERE']))->fetch();
                                ?>

                                <tr>

                                    <?php if($the_old_payment['MONTANT_TOTAL']>$the_old_payment['MONTANT_PAYE']){?>
                                    <td>
                                        <input class="btn btn-danger" name="set_old_matricule" type="button"
                                               value="Pay Tuition" data-toggle="modal" data-target="#payAlert" onclick="rein_alert(<?= htmlspecialchars(json_encode($the_old_payment['MATRICULE'].'~'.$NOM.' '.$PRENOM))?>)">

                                        <!-- <a href="paiement.php?id=<?= $the_old_payment['MATRICULE']; ?>"
                                           class="btn btn-primary">Pay Tuition</a>-->
                                    </td>
                                    <?php }else{?>
                                    <td>
                                        <input class="btn btn-primary" name="set_old_matricule" type="submit"
                                               value="Pay Tuition" title="<?= $the_old_payment['MATRICULE'] ?>"
                                               onclick="Attrib_old_matricule_to_input(this.title)">

                                        <!-- <a href="paiement.php?id=<?= $the_old_payment['MATRICULE']; ?>"
                                           class="btn btn-primary">Pay Tuition</a>-->
                                    </td>
                                    <?php } ?>
                                    <td><?= $MATRICULE ?></td>
                                    <td class="text-capitalize"><?= $NOM ?></td>
                                    <td class="text-capitalize"><?= $PRENOM ?></td>
                                    <td class="text-capitalize"><?= $SEXE ?></td>
                                    <td><?= $DATE_NAISSANCE ?></td>
                                    <td class="text-uppercase"><?= $CNIB ?></td>
                                    <td><?= $EMAIL ?></td>
                                    <td><?= $TELEPHONE ?></td>
                                    <td><?= $NUM_URGENCE ?></td>
                                </tr>
                             <?php 
                             } 
                             ?>
                              </tbody>
                                 </table>
                             <?php
                             }
                             else
                             {
                                 echo "<p class = 'text-center'>No matching records found !</p>";
                             }?>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Debut du modal alert pour l'inscription d'ancien etudiant impayer -->
        <div class="modal fade" id="payAlert" data-backdrop="static" data-keyboard="false" role="dialog" tabindex="-1" aria-hidden="true" >
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                        <div class="modal-header bg-danger text-center text-white">
                                <h5 class="modal-title">Reinscription notification</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                            </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <span class="text-center" id="show_infos"></span>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Return</button>
                    </div>
                </div>
            </div>
        </div>
    <!-- End Delete Department Modal -->
    <script type="text/javascript">
        function rein_alert(info){
            $("#show_infos").html('<span class="text-primary">'+info.split('~')[1]+'</span> whit matricule: <b>'+info.split('~')[0]+'</b> did not finish to pay the tuition of the last year. Please pay all the tuition before getting new reincription');
        }
    </script>
<?php
include('../footer.php');

?>

