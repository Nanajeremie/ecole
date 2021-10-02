<?php
include('../../../utilities/QueryBuilder.php');
$title = 'Test';
$breadcrumb = 'Plan Test';
include('header.php');
$obj = new QueryBuilder();
$max_date = $obj->Requete('SELECT NOW() + INTERVAL 3 DAY as dat')->fetch();
$max_date = $max_date['dat'];

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6">
                            Plan Test Form
                        </div>
                        <div class="col-6 text-right">
                            <a class="btn btn-dark" href="planned_test.php">View planned test</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="upload_test.php" method="post" enctype="multipart/form-data">
                        <div class="row my-4">
                            <?php
                            $modules = $obj->Requete("SELECT m.ID_CLASSE, m.NOM_MODULE, m.ID_MODULE, c.NOM_CLASSE FROM enseigner e , module m, classe c, professeur p, user u WHERE e.ID_PROFESSEUR = p.ID_PROFESSEUR AND m.ID_MODULE = e.ID_MODULE AND c.ID_CLASSE = m.ID_CLASSE AND p.ID_USER = " . $_SESSION['ID_USER'] . " AND e.ANNEE = '".getAnnee(0)['ID_ANNEE']."' AND u.ID_USER = " . $_SESSION['ID_USER'])->fetchAll();
                            $m_class = $obj -> Requete("SELECT * FROM classe WHERE ID_CLASSE IN (SELECT ID_CLASSE FROM enseigner e , module m,  professeur p, user u WHERE e.ID_PROFESSEUR = p.ID_PROFESSEUR AND m.ID_MODULE = e.ID_MODULE  AND p.ID_USER =" .$_SESSION['ID_USER'] ." AND u.ID_USER =" .$_SESSION['ID_USER'].")");
                          
                            ?>
                            <div class="col-lg-3 col-sm-6">
                                <div class="form-group">
                                    <label for="modulus">Modulus</label><span class="text-danger"> * </span>
                                    <select name="modulus" id="modulus" class="form-control" required>
                                        <option value="0" selected disabled>Select Modulus</option>
                                        <?php foreach ($m_class as $module1) { ?>
                                            <optgroup label="<?= $module1["NOM_CLASSE"] ?>">
                                                <?php
                                                foreach ($modules as $module) {
                                                    if ($module["ID_CLASSE"] == $module1["ID_CLASSE"]):
                                                        ?>
                                                        <option value="<?= $module['ID_MODULE'] ?>"><?= $module['NOM_MODULE'] ?></option>
                                                    <?php
                                                    endif;
                                                } ?>
                                            </optgroup>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div id="modulus_area"></div>
                            </div>

                            <div class="col-lg-3 col-sm-6">
                                <div class="form-group">
                                    <label for="dev_date">Test Date</label><span class="text-danger"> * </span>
                                    <input class="form-control" type="datetime-local"
                                           min="<?= substr($max_date, 0, 10) . 'T07:59' ?>" name="dev_date"
                                           id="dev_date" required>
                                    
                                </div>
                                <div id="dev_date_area"></div>
                            </div>

                            <div class="col-lg-3 col-sm-6">
                                <div class="form-group">
                                    <label for="percentage">Percentage</label><span class="text-danger"> * </span>
                                    <select name="percentage" id="percentage" class="form-control" required>
                                        <option value="0" selected disabled>Select Percentage</option>
                                        <?php
                                            $cpt = 10;
                                            while ($cpt < 60): 
                                                $cpt += 5 
                                        ?>
                                            <option value="<?= $cpt ?>"><?= $cpt . " %" ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div id="percent_area"></div>
                            </div>

                            <div class="col-lg-3 col-sm-6">
                                <div class="form-group">
                                    <label for="status">Statut</label>
                                    <select class="form-control" name="status" id="status" readonly>
                                        <option value="0" disabled>Select Statut</option>
                                        <option value="Active" disabled>Active</option>
                                        <option value="Inactive" selected>Inactive</option>
                                    </select>
                                </div>
                                <div id="status_area"></div>
                            </div>

                            <div class="col-lg-12 my-5">
                                <div class="form-group rounded text-center py-5 dropzone" id="test_dropzone">
                                    <div class="dz-message">
                                        <i class="fa fa-download fa-2x text-muted" aria-hidden="true"></i>
                                        <h4 class="text-muted">Drop or click here to upload the test.</h4>
                                    </div>
                                    <div id="file_area"></div>
                                </div>
                            </div>

                            <div class="col-lg-12 mt-2 text-center">
                                <button class="btn btn-outline-dark w-50" name="submit_test" type="submit"
                                        id="submit_test">Submit
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<?php
include('../footer.php');
?>
<script>

    Dropzone.autoDiscover = false;
    $(document).ready(function () {
        var dropzone = new Dropzone("#test_dropzone", {
            url: 'upload_test.php',
            clickable: true,
            autoProcessQueue: false,
            paraName: 'file',
            maxFilesize: 7, //MB
            maxFiles: 1,
            acceptedFiles: '.pdf',
            addRemoveLinks: true,
            uploadMultiple: true,
            thumbnailWidth: 400,
            thumbnailHeight: 400,
            init: function () {
                dzClosure = this;
                $('#submit_test').on("click", function (e) {
                    e.preventDefault();
                    e.stopPropagation();

                    var erreur = [];

                    //----------------------------------------------------------------------------------------

                    if($("#modulus").val() == null || $("#modulus").val() == '' )
                    {
                        $("#modulus").addClass('border border-danger')
                        erreur['modulus'] = 'This input can\'t be empty';
                        $("#modulus_area").html("<div class='text-danger my-2'>"+ erreur['modulus']+"</div>");
                    }
                    else
                    {
                        $("#modulus").removeClass('border border-danger');
                        $("#modulus_area").html("");
                    }

                    //----------------------------------------------------------------------------------------

                    if($("#dev_date").val() == '')
                    {
                        $("#dev_date").addClass('border border-danger');
                        erreur['dev_date'] = 'This input can\'t be empty';
                        $("#dev_date_area").html("<div class='text-danger my-2'>"+ erreur['dev_date']+"</div>");
                    }
                    else
                    {
                        $("#dev_date").removeClass('border border-danger');
                        $("#dev_date_area").html("");
                    }

                    //----------------------------------------------------------------------------------------

                    if($("#percentage").val() == null || $("#percentage").val() == '')
                    {
                        $("#percentage").addClass('border border-danger');
                        erreur['percentage'] = 'This input can\'t be empty';
                        $("#percent_area").html("<div class='text-danger my-2'>"+ erreur['percentage']+"</div>");
                    }
                    else
                    {
                        $("#percentage").removeClass('border border-danger');
                        $("#percent_area").html("");

                    }

                    //----------------------------------------------------------------------------------------

                    if($("#status").val() == null || $("#status").val() == '')
                    {
                        $("#status").addClass('border border-danger');
                        erreur['status'] = 'This input can\'t be empty';
                        $("#status_area").html("<div class='text-danger my-2'>"+ erreur['status']+"</div>");
                    }
                    else
                    {
                        $("#status").removeClass('border border-danger');
                        $("#status_area").html("");

                    }

                    //----------------------------------------------------------------------------------------

                    if (dzClosure.getQueuedFiles().length < 1) {
                            $(".dropzone").addClass("border border-danger");
                            erreur['file'] = 'This input can\'t be empty';
                            $("#file_area").html("<div class='text-danger my-2'>"+ erreur['file']+"</div>");
                        }
                        else
                        {
                            $(".dropzone").removeClass("border border-danger");  
                            $("#file_area").html("");                          
                        }

                        if (Object.keys(erreur).length == 0) {
                            dzClosure.processQueue();
                        }

                });

                dzClosure.on("sending", function (data, xhr, formData) {
                    $(":input[name]", $("form")).each(function () {
                        formData.append(this.name, $(':input[name=' + this.name + ']', $("form")).val());
                    });
                })

                dzClosure.on("complete" , function (file) {
                    dzClosure.removeFile(file);
                })
            },

            success: function (file, response) {
                if(response == "success")
                {
                    toastr.success("Test Uploaded Successfully");
                    $('input[type="text"], input[type="date"], input[type="datetime-local"], input[type="number"], select').val('');
                }
                else
                {
                    toastr.error("An error occured!!!! Maximum of test number reached");
                }
            },
            error: function (file, response) {
                toastr.error("An error occured");
            }
        });
    });
</script>