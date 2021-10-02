<?php
    // ------------------TRAITEMENT DU FORMULAIRE DE REINITAILISATION DU MOT DE PASSE OUBLIE
    //inclusion de la classe contenant les fonction principales
    include('../utilities/QueryBuilder.php');
    $base = new QueryBuilder();

    // fonction permetant de generer un code secree
    function chaine_aleatoire($nb_car,$chaine="1234567890"){
        $nb_lettres=(strlen($chaine)-1);
        $generation="";
        for($i=0;$i<$nb_car;$i++){
            $post=mt_rand(0,$nb_lettres);
            $car=$chaine[$post];
            $generation=$generation.$car;
        }
        return $generation;
    }


    if(isset($_POST['recover'])){
        
        extract($_POST);
        /*function de selection de toutes les donnees dans une table */
        $codeListe = $base->Select('saveCode',['CODE'],[],'',1);

        // on verifie si il y a des donnees dans la table de requete car la fonction 'Select retourn 0 si y a rien et un objet si il ya des donnees'

        if(is_object($codeListe)){
            $cpt = 0;
            $nana;
            do{
                $nana=chaine_aleatoire(6);
                while ($returnListe = $codeListe->fetch()){
                    if($returnListe['CODE'] == $nana){
                       $cpt = 1; 
                    }    
                }

            }while($cpt==1);
            echo $nana;
        }
        else{

            $nana=chaine_aleatoire(6);
        }
       
    //evoie du code de recuperation
    $to       = $recover_mail;
    $subject  = 'Code secret';
    $message  = '<p>Your recover code is: <b style="color:red">'.$nana.'</b></p>';
    $headers  = 'From: [nanajeremie097]@gmail.com' . "\r\n" .
                'MIME-Version: 1.0' . "\r\n" .
                'Content-type: text/html; charset=utf-8';
    if(mail($to, $subject, $message, $headers)){
        //insertion du code secret dans la base de donnees
        $savecode = $base->Insert('savecode', ['CODE'], [$nana]);
        if($savecode==1){
            $codeId = $base->Select('savecode', ['ID_SAVECODE'],array('CODE'=>$nana),'', $order = 1);
            $idCode = $codeId->fetch();
            Redirect('../restore_password.php?email='.$recover_mail.'&id='.$nana);
        $email_infos =  "<p class='text-success'>Your recover code is succesfuly sent in ".$recover_mail." ";
        }
        }
        else
        {
            $email_infos = " <p class='text-danger'>Sorry!! recover code sending failed please check your email address or internet connection and try again</p>";
        }
                  
    }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recover Password</title>
    <link rel="stylesheet" href="../assets/library/bootstrap4/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/library/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="icon" href="../assets/media/logo_bit.png">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mx-auto my-5">
                <div class="card">
                    <div class="card-header py-3 bg-gradient-primary">
                        <h3 class="text-uppercase text-center text-light">Recover password</h3>
                        <p class="text-center text-light small">Hello there, forgot your password ? Don't worry fill the information below and get access to your work environment</p>
                    </div>
                    <div class="card-body p-lg-5">
                    <div id="emaiTxt text-center mb-3">
                    <?php if(isset($email_infos)){echo $email_infos; }?>
                    </div>
                        <form method="post" action="forgot_password.php">
                            <div class="input-group py-3">
                                <input class="form-control" type="email" name="recover_mail" id="recover_mail" placeholder="Enter your email address" required>
                                <div class="input-group-append">
                                    <span class=" input-group-text fas fa-envelope  bg-light text-bluesky"></span>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12 py-3 text-center">
                                    <input class="btn btn-outline-primary w-75 rounded-pill" name="recover" type="submit" value="Recover">
                                </div>
                                <div class="col-12 text-center">
                                    Already have an account <a class="text-bluesky" href="../index.php">Sign in</a>
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