<?php
    include("../../../utilities/QueryBuilder.php");
    include("../functions.php");

    $obj = new QueryBuilder();

    if (isset($_POST['submit_test']))
    {
        extract($_POST);
        extract($_FILES);
        
        $ds = DIRECTORY_SEPARATOR;  //1
 
        $storeFolder = 'Devoirs';   //2
        
        if (!empty($_FILES)) 
        {

            $tempFile = $_FILES['file']['tmp_name'][0];          //3
            $targetPath = dirname( __FILE__, 3 ) . $ds. $storeFolder . $ds;  //4
            $link = $_FILES['file']['name'][0];
            $targetFile =  $targetPath. $link;  //5
            $files_name = move_uploaded_file($tempFile,$targetFile); //6
            $pass=$obj->Requete("SELECT COUNT(*) as nbre FROM devoirs WHERE ID_MODULE='$modulus' AND ID_ANNEE=".getAnnee(0)['ID_ANNEE'])->fetch();
            if ($pass["nbre"] <3){
                $requete = $obj -> Requete("INSERT INTO `devoirs`(`ID_MODULE`, `DATE_UPLOAD`, `DATE_DEV`,`DEVOIR`, `POURCENTAGE`, `STATUT`, `ID_ANNEE` ) VALUES ('$modulus' ,NOW(),'$dev_date', '$link', '$percentage','$status ' , '".getAnnee(0)['ID_ANNEE']."' )");
            }

            if($requete)
            {
                echo"success";
            }
        }

    }
