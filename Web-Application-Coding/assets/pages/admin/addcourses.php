<?php
include_once '../../../utilities/QueryBuilder.php';
$title = 'Course formating';
$breadcrumb = 'All courses';
$obj = new QueryBuilder();
include('header.php');
// @Jeremie => Recuperation de la liste des semestres
$recu_sem = $obj->Requete("SELECT * FROM semestre");

// @Jeremie => Recuperation de la liste des classes
$recu_classe = $obj->Requete("SELECT * FROM classe");

?>
<div class="container-fluid" id="fenetre">
    <div class="row"> 
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6">
                            <h4 class="text-bluesky">Add Module</h4>
                        </div>
                        <div class="col-6 text-right">
                            <a class="btn btn-dark" role="button" href="allcourses.php">List modules</a>
                        </div>
                    </div>
                </div>
                <div class="card-body py-5">
                <div id="txt"></div>
                    <form action="#" class="row" id="mod_form">
                         <div class="form-group col-lg-3">
                           <label for="mod_name">Module<span class="text-danger"> * </span></label>
                            <div class="input-group">
                             <input type="text" class="form-control" placeholder="Module name" id="mod_name" oninput="on_input('mod_name')">
                             <div class="input-group-append">
                                    <span class="input-group-text fas fa-book-reader"></span>
                            </div>
                           </div>
                        </div>
                        <div class="form-group col-lg-3">
                             <label for="mod_sem">Semester<span class="text-danger"> * </span></label>
                             <div class="input-group">
                             <select class="form-control" name="mod_sem" id="mod_sem" onchange="on_input('mod_sem')">
                             <option value="">Choose a Semester</option>
                                <?php
                                    while($get_sem =$recu_sem->fetch()){?>
                                 <option value="<?= $get_sem['ID_SEMESTRE']?>"><?= $get_sem['NOM_SEMESTRE']?></option>
                                 <?php } ?>
                             </select>
                             
                             <div class="input-group-append">
                                <span class="input-group-text fas fa-school"></span>
                            </div>
                            </div>
                         </div>
                         
                         <div class="form-group col-lg-3 ">
                           <label for="mod_class">Classe<span class="text-danger"> * </span></label>
                            <div class="input-group">
                             <select class="form-control" name="mod_class" id="mod_class" onchange="on_input('mod_class')">
                                 <option value="">Choose a class</option>
                                 <?php
                                    while($get_class =$recu_classe->fetch()){?>
                                 <option value="<?= $get_class['ID_CLASSE']?>"><?= $get_class['NOM_CLASSE']?></option>
                                 <?php } ?>
                             </select>
                             <div class="input-group-append">
                                <span class="input-group-text fas fa-school"></span>
                            </div>
                            </div>
                         </div>
                         
                         <div class="form-group col-lg-3">
                             <label for="mod_cred">Credits<span class="text-danger"> * </span></label>
                             <div class="input-group">
                                 <input type="number" class="form-control" placeholder="Ex : 6" name="mod_cred" id="mod_cred" oninput="on_input('mod_cred')">
                                 
                                 <div class="input-group-append">
                                    <span class="input-group-text fas fa-school"></span>
                                </div>
                             </div>
                        </div>
                         
                         <div class="form-group col-lg-12">
                             <label class="text-center" for="description">Description<span class="text-danger"> * </span></label>
                             <textarea rows="5" class="form-control" name="description" id="description" oninput="on_input('description')"></textarea>
                         </div>
                         
                         <!-- SUBMIT BUTTON -->
                         <div class="col-lg-12 form-group text-center my-4">
                             <input class="btn btn-primary col-lg-4" type="button" value="Add" onclick="add_modul()">
                        </div>
                        <!-- SUBMIT BUTTON -->
                         
                    </form>    
                 </div>
            </div>
        </div>

    </div>
</div>
<script>
// @Jeremie=> cette fonction permet d'ajouter une module
function add_modul(){
    var nana = forma_input(['mod_name','mod_sem','mod_class','mod_cred','description']);
    if(nana==0){
        $.post(
            '../../../ajax.php',
            {
                add_mod:'ok',
                mod_name:$("#mod_name").val() ,
                mod_sem:$("#mod_sem").val() ,
                mod_class:$("#mod_class").val() ,
                mod_credi:$("#mod_cred").val(),
                description:$("#description").val(),
            },
            function(donnees){
                if(donnees==1){toastr.success("The module is successfully Added");document.getElementById('mod_form').reset();}
                else if(donnees==0){toastr.error("This module already existe");}
            }
        );
    }else{
    toastr.error("Some fields are empty");
    }
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

// @Jeremie => Cette fonction permet de supprimer le formatage du texte lors du saisi
function on_input(id_input) {
    $("#"+id_input).css('border','1px solid rgba(0,0,0,0.5)');
}
</script>
<?php
include('../footer.php');
?>