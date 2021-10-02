<?php
     $title = 'Grade';
     $breadcrumb = 'Modulus';

     include('../../../utilities/QueryBuilder.php');
     include('header.php');

     
     $obj= new QueryBuilder();
?>

<div class="container-fluid" id="fenetre">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">             
                </div>
                <div class="card-body py-2">  
                    <table id="table" class="table table-bordered table-hover table-responsive">
                        <thead>
                            <tr>
                                <th class="text-truncate">Class</th>
                                <th class="text-truncate">Semestre</th>
                                <th class="text-truncate">Modulus</th>
                                <th class="text-truncate">Professeur</th>
                                <th class="text-truncate">Volume</th>
                                <th class="text-truncate">Description</th>
                            </tr>
                        </thead> 
                        <tbody>
                                <?php
                                  $Modul=$obj->Requete("SELECT * FROM classe c , semestre s , module m , enseigner e , professeur p WHERE m.ID_CLASSE = c.ID_CLASSE AND m.ID_SEMESTRE = s.ID_SEMESTRE AND e.ID_MODULE = m.ID_MODULE AND e.ID_PROFESSEUR = p.ID_PROFESSEUR AND c.ID_CLASSE = (SELECT i.ID_CLASSE FROM inscription i WHERE i.MATRICULE = (SELECT e.MATRICULE FROM etudiant e WHERE e.ID_USER = '".$_SESSION["ID_USER"]."') AND i.ID_ANNEE = '".getAnnee(0)["ID_ANNEE"]."') ");
                                  while($Moduls=$Modul->fetch()):
                                ?>
                                <tr>
                                    <td class="text-truncate" ><?= $Moduls["NOM_CLASSE"]?></td>
                                    <td class="text-truncate"><?= $Moduls["NOM_SEMESTRE"]?></td>
                                    <td class="text-truncate"><?= $Moduls["NOM_MODULE"]?></td>
                                    <td class="text-truncate"><?= $Moduls["NOM_PROF"].' '.$Moduls["PRENOM_PROF"]?></td>
                                    <td class="text-truncate"><?= $Moduls["VOLUME_HORAIRE"]?></td>
                                    <td ><?= $Moduls["DESCRIPTION"]?></td>
                                    
                                </tr>
                                <?php endwhile ;?>   
                          </tbody>
                          <tfoot>
                            <tr>
                                <th class="text-truncate">Class</th>
                                <th class="text-truncate">Semestre</th>
                                <th class="text-truncate">Modulus</th>
                                <th class="text-truncate">Professeur</th>
                                <th class="text-truncate">Volume</th>
                                <th class="text-truncate">Description</th>
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

