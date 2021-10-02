<?php
    include '../../../utilities/QueryBuilder.php';
    $title = 'Canteen Management';
    $breadcrumb = 'Booking';

    include("./header.php");

    $obj = new QueryBuilder();

    $bookings = $obj->Requete("SELECT * FROM abonnements a , cantine c , etudiant e , classe cls, inscription i WHERE a.ID_MOIS = c.ID_MOIS AND i.MATRICULE = e.MATRICULE AND a.MATRICULE = i.MATRICULE AND cls.ID_CLASSE = i.ID_CLASSE AND a.ID_ANNEE = '".getAnnee(0)['ID_ANNEE']."' AND a.STATUT = 'actif'");
?>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6">
                                Bookings
                            </div>
                            <div class="col-6 text-right">
                                <a role="button" class="btn btn-dark rounded-pill" data-toggle="modal" data-target="#new_booking">New Booking</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                       
                        <table class="table table-bordered table-hover table-responsive table-responsive-md table-responsive-lg" id='table'>
                            <thead>
                                <tr>
                                    <th data-field='class'>Classroom</th>
                                    <th data-field='matricule'>Matricule</th>
                                    <th data-field='last_name'>Last Name</th>
                                    <th data-field='first_name'>First Name</th>
                                    <th data-field='month'>Month</th>
                                    <th data-field="start_date">Start Date</th>
                                    <th data-field="end_date">End Date</th>
                                    <th data-field='cost_fees'>Cost Fees</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    while($booking = $bookings->fetch()):
                                ?>
                                    <tr>
                                        <td><?= $booking["NOM_CLASSE"] ?></td>
                                        <td><?= $booking["MATRICULE"] ?></td>
                                        <td><?= $booking["PRENOM"] ?></td>
                                        <td><?= $booking["NOM"] ?></td>
                                        <td><?= $booking["MOIS"] ?></td>
                                        <td><?= date("M d, Y",strtotime($booking["DATE_ABONNEMENT"]))?></td>
                                        <td><?=date("M d, Y",strtotime($booking["DATE_FIN_ABONNEMENT"]))?></td>
                                        <td><?= $booking["COST_FEES"] .' fcfa'?></td>
                                    </tr>
                                <?php
                                    endwhile;
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th data-field='class'>Classroom</th>
                                    <th data-field='matricule'>Matricule</th>
                                    <th data-field='last_name'>Last Name</th>
                                    <th data-field='first_name'>First Name</th>
                                    <th data-field='month'>Month</th>
                                    <th data-field="start_date">Start Date</th>
                                    <th data-field="end_date">End Date</th>
                                    <th data-field='cost_fees'>Cost Fees</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!----------------------------------------------------- Update New Booking ------------------------------------------------->
                <div class="modal fade" id="new_booking" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard='false' aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-gradient-primary">
                                <h5 class="modal-title text-light">Canteen booking</h5>
                                <button type="button" class="close text-light" onclick="location.reload(true)" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="" method="post" id="new_booking_form">
                                    <div class="row">
                                        
                                        <div class="col-lg-6 py-lg-3 py-2">
                                            <div class="form-group">
                                                <label for="matricule">Matricule <span class="text-danger"> *</span></label>
                                                <input type="text" class="form-control" name="matricule" id="matricule">
                                                <div id="matricule_area"></div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 py-lg-3 py-2">
                                            <div class="form-group">
                                                <label for="month">Month <span class="text-danger"> *</span></label>
                                                <select type="month" class="form-control" name="month" id="month">
                                                    <option value="" selected disabled>Select the month</option>
                                                    <?php
                                                        $mois = $obj->Requete("SELECT * FROM cantine WHERE `PRIX` > 0 AND ANNEE_SCOLAIRE = '". getAnnee(0)['ID_ANNEE'] ."'");
                                                        while ($moi = $mois->fetch()) :
                                                    ?>
                                                        <option value="<?=$moi["ID_MOIS"]?>" oncl="console.log(1)" ><?=$moi["MOIS"]?></option>
                                                    <?php
                                                        endwhile;
                                                    ?>
                                                </select>
                                                <div id="month_area"></div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-lg-6 py-lg-3 py-2">
                                            <?php
                                                $cost_per_day = $obj->Requete("SELECT `COST_PER_DAY` FROM `cantine` WHERE `PRIX` > 0 AND ANNEE_SCOLAIRE = '". getAnnee(0)['ID_ANNEE'] ."' LIMIT 1")->fetch();
                                            ?>
                                            <div class="form-group">
                                                <label for="cost_days">Cost per day <span class="text-danger"> * </span></label>
                                                <input type="number" class="form-control" name="cost_days" id="cost_days" value="<?= $cost_per_day['COST_PER_DAY'] ?>" readonly>
                                                <div id="cost_days_area"></div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 py-lg-3 py-2">
                                            <div class="form-group">
                                                <label for="cost_fees">Cost Fees <span class="text-danger"> *</span></label>
                                                <input type="number" class="form-control" name="cost_fees" id="cost_fees" readonly>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 py-lg-3 py-2">
                                            <div class="form-group">
                                                <label for="nb_days">Number Of Days <span class="text-danger"> *</span></label>
                                                <input type="number" class="form-control" name="nb_days" id="nb_days" >
                                                <div id="days_area"></div>
                                            </div>
                                        </div>

                                        <div class="col-lg-12 text-center my-3">
                                            <button type="reset" class="btn btn-outline-dark px-4 rounded-pill" onclick="location.reload(true)" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-outline-primary px-4 rounded-pill" name="update_month_fees" id="update_month_fees">Book Now</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

<?php
    include("../footer.php");
?>

<script>

    $("#nb_days").keyup(function (e) { 
        if ($("#nb_days").val() != '' && $("#nb_days").val() != 0) {
            $("#cost_fees").val($("#cost_days").val() * $("#nb_days").val());
        }
    });

    $("#update_month_fees").click(function (e) { 
        e.preventDefault();
        var erreur = [];
        if($("#matricule").val() == '')
        {
            $("#matricule").addClass('border border-danger');
            erreur['matricule'] = 'This input can\'t be empty';
            $("#matricule_area").html("<div class='text-danger my-2'>"+ erreur['matricule']+"</div>");
        }
        else
        {
            $("#matricule").removeClass('border border-danger');
            $("#matricule_area").html("");
        }

        //----------------------------------------------------------------------------

        if($("#month").val() == '' || $("#month").val() == null)
        {
            $("#month").addClass('border border-danger');
            erreur['month'] = 'This input can\'t be empty';
            $("#month_area").html("<div class='text-danger my-2'>"+ erreur['month']+"</div>");
        }
        else
        {
            $("#month").removeClass('border border-danger');
            $("#month_area").html("");
        }

        //----------------------------------------------------------------------------

        if($("#cost_days").val() == '')
        {
            $("#cost_days").addClass('border border-danger');
            erreur['cost_days'] = 'This input can\'t be empty';
            $("#cost_days_area").html("<div class='text-danger my-2'>"+ erreur['cost_days']+"</div>");
        }
        else
        {
            $("#cost_days").removeClass('border border-danger');
            $("#cost_days_area").html("");
        }

        //----------------------------------------------------------------------------
        
        if($("#nb_days").val() == '')
        {
            $("#nb_days").addClass('border border-danger');
            erreur['nb_days'] = 'This input can\'t be empty';
            $("#days_area").html("<div class='text-danger my-2'>"+ erreur['nb_days']+"</div>");
        }
        else
        {
            $("#nb_days").removeClass('border border-danger');
            $("#days_area").html("");
        }

        if (Object.keys(erreur).length == 0) 
        {
            $.post("../../../ajax.php?action=book_canteen", $("#new_booking_form").serialize(),
                function (data, textStatus, jqXHR) {
                    console.log(data)
                    if (data == 'success') {
                        toastr.success("Canteen booked Successfully");
                        
                        $('input[type="text"], input[type="number"], select').val('');
                    }
                    else
                    {
                        toastr.error("An error occured");
                    }
                },
            );
        }
    });
</script>