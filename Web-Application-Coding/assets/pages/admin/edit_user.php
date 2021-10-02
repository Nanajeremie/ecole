<?php 
    include('../../../utilities/QueryBuilder.php');
    $title = 'Admin Dashbord';
    $breadcrumb = 'Add user';
    include_once ('header.php');
    $user_status='enable';
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
      $all_users = $obj->Requete("SELECT DISTINCT * FROM user WHERE USER_STATUS ='".$user_status."' ORDER BY ID_USER DESC");

     /**
      * Session destruction
      */
      if (isset($_SESSION['update_user_info']) && $_SESSION['update_user_info']==1) 
      {
          alert('success', $_SESSION['user_name'].' with the permission of '.$_SESSION['user_rights'].' '.'User infos successfully operated');
          unset($_SESSION['user_name']);
          unset($_SESSION['update_user_info']);
          unset($_SESSION['user_rights']);
      }
      
      if(isset($_SESSION['del_user']) && $_SESSION['del_user']==1)
      {
          alert('danger','User deleted successfully');
          unset($_SESSION['del_user']);
          
      }
  
      /**
      * Espace requete pour Update les infos des users
      */
      if (isset($_POST['user_update_submit'])) 
      {
          extract($_POST);
          //Update of the designated user
          $update_user = $obj->Update('user',array('USERNAME','DROITS'),array($_POST['user_name'.$id_user_updt_info],$_POST['user_rights'.$id_user_updt_info]),array('ID_USER'=>$id_user_updt_info));

          if ($update_user) 
          {
              $_SESSION['update_user_info'] = 1;
              $_SESSION['user_name'] = $_POST['user_name'.$id_user_updt_info];
              $_SESSION['user_rights'] = $_POST['user_rights'.$id_user_updt_info];
              echo("<script>location.assign('../refresh.html')</script>");
          }
         
               
      }

      //change status of the designated user
      if(isset($_POST['delete_user_submit']))
      {
        extract($_POST);
        $del_user_req = $obj->Update('user',array('USER_STATUS'),array('disabled'.$id_user_del),array('ID_USER'=>$id_user_del));

        if($del_user_req)
        {
            $_SESSION['del_user'] = 1;
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
                            <h2>Edit User infos</h2>
                        </div>
                        <div class="col-6 text-right">
                            <a class="btn btn-dark" role="button" href="add_user.php">Add Users</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                <?php 
                    if (is_object($all_users)) {
                ?>
                <table id="table" class="table table-bordered table-hover table-responsive-lg table-responsive-md table-responsive-sm">
                    <thead>
                        <tr>
                            <th class="text-truncate">Action</th>
                            <th class="text-truncate">User Picture</th>
                            <th class="text-truncate">User Name</th>
                            <th class="text-truncate">User Rights</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if ($all_users->rowCount() > 0):
                        while($all_users_fetch = $all_users->fetch()){

                            $update = 'user_update';
                            $update .= $all_users_fetch['ID_USER'];

                            $delete = 'user_delete';
                            $delete.= $all_users_fetch['ID_USER'];
                            // echo($update);
                     ?>
                        <tr>
                            <td>
                                <div class="btn-group" role="group" aria-label="Button group">
                                    <button onclick="rename_input_updt_user(this.title)" class="btn" data-toggle="modal" title="<?= $all_users_fetch['ID_USER']?>" data-target=<?php echo('#'.$update )?>
                                    ><i class="fas fa-edit text-warning"></i> 
                                    </button>
                                    <button onclick="rename_input_delete_user(this.title)" 
                                    class="btn" data-toggle="modal" 
                                    title="<?= $all_users_fetch['ID_USER']?>" 
                                    data-target="<?php echo ('#'.$delete)?>" > 
                                    <i class="fas fa-trash text-danger"></i>
                                    </button>
                                </div>
                            </td>
                            <td class="text-truncate text-center">
                                <img src="../../media/<?=$all_users_fetch['PROFILE_PIC'];?>" alt="user profile" height="50px" width="50px" class="img-profile rounded-circle">
                            </td>
                            <td class="text-truncate">
                                <?= $all_users_fetch['USERNAME']; ?>
                            </td>
                            <td class="text-truncate">
                                <?= $all_users_fetch['DROITS']; ?>
                            </td>


                        </tr>
                          <!-- Modal pour edit les infos du user -->
                <div class="modal fade" id="<?php echo($update);?>" data-backdrop="static" data-keyboard="false" role="dialog" tabindex="-1" aria-hidden="true">  
                        <div class="modal-dialog modal-xl" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-primary">
                                    <h5 class="modal-title text-white">Update User Information</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="loads()">
                                        <span aria-hidden="true" class="text-white">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="container-fluid ">
                                        <div class="col-12 text-center" id="user_info"></div>
                                        <form action="" method="post" id="user_info_action" class="my-5">
                                        <!-- //Hidden input -->
                                        <input type="text"
                                         id="id_updt_user_<?= $all_users_fetch['ID_USER'] ;?>"
                                         value="<?= $all_users_fetch['ID_USER'] ;?>" hidden name="">
                                            <div class="row">
                                                 <!-- Espace pour le nom du User  -->
                                                <div class="form-group col-lg-6 col-md-6 col-sm-12" id="user_name">
                                                    <label for="user_name">User Name<span class="text-danger"> * </span></label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" 
                                                        name="<?= 'user_name'.$all_users_fetch['ID_USER']?>" 
                                                         id="<?='user_name'.$all_users_fetch['ID_USER']?>" 
                                                         value="<?= $all_users_fetch['USERNAME'] ;?>" required>
                                                        <div class="input-group-append">
                                                            <span class="input-group-text fas fa-user"></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Espace pour les droits du User  -->
                                                <div class="form-group col-lg-6 col-md-6 col-sm-12" id="user_rights">
                                                    <label for="<?='user_rights'.$all_users_fetch['ID_USER'];?>">User Rights<span class="text-danger"> * </span></label>
                                                    <div class="input-group">
                                                        <select class="form-control" 
                                                        name="<?='user_rights'.$all_users_fetch['ID_USER'];?>" 
                                                        id="<?='user_rights'.$all_users_fetch['ID_USER'];?>" 
                                                        
                                                        required>
                                                        <option value="<?= $all_users_fetch['DROITS'];?>" selected><?= $all_users_fetch['DROITS'] ;?></option>
                                                        <?php
                                                            $req1 = $obj->Requete("SELECT DISTINCT DROITS FROM user");
                                                        
                                                            if ($req1->rowCount()>0) {
                                                                while($req1_fetch = $req1->fetch()):      
                                                        ?>
                                                        <option value="<?= $req1_fetch['DROITS'] ?>"><?= $req1_fetch['DROITS'] ?></option>
                                                        <?php 
                                                            endwhile;
                                                            }
                                                        ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                 <!-- Espace pour le boutton submit  -->
                                                <div class="user_submit mx-auto">
                                                  
                                                        <button data-dismiss="modal" aria-label="Close" type="submit" class="btn btn-outline-danger btn-lg my-3" id="user_cancel">
                                                            Cancel
                                                        </button>
                                                        <button name="user_update_submit" class="btn btn-outline-success btn-lg">
                                                            Update User
                                                        </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                    </div>      
                   
                </div>
                <!-- End Modal pour edit les infos du user -->

                 <!-- start Modal pour Supprimer les user -->
                 <div class="modal fade" id="<?= $delete ?>" tabindex="-1" role="dialog" aria-hidden="true">
                    <form action="" method="post">  
                    <!-- //Hidden input -->
                    <input type="text"
                    id="id_user_delete_<?= $all_users_fetch['ID_USER'] ?>" 
                    value="<?= $all_users_fetch['ID_USER'] ?>"
                     hidden
                     name="" >
                        
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-danger text-center text-white">
                                        <h5 class="modal-title">Delete User</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                </div>
                                <div class="modal-body">
                                    <div class="container-fluid">
                                        <h3>
                                        You really want to delete the user ?
                                        <?= $all_users_fetch['USERNAME']." ".$all_users_fetch['DROITS'] ?>
                                        </h3>
                                    </div>
                                </div>
                                <div class="modal-footer text-center">

                                        <button type="button" class="btn btn-warning" data-dismiss="modal">
                                        Cancel</button>
                                        <button type="submit" class="btn btn-danger" name="delete_user_submit">
                                        Delete
                                        </button>
                                    
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                  <!-- End Modal pour Supprimer les user -->
                        <?php 
                            }
                        endif;
                        ?>
                    </tbody>
                </table>
                <?php 
                    }else{
                ?>
                    <h2 class="text-center"> Nos user for the moment !!!</h2>
                <?php
                    }
                ?>
            </div>
        </div>
    </div>
</div>

<?php
    include('../footer.php');
?>
<script>
   function rename_input_updt_user(title)
   {
       var entree = document.getElementById('id_updt_user_'+title);
       entree.name="id_user_updt_info";
   }

   function rename_input_delete_user(title)
   {
       var sortie =  document.getElementById('id_user_delete_'+title);
       sortie.name="id_user_del";
   }
</script>