<?php

// creation de l'objet QueryBuilder
include('../../../utilities/QueryBuilder.php');
include('../../../assets/pages/functions.php');
$obj = new QueryBuilder();

if(isset($_FILES['file'])){

    $_fileType = $_FILES['file']['type'];
    $_fileName = $_FILES['file']['name'];
    $_file_Tmp = $_FILES['file']['tmp_name'];
    $_file_size = $_FILES['file']['size'];
    // $file_location = "data-bank/upload-files/photo/".$_fileName;
    $response= array(
        'status'=>-1,
        'message'=>"The file is successfully added"
    );
    
      if($_FILES['file']['error'] == 0 ){
        $extension = explode(".",$_fileName);
        $extension = ".".$extension[count($extension)-1];
        $extensions_array =array(".png",".jpeg",".jpg",".mpg",".mp2",".ogg",".mp4",".avi",".m4p",".webm",".pdf",".xls",".xlsx",".txt",".docx",".doc",".mp3",
    ".mov");
        $response['message'] ="oui les amis";
        if(in_array(strtolower($extension),$extensions_array)){
            $file_location = strval(filter_file("data-bank/upload-files",$extension,$_fileName));
            
            move_uploaded_file($_file_Tmp,$file_location);
            
            if($_file_size<=200000000){
                move_uploaded_file($_file_Tmp,$file_location);
                
                    $response['status']=1;

                    $response['message'] =$_fileName;
            }else{
                $response['message'] = "ERROR detected!! The weight of the chosen file is too high";
            }
        }else{
            $response['message'] = "ERROR detected!! The extension ".$extension." is not autorized";
        }
      }else{
        $response['message'] = "ERROR detected!! Failed to load the file. May be the file is too large";      
      }

      echo json_encode($response); 
}
if(isset($_POST['type'])){

    extract($_POST);
    if($type =="Insert"){
        $add_file_info = $obj->Insert('data_bank',['MATRICULE','MODULE','TITRE','FILE_NAME','DESCRIPTION','CREATE_DATE'],[$matricule,$name_module,$title,$file_name,$describ,'NOW()']);
    echo 1;
    }elseif($type=="Update"){
        if($file_name !=""){
            $add_file_info = $obj->Update('data_bank',['MODULE','TITRE','FILE_NAME','DESCRIPTION','CREATE_DATE'],[$name_module,$title,$file_name,$describ,'NOW()'],array("ID_DATA_BANK"=>$id));
            
        }else{
            $add_file_infos = $obj->Update('data_bank',['MODULE','TITRE','DESCRIPTION','CREATE_DATE'],[$name_module,$title,$describ,'NOW()'],array("ID_DATA_BANK"=>$id));
            
        }
       echo 2;
    }
    

}

?>
