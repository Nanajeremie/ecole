<?php 
    include('../../../utilities/QueryBuilder.php');
    $title = 'Admin Dashbord';
    $breadcrumb = 'Add user';
    include_once ('header.php');

     //droit_acces();
     $obj = new QueryBuilder();

     $academic_years_exist = $obj->Select('annee_scolaire', array(), array());
     //of any line of academic year exists in the database
     if (is_object($academic_years_exist))
     {
         $academic_years_exist = $academic_years_exist->rowCount();
     }
     //if there is no records in the database
     else
     {
         $academic_years_exist = 0;
     }
 
     if(array_key_exists('confirm_new_year_creation' , $_POST))
     {
         echo "<script>window.open('new_year.php' , '_self') </script>";
     }
 
     if(array_key_exists('confirm_end_year_creation' , $_POST))
     {
         echo "<script>window.open('end_year.php' , '_self') </script>";
     }

     /**
      * Espace requete pour Afficher les infos des users
      */
      $all_users = $obj->Requete("SELECT DISTINCT * FROM user ORDER BY ID_USER DESC");
?>
<div class="container-fluid">
     <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6">
                            <h4 class="text-bluesky">All users</h4>
                        </div>
                        <div class="col-6 text-right">
                            <a class="btn btn-dark" href="add_user.php">Add New User</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                
                    <table id="table" class="table table-bordered table-hover table-responsive-lg table-responsive-md table-responsive-sm">
                        <thead>
                            <tr>
                                <th class="text-truncate">Image</th>
                                <th class="text-truncate">User name</th>
                                <th class="text-truncate">Rights</th>
                                <th class="text-truncate"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($all_users->rowCount() > 0){
                                while($all_users_fetch = $all_users->fetch()){

                                    $update = 'user_update';
                                    $update .= $all_users_fetch['ID_USER'];
                                    // echo($update);
                            ?>

                            <tr>
                                <td class="text-truncate text-center">
                                    <img src="../../media/user_pict/<?=$all_users_fetch['PROFILE_PIC'];?>" alt="user profile" height="150px" width="150px">
                                </td>
                                <td class="text-truncate">
                                    <?= $all_users_fetch['USERNAME']; ?>
                                </td>
                                <td class="text-truncate">
                                    <?= $all_users_fetch['DROITS']; ?>
                                </td>
                            </tr>
                            <?php 
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                    <?php 
                        // else{
                    ?>
                        <!-- <h2 class="text-center"> Nos user for the moment !!!</h2> -->
                    <?php
                        // }
                    ?>
                </div>
            </div>
     </div>
</div>
<?php 
    include('../footer.php');
?>