<?php

    $title = 'Library';
    $breadcrumb = 'Booking pending confirmation';

    include('../../../utilities/QueryBuilder.php');
    include('header.php');
    
    $obj= new QueryBuilder();

    if(isset($_SESSION['booking_validation']) && $_SESSION['booking_validation'] == 1)
    {
        alert('success' , "Booking validated Successfully.");
        unset($_SESSION['booking_validation']);
    }

    if (isset($_POST["validate_loan"])) 
    {
        extract($_POST);
        $date = $obj->Requete("SELECT NOW() as current_dates , DATE_ADD(NOW() , INTERVAL 2 WEEK) as return_date")->fetch();

        $requete = $obj->Update("louer" , ['DATE_EMPRUNT' , 'DATE_REMISE' , 'STATUT'] , [$date["current_dates"] , $date["return_date"] , 'active'] , ['ID_LOUER'=>$id_louer]);
        $update = $obj->Update("documents" , ['STATUT'] , ['in_location'] , ['CODE_LIVRE'=>$code_livre]);

        if ($requete && $update) 
        {
            $_SESSION['booking_validation'] = 1;
            echo("<script>location.assign('../refresh.html')</script>");
        }
    }

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">

                <div class="card-header">
                    <h6 class="text-text-bluesky">Pending for confirmation</h6>
                </div>

                <div class="card-body">
                    <table class="table table-bordered table-hover table-responsive-lg table-responsive-md table-responsive-sm" id="table">
                        <thead>
                            <tr>
                                <th class="text-truncate">Action</th>
                                <th class="text-truncate">Matricule</th>
                                <th class="text-truncate">Full Name</th>
                                <th class="text-truncate">Book Code</th>
                                <th class="text-truncate">Book Title</th>
                                <th class="text-truncate">Loan Date</th>
                                <th class="text-truncate">Book Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                                $pending_bookings = $obj->Requete("SELECT * FROM louer l , documents d WHERE d.CODE_LIVRE = l.CODE_LIVRE AND l.STATUT = 'unactive' AND l.ID_ANNEE = '".getAnnee(0)["ID_ANNEE"]."'");
                                while ($pending_booking = $pending_bookings->fetch()):
                                    $etudiant = $obj->Requete("SELECT NOM , PRENOM FROM etudiant WHERE MATRICULE = '".$pending_booking["MATRICULE"]."'")->fetch();
                            ?>
                                    <tr>
                                        <td class="text-truncate">
                                            <div class="btn-group">
                                            <a onclick="get_book(<?= htmlspecialchars(json_encode($pending_booking['ID_LOUER'])) ?>)" class='btn btn-outline-dark' role='button' data-toggle="modal" data-target="#booking_confirmation_modal"><i class='fas fa-pen'></i></a>
                                            <a onclick="getTheId(<?= htmlspecialchars(json_encode($pending_booking['ID_LOUER'])) ?> , 'id_louer')" class='btn btn-outline-danger' role='button' data-toggle="modal" data-target="#delete_pending_booking"><i class='fas fa-trash'></i></a>
                                            </div>
                                        </td>
                                        <td class="text-truncate"><?= $pending_booking["MATRICULE"] ?></td>
                                        <td class="text-truncate"><?= $etudiant["PRENOM"].' '.$etudiant["NOM"] ?></td>
                                        <td class="text-truncate"><?= $pending_booking["CODE_LIVRE"] ?></td>
                                        <td class="text-truncate"><?= $pending_booking["TITRE"] ?></td>
                                        <td class="text-truncate"><?= $pending_booking["DATE_EMPRUNT"] ?></td>
                                        <td class="text-truncate"><?= $pending_booking["BOOK_STATUS"] ?></td>
                                    </tr>
                            <?php
                                endwhile;
                            ?>
                        </tbody>

                        <tfoot>
                            <tr>
                                <th>Action</th>
                                <th>Matricule</th>
                                <th>Full Name</th>
                                <th>Book Code</th>
                                <th>Book Title</th>
                                <th>Loan Date</th>
                                <th>Book Status</th>
                            </tr>
                        </tfoot>
                    </table>   
                </div>
                
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="booking_confirmation_modal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard='false' aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary">
                <h5 class="modal-title text-light">Validate Booking</h5>
                <button type="button" onclick="document.location.reload(true)" class="close text-light" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" name="booking_confirmation_form" method="post">
                    <div class="row" id="booking_confirmation_form">
                    
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<!-- ------------------------------------------------------ Delete question ----------------------------------------------------- -->

<div class="modal fade" id="delete_pending_booking" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard='false' aria-hidden="true">
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
                            Are you sure you want to delete this booking from the books booking's list? Remember that this action is irreversible.
                        </div>
                        <input id="id_louer" name="id_louer" hidden>
                        <div class="col-lg-12 text-center my-3">
                            <input type="button" class="btn btn-outline-dark px-4 rounded-pill" data-dismiss="modal" value="Reset">
                            <input type="button" class="btn btn-outline-danger px-4 rounded-pill" onclick="delete_booking_function()" name="confirm_deletion" id="confirm_deletion" value="Delete the booking">
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

    function getTheId(target_id , id) 
    {  
        $("#"+id).val(target_id);
    }

    function get_book(id)
    {
        $.ajax({
            type: "post",
            url: "../../../ajax.php?action=validate_booking_book_id&id_louer="+id,
            data: {
                    'book_id' : $('#'+id).val()
                  },
            dataType: "text",
            success: function (response) 
            {
                $("#booking_confirmation_form").html(response);
            }
        });
    }

    function delete_booking_function() 
    {  
        $.post("../../../ajax.php?action=delete_book_pending_booking", $("#delete_pending_booking_form").serialize(),
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