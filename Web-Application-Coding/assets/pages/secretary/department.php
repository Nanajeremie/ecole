<?php 
    include('../../../utilities/QueryBuilder.php');
    $title = 'Department';
    $breadcrumb = 'All Departments';
    
    $obj = new QueryBuilder();
    // liste des filieres
    $checkDeprt = $obj->Select('department', [], [], $orderBy = '', $order = 1);

    include('header.php');

?>
<div class="container-fluid" id="fenetre">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6">
                            <h6 class="text-bluesky">All Departments</h6>
                        </div>
                    </div>
                </div>
                <div class="card-body py-2">
                    <table class="table table-bordered table-responsive-lg table-responsive-md table-responsive-sm" id="table">
                        <thead>
                            <tr>
                                <th class="text-truncate">Deparment Name</th>
                                <th class="text-truncate">Department Chief</th>
                                <th class="text-truncate">Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if(is_object($checkDeprt))
                                {
                                    while($depListe = $checkDeprt->fetch())
                                    {
                            ?>
                                <tr>
                                    <td class="text-truncate"><?= $depListe['NOM_DEPARTEMENT'] ?></td>
                                    <td class="text-capitalize text-truncate""><?= $depListe['CHEF_DEPARTEMENT']?></td>
                                    <td><?= $depListe['DESCRIPTION'] ?></td>
                                </tr>
                            <?php   
                                    } 
                                }
                            ?>
                                
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include('../footer.php');
?>