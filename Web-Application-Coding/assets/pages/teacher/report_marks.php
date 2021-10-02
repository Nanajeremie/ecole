<?php
    include('../../../utilities/QueryBuilder.php');
    $title = 'Marks';
    $breadcrumb = 'Report Marks';
    
    include('header.php');

    $prof=$obj->Requete("SELECT * FROM professeur p, enseigner e WHERE p.ID_USER='".$_SESSION["ID_USER"]."'")->fetch();
    
    $prof_class=$obj->Requete("SELECT * FROM classe c WHERE c.ID_CLASSE IN (SELECT ID_CLASSE FROM enseigner e, module m WHERE e.ID_MODULE=m.ID_MODULE AND e.ID_PROFESSEUR='".$prof["ID_PROFESSEUR"]."')");

    $prof_class2=$obj->Requete("SELECT * FROM classe c WHERE c.ID_CLASSE IN (SELECT ID_CLASSE FROM enseigner e, module m WHERE e.ID_MODULE=m.ID_MODULE AND e.ID_PROFESSEUR='".$prof["ID_PROFESSEUR"]."')");

    $student_notes = array();

    $temoin = 0;

    if($get_prof_resul = $prof_class2->fetch())
    {
        
        $module_prof=$obj->Requete("SELECT * FROM module m, enseigner e WHERE m.ID_CLASSE='".$get_prof_resul['ID_CLASSE']."' AND e.ID_MODULE=m.ID_MODULE ");
        
        if($back_module =$module_prof->fetch())
        {
            $verify_test = $obj->Requete("SELECT * FROM devoirs WHERE ID_MODULE ='".$back_module['ID_MODULE']."'");

            if($back_test =$verify_test->fetch())
            {
                $requetes = $obj->Requete("SELECT n.MATRICULE, n.ID_MODULE , e.NOM , e.PRENOM , n.NOTE1 , n.NOTE2 , n.NOTE3 , n.NOTE_PARTICIPATION, n.NOTE_ADMINISTRATION, n.SANCTION, n.MOYENNE FROM notes n , etudiant e WHERE e.MATRICULE = n.MATRICULE AND n.ID_MODULE='".$back_module['ID_MODULE']."' AND n.ANNEE_SCOLAIRE='".getAnnee(0)['ID_ANNEE']."' ORDER BY n.MATRICULE ASC");
                
                $note1 = $obj->Requete("SELECT * FROM module m, devoirs d WHERE m.ID_MODULE = d.ID_MODULE AND m.ID_MODULE='".$back_module['ID_MODULE']."'");
                
                $get_Classe = $obj->Requete("SELECT * FROM module m, devoirs d WHERE m.ID_MODULE = d.ID_MODULE AND m.ID_MODULE='".$back_module['ID_MODULE']."'")->fetch();
                
                $class_name = $obj->Requete("SELECT * FROM classe WHERE ID_CLASSE='".$get_Classe['ID_CLASSE']."'")->fetch()['NOM_CLASSE'];
                
                $dev_list = 0;

                $head = '<tr><th class="text-truncate">Matricule</th>
                            <th class="text-truncate">Firt Name</th>
                            <th class="text-truncate">Last Name</th>';
                
                while($rep = $note1->fetch())
                {
                    $dev_list++;
                    $head .='<th class="text-truncate">Mark '.($dev_list).'</th>';
                    array_push($student_notes,'NOTE'.$dev_list);
                }
                
                $head .='<th class="text-truncate">Participation Mark</th></tr>';

                $temoin = 1;
            }
            
        }
    }

?>

<script>

    var modulee ;
    var modules = "";
    var le_module;
     // Ajax pour selectionner les modules de la classe
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
            });
    }
    
    function gModStudReport(modules) {
        modulee = modules;
        // var classe= "";
        $.ajax({
            url: "test.php",
            type: "post",
            dataType: "json",
            data:$('form').serialize(),
            success: function (data) {
                var html_data="";
                console.log(data)
                for ( count = 0; count < data.length-1; count++) 
                {
                    html_data += '<tr> <td  class="text-truncate">' + data[count].MATRICULE + '</td>';
                    
                    html_data += '<td class="text-truncate PRENOM" data-name="PRENOM" data-type="text" data-title = "First Name" data-pk="' + data[count].MATRICULE + '">' + data[count].PRENOM + '</td>';

                    html_data += '<td class="text-truncate NOM" data-name="NOM" data-type="text" data-title="Last Name" data-pk="' + data[count].MATRICULE + '">' + data[count].NOM + '</td>';
                    
                    if(data[count].NOTE1 !="#")
                    {
                       html_data += '<td class="text-truncate NOTE1" data-name="NOTE1" data-type="number" data-title="Mark 1" data-pk="' + data[count].MATRICULE + '">' + data[count].NOTE1 + '</td>'; 
                    }

                    if(data[count].NOTE2 != "#")
                    {
                        html_data += '<td class="text-truncate NOTE2" data-name="NOTE2" data-type="number" data-title="Mark 1" data-pk="' + data[count].MATRICULE + '">' + data[count].NOTE2 + '</td>'; 
                    }

                    if(data[count].NOTE3 !="#")
                    {
                        html_data += '<td class="text-truncate NOTE3" data-name="NOTE3" data-type="number" data-title="Mark 1" data-pk="' + data[count].MATRICULE + '">' + data[count].NOTE3 + '</td>'; 
                    }

                    html_data += '<td class="text-truncate NOTE_PARTICIPATION" data-name="NOTE_PARTICIPATION" data-type="number" data-title="Participation Mark" data-pk="' + data[count].MATRICULE + '">' + data[count].NOTE_PARTICIPATION + '</td><tr>';

                    modules = data[count].ID_MODULE;
                    le_module = data[count].ID_MODULE;
                }
                // classe =  data[data.length].classe;
                var table = '<table  id="table" class="table table-bordered table-hover table-responsive-lg table-responsive-md table-responsive-sm"> <thead>' + <?= json_encode($head) ?> +' </thead><tbody>'+ html_data +'</tbody><tfoot>'+ <?= json_encode($head) ?> +'</tfoot></table>';

                $("#student_information").html(table);
                // $("#get_classe").html(classe);
            },
            erro: function (error) {
                console.log(error);
            }
        });
        
        
        // $.post(
        //     '../../../ajax.php', 
        //     {
        //         get_mod_id: 'modul',
        //         get_mod_val: modules,
        //     }, 
        //     function (donnees) {
        //         $("#head").html(donnees);
        //         $("#foot").html(donnees);
        //     });
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

                        <!-- <div class="col-sm-12 col-md-4 col-lg-3 mt-3 text-primary h5">Classe name: <span class="text-danger h6" id="get_classe"><?php if(isset($class_name)){echo $class_name;}?></span></div> -->

                        <div class="col-sm-12 col-md-6 col-lg-7 mt-3 text-right">
                            <form action="" method="post">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-input">
                                            <select onchange="getModules(this.value , <?= htmlspecialchars(json_encode($prof["ID_PROFESSEUR"])) ?>)" name="classe_mark" id="classe_mark" class="form-control">
                                                <option value="" disabled selected>Select Class</option>
                                                <?php  while ($clas=$prof_class->fetch()):?>
                                                    <option value="<?=$clas['ID_CLASSE']?>"><?=$clas['NOM_CLASSE']?></option>
                                                <?php endwhile;?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-input">
                                            <select onchange="gModStudReport(this.value)"  disabled name="modulus" id="modulus" class="form-control" >
                                                <option value="">Select Modulus</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
 
                <div class="card-body" id="student_information">
                   <?php 
                        if($temoin == 1)
                        {
                    ?>
                            <div class=" card card-body">
                                <p class="text-center">You need at least to choose the classroom and the module!!!</p> 
                            </div>
                                    <?php 
                                        // while($re = $requetes->fetch())
                                        // {
                                        //     if(empty($re['SANCTION']))
                                        //     {
                                        //         $sanction = 0;
                                        //     }
                                        //     else
                                        //     {
                                        //         $sanction = $re['SANCTION'];
                                        //     }
                                    ?>
                                        <!-- <tr> 
                                        <td><?= $re['MATRICULE'];?></td>
                                        <td data-name="PRENOM" class="PRENOM" data-type="text" data-pk="<?= $re['MATRICULE']?>"><?= $re['PRENOM']?></td>
                                        <td data-name="NOM" class="NOM" data-type="text" data-pk="<?= $re['MATRICULE']?>"><?= $re['NOM']?></td> -->
                                    <?php 
                                        // Afficher les notes par lignes
                                        // for($note_column=0 ; $note_column<count($student_notes) ; $note_column++)
                                        // {
                                    ?>
                                        <!-- <td data-name="NOTE1" class="NOTE1" data-type="number" data-pk="<?= $re['MATRICULE']?>"><?= $re[$student_notes[$note_column]]?></td>  -->
                                    <?php 
                                        //}
                                    ?>
                                            <!-- <td data-name="NOTE_PARTICIPATION" class="NOTE_PARTICIPATION" data-type="number" data-pk="<?= $re['MATRICULE']?>"><?= $re['NOTE_PARTICIPATION']?></td>
                                        </tr> -->
                                    <?php 
                                        //} 
                                    ?>
                                
                    <?php 
                        }
                        else
                        {
                    ?>
                        <p class="text-center">You need at least to choose the classroom and the module!!!</p> 
                    <?php 
                        }
                    ?>
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
            selector: 'td.NOTE1',
            url: 'update.php',
            title: 'Mark 1',
            params: function (params) {  
                params.module = le_module;
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
    
        $("#student_information").editable({
            container: 'body',
            selector: 'td.NOTE2',
            url: 'update.php',
            title: 'Mark 2',
            params: function (params) {  
                params.module = le_module;
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

        $("#student_information").editable({
            container: 'body',
            selector: 'td.NOTE3',
            url: 'update.php',
            title: 'Mark 3',
            params: function (params) {  
                params.module = le_module;
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

        $("#student_information").editable({
            container: 'body',
            selector: 'td.NOTE_PARTICIPATION',
            url: 'update.php',
            title: 'Participation Mark',
            params: function (params) {  
                params.module = le_module;
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