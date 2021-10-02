<?php 
    include('../../../utilities/QueryBuilder.php');
    $title = 'Data Bank Upload file';
    $breadcrumb = 'Student';
    include_once ('header.php');

    $obj = new QueryBuilder();

    $academic_years_exist = $obj->Select('annee_scolaire', array(), array());
    //if any line of academic year exists in the database
    if (is_object($academic_years_exist))
    {
        $academic_years_exist = $academic_years_exist->rowCount();
    }
    //if there is no records in the database
    else
    {
        $academic_years_exist = 0;
    }

    if(array_key_exists('confirm_new_year_creation' , $_POST))
    {
        echo "<script>window.open('new_year.php' , '_self') </script>";
    }

    if(array_key_exists('confirm_end_year_creation' , $_POST))
    {
        echo "<script>window.open('end_year.php' , '_self') </script>";
    }

    // Requetes pour afficher les fichiers uploader (on recupere la liste des modulse existante )
    $select_module = $obj->Requete("SELECT * FROM module");

    // ici on affiche la liste des fichiers upload par l'utilisateur courant
    $find_file_liste = $obj->Requete("SELECT * FROM data_bank WHERE Matricule='".$_SESSION['USERNAME']."'");
    

?>
<div class="container-fluid">
 <div class="row">
  <div class="col-lg-12">
  <!--  Titre de la liste des fichiers deja uploader  -->
    <div class="card">
        <div class="card-header">
            <div class="row pd-3">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <h4 class="text-bluesky">List of documents</h4>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 text-right">
                    <button class="btn btn-dark my-3" name="data_bank_upload_btn" id="data_bank_upload_btn" data-toggle="modal" data-target="#data_bank_upload_modal" onclick="initilize()">
                        Upload file 
                    </button>
                </div>
            </div>
        </div>
    
        <div class="card-body py-2">
            <table class="table table-stripped table-bordered" id="table">
            <!-- En tete de la table -->
                <thead>
                    <tr>
                        <th data-file='action'>Action</th>
                        <th data-field="module">Titre</th>
                        <th data-field="module">Module</th>
                        <th data-field="Information">File</th>
                        <th data-field="Description">Description</th>
                        <th data-field="Action">Date</th>
                    </tr>
                </thead>
            <!-- Contenue de la table -->
                <tbody id="data_bank_content">
                        <?php

                        while($show_file = $find_file_liste->fetch()){?>

                        <tr>
                        <td>
                           <div class="btn-group d-flex justify-content-between col-12">
                                <button class="btn btn-warning col-5" name="edit_data_bank_btn"  type="button" role="button" data-toggle="modal" data-target="#edit_data_bank_modal" onclick="change(<?=htmlspecialchars(json_encode($show_file['ID_DATA_BANK']))?>)">
                                    <i class="fas fa-pen text-white"></i>
                                </button>
                                <button class="btn btn-danger col-5" name="delete_data_bank_btn" role="button" data-toggle="modal" data-target="#delete_data_bank_modal" onclick="deletes(<?=htmlspecialchars(json_encode($show_file['ID_DATA_BANK']))?>)">
                                    <i class="fas fa-trash text-white"></i>
                                </button>
                            </div>
                        </td>
                        <td class="text" data-name="titre" data-type="text">
                            <p><?= $show_file['TITRE'];?></p>
                        </td>
                        <td class="text" data-name="module" data-type="text">
                            <p><?= $show_file['MODULE'];?></p>
                        </td>
                        <td class="text" data-name="file-info" data-type="text">
                         <p><?= $show_file['FILE_NAME'];?></p>
                        </td>
                        <td class="text" data-name="file-descript" data-type="text">
                            <p>
                                <?= $show_file['DESCRIPTION'] ?>
                            </p>
                        </td>
                        <td class="text" data-name="date" data-type="text">
                            <p><?= $show_file['CREATE_DATE'];?></p>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            <!-- Pied de la table -->
                <tfoot>
                    <tr>
                    <th data-file='action'>Action</th>
                        <th data-field="titre">Titre</th>
                        <th data-field="module">Module</th>
                        <th data-field="Information">File</th>
                        <th data-field="Description">Description</th>
                        <th data-field="Action">Date</th>
                    </tr>
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
<script>

    function uploadData(matri){
    // alert(module);

    var matricule = matri;
    var descrip = $("#desc_data_bank").val();
    var titre = $("#titre_data_bank").val();
    var nom_module = $("#module_data_bank").val();

    if(descrip =="" || titre =="" || nom_module =="" || $('#upload_data_bank')[0].files.length === 0){
        $("#flag").html("All the fields containing asterisk (*) should be fill");
        $("#flag").css("color",'red');
    }else{
            // descrip:descrip,'nom_module':nom_module,'matricule':matricule
        var fd = new FormData();
        var files = $("#upload_data_bank")[0].files[0];
        fd.append('file',files);
        $.ajax({
            url: 'modal_img.php',
            type:'post',
            data:fd,
            contentType:false,
            processData:false,
            dataType:'json',
            success:function(response){
                if(response.status!=-1){
                    file_detail("Insert",response.message,titre,nom_module,descrip,matricule,0);
                }
                else{
                    $("#flag").html(response.message);
                    $("#flag").css("color",'red');
                }
            }
        });
    }
}

// cette fonction permet de initialiser le modal lorsque l'on click sur upload file
function initilize(){
    $("#flag").html("");
    $("#flag").css("color",'black');
}
// @Jeremie=> cette fonction permet de delete un fichier deja upload
function deletes(id){
    $("#hidden_id").val(id);
}
function remove(){
    $.post(
           '../../../ajax.php', {
               file_rem:"remove",
               file_id :$("#hidden_id").val(),
           }, function (donnees){
                toastr.success("The file is successfully Deleted");
                loads("student_uploaded_file_management.php?page=student_uploaded_file_management",1000);   
           });
}

// @Jeremie=> Cette fonction permet de modifier les infos d'un fichier deja upload
function change(id){
    $.post(
           '../../../ajax.php', {
               file_change:"change",
               file_id:id,
           }, function (donnees){
               $("#check").html(donnees);
           });
}

function loads(page_name,time){
    setTimeout(() => {
        window.location.replace(page_name);
    }, time);
	
}

// @Jeremie => cette fonction permet de faire le update final du fichier

function modify_file(file_id){  
    var descrip = $("#desc_update_bank").val();
    var titre = $("#titre_update_bank").val();
    var nom_module = $("#module_update_bank").val();

    if(descrip =="" || titre =="" || nom_module ==""){
        $("#flag_update").html("All the fields containing asterisk (*) should be fill");
        $("#flag_update").css("color",'red');
    }else{

            if ($('#file_update_bank')[0].files.length === 0) {
                file_detail("Update","",titre,nom_module,descrip,"no",file_id);
            } else {
                   // descrip:descrip,'nom_module':nom_module,'matricule':matricule
                var fd = new FormData();
                var files = $("#file_update_bank")[0].files[0];
                fd.append('file',files);
                $.ajax({
                    url: 'modal_img.php',
                    type:'post',
                    data:fd,
                    contentType:false,
                    processData:false,
                    dataType:'json',
                    success:function(response){
                        if(response.status!=-1){
                            file_detail("Update",response.message,titre,nom_module,descrip,"no",file_id);
                        }
                        else{
                            $("#flag_update").html(response.message);
                            $("#flag_update").css("color",'red');
                        }
                    }
                });
            }

    }
}
// @Jeremie => la fonction charger de deverser les donnees dans la base de donnees
// Elle marche avec tous Upload comme Update
function file_detail(type,file_name,titre,nom_module,descrip,matricule,id){
        $.post(
           'modal_img.php', {
               id : id,
               type : type,
               file_name:file_name,
               title:titre,
               name_module:nom_module,
               describ:descrip,
               matricule : matricule
           }, function (donnees){
                $("#flag_update").html("The file is successfully Updated");
                $("#flag_update").css("color",'green');
                toastr.success("The file is successfully Updated");
                document.getElementById("send_file").reset();
                $(".custom-file-label").html("");
           });
    } 
</script>

<!-- Modal pour upload les fichiers -->
<div class="modal fade" wow fadeUp id="data_bank_upload_modal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title text-primary" id="my-modal-title"><span class="fas fa-database text-primary"></span> Data bank center</h5>
                <button class="close" data-dismiss="modal" aria-label="Close" onclick="loads('student_uploaded_file_management.php?page=student_uploaded_file_management',200)">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <div id="flag" class="text-center my-2"></div>
            <form action="" method="post" enctype="multipart/form-data" id="send_file">
                    <div class="row">
                        <!-- Select des modules du uploadeur du fichier a uploader -->
                        <div class="form-group col-lg-12">
                            <label for="module_data_bank">Modules<span class="text-danger"> * </span></label>
                            <div class="input-group">
                                <select class="form-control" name="module_data_bank" id="module_data_bank">
                                    <option value=""> Selectionner un module</option>
                                    <?php
                                        while($get_module=$select_module->fetch()){?>
                                            <option value="<?=$get_module['NOM_MODULE']?>"><?=$get_module['NOM_MODULE']?></option>
                                    <?php } ?>
                                    
                                </select>
                                <div class="input-group-append">
                                <span class="input-group-text fas fa-school"></span>
                            </div>
                            </div>
                        </div>

                        <!-- Input du titre du fichier a uploader -->
                        <div class="form-group col-lg-12">
                            <label for="titre_data_bank">Title<span class="text-danger"> * </span></label>
                                <div class="input-group">
                                <input type="text" class="form-control" name="titre_data_bank" id="titre_data_bank">
                                <div class="input-group-append">
                                        <span class="input-group-text fas fa-book-reader"></span>
                                </div>
                            </div>  
                        </div>
                        <!-- Espace pour upload les fichiers  -->
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="upload_data_bank">Cover Picture <span class="text-danger"> * </span></label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="upload_data_bank" name="upload_data_bank">
                                    <label class="custom-file-label" for="upload_data_bank">Choose file</label>
                                </div>
                            </div>
                       </div>
                        <!-- Description du fichier a uploader -->
                        <div class="form-group col-lg-12">
                            <label for="desc_data_bank">Description<span class="text-danger"></span></label>
                            <div class="input-group">
                                <textarea name="desc_data_bank" id="desc_data_bank" class="form-control">
                                </textarea>
                                    <div class="input-group-append">
                                        <span class="input-group-text fas fa-book-reader"></span>
                                    </div>
                            </div>  
                        </div>
                        <!-- Espace pour sortir/ submit le formulaire -->
                        <div class="col text-right">
                            <button class="btn btn-danger" data-dismiss="modal" aria-label="Close">Cancel</button>
                            <button class="btn btn-success" type="button" name="confirm_data_bank" id="confirm_data_bank" onclick="uploadData(<?=htmlspecialchars(json_encode($_SESSION['USERNAME']))?>)">Confirm</button>
                        </div>
                    </div> 
                </form>
            </div>
        </div>
    </div>
</div>

<!-- ---------------------------Modal pour edit les fichiers ------------------->
<div class="modal fade" id="edit_data_bank_modal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title text-primary" id="my-modal-title"><span class="fas fa-pen text-primary"></span> Edit Data bank center</h5>
                <button class="close" data-dismiss="modal" aria-label="Close" onclick="loads('student_uploaded_file_management.php?page=student_uploaded_file_management',10)">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <div id="flag_update" class="text-center my-2"></div>
            <div id="check"></div>
            </div>
        </div>
    </div>
</div>

<!-------------------- ------------------------------------Modal pour supprimer les fichiers ------------------------------------------------->

<div class="modal fade" id="delete_data_bank_modal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title text-primary" id="my-modal-title"><span class="fas fa-trash text-primary"></span>Delete Data bank center</h5>
                <form action="">
                    <input type="text" id="hidden_id" hidden>
                    <button class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </form>
            </div>
            <div class="modal-body">
                <form action="" method="post">
                    <div class="row">
                        <div class="col-12">
                            <p class="delete_bank_data">Do you really want to delete this file</p>
                        </div>
                    <!-- Espace pour sortir/ submit le formulaire -->
                        <div class="col text-right">
                            <button class="btn btn-danger" data-dismiss="modal" aria-label="Close">Cancel</button>
                            <button class="btn btn-success" type="button" name="confirm_data_bank_delete" id="confirm_data_bank_delete" onclick="remove()">Delete</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    // Script pour remplacer l'affichage standar de type="file" 
    $(".custom-file-input").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });
</script>