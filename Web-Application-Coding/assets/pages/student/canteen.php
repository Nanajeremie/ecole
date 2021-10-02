<?php
    $title = 'Canteen';
    $breadcrumb = 'View Survey';
    

    include('../../../utilities/QueryBuilder.php');
    include('header.php');
    
    if(isset($_SESSION['cantine_booking_validate']) && $_SESSION['cantine_booking_validate'] == 1)
    {
        alert('success' , "Canteen fees booked Successfully. The secretary will confirm you asap!!!");
        unset($_SESSION['cantine_booking_validate']);
    }

    $obj= new QueryBuilder();

    $canteen_fees = $obj->Requete("SELECT * FROM `cantine` WHERE `PRIX` > 0 AND `ANNEE_SCOLAIRE` = '".getAnnee(0)["ID_ANNEE"]."'");
    $current_date = $obj->Requete("SELECT NOW() as curent_date")->fetch();

    if(isset($_POST["book_this_month"]))
    {
        $matricule = $obj->Requete("SELECT MATRICULE FROM etudiant WHERE ID_USER = '".$_SESSION["ID_USER"]."'")->fetch();
        extract($_POST);
        $requete = $obj->Requete("INSERT INTO `abonnements`(`ID_MOIS`, `MATRICULE`, `ID_ANNEE`, `DATE_ABONNEMENT`, `DATE_DEBUT_ABONNEMENT` , `NUMBER_DAYS`, `STATUT`) VALUES ('$canteen_fee_id' , '".$matricule["MATRICULE"]."' , '".getAnnee(0)["ID_ANNEE"]."' , NOW() , '$start_date' , '$nb_days' , 'pending') ");
        
        if($requete)
        {
            $_SESSION['cantine_booking_validate'] = 1;
            echo("<script>location.assign('../refresh.html')</script>");
        }
       
    }
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="text-bluesky">Book for the canteen</h6>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover table-responsive-lg table-responsive-md table-responsive-sm" id="table">
                        <thead>
                            <tr>
                                <th class="text-truncate">Action</th>
                                <th class="text-truncate">Month</th>
                                <th class="text-truncate">Price</th>
                                <th class="text-truncate">Payment Deadline</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                                while ($canteen_fee = $canteen_fees->fetch()) :
                                    $check = $obj->Requete("SELECT * FROM `abonnements` WHERE `ID_MOIS` = '".$canteen_fee["ID_MOIS"]."' AND `MATRICULE` = (SELECT MATRICULE FROM etudiant WHERE ID_USER = '".$_SESSION["ID_USER"]."') AND `ID_ANNEE` = '".getAnnee(0)["ID_ANNEE"]."'")->fetch();
                            ?>
                                <tr>
                                    <td class="text-truncate">
                                        <?php
                                            if($check == false) :
                                                if ($current_date["curent_date"] > $canteen_fee["DATE_LIMITE_PAIEMENT"] ) :
                                        ?>
                                            <div class="btn btn-danger btn-sm btn-md btn-lg text-truncate">Out of date <i class="fas fa-hourglass-end px-2"></i></div>
                                            <?php
                                                else:
                                            ?>
                                            <a class='btn btn-outline-success btn-sm btn-md btn-lg text-truncate' role='button' data-toggle="modal" data-target="#cannten_booking_modal" onclick="get_canteen_fee(<?= htmlspecialchars(json_encode($canteen_fee['ID_MOIS'])) ?>)">Book now <i class="fas fa-utensils px-2"></i></a>
                                        <?php
                                                endif;
                                            else:
                                                if($check["STATUT"] == 'pending'):
                                        ?>
                                                <div class="btn btn-warning btn-sm btn-md btn-lg text-truncate text-white">Pending for approval <i class="fas fa-bell px-2"></i></div>
                                            <?php
                                                elseif ($check["STATUT"] == 'actif'):
                                            ?>
                                                <div class="btn btn-primary btn-sm btn-md btn-lg text-truncate">Booked Successfully <i class="fas fa-check px-2"></i></div>
                                        <?php
                                                endif;
                                            endif;
                                        ?> 
                                    </td>
                                    <td class="text-truncate"><?= $canteen_fee["MOIS"] ?></td>
                                    <td class="text-truncate"><?= $canteen_fee["PRIX"] ?></td>
                                    <td class="text-truncate"><?= date("M d, Y",strtotime($canteen_fee["DATE_LIMITE_PAIEMENT"])) ?></td>
                                </tr>
                            <?php
                                endwhile;
                            ?>
                        </tbody>

                        <tfoot>
                            <tr>
                                <th>Action</th>
                                <th>Month</th>
                                <th>Price</th>
                                <th>Payment Deadline</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="cannten_booking_modal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard='false' aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary">
                <h6 class="modal-title text-light">Canteeen booking</h6>
                <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="post" id="canteen_fee_modal_content">
                    
                </form>
            </div>
        </div>
    </div>
</div>

<?php
    include("../footer.php");
?>

<script>
    function get_canteen_fee(id)
    {
        $.ajax({
            type: "post",
            url: "../../../ajax.php?action=get_canteen_fee&fee_id="+id,
            data: {
                    'month_id' : $('#'+id).val()
                    },
            dataType: "text",
            success: function (response) {
                $("#canteen_fee_modal_content").html(response);
             
            }
        });
    }
    
</script>