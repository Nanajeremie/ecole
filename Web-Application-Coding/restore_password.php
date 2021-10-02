<?php
// ------------------TRAITEMENT DU FORMULAIRE DE REINITAILISATION DU MOT DE PASSE
include('utilities/QueryBuilder.php');
$base = new QueryBuilder();

$idCode;
$emailR;
if(isset($_GET) AND isset($_GET['id']) AND !empty($_GET['id']) AND isset($_GET['email']) AND !empty($_GET['email'])){
    extract($_GET);
    $idCode = $id;
    $emailR = trim($email);
    
    $msgSend="You received the secret code in ".$emailR.". Please check it ";
    $getmsg=SetMessage($msgSend, 'success');
}
if(isset($_POST['submit'])){
    $password=$_POST['password'];
    $password_confirm=$_POST['password_confirm'];
    $code=$_POST['code'];
    if($password==$password_confirm){
        $check = $base->Select( $table = 'savecode', ['CODE'],array('CODE'=>$code),'', $order = 1);
        if(is_object($check)){
            $intance = $base->pdo;
            
            $verify_email = $intance->query("SELECT * FROM etudiant WHERE EMAIL='".$emailR."'");
            $verify_email1 = $intance->query("SELECT * FROM etudiant WHERE EMAIL='".$emailR."'")->fetch();
          
            $email_Nbr = $verify_email->rowCount();
            if($email_Nbr>=1){
                $backUpde = $base->Update('user',['PASSWORD'], [$password], array("ID_USER"=>$verify_email1['ID_USER']));
                if($backUpde==1){
                    $sup = $base->Delete('savecode', array('ID_SAVECODE'=>$idCode));
                    if($sup==1){
                        Redirect('index.php?upd=1');
                    }
                }
            }else{
                $verify_email_admin = $intance->query("SELECT * FROM administration WHERE EMAIL='".$emailR."'");
                $verify_admin = $intance->query("SELECT * FROM administration WHERE EMAIL='".$emailR."'")->fetch();
                $email_num = $verify_email_admin->rowCount();
                if($email_num==0){
                    $errorSecretCode="Your Email does not exit. Please check it";
                    $errorMsg=SetMessage($errorSecretCode, 'alert');
                }else{
                    $backUpde = $base->Update('user',['PASSWORD'], [$password], array("ID_USER"=>$verify_admin['ID_USER']));
                    if($backUpde==1){
                        $sup = $base->Delete('savecode', array('ID_SAVECODE'=>$idCode));
                        if($sup==1){
                            Redirect('index.php?upd=1');
                        }
                    }
                }
            }
            
        }
        else{
            $errorSecretCode="Your secret code is incorrect ";
            $errorMsg=SetMessage($errorSecretCode, 'alert');    
        }
    }else{
        $errorPassword="Your password and confirm password are not the same ";
        $errorMsg=SetMessage($errorPassword, 'alert');
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restore Password</title>
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
                        <h3 class="text-uppercase text-center text-light">Restore Password</h3>
                        <p class="text-center text-light small">Hello there, you are about to restore your password</p>
                    </div>
                    <div class="mt-2 col-12 text-center"><?php if(isset($errorMsg)){echo $errorMsg;}else{ echo $getmsg;}?></div>
                    <div class="card-body p-lg-5">
                        <form method="post" action="#">
                           <div class="input-group py-3">
                                <input class="form-control" type="text" name="code" id="code" placeholder="Your secret code" value="<?php if(isset($code)){echo $code;} ?>" required>
                                <div class="input-group-append">
                                    <span class=" input-group-text fas fa-lock bg-light text-bluesky"></span>
                                </div>
                            </div>
                            <div class="input-group py-3">
                                <input class="form-control" type="password" name="password" id="password" placeholder="Password" required>
                                <div class="input-group-append">
                                    <span class=" input-group-text fas fa-lock bg-light text-bluesky"></span>
                                </div>
                            </div>

                            <div class="input-group py-3">
                                <input class="form-control" type="password" name="password_confirm" id="password_confirm" placeholder="Confirm Password" required>
                                <div class="input-group-append">
                                    <span class=" input-group-text fas fa-lock bg-light text-bluesky"></span>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6 text-right">
                                    <a class="text-bluesky" href="index.php">Sign In</a>
                                </div>

                                <div class="col-lg-12 py-5 text-center">
                                    <input class="btn btn-outline-primary w-75 rounded-pill" type="submit" name="submit" value="Restore Password">
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