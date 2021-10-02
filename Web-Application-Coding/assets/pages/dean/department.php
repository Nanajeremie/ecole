<?php 
    include('../../../utilities/QueryBuilder.php');
    $title = 'Department';
    $breadcrumb = 'All Departments';
    
    $obj = new QueryBuilder();
    // liste des filieres
    $checkDeprt = $obj->Select('department', [], [], $orderBy = '', $order = 1);

    //suppression d'une classe

    if(isset($_POST['delete_department'])){
        extract($_POST);
        $obj->Delete('department', array('ID_DEPARTEMENT'=>$idDeparte));
        header("Location:department.php");
    }
    include('header.php');

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6">
                            <h4 class="text-bluesky">All Departments</h4>
                        </div>
                        <div class="col-6 text-right">
                            <a class="btn btn-dark" data-toggle="modal" data-target="#new_department" role="button">Add New Department</a>
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
                                <th data-field="department" data-editable="true">Deparment Name</th>
                                <th data-field="name" data-editable="true">Department Chief</th>
                                <th data-field="description" data-editable="true">Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                 if(is_object($checkDeprt)){
                                    while($depListe = $checkDeprt->fetch()){?>
                                <tr>
                                    <!-- <td>
                                        <div class="btn-group" role="group" aria-label="Button group">
                                            <button class="btn" data-toggle="modal" data-target="#upModal" onclick="updateDepartment(<?= $depListe['ID_DEPARTEMENT']?>)"><i class="fas fa-edit text-warning"></i></button>
                                            <button class="btn" data-toggle="modal" data-target="#delModal"><i class="fas fa-trash text-danger" onclick="deleteDepart(<?= htmlspecialchars(json_encode($depListe['ID_DEPARTEMENT']))?>,<?= htmlspecialchars(json_encode($depListe['NOM_DEPARTEMENT']))?>)"></i></button>
                                        </div>
                                    </td> -->
                                    <td><?= $depListe['NOM_DEPARTEMENT'] ?></td>
                                    <td class="text-capitalize"><?= $depListe['CHEF_DEPARTEMENT']?></td>
                                    <td class="small"><?= $depListe['DESCRIPTION'] ?></td>
                                </tr>
                                <?php } }?>
                                
                                 </tbody>
                                 <tfoot>
                                    <tr>
                                        <th data-field="department" data-editable="true">Deparment Name</th>
                                        <th data-field="name" data-editable="true">Department Chief</th>
                                        <th data-field="description" data-editable="true">Description</th>
                                    </tr>
                                </tfoot>
                             </table>
                                <!-- Department Update -->
                                <div class="modal fade" id="upModal" data-backdrop="static" data-keyboard="false" role="dialog" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-xl" role="document">
                                        <div class="modal-content">

                                            <div class="modal-header bg-primary">
                                                <h5 class="modal-title text-white">Update Department Information</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="loads()">
                                                    <span aria-hidden="true" class="text-white">&times;</span>
                                                </button>
                                            </div>

                                            <div class="modal-body">
                                                <div class="container-fluid ">
                                                   <div class="col-12 text-center" id="texte"></div>
                                                    <form action="" method="post" id="txt" class="my-5">
                                                        
                                                    </form>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                                <!-- End Field Update -->


                                <!-- Delete Department Modal -->
                                <div class="modal fade" id="delModal" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                                <div class="modal-header bg-danger text-center text-white">
                                                        <h5 class="modal-title">Delete Field</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                    </div>
                                            <div class="modal-body">
                                                <div class="container-fluid">
                                                    <span class="text-uppercase" id="getDepart"></span>
                                                </div>

                                            </div>
                                            <div class="modal-footer">
                                               <form action="#" method="post">
                                                  <input type="text" value="" id="idDEP" name="idDeparte" hidden>
                                                   <button type="button" class="btn btn-warning" data-dismiss="modal">Reset</button>
                                                <input type="submit" class="btn btn-danger" name="delete_department" value="Delete">
                                               </form>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Delete Department Modal -->
                           
                       

                                <!-- Create New Field -->
                                <div class="modal fade" id="new_department" data-backdrop="static" data-keyboard="false" role="dialog" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-xl" role="document">
                                        <div class="modal-content">
                                                <div class="modal-header bg-primary">
                                                        <h5 class="modal-title text-white">Add New Department</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="loads()">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                            <div class="modal-body">
                                               <div class="col-12 text-center" id="text"></div>
                                                <form action="#" method="post" id="depart" class="my-3">
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <label for="filiere">Department Name<span class="text-danger"> * </span></label>
                                                            <div class="input-group">
                                                                <input type="text" class="form-control" name="department_name" id="department_name" placeholder="Enter Department Name" oninput="EnableDecimal(this)" required>

                                                                <div class="input-group-append">
                                                                    <span class=" input-group-text fas fa-university"></span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-6">
                                                            <label for="department_chief">Department Chief <span class="text-danger"> * </span></label>
                                                            <div class="input-group">
                                                                <input type="text" class="form-control" name="department_chief" id="department_chief" placeholder="Enter Department Chief" oninput="EnableDecimal(this)" required>
                                                            
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text fas fa-user-tie"></span>
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
                                                            <input class="btn btn-primary w-50" type="button" name="submit_department" id="submit_department" value="Create Department" onclick="addDepartment()" disabled > 
                                                        </div>
                                                    </div>
                                                </form>

                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                                <!-- End Create New Field -->

                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var inputs_remplis = [];
    var btn = document.getElementById("submit_department");
    var btn2 = document.getElementById("Up_Dep");
    function addDepartment(){
        $.post(
        '../../../ajax.php', {
            submit_department:$("#submit_department").val(),
            description:$("#description").val(),
            department_name:$("#department_name").val(),
            department_chief:$("#department_chief").val(),
        }, function (donnees){
            alert(donnees);
            if(donnees==1){
                $('#text').css('color','green');
                $('#text').html('A new Department has been succesfuly added');
                $('#text').css('padding','6px');
                $('#text').css('background-color','#e5ffe0');
                var reset = document.getElementById('depart');
                reset.reset();
            }else if(donnees==2){
                $('#text').css('color','red');
                $('#text').css('padding','6px');
                $('#text').html('This Department is already added.');
                $('#text').css('background-color','#f8bccb');
            }
            
        });
        
    }
    function DeprtUpdate(){
        $.post(
        '../../../ajax.php', {

            Up_Dep:$("#Up_Dep").val(),
            idDepart:$("#idDepart").val(),
            up_department_chief:$("#up_department_chief").val(),
            up_description:$("#up_description").val(),
            up_department_name:$("#up_department_name").val(),
        }, function (donnees){
            alert(donnees);
            if(donnees=="ok" || donnees=="okk"){
                $('#texte').css('color','green');
                $('#texte').css('padding','6px');
                $('#texte').css('background-color','#e5ffe0');
                $('#texte').html('The Department has been succesfuly Updated');
            }else if(donnees=="exit"){
                $('#texte').css('color','red');
                $('#texte').css('padding','6px');
                $('#texte').html('This Department is already added.');
                $('#texte').css('background-color','#f8bccb');
            }
            else if(donnees=="empty"){
                $('#texte').css('color','red');
                $('#texte').css('padding','6px');
                $('#texte').html('Please fill up all the fields');
                $('#texte').css('background-color','#f8bccb');
                
            }
            
        });
        
    }
    function deleteDepart(idDeparte,nomDeparte){
       document.getElementById("idDEP").value  = idDeparte;
        $("#getDepart").html("do You really want to delete the selected Department??<br><b>"+nomDeparte+"</b>")
    }
    function EnableDecimal(val1){
    if(val1.id=="department_name" || val1.id=="department_chief" || val1.id=="description" || val1.id=="up_description" || val1.id=="up_department_name" || val1.id=="up_department_chief"){
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
        if (inputs_remplis.length == 3)
        {
            btn.disabled = false;
        }
        else
        {
            btn.disabled = true;
        }
}   
    function updateDepartment(id){
         $.post(
            '../../../ajax.php', {
                depUpd:'ok',
                depId:id,
            }, function (donnees){
                $('#txt').html(donnees);
            });

}
    function loads(){
         window.location.replace("department.php");
    }
</script>
<?php
include('footer.php');
?>