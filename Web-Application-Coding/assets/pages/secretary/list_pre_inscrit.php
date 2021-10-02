<?php
    include '../../../utilities/QueryBuilder.php';
    $title = 'Liste complete des eleves';
    $breadcrumb = 'Tous les eleves';

    $obj = new QueryBuilder();

    $all_new_student = $obj->Requete("SELECT * FROM etudiant e, inscription i, niveau n, classe c WHERE i.MATRICULE=e.MATRICULE AND c.ID_CLASSE = i.ID_CLASSE AND c.ID_NIVEAU = n.ID_NIVEAU AND e.STATUT=1");
    
    include('header.php');
?>
    <div class="container-fluid" id="fenetre">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <!-- <div class="card-header">
                    
                        <div class="row">
                            <div class="col-6">
                                <h4 class="text-bluesky">All Pre-Registered Student</h4>
                            </div>
                            <div class="col-6 text-right">
                                <a class="btn btn-dark" role="button" href="inscription.php">Add new student</a>
                            </div>
                        </div>
                    </div> -->
                    <div class="card-body py-2">
                    <input type="text" name="StdMatricule" id="StdMatricule" hidden>
                        <?php 
                            if (is_object($all_new_student)) 
                            {
                        ?>
                                <table class=" table table-bordered table-hover" id="table">
                                    <thead> 
                                        <tr>
                                            <th class="text-truncate">ACTION</th>
                                            <th class="text-truncate">NOM</th>
                                            <th class="text-truncate">PRENOM</th>
                                            <th class="text-truncate">GENRE</th>
                                            <th class="text-truncate">CLASSE</th>
                                            <th class="text-truncate">NIVEAU</th>
                                            <th class="text-truncate">Numero d'urgence</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php
                                            while ($new_student = $all_new_student->fetch()) 
                                            {
                                                $class_data = $obj->Select('classe', array(), array('ID_CLASSE' => $new_student['ID_CLASSE']))->fetch();
                                                
                                        ?>

                                                <tr>
                                                    <td>
                                                        <div class="btn-group" role="group" aria-label="Button group">
                                                            <button onclick="updStudent(<?=htmlspecialchars($new_student['ID_INSCRIPTION'])?>,'<?=htmlspecialchars($new_student['MATRICULE'])?>')" class="btn text-truncate"  data-toggle="modal" data-target="#updModal">
                                                                <i class="fas fa-edit text-warning"></i>
                                                            </button>
                                                            <button onclick="setStudentMatriculeDel(<?=htmlspecialchars($new_student['ID_INSCRIPTION'])?>,'<?=htmlspecialchars($new_student['MATRICULE'])?>')" class="btn text-truncate" data-toggle="modal" data-target="#del">
                                                                <i class="fas fa-trash text-danger"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                    <td class="text-uppercase text-truncate"><?= $new_student['NOM']; ?></td>
                                                    <td class="text-truncate"><?= $new_student['PRENOM']; ?></td>
                                                    <td class="text-truncate"><?= $new_student['SEXE']; ?></td>
                                                    <td class="text-truncate"><?= $class_data['NOM_CLASSE']; ?></td>
                                                    <td class="text-truncate"><?= $new_student['NOM_NIVEAU']; ?></td>
                                                    <td class="text-truncate"><?= $new_student['NUM_URGENCE']; ?></td>
                                                </tr>

                                                <!--Update Modal -->
                                                <div class="modal fade" id="updModal" data-backdrop="static" data-keyboard="false" role="dialog" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                                                        <div class="modal-content ">
                                                            <div class="modal-header bg-dark">
                                                                <h5 class="modal-title text-white">Modification des informations d'un eleve</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeModal()">
                                                                    <span aria-hidden="true" class="text-white">&times;</span>
                                                                </button>
                                                            </div>

                                                            <div class="modal-body" id="strUpdContent">
                                                                
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Delete Modal-->
                                                <div class="modal fade" id="del" data-backdrop="static" data-keyboard="false" role="dialog" tabindex="-1" aria-hidden="true">
                                                    <form action="" method="post">
                                                        <div class="modal-dialog modal-md" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header bg-danger text-white">
                                                                    <h5 class="modal-title">Tentative de Suppression</h5>
                                                                    <button class="close text-white" data-dismiss="modal" aria-label="Close" onclick="closeModal()">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>

                                                                <div class="modal-body">
                                                                    <div class="container-fluid">
                                                                        Voulez-vous reelement supprimer l'eleve selectionner? Cette action est irreversible.
                                                                    </div>
                                                                </div>

                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                                                    <button type="submit" class="btn btn-danger" name="delete_student " data-dismiss="modal" onclick="removeStd()">Suprimmer </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>

                                        <?php
                                            }
                                        ?>
                                    </tbody>

                                    <tfoot> 
                                        <tr>
                                        <th class="text-truncate">ACTION</th>
                                            <th class="text-truncate">NOM</th>
                                            <th class="text-truncate">PRENOM</th>
                                            <th class="text-truncate">GENRE</th>
                                            <th class="text-truncate">CLASSE</th>
                                            <th class="text-truncate">NIVEAU</th>
                                            <th class="text-truncate">NUMERO D"URGENCE</th>
                                        </tr>
                                    </tfoot>
                                </table>
                        <?php
                            } 
                            else 
                            {
                        ?>
                            <p class="text-center">No student for the moment!!!</p>
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
<script>
    // Recuperation du matricule de l'etudiant lorsque cliquee
    function setStudentMatriculeDel(id_inscription,matricule) {
        $("#StdMatricule").val(id_inscription+"%%"+matricule);
    }
    // Affichage des informations d'un eleve dans le modal de modification
    function updStudent(id_inscription,matricule) {
        $("#StdMatricule").val(id_inscription+"%%"+matricule);
    $.post(
            '../../../school_ajax.php',{
                std_upd_key:'std_upd_key',
                std_inscription:id_inscription,
                std_matricule:matricule,
            }, function (data){
                $("#strUpdContent").html(data);
                // initialiser les input du modal en mode read only lorsqu'on clique pour modifier les infos d'un eleve
                processInput('strUpdContent','hide');
            }
        );
    }
    // valider la modification des infos de l'eleve
    function updStdInfo(){
        
        $.post(
            '../../../school_ajax.php',{
                vali_std_upd_key:'vali_std_upd_key',
                val_std_matricule:$("#StdMatricule").val().split('%%')[1],
                val_id_inscription:$("#StdMatricule").val().split('%%')[0],
                inscription_year : $("#inscription_year :selected").val(),
                inscription_class : $("#inscription_class :selected").val(),
                inscription_nom : $("#inscription_nom").val(),
                inscription_prenom : $("#inscription_prenom").val(),
                inscription_sexe : $("#inscription_sexe").val(),
                inscription_dnaiss : $("#inscription_dnaiss").val(),
                inscription_fath_name : $("#inscription_fath_name").val(),
                inscription_moth_name : $("#inscription_moth_name").val(),
                inscription_father_job : $("#inscription_father_job").val(),
                inscription_moth_job : $("#inscription_moth_job").val(),
                inscription_email : $("#inscription_email").val(),
                inscription_tel : $("#inscription_tel").val(),
                inscription_cnib : $("#inscription_cnib").val(),
                inscription_nom : $("#inscription_nom").val(),
                inscription_nom : $("#inscription_nom").val(),
                inscription_tel_urg:$("#inscription_tel_urg").val(),
            }, function (data){
                if(data==1){
                    updStudent($("#StdMatricule").val().split('%%')[0],$("#StdMatricule").val().split('%%')[1]);
                    toastr.success("Les informations ont ete modifiee avec success");
                }
            }
        );
    }
    // suppression d'un eleve
    function removeStd(){
        $.post(
        '../../../school_ajax.php',{
                vali_std_del_key:'vali_std_del_key',
                val_std_del_matricule:$("#StdMatricule").val().split('%%')[1],
            }, function (data){
                console.log(data);
                if(data==1){
                    closeModal();
                }
            }
        );
    }
    // Cette fonction permet de desactiver le mode readOnly des input du modal 
    function enableEditing(){
        processInput('strUpdContent','show');
        $("#enableModi").css('display','none');
        $("#editInput").css('display','flex');
    }
    // Cette fonction permet de cacher ou d'afficher les element d'un formulaire en fonction du parametre passee a la variable "mod = hide/show"
    function processInput(id, mod){
        //cacher
        if(mod=='hide'){
            let nodes = document.querySelectorAll("#"+id+" input,#"+id+" select,#"+id+" button");
            
            nodes.forEach(element => {
                
                if(element.localName=='select'){
                    $(element.localName).prop('disabled', true);
                }
                else if(element.localName=='button' && element.id != "enableModi"){
                    $("#"+element.id).css('display','none');
                }else if(element.localName=='input'){
                    element.readOnly=true;
                }
                
            });
        }
        // afficher
        if(mod=='show'){
            let nodes = document.querySelectorAll("#"+id+" input,#"+id+" select,#"+id+" button");
            nodes.forEach(element => {
            
                if(element.localName=='select'){
                    $(element.localName).prop('disabled', false);
                }
                else if(element.localName=='button'){
                    $(element.localName).css('display','flex');
                }else if(element.localName=='input'){
                    element.readOnly=false;
                }
                
            });
        }  
    }
    // Cette fonction permet de valider les champs obligatoires
    function validateInput(idInpu, idControl){
        let idInput = idInpu.id;
        let idControle = idControl.id;
        // si c'est un input
        if($('#'+idInput).val()==""){
            // signaler l'erreur
            document.querySelector('#'+idInput).style.border = '1px solid rgba(255,0,0,0.5)';
        }else{
            // si c'est un select
            if($('#'+idInput+' :selected').val()==""){
               // signaler l'erreur
                document.querySelector('#'+idInput).style.border = '1px solid rgba(255,0,0,0.5)';
            }else{
                document.querySelector('#'+idInput).style.border = '1px solid rgba(0,0,0,0.5)';
            }
        }
        checkFormInputValue('strUpdContent',idControle);
    }

    // cette confirme que tous les champs sont reelement rempli avant l'envoie du formulaire
    function checkFormInputValue(parentId,idControle){
       console.log("verification...");
        let nodes = document.querySelectorAll("#"+parentId+" input,#"+parentId+" select");
        let checker = 0;

        for( var i = 0; i<nodes.length; i++) {
            if(nodes[i].localName=='select' && $("#"+nodes[i].id+" :selected").val()==""){
                checker = 1;
                break;
            } 
            else{
                if(nodes[i].localName=='input' && $("#"+nodes[i].id).val()==""){
                    checker = 1;
                    break;
                }
            }
        }
        if(checker==1){
            $("#"+idControle).prop('disabled', true);
        }
        else{
            $("#"+idControle).prop('disabled', false);
        }
    }
    // fermeture du modal
    function closeModal(){
        loads('http://localhost/COLLEGES/Web-Application-Coding/assets/pages/secretary/list_pre_inscrit.php?page=list_pre_inscrit');
    }
    // Actualiser la page
    function loads(link){
         window.location.replace(link);
    }
</script>