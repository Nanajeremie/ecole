<?php
    include '../../../utilities/QueryBuilder.php';
    $title = 'Library';
    $breadcrumb = 'Books';
    $obj= new QueryBuilder();

    $depat=$obj->Select('department',[],[]);
    $books=$obj->Select('documents',[],[]);

    if(isset($_SESSION['add_doc']) && $_SESSION['add_doc'] == 1)
    {
        alert('success' , "Book added Successfully.");
        unset($_SESSION['add_doc']);
    }
    
    if(isset($_SESSION['update_doc']) && $_SESSION['update_doc'] == 1)
    {
        alert('success' , "Book updated Successfully.");
        unset($_SESSION['update_doc']);
    }
    
    if(isset($_SESSION['del_doc']) && $_SESSION['del_doc'] == 1)
    {
        alert('success' , "Book deleted Successfully.");
        unset($_SESSION['del_doc']);
    }
    
    if(isset($_POST["add_book"])){

        $verif=$obj->Select('documents',[],array("CODE_LIVRE"=>$_POST["code"]));

        if(!is_object($verif)){
                    $ads=DIRECTORY_SEPARATOR;
                    $destFolder='Bookcover';
                    if(!empty($_FILES))     
                    {
                        if($_FILES["fileToUpload"]["size"]<2000000) {
                            $temf = $_FILES['file']['tmp_name'];
                            $targetpath = dirname(__FILE__, 3) . $ads . $destFolder . $ads;
                            $link = $_FILES['file']['name'];
                            $targetFile = $targetpath . $link;
                            $filename = move_uploaded_file($temf, $targetFile);

                            $requete = $obj->Insert('documents', array("CODE_LIVRE", "TITRE", "AUTHEUR", "DATE_EDITION", "DEPARTMENT", "BOOK_STATUS", "PICTURE" , "STATUT"),
                                array($_POST["code"], $_POST["book_title"], $_POST["author"], $_POST["edition_date"], $_POST["department"], $_POST["initial_state"], $link , "free"));
                            if ($requete) 
                            {
                                $_SESSION['add_doc'] = 1;
                                echo("<script>location.assign('../refresh.html')</script>");
                            }
                        }else{
                           // alert('warning','Picture too big','slideInRight');
                        }
                    }

        }
    }

    if(isset($_POST['updat_book'])) {
        $ads = DIRECTORY_SEPARATOR;
        $destFolder = 'Bookcover';
        if (!empty($_FILES)) {
            if($_FILES["file"]["name"] != "")
            {
                if ($_FILES["fileToUpload"]["size"] < 2000000) 
                {
                    $temf = $_FILES['file']['tmp_name'];
                    $targetpath = dirname(__FILE__, 3) . $ads . $destFolder . $ads;
                    $link = $_FILES['file']['name'];
                    $targetFile = $targetpath . $link;
                    $filename = move_uploaded_file($temf, $targetFile);
                    $requete = $obj->Update('documents', array('TITRE', 'DEPARTMENT', 'BOOK_STATUS', 'AUTHEUR', 'DATE_EDITION', 'PICTURE'),
                        array($_POST['book_title'], $_POST['department'], $_POST['initial_state'], $_POST['author'], $_POST['edition_date'], $link),
                        array('CODE_LIVRE' => $_POST['code']));
                    if ($requete) 
                    {
                        $_SESSION['update_doc'] = 1;
                        echo("<script>location.assign('../refresh.html')</script>");
                    }
                }
            }
            else
            {
                $requete = $obj->Update('documents', array('TITRE', 'DEPARTMENT', 'BOOK_STATUS', 'AUTHEUR', 'DATE_EDITION'),
                array($_POST['book_title'], $_POST['department'], $_POST['initial_state'], $_POST['author'], $_POST['edition_date']),
                array('CODE_LIVRE' => $_POST['code']));
                if ($requete) 
                {
                    $_SESSION['update_doc'] = 1;
                    echo("<script>location.assign('../refresh.html')</script>");
                }
            }
        }
    }

    if (isset($_POST['delete_book'])){
        $requete = $obj->Delete('documents',array('CODE_LIVRE'=>$_POST['sup']));
        if ($requete) 
        {
            $_SESSION['del_doc'] = 1;
            echo("<script>location.assign('../refresh.html')</script>");
        }
    }


include('./header.php');

?>
 
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6">
                            Books History
                        </div>
                        <div class="col-6 text-right">
                            <a role="button" class="btn btn-dark rounded-pill" data-toggle="modal" data-target="#new_book">Add a book</a>
                        </div>
                    </div>
                    
                </div>
                <div class="card-body">
                    <?php if(is_object($books)): ?>
                    <table id='table' class="table table-bordered table-hover table-responsive table-responsive-md table-responsive-lg">
                        <thead>
                            <tr>
                                <th class="text-truncate">Action</th>
                                <th class="text-truncate">Cover Page</th>
                                <th class="text-truncate">Code</th>
                                <th class="text-truncate">Title</th>
                                <th class="text-truncate">Author</th>
                                <th class="text-truncate">Edition Date</th>
                                <th class="text-truncate">Department</th>
                                <th class="text-truncate">Status</th>
                                <th class="text-truncate">Availability</th>
                            </tr>
                        </thead>

                        <tbody>
                        <?php while ($book=$books->fetch()):
                            $nom="";
                            if($book["DEPARTMENT"]!=0) {
                                $s_de = $obj->Select('department', [], array('ID_DEPARTEMENT' => $book["DEPARTMENT"]))->fetch();

                            }else{
                                $s_de["NOM_DEPARTEMENT"] ="Both";
                            }
                            ?>
                        <tr>
                            <td class="text-truncate">
                                <a onclick="book_up(this.id)" id="<?= $book["CODE_LIVRE"]?>" class='btn btn-outline-dark text-truncate' role='button' data-toggle="modal" data-target="#update_book"><i class='fas fa-pen'></i></a>
                                <a title="<?= $book["CODE_LIVRE"]?>" onclick="document.getElementById('sup').value=this.title" class='btn btn-outline-danger text-truncate' role='button' data-toggle="modal" data-target="#delete_book"><i class='fas fa-trash'></i></a>
                            </td>
                            <td class='text-center'>
                                <img src="<?= '../../Bookcover/'.$book['PICTURE'] ?>" alt="<?= 'Book Cover pic'?>" class='py-1' width='60px' , height='60px'>
                            </td>
                            <td class="text-truncate"><?= $book["CODE_LIVRE"]?></td>
                            <td class="text-truncate"><?= $book["TITRE"]?></td>
                            <td class="text-truncate"><?= $book["AUTHEUR"]?></td>
                            <td class="text-truncate"><?= $book["DATE_EDITION"]?></td>
                            <td class="text-truncate"><?= $s_de["NOM_DEPARTEMENT"]?></td>
                            <td class="text-truncate"> <span class="badge badge-success p-lg-2"><?= $book["BOOK_STATUS"]?></span></td>
                            <td class="text-truncate"><?= $book["STATUT"] == 'free' ? '<div class="text-success">Available</div>' : '<div class="text-danger">Unavailable</div>'?></td>
                        </tr>
                        <?php endwhile;?>

                        </tbody>

                        <tfoot>
                            <tr>
                                <th class="text-truncate">Action</th>
                                <th class="text-truncate">Cover Page</th>
                                <th class="text-truncate">Code</th>
                                <th class="text-truncate">Title</th>
                                <th class="text-truncate">Author</th>
                                <th class="text-truncate">Edition Date</th>
                                <th class="text-truncate">Department</th>
                                <th class="text-truncate">Status</th>
                                <th class="text-truncate">Availability</th>
                            </tr>
                        </tfoot>
                    </table>
                    <?php endif; ?>
                </div>
            </div>
            
            <!----------------------------------------------------- Add Book Modal ------------------------------------------------->
            <div class="modal fade" id="new_book" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard='false' aria-hidden="true">
                <div class="modal-dialog modal-lg mr-0 mt-0" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-gradient-primary">
                            <h5 class="modal-title text-light">Register a new book</h5>
                                <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data">
                                <div class="row">

                                    <!-------------------------------------- Book Code --------------------------------------->
                                    <div class="col-lg-6 py-lg-3 py-2">
                                        <div class="form-group">
                                            <label for="code">Book Code <span class="text-danger"> * </span></label>
                                            <input oninput="controle(this.name,this.value)" class="form-control" type="text" name="code" id="code" required>
                                            <div id="code_v" class="text-danger text-center"  style="display: none">This code already exist</div>
                                        </div>
                                    </div>

                                    <!-------------------------------------- Book Title --------------------------------------->
                                    <div class="col-lg-6 py-lg-3 py-2">
                                        <div class="form-group">
                                            <label for="book_title">Book Title  <span class="text-danger"> * </span></label>
                                            <input type="text" name="book_title" id="book_title" class="form-control" required>
                                        </div>                                        
                                    </div>

                                    
                                    <div class="col-lg-12 py-lg-3 py-2">
                                        <div class="accordion " id="accordionExample">
                                            <div class="accordion-item ">
                                                <h4 class="accordion-header" id="headingOne">
                                                    <button type="button" class="btn btn-secondary accordion-button w-100" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                        Do you want to add an existing book ? 
                                                    </button>
                                                </h4>

                                                <div id="collapseOne" class="accordion-collapse collapse border" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body px-2 py-lg-4 py-md-2">
                                                        <div class="form-group">
                                                            <label for="title">Title <span class="text-danger"> * </span></label>
                                                            <select class="form-control" name="title" id="title">
                                                                <option value="">Select the title</option>
                                                                <option value="">Candide</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-------------------------------------- department --------------------------------------->
                                    <div class="col-lg-6 py-lg-3 py-2">
                                        <div class="form-group">
                                            <label for="department">Department  <span class="text-danger"> * </span></label>
                                            <select type="number" name="department" id="department" class="form-control" required>
                                                <option value="" selected disabled>Select department</option>
                                                <?php while ($dep=$depat->fetch()):?>
                                                    <option value="<?=$dep["ID_DEPARTEMENT"]?>" ><?=$dep["NOM_DEPARTEMENT"]?></option>
                                                <?php endwhile;?>
                                                <option value=0 >Both</option>
                                            </select>
                                        </div>
                                    </div>


                                    <!-------------------------------------- Book Status --------------------------------------->
                                    <div class="col-lg-6 py-lg-3 py-2">
                                        <div class="form-group">
                                            <label for="initial_state">Book Status <span class="text-danger"> * </span></label>
                                            <select name="initial_state" id="initial_state" class="custom-select" required>
                                                <option value="0">Select the status</option>
                                                <option value="new">New</option>
                                                <option value="good">Good</option>
                                                <option value="old">Old</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-------------------------------------- Author --------------------------------------->
                                    <div class="col-lg-6 py-lg-3 py-2">
                                        <div class="form-group">
                                            <label for="author">Author  <span class="text-danger"> * </span></label>
                                            <input type="text" name="author" id="author" class="form-control" required>
                                        </div>
                                    </div>

                                    <!-------------------------------------- Edition Date --------------------------------------->
                                    <div class="col-lg-6 py-lg-3 py-2">
                                        <div class="form-group">
                                            <label for="edition_date">Edition Date  <span class="text-danger"> </span></label>
                                            <input type="date" name="edition_date" id="edition_date" class="form-control">
                                        </div>
                                    </div>

                                    <!-------------------------------------- Cover Picture --------------------------------------->
                                    <div class="col-lg-6 py-lg-3 py-2">
                                       <div class="form-group">
                                            <label for="cover_pic">Cover Picture <span class="text-danger"> </span></label>
                                           <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="cover_pic" name="file">
                                                <label class="custom-file-label" for="cover_pic">Choose file</label>
                                            </div>
                                       </div>
                                    </div>

                                    <div class="col-lg-12 text-center my-3">
                                        <button type="reset" class="btn btn-outline-dark px-4 rounded-pill" data-dismiss="modal">Close</button>
                                        <button type="submit" onclick="save_book()" class="btn btn-outline-primary px-4 rounded-pill" name="add_book" id="add_book">Add Book</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!----------------------------------------------------- Update Book Modal ------------------------------------------------->
            <div class="modal fade" id="update_book" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard='false' aria-hidden="true">
                <div class="modal-dialog modal-lg mr-0 mt-0" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-gradient-primary">
                            <h5 class="modal-title text-light">Update Book Information</h5>
                                <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                        </div>
                        <div  class="modal-body">
                            <form id="mod_rand_up" action="" method="post" enctype="multipart/form-data">
                                <div class="row">

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!----------------------------------------------------- Delete Book Modal ------------------------------------------------->
            <div class="modal fade" id="delete_book" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard='false' aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-gradient-danger">
                            <h5 class="modal-title text-light">Delete a book</h5>
                                <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                        </div>
                        <div class="modal-body">
                           <form action="" method="post">
                                <div class="row">
                                    <div class="col-lg-12">
                                        Are you sure you want to delete this book ? Remember that this action is irreversible.
                                    </div>
                                    <input hidden id="sup" name="sup" value=""/>
                                    <div class="col-lg-12 text-center my-3">
                                        <button type="reset" class="btn btn-outline-dark px-4 rounded-pill" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-outline-danger px-4 rounded-pill" name="delete_book" id="delete_book">Delete the Book</button>
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
    include('../footer.php');
?>
<script>
    function controle(name,id) {
        if(id.length>1){

            $.post(
                '../../../ajax.php',{
                    veri:'ok',
                    name:name,
                    id:id,
                }, function (cont){
                    // $('').html();
                    if(cont){
                      document.getElementById(name+'_v') .style.display='block';
                    }else {
                        document.getElementById(name+'_v') .style.display='none';
                    }
                });
        }

    }

    function book_up(id) {
        $.post(
            '../../../ajax.php',{
                book:'ok',
                id_book:id,
            }, function (content){
                $('#mod_rand_up').html(content);
            });

    }

    function save_book() {

    }

    $(".custom-file-input").on("change", function() 
    {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });

</script>