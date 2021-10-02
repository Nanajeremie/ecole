<?php
    include('../../../utilities/QueryBuilder.php');
    include('../functions.php');
    $obj = new QueryBuilder();
    $position = strpos($_POST['pk'], "bs");

    $annee = getAnnee(0)['ID_ANNEE'];
    $update_query = $obj ->Requete("UPDATE notes SET ".$_POST["name"] ." = '".$_POST["value"]."' WHERE MATRICULE = '".$_POST['pk']."' AND ID_MODULE = '".$_POST['module']."' AND ANNEE_SCOLAIRE = '".$annee."'");
    
    print_r($update_query);
   