<?php
    include '../../../utilities/QueryBuilder.php';
    $title = 'Library';
    $breadcrumb = 'Books';
    $obj= new QueryBuilder();

    $depat=$obj->Select('department',[],[]);
    $books=$obj->Select('documents',[],[]);

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
                    </div>
                    
                </div>
                <div class="card-body">
                    <?php if(is_object($books)): ?>
                    <table id='table' class="table table-bordered table-hover table-responsive table-responsive-md table-responsive-lg">
                        <thead>
                            <tr>
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
        </div>
    </div>
</div>

<?php
    include('../footer.php');
?>
