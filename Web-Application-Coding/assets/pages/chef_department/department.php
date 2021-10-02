<?php 
    include('../../../utilities/QueryBuilder.php');
    $title = 'Department';
    $breadcrumb = 'All Departments';
    
    $obj = new QueryBuilder();
    // liste des filieres
    $checkDeprt = $obj->Select('department', [], [], $orderBy = '', $order = 1);

    if(isset($_SESSION['del_department']) && $_SESSION['del_department'] == 1)
    {
        alert('success' , "Department deleted successfully!");
        unset($_SESSION['del_department']);
    }
    
    //suppression d'une classe

    if(isset($_POST['delete_department'])){
        extract($_POST);
        $requete = $obj->Delete('department', array('ID_DEPARTEMENT'=>$idDeparte));
        if($requete)
        {
            $_SESSION['del_department'] = 1;
            echo("<script>location.assign('../refresh.html')</script>");
        }
    }
    include('header.php');

?>
<div class="container-fluid" id="fenetre">
    <div class="row">
        <div class="col-lg-12">
            <div class="card wow fadeInDown">
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
                    
                    <table class="table table-bordered table-hover table-responsive-lg table-responsive-md table-responsive-sm" id="table">
                        <thead>
                            <tr>
                                <th class="text-truncate">Action</th>
                                <th class="text-truncate">Deparment Name</th>
                                <th class="text-truncate">Department Chief</th>
                                <th class="text-truncate">Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                 if(is_object($checkDeprt)){
                                    while($depListe = $checkDeprt->fetch()){?>
                                <tr>
                                    <td class="text-truncate">
                                        <div class="btn-group" role="group" aria-label="Button group">
                                            <button class="btn text-truncate" data-toggle="modal" data-target="#upModal" onclick="updateDepartment(<?= $depListe['ID_DEPARTEMENT']?>)"><i class="fas fa-edit text-warning"></i></button>
                                            <button class="btn text-truncate" data-toggle="modal" data-target="#delModal"><i class="fas fa-trash text-danger" onclick="deleteDepart(<?= htmlspecialchars(json_encode($depListe['ID_DEPARTEMENT']))?>,<?= htmlspecialchars(json_encode($depListe['NOM_DEPARTEMENT']))?>)"></i></button>
                                        </div>
                                    </td>
                                    <td class="text-truncate"><?= $depListe['NOM_DEPARTEMENT'] ?></td>
                                    <td class="text-capitalize text-truncate"><?= $depListe['CHEF_DEPARTEMENT']?></td>
                                    <td class="small"><?= $depListe['DESCRIPTION'] ?></td>
                                </tr>
                                <?php } }?>
                                
                        </tbody>
                        <tfoot>
                            <tr>
                                <th class="text-truncate">Action</th>
                                <th class="text-truncate">Deparment Name</th>
                                <th class="text-truncate">Department Chief</th>
                                <th class="text-truncate">Description</th>
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
            if(donnees==1)
            {
                toastr.success("A new Department has been succesfuly added");
                
                var reset = document.getElementById('depart');
                reset.reset();
            }
            else if(donnees==2)
            {
                toastr.error("This Department is already added");
                
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
        
            if(donnees=="ok" || donnees=="okk")
            {
                toastr.success("The Department has been succesfuly Updated");
            
            }
            
            else if(donnees=="exit")
            {
                toastr.error("This Department is already added");
               
            }
            
            else if(donnees=="empty")
            {
                toastr.error("Please fill up all the fields");
                
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
include('../footer.php');
?>