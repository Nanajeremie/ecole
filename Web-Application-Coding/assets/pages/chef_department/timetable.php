<?php
     $title = 'Timetable';
     $breadcrumb = 'Overview';
 
     include('../../../utilities/QueryBuilder.php');
     include('header.php');
     
     $obj= new QueryBuilder();

     $filieres = $obj->Requete("SELECT * FROM filieres");
     $time_limit = $obj->Requete("SELECT NOW() as date_min")->fetch();

     $timetables = $obj->Requete("SELECT * FROM timetable t , classe c WHERE t.ID_CLASSE = c.ID_CLASSE AND t.ID_ANNEE = '".getAnnee(0)['ID_ANNEE']."'");
?>


<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">

            <div class="card d-none wow fadeInDown my-4" id="new_time_table_form">
                <div class="card-header">
                    <div class="row">
                        <div class="col">
                            <h6>Publish New Timetable</h6>
                        </div>
                        <div class="col text-right">
                            <a id="close_new_timetable"><i class="fas fa-times" aria-hidden="true"></i></a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="" method="post" enctype="multipart/form-data" id="timetable_form">
                        <div class="row">
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label for="classroom">Classroom <span class="text-danger"> * </span></label>
                                    <select name="classroom" id="classroom" class="custom-select" required>
                                        <option value="" selected disabled>Select a classroom please</option>
                                        <?php while($filiere = $filieres->fetch()): ?>
                                            <optgroup label="<?= $filiere['NOM_FILIERE']?>">
                                                <?php  
                                                    $classes = $obj->Requete("SELECT * FROM classe WHERE ID_FILIERE = '".$filiere['ID_FILIERE']."'");
                                                    while($classe = $classes->fetch()):
                                                ?>
                                                <option value="<?= $classe['ID_CLASSE'] ?>"><?= $classe['NOM_CLASSE'] ?></option>
                                                <?php
                                                    endwhile;
                                                ?>
                                            </optgroup>
                                        <?php endwhile; ?>
                                    </select>
                                    <div id="classroom_area"></div>
                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label for="week_number">Week number <span class="text-danger"> * </span></label>
                                    <input type="number" name="week_number" id="week_number" min="<?= substr($time_limit['date_min'], 0, 10) . 'T07:59' ?>" class="form-control" placeholder="Exemple : 5" required>
                                    <div id="week_num_area"></div>
                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label for="week_range">Start Date <span class="text-danger"> * </span></label>
                                    <input type="date" name="week_range" id="week_range" class="form-control" min="<?= substr($time_limit['date_min'], 0, 10) ?>" required>
                                    <div id="week_range_area"></div>
                                </div>
                            </div>

                            <div class="col-lg-12 my-5">
                                <div class="form-group rounded text-center py-5 dropzone" id="test_dropzone">
                                    <div class="dz-message">
                                        <i class="fa fa-download fa-2x text-muted" aria-hidden="true"></i>
                                        <h4 class="text-muted">Drop or click here to upload the timetable.</h4>
                                    </div>
                                    <div id="file_area"></div>
                                </div>
                            </div>

                            <div class="col-lg-12 text-center">
                                <button type="reset" class="btn btn-outline-dark rounded-pill px-3 px-md-4 px-lg-5">Reset</button>
                                <button class="btn btn-outline-primary rounded-pill px-3 px-md-4 px-lg-5" name="submit_timetable" type="submit" id="submit_timetable">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card wow fadeInDown">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6">
                            <h6>Timetable</h6>
                        </div>
                        <div class="col-6 text-right">
                            <button class="btn btn-dark rounded-pill" id="publish_new_timetable" name="publish_new_timetable">Publish New one</button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <table class="table table-bordered" id="table">
                        <thead>
                            <tr>
                                <th>Classroom</th>
                                <th>Week Number</th>
                                <th>Start Date</th>
                                <th>Timetable</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                                while($timetable = $timetables->fetch()):
                            ?>
                            <tr>
                                <td><?= $timetable["NOM_CLASSE"] ?></td>
                                <td><?= $timetable["WEEK_NUMBER"] ?></td>
                                <td><?= date("M d, Y",strtotime($timetable["START_DATE"])) ?></td>
                                <td><a class="btn btn-dark" target="_blank" href="<?='..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Timetable'.DIRECTORY_SEPARATOR . $timetable["TIMETABLE_FILE"] ?>">Check it</a></td>
                            </tr>
                            <?php
                                endwhile;
                            ?>
                        </tbody>

                        <tfoot>
                            <tr>
                                <th>Classroom</th>
                                <th>Week Number</th>
                                <th>Start Date</th>
                                <th>Timetable</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

            </div>

        </div>
    </div>
</div>

<?php
    include("../footer.php");
?>

<script>
    $("#publish_new_timetable").click(function (e) { 
        e.preventDefault();
        $("#new_time_table_form").removeClass("d-none");
    });

    $("#close_new_timetable").click(function (e) { 
        e.preventDefault();
        $("#new_time_table_form").addClass("d-none");
        location.reload();
    });
    
    Dropzone.autoDiscover = false;
    $(document).ready(function () {

        var dropzone = new Dropzone("#test_dropzone", {
                url: '../../../ajax.php',
                clickable: true,
                autoProcessQueue: false,
                paraName: 'file',
                maxFilesize: 7, //MB
                maxFiles: 1,
                acceptedFiles: '.pdf',
                addRemoveLinks: true,
                uploadMultiple: false,
                thumbnailWidth: 400,
                thumbnailHeight: 400,
                init: function () {
                    dzClosure = this;
                    $('#submit_timetable').on("click", function (e) {
                        e.preventDefault();
                        e.stopPropagation();
                        var erreur = [];

                        //----------------------------------------------------------------------------------------

                        if($("#classroom").val() == null || $("#classroom").val() == '' )
                        {
                            $("#classroom").addClass('border border-danger')
                            erreur['class'] = 'This input can\'t be empty';
                            $("#classroom_area").html("<div class='text-danger my-2'>"+ erreur['class']+"</div>");
                        }
                        else
                        {
                            $("#classroom").removeClass('border border-danger');
                            $("#classroom_area").html("");
                        }

                        //----------------------------------------------------------------------------------------

                        if($("#week_number").val() == '')
                        {
                            $("#week_number").addClass('border border-danger');
                            erreur['week_number'] = 'This input can\'t be empty';
                            $("#week_num_area").html("<div class='text-danger my-2'>"+ erreur['week_number']+"</div>");
                        }
                        else
                        {
                            $("#week_number").removeClass('border border-danger');
                            $("#week_num_area").html("");
                        }

                        //----------------------------------------------------------------------------------------

                        if($("#week_range").val() == '')
                        {
                            $("#week_range").addClass('border border-danger');
                            erreur['week_range'] = 'This input can\'t be empty';
                            $("#week_range_area").html("<div class='text-danger my-2'>"+ erreur['week_range']+"</div>");
                        }
                        else
                        {
                            $("#week_range").removeClass('border border-danger');
                            $("#week_range_area").html("");

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

                    if (response == 'success') {
                        toastr.success("Timetable Uploaded Successfully");
                        $('input[type="text"], input[type="date"], input[type="file"], input[type="number"], select').val('');
                    }
                    else
                    {
                        toastr.error("An error occured");
                    }
                                        
                },
                error: function (file, response) {
                    toastr.error("An error occured");
                }
        });
    });
</script>