<?php
    
    $title = 'Test';
    $breadcrumb = 'All Planned Test';

    include('../../../utilities/QueryBuilder.php');
    include('header.php');

    $obj = new QueryBuilder();
    $requetes = $obj ->Requete("SELECT c.NOM_CLASSE , m.NOM_MODULE , d.DATE_DEV , d.DEVOIR , d.POURCENTAGE , d.STATUT FROM devoirs d , enseigner e , classe c , module m WHERE d.ID_MODULE = m.ID_MODULE AND d.ID_MODULE = e.ID_MODULE AND e.ID_PROFESSEUR = (SELECT pr.ID_PROFESSEUR FROM professeur pr WHERE pr.ID_USER = '". $_SESSION['ID_USER'] ."') AND m.ID_CLASSE = c.ID_CLASSE AND d.ID_ANNEE = '". getAnnee(0)['ID_ANNEE']."'")->fetchAll();
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
                        <div class="col-6 text-right">
                            <a class="btn btn-dark" href="plan_test.php">Plan A Test</a> 
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered" id="table">
                        <thead>
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
                                <td class="text-truncate"><?= $requete["NOM_CLASSE"]?></td>
                                <td class="text-truncate"><?= $requete["NOM_MODULE"]?></td>
                                <td class="text-truncate"><?= $requete["DATE_DEV"] ?> </td>
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

<?php
    include('../footer.php');
?>