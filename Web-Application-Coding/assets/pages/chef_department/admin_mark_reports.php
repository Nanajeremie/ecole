<?php
    include('../../../utilities/QueryBuilder.php');
    $title = 'Administration Marks Management';
    $breadcrumb = 'Report Marks';

    $obj = new QueryBuilder();
    include('header.php');

    $class=$obj->Select('classe',[],[]); 

   $student_notes = array();

       $module_current_teach=$obj->Requete("SELECT * FROM module m, enseigner e WHERE e.ID_MODULE=m.ID_MODULE AND e.ANNEE = '".getAnnee(0)['ID_ANNEE']."'");
        
       if($back_module =$module_current_teach->fetch()){
           $verify_test = $obj->Requete("SELECT * FROM devoirs WHERE ID_MODULE ='".$back_module['ID_MODULE']."'");

           if($back_test =$verify_test->fetch()){
                $requetes = $obj->Requete("SELECT n.MATRICULE, n.ID_MODULE , e.NOM , e.PRENOM , n.NOTE_ADMINISTRATION, n.SANCTION  FROM notes n , etudiant e WHERE e.MATRICULE = n.MATRICULE AND n.ID_MODULE='".$back_module['ID_MODULE']."' AND n.ANNEE_SCOLAIRE='".getAnnee(0)['ID_ANNEE']."' ORDER BY n.ID_NOTE ASC");

               $note1 = $obj->Requete("SELECT * FROM module m, devoirs d WHERE m.ID_MODULE = d.ID_MODULE AND m.ID_MODULE='".$back_module['ID_MODULE']."'");
               $get_Classe = $obj->Requete("SELECT * FROM module m, devoirs d WHERE m.ID_MODULE = d.ID_MODULE AND m.ID_MODULE='".$back_module['ID_MODULE']."'")->fetch();
                
               $class_name = $obj->Requete("SELECT * FROM classe WHERE ID_CLASSE='".$get_Classe['ID_CLASSE']."'")->fetch()['NOM_CLASSE'];
           }
       }

?>
<script>   

    var modules = "";

    // Ajax pour selectionner les modules de la classe
    function get_adm_Modules(id) {
        console.log(id);
        $.post(
            '../../../ajax.php',
            {
                adm_modul: 'adm_modul',
                id_classe: id,
            },
            function (donnees) {
                $('#modulus').html(donnees);
                $('#modulus').prop('disabled',false);
            });
    }
    
    function gModStud() {
        $.ajax({
            url: "test.php",
            type: "post",
            dataType: "json",
            data:$('form').serialize(),

            success: function (data) {
                var html_data=""
                for (var count = 0; count < data.length-1; count++) 
                {
                    html_data += '<tr> <td>' + data[count].MATRICULE + '</td>';

                    html_data += '<td data-name="PRENOM" class="PRENOM" data-type="text" data-title="First Name" data-pk="' + data[count].MATRICULE + '">' + data[count].PRENOM + '</td>';

                    html_data += '<td data-name="NOM" class="NOM" data-type="text" data-title="Last Name" data-pk="' + data[count].MATRICULE + '">' + data[count].NOM + '</td>';

                    html_data += '<td data-name="NOTE_ADMINISTRATION" class="NOTE_ADMINISTRATION" data-type="number" data-title="Administration Mark" data-pk="' + data[count].MATRICULE + '">' + data[count].NOTE_ADMINISTRATION + '</td>';

                    html_data += '<td data-name="SANCTION" class="SANCTION" data-type="number" data-title="Sanction" data-pk="' + data[count].MATRICULE + '">' + data[count].SANCTION + '</td>';

                    modules = data[count].ID_MODULE;


                }
                $("#table tbody").html(html_data);
                //$("#get_classe").html(classe);
            },

            erro: function (error) {  
                console.log(error);
            }
        });
    }
    
</script>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow wow fadeInUp">

                <div class="card-header">
                    <div class="row">
                        <div class="col-sm-12 col-md-6 col-lg-5 mt-3">
                            <?= $breadcrumb ?>
                        </div>
                        <div class="col-sm-12 col-md-4 col-lg-3 mt-3 text-primary h5">Classe name: <span class="text-danger h6" id="get_classe"><?php if(isset($class_name)){echo $class_name;}?></span>
                        </div>
                        
                        <div class="col-sm-12 col-md-6 col-lg-7 mt-3 text-right">
                            <form  action="" method="post">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-input">
                                            <select onchange="get_adm_Modules(this.value)" name="classe_mark" id="classe_mark" class="form-control">
                                                <option value="" disabled selected>Select Class</option>
                                                <?php  while ($clas=$class->fetch()):?>
                                                    <option value="<?=$clas['ID_CLASSE']?>"><?=$clas['NOM_CLASSE']?></option>
                                                <?php endwhile;?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-input">
                                            <select onchange="gModStud()"  disabled name="modulus" id="modulus" class="form-control" >
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
                    <table class="table table-bordered" id="table">
                        <thead>
                            <th>Matricule</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Administration Mark</th>
                            <th>Sanction</th>
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
                                //    Afficher les notes par lignes
                                   for($note_column=0;$note_column<count($student_notes);$note_column++){?>
                                   <td data-name="NOTE1" class="NOTE1" data-type="number" data-pk="<?= $re['MATRICULE']?>"><?= $re[$student_notes[$note_column]]?></td>
                                 <?php }
                                   ?>
                                   <td data-name="NOTE_ADMINISTRATION" class="NOTE_ADMINISTRATION" data-type="number" data-pk="<?= $re['MATRICULE']?>"><?= $re['NOTE_ADMINISTRATION']?></td>
                                   <td data-name="SANCTION" class="SANCTION" data-type="number" data-pk="<?= $re['MATRICULE']?>"><?= $sanction?></td>
                               </tr>
                             <?php } ?>
                        </tbody>

                        <tfoot>
                            <th>Matricule</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Administration Mark</th>
                            <th>Sanction</th>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
    include('../footer.php');
?>


<script type="text/javascript">
    
$(document).ready(function () {

    $("#student_information").editable({
        container: 'body',
        selector: 'td.NOTE_ADMINISTRATION',
        url: 'update.php',
        title: 'NOTE_ADMINISTRATION',
        params: function (params) {  
            params.module = modules;
            return params;
        },
        type: "POST",
        validate:function (value) {
            if($.trim(value) == '')
            {
                return "This field is required";
            }
            
            if ($.trim(value) < 0 || $.trim(value) > 10) {
                return "The mark should be between 0 and 10";
            }
          },
        success: function(response, newValue) {
            toastr.success("Inserted Successfully");
        },
        error: function (param) {  
            toastr.error("An error occured");
        }
    });

    $("#student_information").editable({
        container: 'body',
        selector: 'td.SANCTION',
        url: 'update.php',
        title: 'SANCTION',
        params: function (params) {  
            params.module = modules;
            return params;
        },
        type: "POST",
        validate:function (value) {
            if($.trim(value) == '')
            {
                return "This field is required";
            }
            if ($.trim(value) < 0 || $.trim(value) > 20) {
                return "The mark should be between 0 and 20";
            }
          },
        success: function(response, newValue) {
            toastr.success("Inserted Successfully");
        },
        error: function (param) {  
            toastr.error("An error occured");
        }
    });

});

</script>