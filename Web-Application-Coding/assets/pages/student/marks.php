<?php
     $title = 'Grade';
     $breadcrumb = 'Marks';
 
     include('../../../utilities/QueryBuilder.php');
     include('header.php');
     
     $obj= new QueryBuilder();
?>

<div class="container-fluid" id="fenetre">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">  
                    Grade           
                </div>
                <div class="card-body py-2">  
                    <table id="table" class="table table-bordered table-hover table-responsive-lg table-responsive-md table-responsive-sm">
                        <thead>
                            <tr>
                                <th class="text-truncate">Semester</th>
                                <th class="text-truncate">Modulus</th>
                                <th class="text-truncate">Mark 1</th>
                                <th class="text-truncate">Mark 2</th>
                                <th class="text-truncate">Mark 3</th>
                                <th class="text-truncate">Administration Marks</th>
                                <th class="text-truncate" >Participation Marks</th>
                                <th  class="text-truncate">Sanction</th>
                                <th  class="text-truncate">Average</th>
                            </tr>
                        </thead> 

                        <tbody>
                            <?php
                                $Note=$obj->Requete("SELECT * FROM classe c, module m, notes n, semestre s WHERE n.ID_MODULE = m.ID_MODULE AND m.ID_SEMESTRE = s.ID_SEMESTRE AND c.ID_CLASSE = m.ID_CLASSE AND n.MATRICULE = (SELECT e.MATRICULE FROM etudiant e WHERE e.ID_USER = '".$_SESSION["ID_USER"]."') AND n.ANNEE_SCOLAIRE = '".getAnnee(0)["ID_ANNEE"]."'");
                                while($Notes=$Note->fetch()):
                            ?>
                                <tr>
                                    <td class="text-truncate" ><?=$Notes["NOM_SEMESTRE"];?></td>

                                    <td class="text-truncate" ><?=$Notes["NOM_MODULE"];?></td>

                                	<td class="text-truncate">
                                        <?php 
                                            if($Notes["NOTE1"] >= 0 && $Notes["NOTE1"] < 10 ):
                                                $actif = ' text-danger';   
                                        ?>
                                                <span class="py-2 px-4 <?= $actif ?>"><?=$Notes["NOTE1"];?></span>
                                        <?php

                                            elseif($Notes["NOTE1"] >=10 && $Notes["NOTE1"] < 15): 
                                                $actif = ' text-warning';
                                        ?>
                                                <span class="py-2 px-4 <?= $actif ?>"><?=$Notes["NOTE1"];?></span>
                                        <?php
                                            elseif($Notes["NOTE1"] >= 15 && $Notes["NOTE1"] <= 20): 
                                                $actif = ' text-success';
                                        ?>
                                                <span class="py-2 px-4  <?= $actif ?>"><?=$Notes["NOTE1"];?></span>
                                        <?php
                                            endif;
                                        ?> 
                                    </td>

                                    <td class="text-truncate">
                                        <?php 
                                            if($Notes["NOTE2"] >= 0 && $Notes["NOTE2"] < 10 ):
                                                $actif = ' text-danger';   
                                        ?>
                                                <span class="py-2 px-4 <?= $actif ?>"><?=$Notes["NOTE2"];?></span>
                                        <?php

                                            elseif($Notes["NOTE2"] >=10 && $Notes["NOTE2"] < 15): 
                                                $actif = ' text-warning';
                                        ?>
                                                <span class="py-2 px-4 <?= $actif ?>"><?=$Notes["NOTE2"];?></span>
                                        <?php
                                            elseif($Notes["NOTE2"] >= 15 && $Notes["NOTE2"] <= 20): 
                                                $actif = ' text-success';
                                        ?>
                                                <span class="py-2 px-4  <?= $actif ?>"><?=$Notes["NOTE2"];?></span>
                                        <?php
                                            endif;
                                        ?> 
                                    </td>

                                    <td class="text-truncate">
                                        <?php 
                                            if($Notes["NOTE3"] >= 0 && $Notes["NOTE3"] < 10 ):
                                                $actif = ' text-danger';   
                                        ?>
                                                <span class="py-2 px-4 <?= $actif ?>"><?=$Notes["NOTE3"];?></span>
                                        <?php

                                            elseif($Notes["NOTE3"] >=10 && $Notes["NOTE3"] < 15): 
                                                $actif = ' text-warning';
                                        ?>
                                                <span class="py-2 px-4 <?= $actif ?>"><?=$Notes["NOTE3"];?></span>
                                        <?php
                                            elseif($Notes["NOTE3"] >= 15 && $Notes["NOTE3"] <= 20): 
                                                $actif = ' text-success';
                                        ?>
                                                <span class="py-2 px-4  <?= $actif ?>"><?=$Notes["NOTE3"];?></span>
                                        <?php
                                            endif;
                                        ?> 
                                    </td>

                                   	<td class="text-truncate">
                                       <?php 
                                            if($Notes["NOTE_ADMINISTRATION"] >= 0 && $Notes["NOTE_ADMINISTRATION"] < 10 ):
                                                $actif = ' text-danger';   
                                        ?>
                                                <span class="py-2 px-4 <?= $actif ?>"><?=$Notes["NOTE_ADMINISTRATION"];?></span>
                                        <?php

                                            elseif($Notes["NOTE_ADMINISTRATION"] >=10 && $Notes["NOTE_ADMINISTRATION"] < 15): 
                                                $actif = ' text-warning';
                                        ?>
                                                <span class="py-2 px-4 <?= $actif ?>"><?=$Notes["NOTE_ADMINISTRATION"];?></span>
                                        <?php
                                            elseif($Notes["NOTE_ADMINISTRATION"] >= 15 && $Notes["NOTE_ADMINISTRATION"] <= 20): 
                                                $actif = ' text-success';
                                        ?>
                                                <span class="py-2 px-4  <?= $actif ?>"><?=$Notes["NOTE_ADMINISTRATION"];?></span>
                                        <?php
                                            endif;
                                        ?>
                                    </td>

                                    <td class="text-truncate">
                                        <?php 
                                            if($Notes["NOTE_PARTICIPATION"] >= 0 && $Notes["NOTE_PARTICIPATION"] < 10 ):
                                                $actif = ' text-danger';   
                                        ?>
                                                <span class="py-2 px-4 <?= $actif ?>"><?=$Notes["NOTE_PARTICIPATION"];?></span>
                                        <?php
                                            elseif($Notes["NOTE_PARTICIPATION"] >=10 && $Notes["NOTE_PARTICIPATION"] < 15): 
                                                $actif = ' text-warning';
                                        ?>
                                                <span class="py-2 px-4 <?= $actif ?>"><?=$Notes["NOTE_PARTICIPATION"];?></span>
                                        <?php
                                            elseif($Notes["NOTE_PARTICIPATION"] >= 15 && $Notes["NOTE_PARTICIPATION"] <= 20): 
                                                $actif = ' text-success';
                                        ?>
                                                <span class="py-2 px-4  <?= $actif ?>"><?=$Notes["NOTE_PARTICIPATION"];?></span>
                                        <?php
                                            endif;
                                        ?>
                                    </td>

                                    <td class="text-truncate">
                                        <?php 
                                            if($Notes["SANCTION"] >= 0 && $Notes["SANCTION"] < 10 ):
                                                $actif = ' text-danger';   
                                        ?>
                                                <span class="py-2 px-4 <?= $actif ?>"><?=$Notes["SANCTION"];?></span>
                                        <?php
                                            elseif($Notes["SANCTION"] >=10 && $Notes["SANCTION"] < 15): 
                                                $actif = ' text-warning';
                                        ?>
                                                <span class="py-2 px-4 <?= $actif ?>"><?=$Notes["SANCTION"];?></span>
                                        <?php
                                            elseif($Notes["SANCTION"] >= 15 && $Notes["SANCTION"] <= 20): 
                                                $actif = ' text-success';
                                        ?>
                                                <span class="py-2 px-4  <?= $actif ?>"><?=$Notes["SANCTION"];?></span>
                                        <?php
                                            endif;
                                        ?>
                                    </td>

                                    <td class="text-truncate">
                                        <?php 
                                            if($Notes["MOYENNE"] >= 0 && $Notes["MOYENNE"] < 10 ):
                                                $actif = ' text-danger';   
                                        ?>
                                                <span class="py-2 px-4 <?= $actif ?>"><?=$Notes["MOYENNE"];?></span>
                                        <?php
                                            elseif($Notes["MOYENNE"] >=10 && $Notes["MOYENNE"] < 15): 
                                                $actif = ' text-warning';
                                        ?>
                                                <span class="py-2 px-4 <?= $actif ?>"><?=$Notes["MOYENNE"];?></span>
                                        <?php
                                            elseif($Notes["MOYENNE"] >= 15 && $Notes["MOYENNE"] <= 20): 
                                                $actif = ' text-success';
                                        ?>
                                                <span class="py-2 px-4  <?= $actif ?>"><?=$Notes["MOYENNE"];?></span>
                                        <?php
                                            endif;
                                        ?>
                                    </td>
                                </tr>
                            <?php
                                endwhile ;
                            ?>   
                        </tbody>

                        <tfoot>
                            <tr>
                                <th class="text-truncate">Semester</th>
                                <th class="text-truncate">Modulus</th>
                                <th class="text-truncate">Mark 1</th>
                                <th class="text-truncate">Mark 2</th>
                                <th class="text-truncate">Mark 3</th>
                                <th class="text-truncate">Administration Marks</th>
                                <th class="text-truncate" >Participation Marks</th>
                                <th  class="text-truncate">Sanction</th>
                                <th  class="text-truncate">Average</th>
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