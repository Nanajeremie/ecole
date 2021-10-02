<?php
    include('../../../utilities/QueryBuilder.php');
    $title = 'Average';
    $module =null;
    $breadcrumb = 'Generate Averages';
    $obj = new QueryBuilder();

    // @Jeremie Cette partie permet de selectionner les une des classe enseigner par un prof NB: unitile dans cette partie
   $prof=$obj->Requete("SELECT * FROM professeur p, enseigner e WHERE p.ID_USER='".$_SESSION["ID_USER"]."' AND p.ID_PROFESSEUR = e.ID_ENSEIGNER LIMIT 1")->fetch();

    // @Jeremie cette partie permet de selectionner les classe enseignee
   $prof_class=$obj->Requete("SELECT * FROM classe c WHERE c.ID_CLASSE IN (SELECT m.ID_CLASSE FROM enseigner e, module m WHERE e.ID_MODULE=m.ID_MODULE)");

    // @Jermeie => meme chose que la partie precedente mais tres util NB: ne pas toucher sans une permission
    $prof_class2=$obj->Requete("SELECT * FROM classe c WHERE c.ID_CLASSE IN (SELECT m.ID_CLASSE FROM enseigner e, module m WHERE e.ID_MODULE=m.ID_MODULE)");

    include('header.php');
    $student_notes = array();

    $temoin = 0;
    if($get_prof_resul = $prof_class2->fetch()){
        $module_prof=$obj->Requete("SELECT * FROM module m, enseigner e WHERE m.ID_CLASSE=".$get_prof_resul['ID_CLASSE']." AND e.ID_MODULE=m.ID_MODULE ");
        if($back_module =$module_prof->fetch()){
            
            $verify_test = $obj->Requete("SELECT * FROM devoirs WHERE ID_MODULE =".$back_module['ID_MODULE']."");
            if($back_test =$verify_test->fetch()){
                 $requetes = $obj->Requete("SELECT n.MATRICULE, n.ID_MODULE , e.NOM , e.PRENOM , n.NOTE1 , n.NOTE2 , n.NOTE3 , n.NOTE_PARTICIPATION, n.NOTE_ADMINISTRATION, n.SANCTION, n.MOYENNE FROM notes n , etudiant e WHERE e.MATRICULE = n.MATRICULE AND n.ID_MODULE='".$back_module['ID_MODULE']."' AND n.ANNEE_SCOLAIRE='".getAnnee(0)['ID_ANNEE']."' ORDER BY n.ID_NOTE ASC");

                $note1 = $obj->Requete("SELECT * FROM module m, devoirs d WHERE m.ID_MODULE = d.ID_MODULE AND m.ID_MODULE=".$back_module['ID_MODULE']."");

                 $get_Classe = $obj->Requete("SELECT * FROM module m, devoirs d WHERE m.ID_MODULE = d.ID_MODULE AND m.ID_MODULE=".$back_module['ID_MODULE']."")->fetch();

                $class_name = $obj->Requete("SELECT * FROM classe WHERE ID_CLASSE=".$get_Classe['ID_CLASSE']."")->fetch()['NOM_CLASSE'];
                $dev_list = 0;
                $head = '
                        <th>Matricule</th>
                        <th>Firt Name</th>
                        <th>Last Name</th>';

                while($rep = $note1->fetch()){
                    $dev_list++;
                    $head .='<th>Mark '.($dev_list).'</th>';
                    array_push($student_notes,'NOTE'.$dev_list);

                }
                
                $head .='
                        <th>Participation Mark</th>
                        <th>Administration Mark</th>
                        <th>Sanction</th>
                        <th>Average</th>';

                $temoin = 1;
            }

        }

    }
?>
<script>
var modulee ;
    var modules = "";
    function getModules(id,prof_id) {
      
        $.post(
            '../../../ajax.php',
            {
                getMd: 'modul',
                id_classe: id,
                id_prof:prof_id,
            },
            function (donnees) {
                $('#modulus').html(donnees);
                $('#modulus').removeAttr('disabled');
                console.log(donnees);
                
            });
    }
    function gModStud(modul) {
        $('#average').removeAttr('disabled');
        $('#mod').val(modul);
        modulee = modul;
        $.ajax({
            url: "test.php",
            type: "post",
            //contentType: "application/json",
            dataType: "json",
            data:$('form').serialize(),
            success: function (data) {
                if(data[data.length-1].id_module == 0){
                    $('#average').hide();
                }
                console.log(data);
                var html_data="";
                for (var count = 0; count < data.length-2; count++) {
                    html_data += '<tr> <td>' + data[count].MATRICULE + '</td>';

                    html_data += '<td data-name="PRENOM" class="PRENOM" data-type="text" data-pk="' + data[count].MATRICULE + '">' + data[count].PRENOM + '</td>';

                    html_data += '<td data-name="NOM" class="NOM" data-type="text" data-pk="' + data[count].MATRICULE + '">' + data[count].NOM + '</td>';

                   if(data[count].NOTE1 !="#"){
                       html_data += '<td data-name="NOTE1" class="NOTE1" data-type="number" data-title="Mark 1" data-pk="' + data[count].MATRICULE + '">' + data[count].NOTE1 + '</td>'; 
                    }
                    if(data[count].NOTE2 != "#"){
                           html_data += '<td data-name="NOTE1" class="NOTE1" data-type="number" data-title="Mark 1" data-pk="' + data[count].MATRICULE + '">' + data[count].NOTE2 + '</td>'; 
                        }
                    if(data[count].NOTE3 !="#"){
                           html_data += '<td data-name="NOTE1" class="NOTE1" data-type="number" data-title="Mark 1" data-pk="' + data[count].MATRICULE + '">' + data[count].NOTE3 + '</td>'; 
                        }

                    html_data += '<td data-name="NOTE_PARTICIPATION" class="NOTE_PARTICIPATION" data-type="number" data-pk="' + data[count].MATRICULE + '">' + data[count].NOTE_PARTICIPATION + '</td>';

                    html_data += '<td data-name="NOTE_ADMINISTRATION" class="NOTE_ADMINISTRATION" data-type="number" data-pk="' + data[count].MATRICULE + '">' + data[count].NOTE_ADMINISTRATION + '</td>';

                    html_data += '<td data-name="SANCTION" class="SANCTION" data-type="number" data-pk="' + data[count].MATRICULE + '">' + data[count].SANCTION + '</td>';

                    html_data += '<td data-name="MOYENNE" class="MOYENNE" data-type="number" data-pk="' + data[count].MATRICULE + '">' + data[count].MOYENNE + '</td>';
           
                }
                classe =  data[data.length].classe;
                $("#table tbody").html(html_data);
                
                $("#get_classe").html(classe);
            },
            erro: function (error) {
                console.log("error");
            }
        });
        
        
        $.post(
            '../../../ajax.php',
            {
                get_mod_id: 'modul',
                get_mod_val: modul,
            },
            function (donnees) {

                $("#head").html(donnees+"<th>Administration Mark</th><th>Sanction</th><th>Average</th>");
                $("#foot").html(donnees+"<th>Administration Mark</th><th>Sanction</th><th>Average</th>");
            });
        
    }
    //recuperation des poids des notes
    function weight(){
        $.post(
           '../../../ajax.php', {
               showeight:'ok',
               module:modulee,
           }, function (donnees){
               if(donnees.split('`')[1] ==100){
                $('#contents').html(donnees.split('`')[0]);
                document.getElementById("generate").removeAttribute('disabled');
               }else{
                $('#contents').html(donnees.split('`')[0]);
               } 
               
           });

    } 

// modification des poids des notes

function update_weight(newEight,percent){
    $.post(
           '../../../ajax.php', {
               upWeight:'ok',
               id_devoir:newEight,
               module_val : document.getElementById(percent).value,
               id_module :modulee,
           }, function (donnees){
               if(donnees == 1){
                     toastr.error("The amount Weight should be less or equal to 100 / 100");
                     $('#error').html("The amount Weight should be less or equal to 100 / 100");
                    $("#generate").prop('disabled',true);
                     
               }else{

                    toastr.success("Updated Successfully");
                    $('#contents').html(donnees.split('`')[0]);
                    $('#error').html("");
                    if(donnees.split('`')[1]==100){
                        $("#generate").prop('disabled',false);
                    }
                    else{
                        $("#generate").prop('disabled',true); 
                    }
                    
                // alert(donnees);
               }
            
           });
}

// Student average generation method
function generateMean(id_module){
    $.post(
           '../../../ajax.php', {
               mean:'ok',
               module_id:$("#"+id_module).val(),
           }, function (donnees){
                
                $("#student_information").html(donnees);
                toastr.success("The Averages have succcessfully generated!!"); 
           });
}

</script>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow wow fadeInUp">

                <div class="card-header">
                    <div class="row">
                        <div class="col-sm- col-md-2 mt-3">
                            <button class="btn btn-dark" type="button" name="genreate_average" id="average" data-toggle="modal" data-target="#weighter" onclick="weight()" disabled>Generate Averages</button>
                        </div>
                        <div class="col-sm-12 col-md-4 col-lg-3 mt-3 text-primary h5">Classe name: <span class="text-danger h6" id="get_classe"><?php if(isset($class_name)){echo $class_name;}?></span></div>
                        <div class="col-sm-12 col-md-6 col-lg-7 mt-3">
                            <form action="" method="post">
                            <input type="text" name = "module" id = "mod" hidden>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-input">
                                            <select onchange="getModules(this.value,<?=htmlspecialchars(json_encode($prof['ID_PROFESSEUR']))?>)" name="classe_mark" id="classe_mark" class="form-control">
                                                <option value="" disabled selected>Select Class</option>
                                                <?php  while ($clas=$prof_class->fetch()): ?>
                                                    <option value="<?=$clas['ID_CLASSE']?>"><?=$clas['NOM_CLASSE']?></option>
                                                <?php endwhile;?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-input">
                                            <select onchange="gModStud(this.value)" name="modulus" id="modulus" class="form-control" disabled>
                                                <option value="">Select Modulus</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                   <?php 
                        if($temoin == 1){ ?>
                                
                    <table class="table table-bordered" id="table">
                        <thead id="head">
                           
                            <?php echo $head; ?>
                        </thead>
                  <tbody id="student_information">
                          <?php while($re = $requetes->fetch()){
                                if(empty($re['SANCTION'])){
                                    $sanction = 0;
                                }else{
                                    $sanction = $re['SANCTION'];
                                }
                                ?>
                                    <tr>
                                    <td><?= $re['MATRICULE'];?></td>
                                    <td data-name="PRENOM" class="PRENOM" data-type="text" data-pk="<?= $re['MATRICULE']?>"><?= $re['PRENOM']?></td>
                                    <td data-name="NOM" class="NOM" data-type="text" data-pk="<?= $re['MATRICULE']?>"><?= $re['NOM']?></td>
                                    <?php
                                    // Afficher les notes par lignes
                                    for($note_column=0;$note_column<count($student_notes);$note_column++){?>
                                    <td data-name="NOTE1" class="NOTE1" data-type="number" data-pk="<?= $re['MATRICULE']?>"><?= $re[$student_notes[$note_column]]?></td>
                                   <?php }
                                    ?>
                                    <td data-name="NOTE_PARTICIPATION" class="NOTE_PARTICIPATION" data-type="number" data-pk="<?= $re['MATRICULE']?>"><?= $re['NOTE_PARTICIPATION']?></td>
                                    <td data-name="NOTE_ADMINISTRATION" class="NOTE_ADMINISTRATION" data-type="number" data-pk="' + data[count].MATRICULE + '"><?= $re['NOTE_ADMINISTRATION']?></td>
                                    <td data-name="SANCTION" class="SANCTION" data-type="number" data-pk="<?= $re['MATRICULE']?>"><?= $sanction?></td>
                                    <td data-name="MOYENNE" class="MOYENNE" data-type="number" data-pk="<?= $re['MATRICULE']?>"><?= $re['MOYENNE']?></td>
                                </tr>
                               <?php } ?>

                        </tbody>
                        <tfoot id="foot">
                            <?php echo $head;?>
                        </tfoot>
                    </table>
                    <?php }else{?>
                        <h2 class="text-center">Data no found</h2> 
                    <?php }?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Average weights modal-->
<div class="modal fade" id="weighter" data-backdrop="static" data-keyboard="false" role="dialog"
        tabindex="-1" aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div class="modal-content">

            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white">Liste Test weights</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-white">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="error" class="text-danger my-4 text-center"></div>
                <div class="container-fluid ">
                    <div class="row" id="contents"></div>
                </div>
                <button type="button" class="btn btn-success mt-4 mb-3 col-12 w-75 mx-5" id="generate" onclick="generateMean('module_id')" disabled data-dismiss="modal"> Generate average</button>
            </div>

        </div>
    </div>
</div>

<?php
    include('../footer.php');
?>


