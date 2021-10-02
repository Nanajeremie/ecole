<?php
    $title = 'Canteen Management';
    $breadcrumb = 'Fees';

    include("../../../utilities/QueryBuilder.php");
    include("./header.php");

    if(isset($_SESSION['cantine_fees_creat']) && $_SESSION['cantine_fees_creat'] == 1)
    {
        alert('success' , "Canteen fees created Successfully.");
        unset($_SESSION['cantine_fees_creat']);
    }

    if(isset($_SESSION['cantine_fees_upt']) && $_SESSION['cantine_fees_upt'] == 1)
    {
        alert('success' , "Canteen fee updated Successfully.");
        unset($_SESSION['cantine_fees_upt']);
    }

    $month = ['January' , 'February' , 'March' , 'April' , 'May' , 'June' , 'July' , 'August' , 'September' , 'October' , 'November' , 'December'];
    
    if (isset($_POST['create_month_fees'])) 
    {
        extract($_POST);
        $count = [];
        for ($i=0; $i < count($month); $i++) 
        { 
            $requete = $obj->Requete("INSERT INTO `cantine`(`MOIS`, `PRIX`, `ANNEE_SCOLAIRE`, `DATE_CREATION`, `DATE_LIMITE_PAIEMENT` , `COST_PER_DAY`) VALUES ('". $month[$i] ."' , '". $_POST[$month[$i]] ."' , '".getAnnee(0)['ID_ANNEE']."' , NOW() , '".$_POST['deadline'.$month[$i]]."' , '". $_POST['school_fees']."')");
            if ($requete) {
                $count[] = 1;
            }
        }
        if(count($count) == 12)
        {
            $_SESSION['cantine_fees_creat'] = 1;
            echo("<script>location.assign('../refresh.html')</script>");
        }
    }

    if(isset($_POST["update_month_fees"]))
    {
        extract($_POST);
        $requete = $obj->Update("cantine" , ['PRIX' , 'DATE_LIMITE_PAIEMENT'] , [$month_fees , $payment_limit] , ['ID_MOIS'=> $canteen_fee_id]);
        if ($requete) {
            $_SESSION['cantine_fees_upt'] = 1;
            echo("<script>location.assign('../refresh.html')</script>");        
        }
    }

    $canteen_fees = $obj->Requete("SELECT * FROM `cantine` WHERE ANNEE_SCOLAIRE = '".getANNEE(0)['ID_ANNEE']."'");
?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-6"></div>
                            <div class="col-lg-6 text-right">
                                <a role="button" class="btn btn-dark rounded-pill" data-toggle="modal" data-target="#canteen_fees">New Fees</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="table" class="table table-bordered table-hover table-responsive-lg table-responsive-md table-responsive-sm">
                            <thead>
                                <tr>
                                    <th class="text-truncate">Action</th>
                                    <th class="text-truncate">Month</th>
                                    <th class="text-truncate">Amount</th>
                                    <th class="text-truncate">Deadline</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    while($canteen_fee = $canteen_fees->fetch()):
                                ?>
                                    <tr>
                                        <td>
                                            <a class='btn btn-outline-dark text-truncate' role='button' data-toggle="modal" data-target="#update_month_fees" onclick="get_editable_canteen_fees(<?= htmlspecialchars(json_encode($canteen_fee['ID_MOIS'])) ?>)"><i class="fas fa-pen"></i></a>
                                        </td>
                                        <td class="text-truncate"><?= $canteen_fee['MOIS'] ?></td>
                                        <td class="text-truncate"><?= $canteen_fee['PRIX'] .' fcfa' ?></td>
                                        <td class="text-truncate"><?= date("M d, Y",strtotime($canteen_fee['DATE_LIMITE_PAIEMENT'])) ?></td>
                                    </tr>
                                <?php
                                    endwhile;
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th class="text-truncate">Action</th>
                                    <th class="text-truncate">Month</th>
                                    <th class="text-truncate">Amount</th>
                                    <th class="text-truncate">Deadline</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!----------------------------------------------------- Update Month Fees ------------------------------------------------->
                <div class="modal fade" id="update_month_fees" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard='false' aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-gradient-primary">
                                <h5 class="modal-title text-light">Update Month Fees</h5>
                                <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="" method="post" id="update_canteen_fee">
                                    
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!----------------------------------------------------- Month Fees ------------------------------------------------->
                <div class="modal fade" id="canteen_fees" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard='false' aria-hidden="true">
                    <div class="modal-dialog modal-xl" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-gradient-primary">
                                <h5 class="modal-title text-light">Create Month Fees</h5>
                                <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="" method="post">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row mx-lg-2 py-2">
                                                <div class="col-lg-4 pb-lg-4">
                                                    <label for="school_fees">Cost per day</label>
                                                    <input type="number" name="school_fees" id="school_fees" class="form-control" placeholder="Ex : 250" min="0" required>
                                                </div>
                                                <div class="col-lg-8"></div>
                                                <?php
                                                    for ($i=0; $i < count($month); $i++) 
                                                    { 
                                                ?>
                                                    
                                                        <!-------------------------------------- Month Fees --------------------------------------->
                                                        <div class="col-lg-4 pb-lg-4">
                                                            <label for="<?=$month[$i]?>"><?=$month[$i]?><span class="text-danger"> * </span></label>
                                                            <input type="number" min="0" name="<?=$month[$i]?>" id="<?=$month[$i]?>" class="form-control" placeholder="Price" required>
                                                            <input type="text" min="0" name="<?='deadline'.$month[$i]?>" id="<?='deadline'.$month[$i]?>" onfocus="(this.type='datetime-local')" class="form-control my-2" placeholder="Deadline" required>
                                                        </div>
                                                    
                                                <?php
                                                    }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12 text-center my-3">
                                                <button type="reset" class="btn btn-outline-dark px-4 rounded-pill" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-outline-primary px-4 rounded-pill" name="create_month_fees" id="create_month_fees">Create Fees</button>
                                            </div>
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
    function get_editable_canteen_fees(id)
    {
        $.ajax({
            type: "post",
            url: "../../../ajax.php?action=get_editable_canteen_fee&canteen_fee_id="+id,
            data: {
                    'question_id' : $('#'+id).val()
                    },
            dataType: "text",
            success: function (response) {
                $("#update_canteen_fee").html(response);
            }
        });
    }
</script>