<?php
include('../../../utilities/QueryBuilder.php');
$obj = new QueryBuilder();
$title = 'Classes List';
$breadcrumb = 'All classes';
include('header.php');

// Recuperation de la liste des fillieres
$listeFilieres = $obj->Requete("SELECT * FROM filieres");
$listeFiliere = $obj->Requete("SELECT * FROM classe c, department d, filieres f WHERE c.ID_FILIERE = f.ID_FILIERE AND f.ID_DEPARTEMENT = d.ID_DEPARTEMENT ORDER BY c.ID_CLASSE DESC");

//suppression d'une classe

if (isset($_POST['delete_student'])) {
    extract($_POST);
    $obj->Delete('classe', array('ID_CLASSE' => $classeDel));
    header("Location:allclass.php");

}

?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6">
                                <h4 class="text-bluesky">All classes</h4>
                            </div>
                            <div class="col-6 text-right">
                                <a class="btn btn-dark" data-toggle="modal" data-target="#new_class" role="button">Add
                                    new class</a>
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
                               data-show-refresh="true" data-key-events="true" data-show-toggle="true"
                               data-resizable="false" data-cookie="true"
                               data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true"
                               data-toolbar="#toolbar">
                            <thead>
                            <tr>
                                <th data-field="field" data-editable="true">Field/Department</th>
                                <th data-field="name" data-editable="true">Classe name</th>
                                <th data-field="fees" data-editable="true">School Fees</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php

                            while ($backListe = $listeFiliere->fetch()) {
                                ?>
                                <tr>
                                    <td><?= $backListe['NOM_FILIERE'] ?>/<span
                                                class="text-info"><?= $backListe['NOM_DEPARTEMENT'] ?></span></td>
                                    <td><?= $backListe['NOM_CLASSE'] ?></td>
                                    <td><?= $backListe['MONTANT_SCOLARITE'] ?> Fcfa</td>
                                </tr>

                            <?php } ?>
                            </tbody>
                            <tfoot>
                                    <tr>
                                        <th data-field="field" data-editable="true">Deparment Name</th>
                                        <th data-field="name" data-editable="true">Department Chief</th>
                                        <th data-field="fees" data-editable="true">Description</th>
                                    </tr>
                                </tfoot>
                        </table>
                        <!-- Class Update -->
                        <div class="modal fade" id="modalite" data-backdrop="static" data-keyboard="false" role="dialog"
                             tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-xl" role="document">
                                <div class="modal-content">

                                    <div class="modal-header bg-primary">
                                        <h5 class="modal-title text-white">Update Class Information</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                                onclick="loads()">
                                            <span aria-hidden="true" class="text-white">&times;</span>
                                        </button>
                                    </div>

                                    <div class="modal-body">
                                        <div class="col-12 text-center" id="texte"></div>
                                        <div class="container-fluid my-5">
                                            <form action="" method="post" id="txt">
                                            </form>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- End Class Update -->


                        <!-- Delete Class Modal -->
                        <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger text-center text-white col-12">
                                        <h5 class="modal-title">Delete Class</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                                onclick="loads()">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="container-fluid">
                                            <span class="text-uppercase" id="showTxt"></span>
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <form method="post" action="#">
                                            <input type="text" value="" id="getId" name="classeDel" hidden>
                                            <button type="button" class="btn btn-warning" data-dismiss="modal">Reset
                                            </button>
                                            <input type="submit" class="btn btn-danger" name="delete_student"
                                                   value="Delete">
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Delete Class Modal -->


                        <!-- Create New Class -->
                        <div class="modal fade" id="new_class" data-backdrop="static" data-keyboard="false"
                             role="dialog" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-xl" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Add New Class</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                                onclick="loads()">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body ">
                                        <div class="col-12 text-center" id="text"></div>
                                        <form action="" method="post" class="my-5" id="class">
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <label for="filiere">Field</label>
                                                    <div class="input-group">
                                                        <select class="form-control" name="filiere" id="filiere"
                                                                oninput="EnableDecimal(this)">
                                                            <option value="Select">Select field</option>
                                                            <?php
                                                            if (is_object($listeFilieres)) {
                                                                while ($repListes = $listeFilieres->fetch()) {
                                                                    ?>

                                                                    <option value="<?= $repListes['ID_FILIERE'] ?>"><?= $repListes['NOM_FILIERE'] ?></option>
                                                                <?php }
                                                            } ?>
                                                        </select>
                                                        <div class="input-group-append">
                                                            <span class=" input-group-text fas fa-school"></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-lg-4">
                                                    <label for="classe_nom">Class Name</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="classe_nom"
                                                               id="classe_nom" placeholder="Enter Class Name"
                                                               oninput="EnableDecimal(this)" required>

                                                        <div class="input-group-append">
                                                            <span class="input-group-text fas fa-school"></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-lg-4">
                                                    <label for="class_fees">School Fees</label>
                                                    <div class="input-group">
                                                        <input type="number" class="form-control" name="class_fees"
                                                               id="class_fees" placeholder="Enter Class Name"
                                                               oninput="EnableDecimal(this)" required>

                                                        <div class="input-group-append">
                                                            <span class="input-group-text fas fa-money-bill-wave-alt"></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-lg-12 text-center my-5">
                                                    <input class="btn btn-primary w-50" type="button"
                                                           name="submit_class" id="create" value="Create class"
                                                           onclick="Inscription()" disabled>
                                                </div>
                                            </div>
                                        </form>

                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- End Create New Class -->

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        var inputs_remplis = [];
        var btn = document.getElementById("create");

        function Inscription() {
            var filiere = $("#filiere :selected").val();
            $.post(
                '../../../ajax.php', {

                    submit: $("#create").val(),
                    filiere: $("#filiere :selected").val(),
                    classe_nom: $("#classe_nom").val(),
                    class_fees: $("#class_fees").val(),
                }, function (donnees) {
                    if (donnees == 1) {
                        $('#text').css('color', 'green');
                        $('#text').html('A new class has been succesfuly added');
                        $('#text').css('padding', '6px');
                        $('#text').css('background-color', '#e5ffe0');
                        var reset = document.getElementById('class');
                        reset.reset();
                    } else if (donnees == 2) {
                        $('#text').css('color', 'red');
                        $('#text').css('padding', '6px');
                        $('#text').html('This class is already added.');
                        $('#text').css('background-color', '#f8bccb');
                    } else if (donnees == 3) {
                        $('#text').css('color', 'red');
                        $('#text').css('padding', '6px');
                        $('#text').html('Please choose a field');
                        $('#text').css('background-color', '#f8bccb');

                    }

                });

        }

        function Update() {
            $.post(
                '../../../ajax.php', {

                    Update: $("#update").val(),
                    up_filiere: $("#up_filiere :selected").val(),
                    up_classe_nom: $("#up_classe_nom").val(),
                    up_class_fees: $("#up_class_fees").val(),
                    idClasse: $("#idClasse").val(),
                }, function (donnees) {
                    if (donnees == "ok" || donnees == "okk") {
                        $('#texte').css('color', 'green');
                        $('#texte').css('padding', '6px');
                        $('#texte').css('background-color', '#e5ffe0');
                        $('#texte').html('The class has been succesfuly Updated');
                    } else if (donnees == "exit") {
                        $('#texte').css('color', 'red');
                        $('#texte').css('padding', '6px');
                        $('#texte').html('This class is already added.');
                        $('#texte').css('background-color', '#f8bccb');
                    } else if (donnees == "empty") {
                        $('#texte').css('color', 'red');
                        $('#texte').css('padding', '6px');
                        $('#texte').html('Please fill up all the fields');
                        $('#texte').css('background-color', '#f8bccb');

                    }

                });

        }

        function deleteClasse(idClasse, nomClasse) {
            document.getElementById("getId").value = idClasse;
            $("#showTxt").html("do You really want to delete the selected class??<br><b>" + nomClasse + "</b>")
        }

        function EnableDecimal(val1) {
            if (val1.id == "filiere" || val1.id == "classe_nom" || val1.id == "class_fees" || val1.id == "up_filiere" || val1.id == "up_classe_nom" || val1.id == "up_class_fees") {
                if (val1.value == "") {
                    val1.style.color = 'red';
                    val1.style.border = '1px solid red';
                    val1.style.backgroundColor = '#ffdbd8';
                    Pop(val1);
                } else {
                    val1.style.color = 'black';
                    val1.style.border = '1px solid #ced3db';
                    val1.style.backgroundColor = 'white';
                    Push(val1);
                }
            }
        }

        function Push(input) {
            //console.log(input);
            //console.log(inputs_remplis.indexOf(input.id));
            if (inputs_remplis.indexOf(input.id) <= -1) {
                inputs_remplis.push(input.id);
            }
            Enable();
        }

        function Pop(input) {
            //console.log(inputs_remplis.indexOf(input.id));
            if (inputs_remplis.indexOf(input.id) >= 0) {
                inputs_remplis.splice(inputs_remplis.indexOf(input.id), 1);
            }
            Enable();
        }

        function Enable() {
            if (inputs_remplis.length == 3) {
                btn.disabled = false;
            } else {
                btn.disabled = true;
            }
        }

        function updateClasse(id) {
            console.log(id);
            $.post(
                '../../../ajax.php', {

                    upd: 'ok',
                    matricule: id,
                }, function (donnees) {
                    $('#txt').html(donnees);
                });

        }

        function loads() {
            window.location.replace("allclass.php");
        }
    </script>
<?php
include('footer.php');
?>