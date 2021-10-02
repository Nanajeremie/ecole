<?php

    include '../../../utilities/QueryBuilder.php';

    $title = 'Library';
    $breadcrumb = 'Booking';

    $obj = new QueryBuilder();

    $min_date = $obj->Requete('SELECT NOW() as dat')->fetch();
    $min_date = $min_date['dat'];

    $max_date = $obj->Requete('SELECT NOW() + INTERVAL 14 DAY as dat')->fetch();
    $max_date = $max_date['dat'];
    $book_students = $obj->Select('louer', [], ['STATUT'=>'active']);

    if(isset($_SESSION['new_loan']) && $_SESSION['new_loan'] == 1)
    {
        alert('success' , "Booking made Successfully.");
        unset($_SESSION['new_loan']);
    }

    if(isset($_SESSION['end_loan']) && $_SESSION['end_loan'] == 1)
    {
        alert('success' , "Book gived back successfully.");
        unset($_SESSION['end_loan']);
    }

    if(isset($_SESSION['del_loan']) && $_SESSION['del_loan'] == 1)
    {
        alert('success' , "Booking deleted successfully.");
        unset($_SESSION['del_loan']);
    }

    if(isset($_SESSION['reloan']) && $_SESSION['reloan'] == 1)
    {
        alert('success' , "Return date updated successfully.");
        unset($_SESSION['reloan']);
    }

    //-------------------------------------------------- New Book Loan -------------------------------------------------

    if (isset($_POST['upd_booking'])) 
    {
        $verif = $obj->Select('louer', [], array("MATRICULE" => $_POST["matricule"], "CODE_LIVRE" => $_POST['code']));

        if (!is_object($verif)) {
            $query = $obj->Insert('louer', array("MATRICULE", "CODE_LIVRE", "DATE_EMPRUNT", "DATE_REMISE", "ETAT_EMPRUNT", "STATUT"),
                array($_POST['matricule'], $_POST['code'], $_POST['loan_date'], $_POST['return_date'], $_POST['initial_state'] , 'active'));
            
            if ($query) 
            {
                $_SESSION['new_loan'] = 1;
                echo("<script>location.assign('../refresh.html')</script>");
            }
        }
    }

    //-------------------------------------------------- Delete Book Loan -------------------------------------------------

    if (isset($_POST['delete_book'])) 
    {
        $query = $obj->Delete('louer', array('ID_LOUER' => $_POST['del_book']));

        if ($query) 
        {
            $_SESSION['del_loan'] = 1;
            echo("<script>location.assign('../refresh.html')</script>");
        }    
    }

    //-------------------------------------------------- Update Book Loan -------------------------------------------------

    if (isset($_POST['update_booking'])) 
    {
        if ($_POST['return_date'] == '') 
        {
            $query = $obj->Update('louer', array("ETAT_RETOUR"), array($_POST['return_state']), array('ID_LOUER' => $_POST['ide']));
            $query1 = $obj->Update('documents', array('BOOK_STATUS' , 'STATUT') , array($_POST['return_state'] , 'free') , array('CODE_LIVRE' => $_POST['code']));
            
            if ($query && $query1) {
                $_SESSION['end_loan'] = 1;
                echo("<script>location.assign('../refresh.html')</script>");
            }
        }

        //---------------------------------------------- Ask for reloan -------------------------------------------------

        else 
        {
            $query = $obj->Update('louer', array("DATE_REMISE"), array($_POST['return_date']), array('ID_LOUER' => $_POST['ide']));

            if ($query) 
            {
                $_SESSION['reloan'] = 1;
                echo("<script>location.assign('../refresh.html')</script>");
            }
        }
    }

    include('./header.php');

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card wow fadeIn">

                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            Books Booking
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 text-right">
                            <a role="button" data-toggle="modal" data-target="#new_booking"
                               class="btn btn-dark rounded-pill">New booking</a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <table id='table' class="table table-bordered table-hover table-responsive-lg table-responsive-md table-responsive-sm">
                        <thead>
                            <tr>
                                <th class="text-truncate">Action</th>
                                <th class="text-truncate">Matricule</th>
                                <th class="text-truncate">Book Code</th>
                                <th class="text-truncate">Loan Date</th>
                                <th class="text-truncate">Return Date</th>
                                <th class="text-truncate">Status</th>
                                <th class="text-truncate">Return Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php 
                                try 
                                {
                                    while ($book_student = $book_students->fetch()): 
                            ?>
                                <tr>
                                    <td class="text-truncate">
                                        <a onclick="book_up(this.id) " id="<?= $book_student['ID_LOUER'] ?>"
                                        class='btn btn-outline-dark' role='button' data-toggle="modal"
                                        data-target="#update_booking"><i class='fas fa-pen'></i></a>
                                        <a title="<?= $book_student['ID_LOUER'] ?>"
                                        onclick="document.getElementById('del_book').value=this.title"
                                        class='btn btn-outline-danger' role='button' data-toggle="modal"
                                        data-target="#delete_booking"><i class='fas fa-trash'></i></a>
                                    </td>
                                    <td class="text-truncate"><?= $book_student['MATRICULE'] ?></td>
                                    <td class="text-truncate"><?= $book_student['CODE_LIVRE'] ?></td>
                                    <td class="text-truncate"><?= $book_student['DATE_EMPRUNT'] ?></td>
                                    <td class="text-truncate"><?= $book_student['DATE_REMISE'] ?></td>
                                    <td class="text-truncate"><?= $book_student['ETAT_EMPRUNT'] ?></td>
                                    <td class="text-truncate"><?= empty($book_student['ETAT_RETOUR']) ? '<div class="text-primary">Currently reading by a student</div>' : $book_student['ETAT_RETOUR'] ?></td>
                                </tr>
                            <?php 
                                    endwhile;
                                } 
                                catch (\Throwable $th) 
                                {
                                    
                                }
                            ?>
                        </tbody>

                        <tfoot>
                            <tr>
                                <th class="text-truncate">Action</th>
                                <th class="text-truncate">Matricule</th>
                                <th class="text-truncate">Book Code</th>
                                <th class="text-truncate">Loan Date</th>
                                <th class="text-truncate">Return Date</th>
                                <th class="text-truncate">Status</th>
                                <th class="text-truncate">Return Status</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- --------------------------------------------- New Booking Modal --------------------------------------------------- -->

<div class="modal fade" id="new_booking" data-backdrop="static" data-keyboard='false' tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md mr-0 mt-0" role="document">
        <div class="modal-content ">

            <div class="modal-header bg-gradient-primary">
                <h5 class="modal-title text-light">Book Booking</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body py-lg-5">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <form action="" method="post">
                                <div class="row">
                                    <!-- ------------------------------------------------------------------------------------------------------- -->
                                    <div class="col-lg-12 text-left">
                                        <h6 class="font-weight-bold text-bluesky">Student Information</h6>
                                        <hr class="bg-gradient-primary">
                                    </div>

                                    <!-------------------------------------- MATRICULE --------------------------------------->
                                    <div class="col-lg-6 py-lg-3 py-2">
                                        <div class="form-group">
                                            <label for="matricule">Matricule <span
                                                        class="text-danger"> * </span></label>
                                            <input oninput="contro(this.id,this.value,'MATRICULE','etudiant')" class="form-control"
                                                   type="text" name="matricule" id="matricule" required>

                                        </div>
                                    </div>

                                    <!-------------------------------------- BOOK CODE --------------------------------------->
                                    <div class="col-lg-6 py-lg-3 py-2">
                                        <div class="form-group">
                                            <label for="code">Book Code <span class="text-danger"> * </span></label>
                                            <input oninput="contro(this.id,this.value,'CODE_LIVRE','documents')"
                                                   class="form-control" type="text" name="code" id="code" required>
                                        </div>
                                    </div>


                                    <!-- ------------------------------------------------------------------------------------------------------- -->
                                    <div class="col-lg-12 text-left mt-2">
                                        <h6 class="font-weight-bold text-bluesky">Booking Period</h6>
                                        <hr class="bg-gradient-primary">
                                    </div>

                                    <!-------------------------------------- LOAN DATE --------------------------------------->
                                    <div class="col-lg-6 py-lg-3 py-2">
                                        <div class="form-group">
                                            <label for="loan_date">Start Date <span
                                                        class="text-danger"> * </span></label>
                                            <input onchange="document.getElementById('return_date').value=this.value"
                                                   class="form-control" type="date"
                                                   value="<?=substr($min_date, 0, 10)?>"
                                                   name="loan_date" id="loan_date"
                                                   required readonly>
                                        </div>
                                    </div>

                                    <!-------------------------------------- Return DATE --------------------------------------->
                                    <div class="col-lg-6 py-lg-3 py-2">
                                        <div class="form-group">
                                            <label for="return_date">Return Date <span
                                                        class="text-danger">  </span></label>
                                            <input class="form-control" type="date"
                                                   min="<?= substr($max_date, 0, 10) ?>"
                                                   value="<?= substr($max_date, 0, 10) ?>" name="return_date"
                                                   id="return_date" readonly>
                                        </div>
                                    </div>


                                    <!-- ------------------------------------------------------------------------------------------------------- -->
                                    <div class="col-lg-12 text-left mt-2">
                                        <h6 class="font-weight-bold text-bluesky">Book Status</h6>
                                        <hr class="bg-gradient-primary">
                                    </div>

                                    <!-------------------------------------- Book Initial State --------------------------------------->
                                    <div class="col-lg-6 py-lg-3 py-2">
                                        <div class="form-group">
                                            <label for="initial_state">Book Initial Status <span
                                                        class="text-danger"> * </span></label>
                                            <select name="initial_state" id="initial_state" class="form-control"
                                                    required>
                                                <option value="0">Select the status</option>
                                                <option value="New">New</option>
                                                <option value="Good">Good</option>
                                                <option value="Old">Old</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-12 text-right my-3">
                                        <button type="reset" class="btn btn-outline-dark px-4 rounded-pill"
                                                data-dismiss="modal">Close
                                        </button>
                                        <button type="submit" class="btn btn-outline-primary px-4 rounded-pill"
                                                name="upd_booking">Book Now
                                        </button>
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


<!-- --------------------------------------------- Udpate Booking Modal ------------------------------------------------ -->

<div class="modal fade" id="update_booking" data-backdrop="static" data-keyboard='false' tabindex="-1" role="dialog"
     aria-hidden="true">
    <div class="modal-dialog modal-md mr-0 mt-0" role="document">
        <div class="modal-content ">
            <div class="modal-header bg-gradient-primary">
                <h5 class="modal-title text-light">Update Booking</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body py-lg-5">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <form action="" method="post">
                                <div class="row" id="update_part">

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!----------------------------------------------------- Delete Booking Modal ------------------------------------------------->

<div class="modal fade" id="delete_booking" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard='false'
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header bg-gradient-danger">
                <h5 class="modal-title text-light">Delete a booking</h5>
                <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form action="" method="post">
                    <div class="row">
                        <div class="col-lg-12">
                            Are you sure you want to delete this booking ? Remember that this action is irreversible.
                        </div>
                        <input hidden name="del_book" id="del_book" value=""/>
                        <div class="col-lg-12 text-center my-3">
                            <button type="reset" class="btn btn-outline-dark px-4 rounded-pill" data-dismiss="modal">
                                Close
                            </button>
                            <button type="submit" class="btn btn-outline-danger px-4 rounded-pill" name="delete_book"
                                    id="delete_book">Delete the Booking
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> 


<?php

    include('../footer.php');

?>

<script>
    function contro(id, value, colonne,table) {
        if (id.length >= 1) {

            $.post(
                '../../../ajax.php', {
                    ver: 'ok',
                    ref: id,
                    ide: value,
                    colonne: colonne,
                    table:table,
                }, function (cont) {
                    // $('').html();
                    if (cont) {
                        document.getElementById(id).style.borderColor = 'green';
                    } else {
                        document.getElementById(id).style.borderColor = 'red';

                    }
                });
        }
    }

    function book_up(id) {
        $.post(
            '../../../ajax.php', {
                book_ret: 'ok',
                id_louer: id,
            }, function (content) {
                $('#update_part').html(content);
            });

    }

    function time_book(value) {

        if (value != ''){
            document.getElementById('back_book').style.display="none";
        }

    }
</script>
