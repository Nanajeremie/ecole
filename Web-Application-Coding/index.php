<?php 
include 'utilities/QueryBuilder.php';
$obj = new QueryBuilder();

if(isset($_GET) AND isset($_GET['upd']) AND !empty($_GET['upd'])){
    extract($_GET);
    $updt = $upd;
    $Updmsg="Your password has been succesfuly modified. Connect yourself ";
    $getUpdMsg=SetMessage($Updmsg, 'success');
}
if (isset($_POST['submit']))
{
    
    extract($_POST);
    $cookies=array();
    //var_dump(extract($_POST));
    $values = array($login_username,$login_password);
    $columns = array('USERNAME','PASSWORD');
    $table='user';
    $sessions=array('ID_USER','USERNAME', 'DROITS');
    $return = array('DROITS');
    
    if (isset($login_remember) AND !empty($login_remember ))
    {
        $cookies=array('USERNAME'=>$login_username,'PASSWORD'=>$login_password);
    }

    $connecter= $obj->Connexion($table, $columns, $values,$return,$cookies,$sessions);
  

    if (count($connecter)>0) {

        $_SESSION['connecte'] = 1;
        
        $school_years = $obj->Select('annee_scolaire', array(), array(), 'ID_ANNEE', 0);
        if(is_object($school_years))
        {
            //Redirection des utilisateurs en fonction de leurs droits
            switch ($connecter[0]) {
                case 'admin':
                    sleep(1);
                    echo "<script>window.open('assets/pages/admin/index.php?nouv=new' , '_self') </script>";
                    break;
    
                case 'dean':
                    sleep(1);
                    echo "<script>window.open('assets/pages/dean/index.php' , '_self') </script>";
                    break;
    
                case 'chef_department':
                    sleep(1);
                    echo "<script>window.open('assets/pages/chef_department/index.php' , '_self') </script>";
                    break;
                
                case 'chef_scolarite':
                    sleep(1);
                    echo "<script>window.open('assets/pages/chef_scolarite/index.php' , '_self') </script>";
                    break;
    
                case 'secretary':
                    sleep(1);
                    echo "<script>window.open('assets/pages/secretary/index.php' , '_self') </script>";
                    break;
    
                case 'student':
                    sleep(1);
                    echo "<script>window.open('assets/pages/student/index.php' , '_self') </script>";
                    break;
    
                case 'teacher':
                    sleep(1);
                    echo "<script>window.open('assets/pages/teacher/index.php' , '_self') </script>";
                    break;
                
                default:
                    # code...
                    break;
            }  
        }
        else
        {
            switch ($connecter[0]) {
                case 'admin':
                    sleep(1);
                    echo "<script>window.open('assets/pages/admin/new_year.php?nouv=new' , '_self') </script>";
                    break;
                default :
                    $annee_vide = "Sorry!!! There is no academic year for the moment. Wait until next days!!";
                    break;
                
                }
        }
    }
    else
    {
        
        $message="Password or Username incorect!!! Try again ";
        $getmsg=SetMessage($message, 'alert');
    }
}
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="assets/library/bootstrap4/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/library/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="icon" href="assets/media/logo_bit.png">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mx-auto my-5">
                <div class="card">
                    <div class="card-header py-3 bg-gradient-primary">
                        <h3 class="text-uppercase text-center text-light">sign in</h3>
                        <p class="text-center text-light small">Hello there, Sign in and start managing your Admin Interface</p>
                    </div>
                    <div class="mt-2 col-12 text-center ">
                        <?php 
                            if (isset($getmsg)) {echo $getmsg;}else{if(isset($getUpdMsg)){echo $getUpdMsg;} } ;
                            if(isset($annee_vide)){echo $annee_vide;};
                        ?>
                    </div>
                    <div class="card-body p-lg-4">
                        
                        <form method="post" action="index.php">

                            <div class="input-group py-3">
                                <input class="form-control <?php if (isset($getmsg)): ?> border border-danger text-danger<?php endif ?>" type="text" name="login_username" id="login_username" placeholder="Username" value="<?php if(isset($_COOKIE['USERNAME']))
                                        {
                                            echo $_COOKIE['USERNAME'];
                                        }
                                        ?>" required>
                                <div class="input-group-append">
                                    <span class=" input-group-text fas fa-user-alt bg-light text-bluesky"></span>
                                </div>
                            </div>

                            <div class="input-group py-5">
                                <input class="form-control <?php if (isset($getmsg)): ?> border border-danger <?php endif ?>" type="password" name="login_password" id="login_password" placeholder="Password" value="<?php if(isset($_COOKIE['PASSWORD']))
                                        {
                                            echo $_COOKIE['PASSWORD'];
                                        }
                                        ?>" required>
                                <div class="input-group-append">
                                    <span class=" input-group-text fas fa-lock bg-light text-bluesky "></span>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <label for="login_remember">
                                        <input type="checkbox" name="login_remember" id="login_remember" > Remember me
                                    </label>
                                </div>
                                <div class="col-6 text-right">
                                    <a class="text-bluesky" href="mail/forgot_password.php">Forgot Password?</a>
                                </div>

                                <div class="col-lg-12 py-5 text-center">
                                    <input  name="submit" class="btn btn-outline-primary w-75 rounded-pill" type="submit" value="Login">
                                </div> 
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>