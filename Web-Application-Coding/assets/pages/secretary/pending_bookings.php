<?php
    $title = 'Canteen Management';
    $breadcrumb = 'Pending bookings';

    include('../../../utilities/QueryBuilder.php');
    include("./header.php");

    if(isset($_SESSION['pending_booking_validate']) && $_SESSION['pending_booking_validate'] == 1)
    {
        alert('success' , "Student canteen booking validated Successfully.");
        unset($_SESSION['pending_booking_validate']);
    }

    $pending_bookings = $obj->Requete("SELECT * FROM abonnements a , cantine c , etudiant e , classe cls, inscription i WHERE a.ID_MOIS = c.ID_MOIS AND i.MATRICULE = e.MATRICULE AND a.MATRICULE = i.MATRICULE AND cls.ID_CLASSE = i.ID_CLASSE AND a.ID_ANNEE = '".getAnnee(0)['ID_ANNEE']."' AND a.STATUT = 'pending'");

    if (isset($_POST["validate_book"])) {
        extract($_POST);

        $requete = $obj->Requete("UPDATE `abonnements` SET DATE_FIN_ABONNEMENT = DATE_ADD('".$start_date."' , INTERVAL '".$nb_days."' DAY) , NUMBER_DAYS = '". $nb_days ."' , COST_FEES = '". $cost_fees ."' , STATUT = 'actif' WHERE ID_ABONNEMENT = '".$id_abonnements."'");
        if ($requete) {
            $_SESSION['pending_booking_validate'] = 1;
            echo("<script>location.assign('../refresh.html')</script>");        
        }
    }
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Pending for validation
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover table-responsive table-responsive-md table-responsive-lg" id="table">
                        <thead>
                            <tr>
                                <th class="text-truncate">Action</th>
                                <th class="text-truncate">Classroom</th>
                                <th class="text-truncate">Matricule</th>
                                <th class="text-truncate">Last Name</th>
                                <th class="text-truncate">First Name</th>
                                <th class="text-truncate">Month</th>
                                <th class="text-truncate">Number of days</th>
                                <th class="text-truncate">Submission Date</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                                while($pending_booking = $pending_bookings->fetch()):
                            ?>
                            <tr>
                                <td class="text-truncate">
                                    <div class="btn-group">
                                        <a class="btn border text-truncate" role="button" data-toggle="modal" data-target="#edit_booking" onclick="get_editable_data(<?= htmlspecialchars(json_encode($pending_booking['ID_ABONNEMENT'])) ?>)"><i class="fas fa-pencil-alt text-dark"></i></a>
                                        <a class="btn border text-truncate" role="button" data-toggle="modal" data-target="#delete_pending_book" onclick="getTheId(<?= htmlspecialchars(json_encode($pending_booking['ID_ABONNEMENT'])) ?> , 'id_abonnement')"><i class="fas fa-trash text-danger"></i></a>
                                    </div>
                                </td>
                                <td class="text-truncate"><?= $pending_booking["NOM_CLASSE"] ?></td>
                                <td class="text-truncate"><?= $pending_booking["MATRICULE"] ?></td>
                                <td class="text-truncate"><?= $pending_booking["PRENOM"] ?></td>
                                <td class="text-truncate"><?= $pending_booking["NOM"] ?></td>
                                <td class="text-truncate"><?= $pending_booking["MOIS"] ?></td>
                                <td class="text-truncate"><?= $pending_booking["NUMBER_DAYS"] ?></td>
                                <td class="text-truncate"><?= date("M d, Y | H:i:s",strtotime($pending_booking["DATE_ABONNEMENT"]))?></td>
                            </tr>
                            <?php
                                endwhile;
                            ?>
                        </tbody>

                        <tfoot>
                            <tr>
                                <th class="text-truncate">Action</th>
                                <th class="text-truncate">Classroom</th>
                                <th class="text-truncate">Matricule</th>
                                <th class="text-truncate">Last Name</th>
                                <th class="text-truncate">First Name</th>
                                <th class="text-truncate">Month</th>
                                <th class="text-truncate">Number of days</th>
                                <th class="text-truncate">Submission Date</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="edit_booking" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard='false' aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary">
                <h5 class="modal-title text-light">Modify Question</h5>
                <button type="button" onclick="document.location.reload(true)" class="close text-light" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" id="edit_booking_form" name="edit_booking_form" method="post">
                    
                </form>
            </div>

        </div>
    </div>
</div>


<!-- ------------------------------------------------------ Delete Pending Booking ----------------------------------------------------- -->

<div class="modal fade" id="delete_pending_book" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard='false' aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-danger">
                <h5 class="modal-title text-light">Deletion Confirmation</h5>
                <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="post" id="delete_pending_booking_form">
                    <div class="row">
                        <div class="col-lg-12">
                            Are you sure you want to delete this booking from the booking's list? Remember that this action is irreversible.
                        </div>
                        <input id="id_abonnement" name="id_abonnement" hidden>
                        <div class="col-lg-12 text-center my-3">
                            <input type="button" class="btn btn-outline-dark px-4 rounded-pill" data-dismiss="modal" value="Reset">
                            <input type="button" class="btn btn-outline-danger px-4 rounded-pill" onclick="delete_question_function()" name="confirm_deletion" id="confirm_deletion" value="Delete the booking">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> 

<!-- ---------------------------------------------------------------------------------------------------------------------------- -->


<?php
    include("../footer.php");
?>

<script>
     function getTheId(target_id , id) {  
        $("#"+id).val(target_id);
    }

    function get_editable_data(id){
        $.ajax({
            url: "../../../ajax.php?action=get_pending_booking_editable_data&id_abonnement="+id,
            method: "post",
            data: {
                    'id_abonnement' : $('#'+id).val()
                  },
            dataType: "text",
            success: function (response) {
                $("#edit_booking_form").html(response);
            }
        });
    }
   
   
    function delete_question_function() {  
        $.post("../../../ajax.php?action=delete_pending_booking", $("#delete_pending_booking_form").serialize(),
            function (data, textStatus, jqXHR) {
                console.log(data);
                if(data == 'success')
                {
                    toastr.success("Booking deleted successfully");    
                    setTimeout(function(){ location.reload(true) },2000)
                }
                else
                {
                    toastr.error("An error occured");
                }
            },
        );
    }
</script>