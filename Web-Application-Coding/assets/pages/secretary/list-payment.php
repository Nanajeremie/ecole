<?php
    include('../../../utilities/QueryBuilder.php');
    $title = 'List payment';
    $breadcrumb = 'All Students';
    include_once('header.php');

    $obj = new QueryBuilder();
    $status = array();
    $all_new_payment = $obj->Requete('SELECT * FROM etudiant etu, inscription insc, scolarite sco WHERE etu.MATRICULE = insc.MATRICULE  AND sco.ID_INSCRIPTION = insc.ID_INSCRIPTION AND insc.ID_ANNEE="' . getAnnee(0)['ID_ANNEE'] . '"');
    $temps = $obj->Requete('SELECT NOW() as dat')->fetch();

?>
    <div class="container-fluid" id="fenetre">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <ul class="nav nav-tabs">
                                <li class="nav-item">
                                    <a href="#up_to_date" class="nav-link active" data-toggle="tab"
                                       data-target="#up_to_date">Up to date students</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#out_to_date" class="nav-link text-danger" data-toggle="tab"
                                       data-target="#out_to_date">Out to date students</a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="card-body py-2">
                        <div class="tab-content">

                            <!-- Up to date student -->
                            <div class="tab-pane fade show active" id="up_to_date">
                                
                                <table class="table table-bordered table-hover table-responsive table-responsive-md table-responsive-lg" id="table">
                                    <thead>
                                        <tr>
                                            <th class="text-truncate">Action</th>
                                            <th class="text-truncate">Matricule</th>
                                            <th class="text-truncate">First Name</th>
                                            <th class="text-truncate">Last Name</th>
                                            <th class="text-truncate">Gender</th>
                                            <th class="text-truncate">Class</th>
                                            <th class="text-truncate">Remaining amount</th>
                                            <th class="text-truncate">Total amount</th>
                                            <th class="text-truncate">Statut</th>
                                        </tr>
                                    </thead>

                                    <tbody id="cont">
                                        <?php

                                            while ($the_new_payment = $all_new_payment->fetch()) {
                                                $tauxPayer=0;
                                                $temoin = 0;


                                                if ($the_new_payment['MONTANT_TOTAL']!=0){
                                                    $tauxPayer = ($the_new_payment['MONTANT_PAYE'] / $the_new_payment['MONTANT_TOTAL']) * 100;
                                                    $tauxPayer = floatval($tauxPayer);
                                                    
                                                }else{
                                                    $temoin = 1;
                                                    $tauxPayer=100;
                                                }


                                                if ($temps['dat'] <= getAnnee(0)['FIN_VERSEMENT_1']) {
                                                    //vrai
                                                    $temoin = 1;
                                                } else {

                                                    if ($temps['dat'] >= getAnnee(0)['FIN_VERSEMENT_1'] and $temps['dat'] < getAnnee(0)['FIN_VERSEMENT_2'] and $tauxPayer >= 50) {
                                                        $temoin = 1;
                                                        //vrai

                                                    } elseif ($temps['dat'] >= getAnnee(0)['FIN_VERSEMENT_2'] and $temps['dat'] < getAnnee(0)['FIN_VERSEMENT_3'] and $tauxPayer >= 75) {
                                                        //vrai
                                                        $temoin = 1;
                                                    }
                                                    elseif ($temps['dat'] >= getAnnee(0)['FIN_VERSEMENT_3'] and $tauxPayer >= 100) {
                                                        //vrai
                                                        $temoin = 1;
                                                    }
                                                }

                                                $classe = $obj->Select('classe', array(), array('ID_CLASSE' => $the_new_payment['ID_CLASSE']))->fetch();
                                                $filiere = $obj->Select('filieres', array(), array('ID_FILIERE' => $classe['ID_FILIERE']))->fetch();
                                                $update = 'see';
                                                $update = $the_new_payment['MATRICULE'];
                                                if ($temoin == 1) {
                                                    $student_name = $the_new_payment['NOM']." ".$the_new_payment['PRENOM']." : ".$classe['NOM_CLASSE'];
                                                    ?>
                                                    <tr>
                                                        <td class="text-truncate">
                                                            <button class="btn btn-outline-primary" data-toggle="modal"
                                                                    data-target="#updates" onclick="getStudentInfo(<?=htmlspecialchars(json_encode($the_new_payment['ID_INSCRIPTION'])) ?>,<?= htmlspecialchars(json_encode($student_name)) ?>)">
                                                                Details
                                                            </button>
                                                        </td>
                                                        <td class="text-truncate"><?= $the_new_payment['MATRICULE'] ?></td>
                                                        <td class="text-truncate"><?= $the_new_payment['NOM'] ?></td>
                                                        <td class="text-truncate"><?= $the_new_payment['PRENOM'] ?></td>
                                                        <td class="text-truncate"><?= $the_new_payment['SEXE'] ?></td>
                                                        <td class="text-truncate"><?= $classe['NOM_CLASSE'] ?></td>
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
                                                }
                                            };
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
                                            <th class="text-truncate">Remaining amount</th>
                                            <th class="text-truncate">Total amount</th>
                                            <th class="text-truncate">Statut</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <!-- Out to date student -->
                            <div class="tab-pane fade" id="out_to_date">

                                <table class="table table-bordered table-hover table-responsive table-responsive-md table-responsive-lg" id="table1" >
                                    <thead>
                                        <tr>
                                            <th class="text-truncate">Action</th>
                                            <th class="text-truncate">Matricule</th>
                                            <th class="text-truncate">First Name</th>
                                            <th class="text-truncate">Last Name</th>
                                            <th class="text-truncate">Gender</th>
                                            <th class="text-truncate">Class</th>
                                            <th class="text-truncate">Remaining amount</th>
                                            <th class="text-truncate">Total amount</th>
                                            <th class="text-truncate">Statut</th>
                                        </tr>
                                    </thead>

                                    <tbody id="outcont">
                                        <?php

                                            $yaro = $obj->Requete('SELECT * FROM etudiant etu, inscription insc, scolarite sco WHERE etu.MATRICULE = insc.MATRICULE  AND sco.ID_INSCRIPTION = insc.ID_INSCRIPTION AND insc.ID_ANNEE="' . getAnnee(0)['ID_ANNEE'] . '"');

                                            while ($thepayment = $yaro->fetch()) 
                                            {
                                                $temoin=1;
                                                $tauxPayer=0;
                                                $classe = $obj->Select('classe', array(), array('ID_CLASSE' => $thepayment['ID_CLASSE']))->fetch();
                                                $filiere = $obj->Select('filieres', array(), array('ID_FILIERE' => $classe['ID_FILIERE']))->fetch();
                                                $update = 'see';
                                                $update = $thepayment['MATRICULE'];

                                                if ($thepayment['MONTANT_TOTAL']!=0)
                                                {
                                                    $tauxPayer = ($thepayment['MONTANT_PAYE'] / $thepayment['MONTANT_TOTAL']) * 100;
                                                    $tauxPayer = floatval($tauxPayer);
                                                }
                                                // if($tauxPayer!=0){
                                                if ($temps['dat'] >= getAnnee(0)['FIN_VERSEMENT_1'] and $temps['dat'] < getAnnee(0)['FIN_VERSEMENT_2'] and $tauxPayer <= 50) {
                                                    $temoin = 0;
                                                    //vrai

                                                } 

                                                elseif ($temps['dat'] >= getAnnee(0)['FIN_VERSEMENT_2'] and $temps['dat'] < getAnnee(0)['FIN_VERSEMENT_3'] and $tauxPayer <= 75) {
                                                    //vrai
                                                    $temoin = 0;
                                                } 

                                                elseif ($temps['dat'] >= getAnnee(0)['FIN_VERSEMENT_3'] and $tauxPayer < 100) {
                                                    //vrai
                                                    $temoin = 0;
                                                }
                                                //  }

                                                if ($temoin==0)
                                                {
                                                    $student_names = $thepayment['NOM']." ".$thepayment['PRENOM']." : ".$classe['NOM_CLASSE'];

                                        ?>
                                        <tr>
                                            <td class="text-truncate">
                                                <button class="btn btn-outline-primary" data-toggle="modal"
                                                        data-target="#updates" onclick="getStudentInfo(<?=htmlspecialchars(json_encode($thepayment['ID_INSCRIPTION'])) ?>,<?= htmlspecialchars(json_encode($student_names)) ?>)">
                                                    Details
                                                </button>
                                            </td>
                                            <td class="text-truncate"><?= $thepayment['MATRICULE'] ?></td>
                                            <td class="text-truncate"><?= $thepayment['NOM'] ?></td>
                                            <td class="text-truncate"><?= $thepayment['PRENOM'] ?></td>
                                            <td class="text-truncate"><?= $thepayment['SEXE'] ?></td>
                                            <td class="text-truncate"><?= $classe['NOM_CLASSE'] ?></td>
                                            <td class="text-truncate"><?= $thepayment['MONTANT_TOTAL'] - $thepayment['MONTANT_PAYE'] = 0 ? '<span class="text-primary">Completed</span>' : $thepayment['MONTANT_TOTAL'] - $thepayment['MONTANT_PAYE']  ?></td>
                                            <td class="text-truncate"><?= $thepayment['MONTANT_TOTAL'] ?></td>
                                            <?php
                                                if ($tauxPayer < 100 AND $tauxPayer >0) 
                                                {
                                            ?>
                                                <td class="text-warning text-truncate"><?php printf("%.2f",$tauxPayer) ;?> %</td>
                                            <?php 
                                                } 
                                                else 
                                                {
                                            ?>
                                                    <td class="text-success text-truncate"><?php printf("%.2f",$tauxPayer) ;?> %</td>
                                            <?php 
                                                } 
                                            ?>
                                        </tr>
                                        <?php 
                                                } 
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
                                            <th class="text-truncate">Remaining amount</th>
                                            <th class="text-truncate">Total amount</th>
                                            <th class="text-truncate">Statut</th>
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
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white"><?='Paiement Information' ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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
                                                <span class="card-title text-capitalize h4" id="students_head"></span>
                                            </div>
                                            <div class="col-lg-4">
                                                <span>Academic Year : <?= getAnnee(0)['DATE_INI'] ?></span>
                                            </div>
                                        </div>
                                        <div class="card-text"  id="payement_content">
                                            
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
    <!--    Fin modal Update-->

   <!-- Modal Out to date-->
    <div class="modal fade" id="o<?= $update ?>" data-backdrop="static"
         data-keyboard="false" role="dialog" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
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
                                                <span>Academic Year : <?= getAnnee(0) ?></span>
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



