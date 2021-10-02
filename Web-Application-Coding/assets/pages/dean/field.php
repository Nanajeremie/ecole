<?php 
    include('../../../utilities/QueryBuilder.php');
    $title = 'Field';
    $breadcrumb = 'All Fields';
    //suppression d'une Filiere

    if(isset($_POST['delete_field'])){
        extract($_POST);
        $obj->Delete('filieres', array('ID_FILIERE'=>$classeDel));
        header("Location:field.php");

    }
    include('header.php');

    $obj = new QueryBuilder();
// Recuperation de la liste des departements 
    $listeDepart =$obj->Requete("SELECT * FROM department");
    $listeDepart1 =$obj->Requete("SELECT * FROM  department d, filieres f WHERE f.ID_DEPARTEMENT = d.ID_DEPARTEMENT ORDER BY f.ID_FILIERE DESC");
?>
<script src="../../library/jquery/jquery.js" type="text/javascript"></script>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6">
                            <h4 class="text-bluesky">All Fields</h4>
                        </div>
                        <div class="col-6 text-right">
                            <a class="btn btn-dark" data-toggle="modal" data-target="#new_field" role="button">Add New Field</a>
                        </div>
                    </div>
                </div>
                <div class="card-body py-2">
                    <div id="toolbar">
                        <select class="form-control dt-tb">
							<option value="">Export Basic</option>
							<option value="all">Export All</option>
                            <option value="selected">Export Selected</option>
						</select>
                    </div>
                    <table id="table" 
                        data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" 
                        data-show-pagination-switch="true"
                        data-show-refresh="true" data-key-events="true" data-show-toggle="true" data-resizable="false" data-cookie="true"
                        data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar">
                        <thead>
                            <tr>
                                <th data-field="department" data-editable="true">Deparment</th>
                                <th data-field="name" data-editable="true">Field name</th>
                                <th data-field="description" data-editable="true">Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $update = 'update';
                                $delete = 'delete';
                                while($backListeField=$listeDepart1->fetch()){
                            ?>
                                <tr>
                                    <td><?= $backListeField['NOM_DEPARTEMENT'] ?></td>
                                    <td><?= $backListeField['NOM_FILIERE']?></td>
                                    <td class="small"><?= $backListeField['DESCRIPTION']?></td>
                                </tr>
                            <?php
                                  }
                            ?>
                         </tbody>
                         <tfoot>
                            <tr>
                                <th data-field="department" data-editable="true">Deparment</th>
                                <th data-field="name" data-editable="true">Field name</th>
                                <th data-field="description" data-editable="true">Description</th>
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
                     <!-- Delete Department Modal -->
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
                                        <input type="text" value="" id="getId" name="classeDel" hidden>
                                         <button type="button" class="btn btn-warning" data-dismiss="modal">Reset</button>
                                         <input type="submit" class="btn btn-danger" name="delete_field" value="Delete">
                                    </form>
                                 </div>
                             </div>
                         </div>
                     </div>
                     <!-- End Delete Department Modal -->
                     <!-- Create Department Field -->
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
                </div>
            </div>
        </div>
    </div>
</div>
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
                $('#txtField').css('color','green');
                $('#txtField').html('A new Field has been successfully added');
                $('#txtField').css('padding','6px');
                $('#txtField').css('background-color','#e5ffe0');
                var reset = document.getElementById('clear');
                reset.reset();
            }else if(donnees==2){
                $('#txtField').css('color','red');
                $('#txtField').css('padding','6px');
                $('#txtField').html('This Department is already added.');
                $('#txtField').css('background-color','#f8bccb');
            }
            else if(donnees==3){
                $('#txtField').css('color','red');
                $('#txtField').css('padding','6px');
                $('#txtField').html('Please choose a Department');
                $('#txtField').css('background-color','#f8bccb');
                
            }
            
        });
        
    }
    function deleteField(idField,nomField){
       document.getElementById("getId").value  = idField;
        $("#getField").html("do You really want to delete the selected Field?<br><b>"+nomField+"</b>")
    }
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
            alert(dataa);
            if(dataa == 2){
                $('#txtFielde').css('color','green');
                $('#txtFielde').css('padding','6px');
                $('#txtFielde').css('background-color','#e5ffe0');
                $('#txtFielde').html('The Field has been successfully Updated');
            }else if(dataa == 1){
                $('#txtFielde').css('color','red');
                $('#txtFielde').css('padding','6px');
                $('#txtFielde').html('This Field is already added.');
                $('#txtFielde').css('background-color','#f8bccb');
            }
            else if(dataa == 0){
                alert("Hello");
                $('#txtFielde').css('color','red');
                $('#txtFielde').css('padding','6px');
                $('#txtFielde').html('Please fill up all the fields');
                $('#txtFielde').css('background-color','#f8bccb');
                
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
include('footer.php');
?>