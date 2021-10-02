<?php 
    include('../../../utilities/QueryBuilder.php');
    $title = 'Diplomas';
    $breadcrumb = 'All Allowed Diplomas';
    //suppression d'une Filiere

    $obj = new QueryBuilder();
    // Recuperation de la liste des departements 
        $listeDepart =$obj->Requete("SELECT * FROM department");
        $listeDepart1 =$obj->Requete("SELECT * FROM  department d, filieres f WHERE f.ID_DEPARTEMENT = d.ID_DEPARTEMENT ORDER BY f.ID_FILIERE DESC");
    // Recuperation de la liste des bacs
        $typebac = $obj->Requete("SELECT * FROM type_bac");
    // Insertion du  nouveau bac

    if(isset($_SESSION['add_bac']) && $_SESSION['add_bac'] == 1)
    {
        alert('success' , "Diploma added Successfully");
        unset($_SESSION['add_bac']);
    }
    
    if(isset($_SESSION['del_bac']) && $_SESSION['del_bac'] == 1)
    {
        alert('success' , "Diploma deleted successfully");
        unset($_SESSION['del_bac']);
    }
    
    if(isset($_SESSION['del_field']) && $_SESSION['del_field'] == 1)
    {
        alert('success' , "Field deleted successfully");
        unset($_SESSION['del_field']);
    }
    
    if(isset($_POST['delete_field']))
    {
        $mesg = "Field deleted";
        extract($_POST);
        $km= $obj->Delete('filieres', array('ID_FILIERE'=>$fieldDel));

        if(isset($fieldDel)){
            $_SESSION['del_field'] = 1;
            echo("<script>location.assign('../refresh.html')</script>");
        }

        //header("Location:field.php");
        $_POST['delete_field']=false;
    }

    if (isset($_POST['addbac']) && !empty($_POST['newbac']))
    {
        extract($_POST);
        $baclist = $obj->Inscription('type_bac',array("NOM_TYPE_BAC"), array($newbac), $status = array("NOM_TYPE_BAC"=>$newbac));
        if ($baclist) {
            $_SESSION['add_bac'] = 1;
            echo("<script>location.assign('../refresh.html')</script>");
        }
    }
    
    //Suppression d'un diplome
    if(isset($_POST['delete_diploma'])){
        extract($_POST);
        $requete = $obj->Delete('type_bac', array('ID_TYPE_BAC'=>$name_diploma));
        
        if ($requete) {
            $_SESSION['del_bac'] = 1;
            echo("<script>location.assign('../refresh.html')</script>");
        }
    }
    
    include('header.php');
?>

<script src="../../library/jquery/jquery.js" type="text/javascript"></script>

<div class="container-fluid" id="fenetre">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">

                <div class="card-header">
                    <div class="row">
                        <div class="col-6">
                            <h4 class="text-bluesky">All Fields</h4><br>
                        </div>
                        <div class="col-6 text-right">
                            <a class="btn btn-dark" data-toggle="modal" data-target="#new_field" role="button">Add New Field</a>
                            <a class="btn btn-dark" data-toggle="modal" data-target="#new_diploma" role="button">Add New Diploma</a>
                        </div>
                    </div>
                </div>

                <div class="card-body py-2">
                    <table id="table" class="table table-bordered table-hover table-responsive-lg table-responsive-md table-responsive-sm">
                        <thead>
                            <tr>
                                <th class="text-truncate">Action</th>
                                <th class="text-truncate">Departement</th>
                                <th class="text-truncate">Field Name</th>
                                <th class="text-truncate">Description</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                                $update = 'update';
                                $delete = 'delete';
                                while($backListeField=$listeDepart1->fetch())
                                {
                            ?>
                                <tr>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Button group">
                                            <button class="btn text-truncate" data-toggle="modal" data-target="#update" onclick="updateField(<?= $backListeField['ID_FILIERE']?>)"><i class="fas fa-edit text-warning"></i></button> 
                                            <button class="btn text-truncate" data-toggle="modal" data-target="#delete">
                                                <i class="fas fa-trash text-danger" onclick="deleteField(<?= htmlspecialchars(json_encode($backListeField['ID_FILIERE']))?>,<?= htmlspecialchars(json_encode($backListeField['NOM_FILIERE']))?>)"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="text-truncate"><?= $backListeField['NOM_DEPARTEMENT'] ?></td>
                                    <td class="text-truncate"><?= $backListeField['NOM_FILIERE'] ?></td>
                                    <td><?= $backListeField['DESCRIPTION'] ?></td>
                                </tr>
                            <?php
                                  }
                            ?>
                        </tbody>

                        <tfoot>
                            <tr>
                                <th class="text-truncate">Action</th>
                                <th class="text-truncate">Departement</th>
                                <th class="text-truncate">Field Name</th>
                                <th class="text-truncate">Description</th>
                            </tr>
                        </tfoot>
                    </table>   

                    <!-- Department Update -->
                    <div class="modal fade" id="update" data-backdrop="static" data-keyboard="false" role="dialog" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-xl" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-primary">
                                    <h5 class="modal-title text-white">Update Field Information</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="loads()">
                                        <span aria-hidden="true" class="text-white">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                <div class="col-12 text-center" id="txtFielde"></div>
                                    <div class="container-fluid my-5">
                                        <form action="" method="post" id="txt">
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Department Update -->

                    <!-- Delete Field Modal -->
                    <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Delete Field</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                <div class="modal-body">
                                    <div class="container-fluid">
                                        <span class="text-uppercase" id="getField"></span>
                                    </div>

                                </div>
                                <div class="modal-footer">
                                <form method="post" action="#">
                                    <input type="text" value="" id="getId" name="fieldDel" hidden>
                                        <button type="button" class="btn btn-warning" data-dismiss="modal" onclick="loads()">Reset</button>
                                        <input type="submit" class="btn btn-danger" name="delete_field" value="Delete" onclick="loads()">
                                </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Delete field Modal -->

                    <!-- Create field Field -->
                    <div class="modal fade" id="new_field" data-backdrop="static" data-keyboard="false" role="dialog" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-xl" role="document">
                            <div class="modal-content">
                                    <div class="modal-header bg-primary">
                                            <h5 class="modal-title text-white">Add New Field</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="loads()">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                        </div>
                                <div class="modal-body ">
                                <div class="col-12 text-center" id="txtField"></div>
                                    <form action="" method="post" class="my-3" id="clear">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <label for="department">Department <span class="text-danger"> * </span></label>
                                                <div class="input-group"> 
                                                    <select class="form-control" name="department" id="department" required>
                                                        <option value="Select">Select department</option>
                                                    <?php if(is_object($listeDepart)){
                                                            while($repListes = $listeDepart->fetch()){?>
                                                            <option value="<?= $repListes['ID_DEPARTEMENT'] ?>"><?= $repListes['NOM_DEPARTEMENT'] ?></option>  
                                                        <?php } } ?>
                                                    </select>
                                                    <div class="input-group-append">
                                                        <span class=" input-group-text fas fa-school"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <label for="classe_nom">Field Name <span class="text-danger"> * </span></label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="classe_nom" id="field_nom" placeholder="Enter Field Name" oninput="EnableDecimal(this)" required>
                                                
                                                    <div class="input-group-append">
                                                        <span class="input-group-text fas fa-school"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-lg-12 mt-5">
                                                <label for="description">Description <span class="text-danger"> * </span></label>
                                                <div class="form-group">
                                                    <textarea name="description" id="description" class="form-control" rows="10" style="resize:none;" placeholder="Enter Description Here" oninput="EnableDecimal(this)" required></textarea>
                                                </div>
                                            </div>

                                            <div class="col-lg-12 text-center my-5">
                                                <input class="btn btn-primary w-50" type="button" name="submit_field" id="submit_field" onclick="addField()" value="Create Field" disabled> 
                                            </div>
                                        </div>
                                    </form>

                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- End Department Field -->

                    <!--  Add new diploma type-->
                    <div class="modal fade " id="new_diploma" data-backdrop="static" data-keyboard="false" role="dialog" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-xl" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-primary">
                                    <h5 class="modal-title text-white">Add New Diploma</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="loads()">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="col-12 text-center" id="txtField"></div>
                                    <form action="" method="post" class="my-3" id="clear">
                                        <div class="row">
                                            <div class="col-lg-12" style="max-height: 300px; overflow-y: auto;">
                                                <table class="table table-bordered table-striped overflow-auto">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center text-dark">Action</th>
                                                            <th class="text-center">Type of Baccalaureat</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="col-lg-12" style="max-height: 300px; overflow-y: auto;">
                                                    <script>
                                                        //creation of a variable containnig the list of diploma types
                                                        var  list_of_diplomas = [];
                                                    </script>
                                                    <?php
                                                    //var_dump(json_encode($typebac->fetchAll()[0]['NOM_TYPE_BAC']));
                                                    while ( $listebac = $typebac->fetch()){
                                                     ?>
                                                        <tr>
                                                            <td class="text-center">
                                                                <button class="btn"  data-toggle="modal" data-target="#delete_diploma">
                                                                    <i class="fas fa-trash text-danger" onclick="deleteDiploma(<?= htmlspecialchars(json_encode($listebac['ID_TYPE_BAC']))?>,<?= htmlspecialchars(json_encode($listebac['NOM_TYPE_BAC']))?>)"></i>
                                                                </button>
                                                            </td>
                                                            <td class="text-center">
                                                                <?php echo $listebac['NOM_TYPE_BAC']; ?>
                                                            </td>
                                                        </tr>
                                                        <script>
                                                            //appending the current diploma to the var  list_of_diplomas
                                                            list_of_diplomas.push(<?= json_encode($listebac['NOM_TYPE_BAC'])?>);
                                                        </script>
                                                        <?php };  ?>
                                                    </tbody>
                                                    </div>
                                                </table>
                                            </div>
                                            <div class="col-12 mt-3">
                                                <div class="row">
                                                <div class="col-lg-8">
                                                    <input type="text" class="form-control" name="newbac" id="newbac"  placeholder="Enter the type of baccalaureat" required oninput="checkDiplomas(this)">
                                                </div>
                                                    <div class="col-lg-4">
                                                        <button class="btn btn-success  col-lg-12" type="submit" name="addbac" id="addbac" >Add a new bac</button>
                                                    </div>
                                                    <div class="col-lg-8">
                                                        <div class="text-center text-danger" id="msg" style="display: none;">This diploma already exists</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                    </div>
                </div>
            </div>

            <!-- delete diploma modal-->
            <div class="modal fade" id="delete_diploma" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title">Delete Field</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <div class="container-fluid">
                                <span class="text-uppercase" id="getDiploma">
                                </span>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <form method="post" action="#">
                                <input type="text" id="getIdDiploma" name="name_diploma" hidden>
                                <button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>
                                <input type="submit" class="btn btn-danger" name="delete_diploma" value="OK">
                            </form>
                        </div>

                    </div>
                </div>
            </div>
            <!-- delete diploma modal-->

        </div>
            <!--  End Add new diploma type-->
    
    
<script type="text/javascript">
    var inputs_remplis = [];
    var btn = document.getElementById("submit_field");
    function addField(){
        $.post(
        '../../../ajax.php', {
            submit_field:$("#submit_field").val(),
            department:$("#department :selected").val(),
            description:$("#description").val(),
            field_nom:$("#field_nom").val(),
        }, function (donnees){
            if(donnees==1){
            
                toastr.success("A new Field has been successfully added");
                var reset = document.getElementById('clear');
                reset.reset();
            }
            
            else if(donnees==2){
            
                toastr.error("This Department is already added");
            
            }
            
            else if(donnees==3){
            
                toastr.error("Please choose a Department");
                
            }
            
        });
        
    }

    //FONCTION POUR SUPPRIMER UNE FILIERE
    function deleteField(idField,nomField)
    {
        document.getElementById("getId").value = idField;
        $("#getField").html("Do you really want to delete the seleted Field? <br><b>"+nomField+"</b>")
    }

    //FONCTION POUR SUPPRIMER UN DIPLOME
    function deleteDiploma(idDiploma,nomDiploma)
    {
       document.getElementById("getIdDiploma").value  = idDiploma;
        $("#getDiploma").html("Do You really want to delete the selected Diploma?<br><b>"+nomDiploma+"</b>")
    }

    //FONCTION POUR AJOUTER UN DIPLOME
    function EnableDecimal(val1){
        if(val1.id=="field_nom" || val1.id=="up_field_nom" || val1.id=="description" || val1.id=="up_description"){
            if (val1.value =="")
            {
                val1.style.color = 'red';
                val1.style.border = '1px solid red';
                val1.style.backgroundColor = '#ffdbd8';
                Pop(val1);
            }
            else
            {
                val1.style.color = 'black';
                val1.style.border = '1px solid #ced3db';
                val1.style.backgroundColor = 'white';
            Push(val1);
            }
        }
    }

    function Push(input){
      //console.log(input);
         //console.log(inputs_remplis.indexOf(input.id));
         if (inputs_remplis.indexOf(input.id) <= -1)
         {
             inputs_remplis.push(input.id);
         }
         Enable();
    }

    function Pop(input){
        //console.log(inputs_remplis.indexOf(input.id));
        if (inputs_remplis.indexOf(input.id) >= 0)
           {
            inputs_remplis.splice(inputs_remplis.indexOf(input.id), 1);
           }
           Enable();
    }

    function Enable(){
        if (inputs_remplis.length == 2)
        {
            btn.disabled = false;
        }
        else
        {
            btn.disabled = true;
        }
    }  

    function FieldtUpdate(){
        $.post(
        '../../../ajax.php', {
            upField:$("#upField").val(),
            idField:$("#idField").val(),
            up_department:$("#up_department").val(),
            up_description:$("#up_description").val(),
            up_field_nom:$("#up_field_nom").val(),
        }, function(dataa){
            if(dataa == 2){
            
                toastr.success("The Field has been successfully Updated");
                
            }
            
            else if(dataa == 1){
            
                toastr.error("This Field is already added");
            
            }
            
            else if(dataa == 0){
            
                toastr.error("Please fill up all the fields");
                
            }
            
        });
        
    }

    function updateField(id){
        
         $.post(
            '../../../ajax.php', {
                fieldUpd:'ok',
                fieldId:id,
            }, function (donnees){
                $('#txt').html(donnees);
            });

    }

    function loads(){
         window.location.replace("field.php");
    }
</script>

<?php
    include('../footer.php');
?>

<script>
    //selection of the button for add new diploma
    var btn_add_diploma = $('#addbac');
    //selection of the message to show if the typed diploma alredy exists
    var msg = $('#msg');
    //function to check wether the typed diploma already exists
    function checkDiplomas(input)
    {
        //we checks if the typed diploma is in the array of recorded diplomas
        if (list_of_diplomas.indexOf(input.value)>0)
        {
            //if it already exists, we disable the button
            btn_add_diploma.addClass('disabled');
            //showing the message
            msg.show();
        }
        //otherwise we reactive the button
        else
        {
            btn_add_diploma.removeClass('disabled');
            //hiding the message
            msg.hide();
        }

        // var scrol = document.getElementById("new_diploma");
        // style.overflow()='scroll';

    }
</script>