<?php
     $title = 'Library';
     $breadcrumb = 'Book Booking';
 
     include('../../../utilities/QueryBuilder.php');
     include('header.php');
     
    $obj= new QueryBuilder();

    if(isset($_SESSION['booking_validation']) && $_SESSION['booking_validation'] == 1)
    {
        alert('success' , "Booking request sent Successfully.");
        unset($_SESSION['booking_validation']);
    }

    if (isset($_POST["btn_loan"])) {
        extract($_POST);

        $requete = $obj->Requete("INSERT INTO `louer`(`MATRICULE`, `CODE_LIVRE`, `DATE_EMPRUNT`, `DATE_REMISE`, `ETAT_EMPRUNT` , `STATUT` , `ID_ANNEE`) VALUES((SELECT MATRICULE FROM etudiant WHERE ID_USER = '".$_SESSION["ID_USER"]."') , '$code_livre' , NOW() , DATE_ADD(NOW() , INTERVAL 2 WEEK) , (SELECT BOOK_STATUS FROM documents WHERE CODE_LIVRE = '".$code_livre."') , 'unactive' , '".getAnnee(0)['ID_ANNEE']."')");
        $update = $obj->Update("documents" , ['STATUT'] , ['pending_for_confirmation'] , ['CODE_LIVRE'=>$code_livre]);
        if ($requete && $update) {
            $_SESSION['booking_validation'] = 1;
            echo("<script>location.assign('../refresh.html')</script>");
        }
    }   
    

?>

<style>
    .notify_badge{
        position: absolute;
        /* top: 10px; */
        font-family: 'Times New Roman';
        background-color: white;
        text-align: center;
        border-radius: 30px;
        border: 1px solid green;
        box-shadow: rgba(0, 0, 0, 0.55) 15px 15px 15px;
        padding: 5px 10px;
        font-size: 18px;
    }
</style>

<div class="container-fluid"> 
    <div class="row">

        <!--==================== Barre de recherche ====================-->

        <div class="col-12 my-3">
            <div class="text-left text-sm-left text-lg-right">
                <form method="get" class="wow slideInDown">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-12 col-xs-12">
                            <div class="input-group">
                                <input type="search" class="form-control mr-2" placeholder="Keyword" name="inputSearch">
                                <input type="submit" class="btn btn-primary" value="Search" name="fileSearch">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!--====================Book  Detail ====================-->

        <div class="col-lg-12">
            <div class="card wow slideInRight">
                <div class="card-header">
                    <div class="row ">
                        <div class="col-6 text-primary align-self-center">
                           <h6>BIT  Books</h6>
                        </div>
                        <div class="col-6 text-right align-self-center">
                            <a href="library_historic.php" role="button" class="text-white text-truncate btn btn-dark">Booking logs</a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <?php 

                        $book_request1 = $obj->Requete("SELECT * , COUNT(TITRE) as total FROM documents d , department dep WHERE dep.ID_DEPARTEMENT = d.DEPARTMENT AND d.STATUT = 'free' GROUP BY (d.TITRE)");

                        while($result = $book_request1->fetch())
                        {
                            $requete_book = $obj->Requete("SELECT d.STATUT FROM louer l , documents d WHERE d.CODE_LIVRE = l.CODE_LIVRE AND d.TITRE = '".$result["TITRE"]."' AND l.MATRICULE = (SELECT MATRICULE FROM etudiant WHERE ID_USER = '".$_SESSION["ID_USER"]."')")->fetch();
                    ?>
                        <!-- DESKTOP VIEW AND TABLET VIEW -->

                            <div class="card d-none d-lg-block">
                                <div class="card-body"> 
                                    <div class="row">
                                        <div class="col-lg-2 col-md-2 text-center my-auto">
                                            <div class="card-img">
                                                <span class="notify_badge">
                                                    <?php
                                                        if ($result["total"]>0) {
                                                            echo('<span class="text-success">Available</span>');
                                                        }
                                                        else
                                                        {
                                                            echo('<span class="text-danger">Out of stock</span>');
                                                        }
                                                    ?>
                                                </span>
                                                <img src="<?='../../Bookcover/'.$result["PICTURE"]?>" width="100%" height="150px">
                                            </div>
                                        </div>

                                        <div class="col-lg-8 col-md-8 py-lg-0">
                                            <h5 class="text-bluesky"><?= $result['TITRE'];?></h5>
                                            <span><span class="font-weight-bold">Auteur : </span><?=$result['AUTHEUR'];?></span><br>   
                                            <span><span class="font-weight-bold">Edition Date : </span><?=$result['DATE_EDITION'];?></span><br>
                                            <span class="my-2"><span class="font-weight-bold">Department : </span><?=$result['NOM_DEPARTEMENT'];?></span>
                                            <p class="text-justify"><span class="font-weight-bold">Overview : </span><?= $result['DESCRIPTION'] ?></p>
                                        </div>

                                        <div class="col-lg-2 text-right">
                                            <form action="" method="post">
                                            <?php
                                                if($requete_book != false):
                                                    if ($requete_book["STATUT"] == "pending_for_confirmation"):
                                            ?>
                                                    <div class="btn btn-warning btn-sm btn-md btn-lg">Wait for confirmation</div>
                                                <?php
                                                    elseif ($requete_book["STATUT"] == "in_location"):
                                                ?>
                                                    <div class="btn btn-success btn-sm btn-md btn-lg">You currently have this book</div>
                                                <?php
                                                    else:
                                                ?>
                                                    <button type="button" class="btn btn-primary rounded-pill px-4" name="book_btn"  data-toggle="modal" data-target="#loan_book" onclick="get_book(<?= htmlspecialchars(json_encode($result['CODE_LIVRE'])) ?>)">Loan</button>
                                                <?php
                                                    endif;
                                                else:
                                                ?>
                                                <button type="button" class="btn btn-primary rounded-pill px-4" name="book_btn"  data-toggle="modal" data-target="#loan_book" onclick="get_book(<?= htmlspecialchars(json_encode($result['CODE_LIVRE'])) ?>)">Loan</button>
                                            <?php
                                                endif;
                                            ?>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <!-- MOBILE VIEW -->

                            <div class="card d-md-none d-lg-none d-sm-block my-3">
                                <div class="card-header bg-white">
                                    <div class="card-img text-center">
                                        <span class="notify_badge">
                                            <?php
                                                if ($result["total"]>0) {
                                                    echo('<span class="text-success">Available</span>');
                                                }
                                                else
                                                {
                                                    echo('<span class="text-danger">Out of stock</span>');
                                                }
                                            ?>
                                        </span>
                                    
                                        <img src="<?='../../Bookcover/'.$result["PICTURE"]?>" width="100%" height="275px">
                                    </div>
                                    
                                </div>
                                <div class="card-body">
                                    <div class="col-lg-12">
                                        <h5 class="text-bluesky my-2"><?= $result['TITRE'];?></h5>
                                        <span class="my-2"><span class="font-weight-bold">Auteur : </span><?=$result['AUTHEUR'];?></span>
                                        <span class="my-2"><span class="font-weight-bold">Edition Date : </span><?=$result['DATE_EDITION'];?></span>
                                        <span class="my-2"><span class="font-weight-bold">Department : </span><?=$result['NOM_DEPARTEMENT'];?></span>
                                        <p class="text-justify my-2"><span class="font-weight-bold">Overview : </span><?= $result['DESCRIPTION'] ?></p>
                                        
                                        <form action="" method="post">
                                            <button type="button" class="btn btn-primary rounded-pill px-4" name="book_btn"  data-toggle="modal" data-target="#loan_book" onclick="get_book(<?= htmlspecialchars(json_encode($result['CODE_LIVRE'])) ?>)">Loan</button>
                                        </form>
                                    </div>
                                </div>
                            </div>  

                        <!-- ----------  -->
                    <?php 
                        }        
                    ?>
                </div>
            </div>
        </div>
    </div>

<!--==================== Loan modal ====================-->
<div class="modal fade mt-2" id="loan_book" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard='false' aria-hidden="true">
    <div class="modal-dialog modal-lg mt-0" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary">
                <h6 class="modal-title">Loan a book</h6>
                <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                
                <form action="" method="post">
                    <div class="row my-4" id="booking_confirmation">
                        
                    </div>
                </form>
               
            </div>
        </div>
    </div>
</div>


<?php
    include("../footer.php");
?>

<script>
    function get_book(id)
    {
        $.ajax({
            type: "post",
            url: "../../../ajax.php?action=get_booking_book_id&book_id="+id,
            data: {
                    'book_id' : $('#'+id).val()
                    },
            dataType: "text",
            success: function (response) {
                $("#booking_confirmation").html(response);
             
            }
        });
    }
</script>