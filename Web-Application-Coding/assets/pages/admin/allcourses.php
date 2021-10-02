<?php

    include '../../../utilities/QueryBuilder.php';
    $title = 'Courses list';
    $breadcrumb = 'All courses';
    $obj = new QueryBuilder();
    include('header.php');
    //@Jeremie=> Recuperation de la des modules
    $recu_module = $obj->Requete("SELECT * FROM module m, classe c, filieres f WHERE m.ID_CLASSE = c.ID_CLASSE AND c.ID_FILIERE = f.ID_FILIERE ORDER BY m.NOM_MODULE");

?>
<script>
// @jeremie => cette fonction permer de'actualiser le contenu de semestre dans le modal
function say(){
    $.post(
        '../../../ajax.php', {
            modal_cont:'ok',
        }, function (donnees){
            $('#semest_content').html(donnees);
        });
}

</script>
<div class="container-fluid" id="fenetre">
    <div class="row"> 
        <div class="col-12">
            <div class="card">

                <div class="card-header col-12">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-6 ">
                            <h4 class="text-bluesky" >All courses</h4>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-6">
                            <div class="row justify-content-lg-end">
                                <div class="col-sm-12 col-lg-6">
                                    <button class="btn btn-secondary  mr-3 " data-toggle="modal" data-target="#semester"  onclick="say()">Add Semester</button>
                                </div>
                                <div class="col-sm-12 col-lg-6">
                                    <a class=" btn btn-dark"  href="addcourses.php">Add Module</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body py-5">   
                    <table id="table" class="table table-bordered table-hover table-responsive-lg table-responsive-md table-responsive-sm">
                        <thead>
                            <tr>
                                <th class="text-truncate">Action</th>
                                <th class="text-truncate">Module</th>
                                <th class="text-truncate">Class</th>
                                <th class="text-truncate">Field</th>
                                <th class="text-truncate">Credits</th>
                            </tr>
                        </thead>

                        <tbody>
                        <?php 
                        while($get_module = $recu_module->fetch()){?>
                            <tr>
                                <td class="text-truncate">
                                    <div class="btn-group" role="group" aria-label="Button group">
                                        <button class="btn text-truncate" data-toggle="modal" data-target="#mod_upd" onclick="update_modal(<?=$get_module['ID_MODULE']?>)"><i class="fas fa-edit text-warning"></i></button>
                                        <button class="btn text-truncate" data-toggle="modal" data-target="#mod_del"><i class="fas fa-trash text-danger" onclick="delet_modal(<?=$get_module['ID_MODULE']?>)"></i></button>
                                    </div>
                                </td>
                                <td class="text-truncate"><?=$get_module['NOM_MODULE']?></td>   
                                <td class="text-truncate"><?=$get_module['NOM_CLASSE']?></td>   
                                <td class="text-truncate"><?=$get_module['NOM_FILIERE']?></td>   
                                <td class="text-truncate"><?=$get_module['VOLUME_HORAIRE']?></td> 
                            </tr>
                        <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th class="text-truncate">Action</th>
                                <th class="text-truncate">Module</th>
                                <th class="text-truncate">Field</th>
                                <th class="text-truncate">Credits</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <!-- Semester Modal -->  
        <div class="modal fade" id="semester" data-backdrop="static" data-keyboard="false" role="dialog" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title text-white">Add semester</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="loads('allcourses.php',0)>
                            <span aria-hidden="true" class="text-white">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" >
                        <h5 class="text-center mt-2 mb-3"></h5>
                        <div id="accordion">
                            <div class="card">
                                <div class="card-header">
                                    <a class="collapsed card-link text-center col-12" data-toggle="collapse" data-parent="#accordion" href="#liste_sem">
                                        Click to show the available semesters <i class="fas fa-arrow-down"></i>
                                    </a>
                                </div>
                                <div id="liste_sem" class="collapse">
                                    <div class="card-body" id="semest_content"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                <h5 class="col-12 mb-2 text-center text-dark mt-4"> Add new Semester</h5>
                <div id="txt" class="text-center"></div>
                <div class="container-fluid ">
                <form action="" method="post" class="" id="semester">
                    <div class="row">
                        <div class="col-12">
                            <div class="input-group mx-2">
                                <input type="text" class="form-control" name="semestre_nom"
                                        id="semestre_nom" placeholder="Enter Semester Name">
                                <div class="input-group-append">
                                    <span class="input-group-text fas fa-school"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 text-center my-2">
                            <input class="btn btn-primary w-75" type="button"
                                name="submit_semestre" id="create" value="Create Semester"
                                onclick="add_module()">
                        </div>
                    </div>
                </form>
                </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal madule update -->
        <div class="modal fade" id="mod_upd" data-backdrop="static" data-keyboard="false" role="dialog" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title text-white">Update module</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="loads('allcourses.php',0)">
                            <span aria-hidden="true" class="text-white">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" >
                        <h5 class="text-center mt-2 mb-3"></h5>
                        <div class="container-fluid " id="modal_content">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal delete module -->
        <div class="modal fade" id="mod_del" data-backdrop="static" data-keyboard="false" role="dialog" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-danger">
                        <h5 class="modal-title text-white">Delete</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="loads('allcourses.php',0)">
                            <span aria-hidden="true" class="text-white">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" >
                        <h5 class="text-center mt-2 mb-3 text-danger">Do you really want to delete this module ?</h5>
                        <div class="container-fluid ">
                            <form action="" method="post" class="" id="semester">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="input-group mx-2">
                                            <input type="text" class="form-control" name="module_id"
                                                id="module_id" hidden>
                                        </div>
                                    </div>
                                    <div class="col-6 text-center my-2">
                                        <input class="btn btn-warning w-75 text-white" type="button"
                                            name="submit_semestre" id="" value="Cancel" data-dismiss="modal" aria-label="Close" >
                                    </div>
                                    <div class="col-6 text-center my-2">
                                        <input class="btn btn-danger w-75" type="button"
                                            name="submit_semestre" id="" value="Delete"
                                            onclick="remove_module()">
                                    </div>
                                    
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
// @Jeremie => cette fonction permer de mettre a jour un module
function update_modal(mod_id) {
    $.post(
        '../../../ajax.php',
        {
            upd_module:'ok',
            module_id : mod_id,
        },
        function (donnees) {
            console.log(donnees);
            $('#modal_content').html(donnees);
        }
    );
}
// @Jeremie => cette fonction permet de confirmer la mise a jour du module
function updat_module() {
    var nana = forma_input(['upd_mod_name','upd_mod_sem','upd_mod_class','upd_mod_cred','upd_description']);
    if(nana==0){
    $.post(
            '../../../ajax.php',
            {
                upd_mod:'ok',
                mod_val:$("#mod_val").val(),
                upd_mod_name:$("#upd_mod_name").val() ,
                upd_mod_sem:$("#upd_mod_sem").val() ,
                upd_mod_class:$("#upd_mod_class").val() ,
                upd_mod_credi:$("#upd_mod_cred").val(),
                upd_description:$("#upd_description").val(),
            },
            function(donnees){
                if(donnees==1){toastr.success("The module is successfully Added");loads('allcourses.php',1000);}
                else if(donnees==0){toastr.error("This module already existe");}
            }
        );
    }else{
    toastr.error("Some fields are empty");
    }
}
// @Jeremie => cette fonction permet de lancer le modal delete

function delet_modal(mod_id) {
    $("#module_id").val(mod_id);
}

// @Jeremie =>Cette fonction permet de confirmer la suppression du module
function remove_module() {
    $.post(
        '../../../ajax.php',
        {
            remove_act:'ok',
            mod_id:$("#module_id").val(),
        },
        function (donnees) {
            if(donnees==1){
                toastr.success("The module has been deleted successfully");
                loads('allcourses.php',1000);
            }
        }
    );
}

// @Jeremie => Cette fonction permet d'ajouter une semestre
function add_module(){
    $.post(
        '../../../ajax.php', {
            action:'ok',
            sem_names:$('#semestre_nom').val(),
        }, function (donnees){
            if(donnees==1){
                $('#txt').html("The semester has been added successfully");
                $('#txt').css('color','green');
                say();
                toastr.success("The semester has been added successfully");
                document.getElementById("semester").reset();
            }else if(donnees==0){
                $('#txt').html("This semester is already added ");
                $('#txt').css('color','red');
            }else if(donnees==-1){
                $('#txt').html("The name of the semester should not be empty");
                $('#txt').css('color','red');
            }
        });
   }

// @Jeremie=> cette fonction permet d'editer les noms de semestre
function edit(id_sem){
    if($("#chk"+id_sem).is(":checked")){
        $('#'+id_sem).prop('readonly',false);
        $("#upd"+id_sem).prop('disabled',false);
        $("#del"+id_sem).prop('disabled',false);
    }
    else{
        $('#'+id_sem).prop('readonly',true);
        $("#upd"+id_sem).prop('disabled',true);
        $("#del"+id_sem).prop('disabled',true);
    }
    
}

// @Jeremie Cette fonction permet de suprimer une semestre
function semest_del(sem_id){
    $.post(
        '../../../ajax.php', {
            sem_del:'ok',
            sem_id:sem_id,
        }, function (donnees){
            if(donnees==1){
                say();
                toastr.success("The Semester is successfully Deleted");
            }
        });
}

// @Jeremie=> cette fonction permet de mettre a jour une semestre
function semest_up(sem_id){
    $.post(
        '../../../ajax.php', {
            sem_upd:'ok',
            sem_id_upd:sem_id,
            sem_name_upd:$("#"+sem_id).val(),
        }, function (donnees){
            if(donnees==1){
                say();
                toastr.success("The Semester is successfully Updated");
            }else if(donnees==-1){
                toastr.error("The name of the semester should not be empty");
                $("#"+sem_id).css('border','1px solid red')
            }else if(donnees==0){
                toastr.error("This semester already existe!!");
                $("#"+sem_id).css('border','1px solid red')
            }
        });
}
   
function loads(page_name,time){
    setTimeout(() => {
        window.location.replace(page_name);
    }, time);
	
}

// @Jeremie => cette fonction est liee a la fonction add_modul just au dessus et permet de formater les input vides
function forma_input(inputs_listes) {
    var check_input = 0;
    for(var i = 0; i<inputs_listes.length;i++){
        if($("#"+inputs_listes[i]).val()==""){
            $("#"+inputs_listes[i]).css('border','1px solid red');
            check_input = 1;
        }else{
            $("#"+inputs_listes[i]).css('border','1px solid black');
        }
    }
    return check_input;
}

</script>
<?php
    include('../footer.php');
?>