<?php
     $title = 'Library';
     $breadcrumb = 'Booking Logs';
 
     include('../../../utilities/QueryBuilder.php');
     include('header.php');
     
     $obj= new QueryBuilder();

     // Requete pour afficher les differents livres empruntes par l'etudiant
     $request = $obj->Requete("SELECT * FROM louer l , documents d WHERE l.CODE_LIVRE = d.CODE_LIVRE AND l.MATRICULE = (SELECT MATRICULE FROM etudiant WHERE ID_USER = '".$_SESSION["ID_USER"]."')");
   

?>
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-8 text-primary align-self-center">
                    <h6>Booked books logs</h6>
                </div>
                <div class="col-4 text-right align-self-center">
                    <a href="librairy.php" role="button" class="text-white btn btn-dark">Books</a>
                </div>
            </div>
        </div>
        <div class="card-body">
           
                <table class="table table-bordered table-hover table-responsive-lg table-responsive-md table-responsive-sm" id="table">
                    <thead>
                        <tr>
                            <th class="text-truncate">Book Name</th>
                            <th class="text-truncate">Author</th>
                            <th class="text-truncate">Booked day</th>
                            <th class="text-truncate">Return date</th>
                            <th class="text-truncate">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                        while($result_request1 = $request->fetch()){
                    ?>
                        <tr>
                            <td class="text-truncate"><?= $result_request1['TITRE'];?></td>
                            <td class="text-truncate"><?= $result_request1['AUTHEUR'];?></td>
                            <td class="text-truncate"><?= $result_request1['DATE_EMPRUNT'];?></td>
                            <td class="text-truncate"><?= $result_request1['DATE_REMISE'];?></td>
                            <td class="text-truncate">
                                <?php 
                                    if (empty($result_request1['ETAT_RETOUR'])) 
                                    {
                                        if ($result_request1["STATUT"] == 'in_location') {
                                            echo('<span class="text-bluesky">In reading</span>');
                                        }
                                        elseif($result_request1["STATUT"] == 'pending_for_confirmation')
                                        {
                                            echo('<span class="text-warning">Waiting for validation</span>');
                                        }
                                    }
                                    else
                                    {
                                        echo('<span class="text-success">Gived back</span>');
                                    }
                                ?>
                            </td>
                        </tr>
                    <?php 
                        }
                    ?>
                    </tbody>
                </table>

            
        </div>
    </div>
</div>
<?php
    include("../footer.php");
?>