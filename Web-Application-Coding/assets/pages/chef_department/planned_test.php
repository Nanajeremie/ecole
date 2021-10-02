<?php
    
    $title = 'Test';
    $breadcrumb = 'All Planned Test';

    include('../../../utilities/QueryBuilder.php');
    include('header.php');

    if(isset($_SESSION['activate_test']) && $_SESSION['activate_test'] == 1)
    {
        alert('success' , "Test Activated Successfully.");
        unset($_SESSION['activate_test']);
    }

    if(isset($_SESSION['deactivate_test']) && $_SESSION['deactivate_test'] == 1)
    {
        alert('success' , "Test Deactivated Successfully.");
        unset($_SESSION['deactivate_test']);
    }

    if(isset($_SESSION['update_test']) && $_SESSION['update_test'] == 1)
    {
        alert('success' , "Test Information Updated Successfully.");
        unset($_SESSION['update_test']);
    }

    if(isset($_SESSION['del_test']) && $_SESSION['del_test'] == 1)
    {
        alert('success' , "Test Deleted Successfully.");
        unset($_SESSION['del_test']);
    }

    $obj = new QueryBuilder();
    $requetes = $obj ->Requete("SELECT d.ID_DEVOIR, c.NOM_CLASSE , m.NOM_MODULE , d.DATE_DEV , d.DEVOIR , d.POURCENTAGE , d.STATUT FROM devoirs d , professeur pr , enseigner e , classe c , module m WHERE d.ID_MODULE = m.ID_MODULE AND d.ID_MODULE = e.ID_MODULE AND e.ID_PROFESSEUR =  pr.ID_PROFESSEUR AND m.ID_CLASSE = c.ID_CLASSE AND d.ID_ANNEE = '". getAnnee(0)['ID_ANNEE']."'")->fetchAll();

    if(isset($_POST["activate_test"]))
    {
        extract($_POST);

        $requete = $obj->Requete("UPDATE `devoirs` SET STATUT = 'Active' WHERE ID_DEVOIR = '".$id_dev."'");
        if ($requete) {
            $_SESSION['activate_test'] = 1;
            echo("<script>location.assign('../refresh.html')</script>");        
        }
    }

    if(isset($_POST["deactivate_test"]))
    {
        extract($_POST);

        $requete = $obj->Requete("UPDATE `devoirs` SET STATUT = 'Inactive' WHERE ID_DEVOIR = '".$id_dev."'");
        if ($requete) {
            $_SESSION['deactivate_test'] = 1;
            echo("<script>location.assign('../refresh.html')</script>");        
        }
    }

    if(isset($_POST["update_test"]))
    {
        extract($_POST);

        $requete = $obj->Requete("UPDATE `devoirs` SET DATE_DEV = '".$date_dev."' , POURCENTAGE = '". $pourcentage ."' WHERE ID_DEVOIR = '".$id_dev."'");
        if ($requete) {
            $_SESSION['update_test'] = 1;
            echo("<script>location.assign('../refresh.html')</script>");        
        }
    }

    if(isset($_POST["del_test"]))
    {
        extract($_POST);

        $requete = $obj->Requete("DELETE FROM `devoirs` WHERE ID_DEVOIR = '".$id_dev."'");

        if ($requete) {
            $_SESSION['del_test'] = 1;
            echo("<script>location.assign('../refresh.html')</script>");        
        }
    }
?>


<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6">
                            Planned Test
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <table class="table table-bordered table-hover table-responsive table-responsive-md table-responsive-lg" id="table">
                        <thead>
                            <th class="text-truncate">Action</th>
                            <th class="text-truncate">Classroom</th>
                            <th class="text-truncate">Modulus</th>
                            <th class="text-truncate">Test Date</th>
                            <th class="text-truncate">Test</th>
                            <th class="text-truncate">Percentage</th>
                            <th class="text-truncate">Status</th>
                        </thead>

                        <tbody>
                            <?php
                                foreach ($requetes as $requete):
                            ?>
                            <tr>
                                <td class="text-truncate">        
                                    <a class="btn border text-truncate" role="button" data-toggle="modal" data-target="#activate_test" onclick="activate_test(<?= htmlspecialchars(json_encode($requete['ID_DEVOIR'])) ?>)"><i class="fas fa-check text-success"></i></a>
                                    <a class="btn border text-truncate" role="button" data-toggle="modal" data-target="#edit_test" onclick="edit_test(<?= htmlspecialchars(json_encode($requete['ID_DEVOIR'])) ?>)"><i class="fas fa-pencil-alt text-dark"></i></a>
                                    <a class="btn border text-truncate" role="button" data-toggle="modal" data-target="#del_test" onclick="del_test(<?= htmlspecialchars(json_encode($requete['ID_DEVOIR'])) ?>)"><i class="fas fa-trash text-danger"></i></a>
                                </td>
                                <td class="text-truncate"><?= $requete["NOM_CLASSE"]?></td>
                                <td class="text-truncate"><?= $requete["NOM_MODULE"]?></td>
                                <td class="text-truncate"><?= date("M d, Y | H:i:s",strtotime($requete["DATE_DEV"])) ?> </td>
                                <td class="text-truncate"><a class="btn btn-dark text-truncate" href="<?= 'devoir_preview.php?title='.$requete['DEVOIR'] ?>" >Check the file</a></td>
                                <td class="text-truncate"><?= $requete["POURCENTAGE"].' %' ?></td>
                                <?php
                                        if ($requete["STATUT"] == 'Active') :
                                            $actif = 'badge-success';
                                        else:
                                            $actif = 'badge-danger';
                                        endif;
                                ?>
                                <td class="text-truncate">
                                    <span class="py-2 px-4 badge <?= $actif ?>"><?= $requete["STATUT"] ?></span>
                                </td>
                            </tr>
                            <?php
                                endforeach;
                            ?>
                            
                        </tbody>

                        <tfoot>
                            <th>Action</th>
                            <th>Classroom</th>
                            <th>Modulus</th>
                            <th>Test Date</th>
                            <th>Test</th>
                            <th>Percentage</th>
                            <th>Status</th>
                        </tfoot>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="activate_test" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard='false' aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-light">Activate Test</h5>
                <button type="button" onclick="document.location.reload(true)" class="close text-light" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" id="activate_test_form" name="activate_test_form" method="post">
                    
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="edit_test" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard='false' aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary">
                <h5 class="modal-title text-light">Update Test</h5>
                <button type="button" onclick="document.location.reload(true)" class="close text-light" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" id="edit_test_form" name="edit_test_form" method="post">
                    
                </form>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="del_test" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard='false' aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-danger">
                <h5 class="modal-title text-light">Delete Test</h5>
                <button type="button" onclick="document.location.reload(true)" class="close text-light" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" id="del_test_form" name="del_test_form" method="post">
                    
                </form>
            </div>

        </div>
    </div>
</div>

<?php
    include('../footer.php');
?>

<script>

    function activate_test(id){
        $.ajax({
            url: "../../../ajax.php?action=activate_test&id_test="+id,
            method: "post",
            data: {
                    'id_test' : $('#'+id).val()
                  },
            dataType: "text",
            success: function (response) {
                $("#activate_test_form").html(response);
            }
        });
    }

    function edit_test(id){
        $.ajax({
            url: "../../../ajax.php?action=update_test&id_test="+id,
            method: "post",
            data: {
                    'id_test' : $('#'+id).val()
                  },
            dataType: "text",
            success: function (response) {
                $("#edit_test_form").html(response);
            }
        });
    }

    function del_test(id){
        $.ajax({
            url: "../../../ajax.php?action=del_test&id_test="+id,
            method: "post",
            data: {
                    'del_test' : $('#'+id).val()
                  },
            dataType: "text",
            success: function (response) {
                $("#del_test_form").html(response);
            }
        });
    }

</script>