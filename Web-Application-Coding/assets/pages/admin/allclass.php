<?php
    include('../../../utilities/QueryBuilder.php');
    $obj = new QueryBuilder();
    $title = 'Classes List';
    $breadcrumb = 'All classes';
    include('header.php');

    if(isset($_SESSION['del_classe']) && $_SESSION['del_classe'] == 1)
    {
        alert('success' , "Class deleted successfully");
        unset($_SESSION['del_classe']);
    }
    
    // Recuperation de la liste des classe
    $listeClasses = $obj->Requete("SELECT * FROM classe c, niveau n WHERE c.ID_NIVEAU = n.ID_NIVEAU ORDER BY ID_CLASSE DESC");

    // recuperation des niveaux
    $selectNiveau = $obj->Requete("SELECT * FROM niveau ORDER BY ID_NIVEAU DESC");


    //suppression d'une classe

    if (isset($_POST['delete_student'])) 
    {
        extract($_POST);
        $requete = $obj->Delete('classe', array('ID_CLASSE' => $classeDel));
        if($requete)
        {
            $_SESSION['del_classe'] = 1;
            echo("<script>location.assign('../refresh.html')</script>");
        }
    }
?>

    <div class="container-fluid" id="fenetre">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6">
                                <h4 class="text-bluesky">Liste des classes</h4>
                            </div>
                            <div class="col-6 text-right">
                                <a class="btn btn-dark" data-toggle="modal" data-target="#new_class" role="button">Ajouter une classe</a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body py-2">
                        <table id="table" class="table table-bordered table-hover table-responsive-lg table-responsive-md table-responsive-sm">
                            <thead>
                                <tr>
                                    <th class="text-truncate">Action</th>
                                    <th class="text-truncate">Niveau</th>
                                    <th class="text-truncate">Nom de classe</th>
                                    <th class="text-truncate">Scolarite</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                    while ($backListe = $listeClasses->fetch()) 
                                    {
                                ?>
                                    <tr>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="Button group">
                                                <button class="btn text-truncate" data-toggle="modal" data-target="#modalite"><i
                                                            class="fas fa-edit text-warning"
                                                            onclick="updateClasse(<?= $backListe['ID_CLASSE'] ?>)"></i>
                                                </button>
                                                <button class="btn text-truncate" data-toggle="modal" data-target="#delete"
                                                        onclick="deleteClasse(<?= htmlspecialchars(json_encode($backListe['ID_CLASSE'])) ?>,<?= htmlspecialchars(json_encode($backListe['NOM_CLASSE'])) ?>)">
                                                    <i class="fas fa-trash text-danger"></i></button>
                                            </div>
                                        </td>
                                        <td class="text-truncate"><?= $backListe['NOM_NIVEAU'] ?></td>
                                        <td class="text-truncate"><?= $backListe['NOM_CLASSE'] ?></td>
                                        <td class="text-truncate"><?= $backListe['MONTANT_SCOLARITE'] ?> Fcfa</td>
                                    </tr>
                                <?php 
                                    } 
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th class="text-truncate">Action</th>
                                    <th class="text-truncate">Niveau</th>
                                    <th class="text-truncate">Nom de classe</th>
                                    <th class="text-truncate">Scolarite</th>
                                </tr>
                            </tfoot>
                        </table>

                        <!-- Class Update -->
                        <div class="modal fade" id="modalite" data-backdrop="static" data-keyboard="false" role="dialog"
                             tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-xl" role="document">
                                <div class="modal-content">

                                    <div class="modal-header bg-primary">
                                        <h5 class="modal-title text-white">Modification de la classe</h5>
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
                                        <h5 class="modal-title">Suppression d'une classe</h5>
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
                                                   value="Supprimer">
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
                                    <div class="modal-header bg-primary">
                                        <h5 class="modal-title text-white">Ajouter une classe</h5>
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
                                                    <label for="filiere">Niveau</label>
                                                    <div class="input-group">
                                                        <select class="form-control" name="filiere" id="filiere"
                                                                oninput="EnableDecimal(this)">
                                                            <option value="">Choisir un niveau</option>
                                                            <?php
                                                                while ($repListe = $selectNiveau->fetch()) {?>
                                                                    <option value="<?=$repListe['ID_NIVEAU']?>"><?=$repListe['NOM_NIVEAU']?> </option>;
                                                           <?php } ?>
                                                            
                                                        </select>
                                                        <div class="input-group-append">
                                                            <span class=" input-group-text fas fa-school"></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-lg-4">
                                                    <label for="classe_nom">Nom de la classe</label>
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
                                                    <label for="class_fees">Scolarite</label>
                                                    <div class="input-group">
                                                        <input type="number" class="form-control" name="class_fees"
                                                               id="class_fees" placeholder="Enter SChool Fees"
                                                               oninput="EnableDecimal(this)" required>

                                                        <div class="input-group-append">
                                                            <span class="input-group-text fas fa-money-bill-wave-alt"></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-lg-12 text-center my-5">
                                                    <input class="btn btn-primary w-50" type="button"
                                                           name="submit_class" id="create" value="Ajouter"
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
                        toastr.success("A new class has been succesfuly added");
                        var reset = document.getElementById('class');
                        reset.reset();
                    } 
                    else if (donnees == 2) {
                        toastr.error("This class is already added");
                    } 
                    else if (donnees == 3) {
                        toastr.error("Please choose a field");
                    }

                });

        }

        function Update() {
            alert($("#up_class_fees").val());
            $.post(
                '../../../ajax.php', {

                    Update: $("#update").val(),
                    up_filiere: $("#up_filiere :selected").val(),
                    up_classe_nom: $("#up_classe_nom").val(),
                    up_class_fees: $("#up_class_fees").val(),
                    idClasse: $("#idClasse").val(),
                }, function (donnees) {
                    console.log(donnees);
                    if (donnees == "ok" || donnees == "okk") {
                        
                        toastr.success("The class has been succesfuly Updated")
                        
                    } 
                    
                    else if (donnees == "exit") {
                        
                        toastr.error("This class is already added");
                        
                    } 
                    
                    else if (donnees == "empty") {
                        
                        toastr.error("Please fill up all the fields");

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
    include('../footer.php');
?>