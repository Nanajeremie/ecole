<?php
    include('../../../utilities/QueryBuilder.php');
    include('../functions.php');

    $obj = new QueryBuilder();
    
    
    $leModule=$obj->Select('module',[],array('ID_MODULE'=>$_POST['modulus']))->fetch();


    $Verifier=$obj->Select('notes',[],array('ID_MODULE'=>$leModule['ID_MODULE'],'ANNEE_SCOLAIRE'=>getAnnee(0)['ID_ANNEE']));

    if (!is_object($Verifier)){
        $Etudiants=$obj->Select('inscription',[],array('ID_CLASSE'=>$leModule['ID_CLASSE'],'ID_ANNEE'=>getAnnee(0)['ID_ANNEE']));

        if (is_object($Etudiants)){

            while ($letudiant=$Etudiants->fetch()){
                $obj->Insert('notes',array('MATRICULE', 'ID_MODULE', 'ANNEE_SCOLAIRE'),array($letudiant['MATRICULE'],$leModule['ID_MODULE'],$letudiant['ID_ANNEE']));

            }
        }
        else{
            //pas d'etudiant inscrits pour cette classe
        }
    }
    $requetes = $obj->Requete("SELECT n.MATRICULE, n.ID_MODULE , e.NOM , e.PRENOM , n.NOTE1 , n.NOTE2 , n.NOTE3 , n.NOTE_PARTICIPATION, n.NOTE_ADMINISTRATION, n.SANCTION, n.MOYENNE FROM notes n , etudiant e WHERE e.MATRICULE = n.MATRICULE AND n.ID_MODULE='".$_POST['modulus']."' AND n.ANNEE_SCOLAIRE=".getAnnee(0)['ID_ANNEE']." ORDER BY n.MATRICULE ASC");
    
    $note1 = $obj->Requete("SELECT * FROM module m, devoirs d WHERE m.ID_MODULE = d.ID_MODULE AND m.ID_MODULE=".$_POST['modulus']."");

    $get_Classe = $obj->Requete("SELECT * FROM module m, devoirs d WHERE m.ID_MODULE = d.ID_MODULE AND m.ID_MODULE=".$_POST['modulus']."")->fetch();
                
    $class_name = $obj->Requete("SELECT * FROM classe WHERE ID_CLASSE=".$get_Classe['ID_CLASSE']."")->fetch()['NOM_CLASSE'];

    $note_list = 0;
    $note_column=array();
    $note_value=array();

    while($rep = $note1->fetch()){
        $note_list++;
        array_push($note_column,'NOTE'.$note_list);
        
    }
        
    $resul_set = array();
    
    while ($row = $requetes->fetch()) {

        $matri = $row["MATRICULE"];
        $nom = $row["NOM"];
        $prenom = $row["PRENOM"];
        $module = $row['ID_MODULE'];
        
        
        if(in_array("NOTE1",$note_column) == false){
                $note1 = "#"; 
            }else{
                 if(!empty($row[$note_column[0]]))
                {

                     $note1 = $row[$note_column[0]];
                }
                else
                {
                     $note1 = "Waiting";
                }
         }
         if(in_array("NOTE2",$note_column) == false){
                $note2 = "#"; 
            }else{
                 if(!empty($row[$note_column[1]]))
                {

                    $note2 = $row[$note_column[1]];
                }
                else
                {
                    $note2 = "Waiting";
                }
             
         }
        
         if(in_array("NOTE3",$note_column) == false){
                $note3 = "#"; 
            }
        else{
             
                if(!empty($row[$note_column[2]]))
                {

                    $note3 = $row[$note_column[2]];
                }
                else
                {
                    $note3 = "Waiting";
                }
         }
        
        
        if(!empty($row["NOTE_PARTICIPATION"]))
        {
            $note_parti = $row["NOTE_PARTICIPATION"];
        }
        else
        {
            $note_parti = "waiting";
        }

        
        if(!empty($row["NOTE_ADMINISTRATION"]))
        {
            $note_admin = $row["NOTE_ADMINISTRATION"];
        }
        else
        {
            $note_admin = "waiting";
        }

        if(!empty($row["SANCTION"]))
        {
            $sanction = $row["SANCTION"];
        }
        else
        {
            $sanction = "waiting";
        }

        if(!empty($row["MOYENNE"]))
        {
            $moyenne = $row["MOYENNE"];
        }
        else
        {
            $moyenne = "waiting";
        }


       array_push($resul_set, ["MATRICULE" => $matri,
                        "ID_MODULE" => $module,
                        "NOM" => $nom,
                        "PRENOM" => $prenom,
                        "NOTE1"=>$note1,
                        "NOTE2"=>$note2,
                        "NOTE3"=>$note3,
                        "NOTE_PARTICIPATION" => $note_parti,
                        "NOTE_ADMINISTRATION" => $note_admin,
                        "SANCTION" => $sanction,
                        "MOYENNE" => $moyenne
                               ]);             

    }
    
    if(isset($_POST['module'])){
        $verify_admin_partici = $obj->Requete("SELECT * from notes where ID_MODULE=".$_POST['module']." AND NOTE_ADMINISTRATION!='NULL' AND NOTE_PARTICIPATION !='NULL'");
        if($verify_admin_partici->fetch()){
            $id_mod = 1;
        }else{
            $id_mod = 0;
        }
        array_push($resul_set,["id_module"=>$id_mod]);
    }
array_push($resul_set,["classe"=>$class_name]);
    
    
    print (json_encode($resul_set));
