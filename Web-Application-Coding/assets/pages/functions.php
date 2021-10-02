<?php
function MatriculAttrib()
{
    $obj = new QueryBuilder();
    //SELECTION OF THE AMOUNT OF EXISTING ROWS
    $total_rows = $obj->Requete('SELECT COUNT(*) AS MAXI FROM etudiant');
    $total_rows = $total_rows->fetch();
    $total_rows = $total_rows['MAXI'];

    //INCREMENTATION OF THE TOTAL ROW
    $total_rows++;

    //SET OF THE NUMBER OF CHARACTERS OF THE $total_rows
    $t_rows_len = strlen($total_rows);

    //SET OF THE MAXIMUM NUMBER OF ZERO
    //we start to open the file containing the maximum of number in read and modifiy mode
    $file = fopen('../max.txt', 'r+');
    //read and affecting the cntent of the file in the variable $max_zero
    $max_zeros = fgets($file);
    //echo $max_zeros.'<br>';

    //CHECK THAT THE LENGTH OF THE NUMBER OF ROWS IS LESS OR EQUAL TO THE MAX OF ZEROS
    if ($t_rows_len <= $max_zeros) {
        $matricul = 'CSCI';
        for ($i = 0; $i < $max_zeros - $t_rows_len; $i++) {
            $matricul .= '0';
        }
        $matricul .= $total_rows;
    } //OTHERWISE, WE INCREMENT THE NUMBER OF ZEROS
    else {
        /*if (isset($_POST['update']))
        {*/
        $max_zeros++;
        //we set the cursor at the beginning of the file
        fseek($file, 0);
        //we now input the incremented number
        fputs($file, $max_zeros);
        $matricul = 'bs';
        for ($i = 0; $i < $max_zeros - $t_rows_len; $i++) {
            $matricul .= '0';
        }
        $matricul .= $total_rows;
        //}
    }
    //we close the file
    fclose($file);
    return $matricul;
}

// Creer le 26/09/2021 par Jeremie Cette fonction permet de filtrer les donnees des champs
function filtreInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

/*
     * uniquement pour ce proget de dev bit
     * A commenter dans un autre projet
     */
function getAnnee($id_annee)
{

    $obj = new QueryBuilder();
    if ($id_annee == 0) {
        $inscrit = $obj->Requete('SELECT * FROM annee_scolaire WHERE DATE_FIN IS NULL ORDER BY ID_ANNEE DESC LIMIT 1 ');
        $inscrit = $inscrit->fetch();
        return $inscrit;
    }

    else if ($id_annee == -1){
        $inscrit = $obj->Requete('SELECT * FROM annee_scolaire WHERE DATE_FIN IS NOT NULL ORDER BY ID_ANNEE DESC LIMIT 1 ');
        $inscrit = $inscrit->fetch();
        return $inscrit;
    }
    else {
        $inscrit = $obj->Select('annee_scolaire', array(), array('ID_ANNEE' => $id_annee));
        $inscrit = $inscrit->fetch();
        return $inscrit;
    }
}

forcer_la_connection();

function droit_acces()
{
    //gets the pathname of the file
    $link = dirname($_SERVER['SCRIPT_NAME'], 2);
    //if the user rights doesn't exist in the page url
    if(!strstr($_SERVER['SCRIPT_NAME'] , $_SESSION['DROITS']))
    {
        $link .= "/".$_SESSION['DROITS']."/index.php";
        //we directs him to the right folder to which he has right
        Redirect($link);  
    }
}
//if we're in 'pages' folder we can call 'droit_acces' function
if (!strstr($_SERVER['SCRIPT_NAME'] , $_SESSION['DROITS']) AND strstr($_SERVER['SCRIPT_NAME'] , 'pages'))
{
    //call droit_acces()
    droit_acces();
}
////////////////////
function LogRead($path = '')
{
	$all_logs_variables = array();
	//checks if the path isnt empty
	if (!empty($path)) 
	{
		//gets the real path of the file/folder
		$path = realpath($path);
		//opening the file
		$logs_record = fopen($path.DIRECTORY_SEPARATOR.'logs_record.txt', 'r');
		//starts reading the file
		while ($line_to_read = fgets($logs_record))
		{
			//creation of an array function of the line and the delimiter |
			$logs_variables = explode('|', $line_to_read);
			//appending the current line to other
			$all_logs_variables[] = $logs_variables;
		}
	}
	//if $all_logs_variables isn't empty
	if (!empty($all_logs_variables))
	{
		return $all_logs_variables;
	}
	//otherwise, we return 0
	else
	{
		return 0;
	}
}

//droit_acces();


// filtrage de la liste de l'historique bourse
function fill_histor_brouse($new,$last){
$norm_liste = array();
$liste_matri = array();

$new_array = array();
$matricule = array();

while($news = $new->fetch()){
    array_push($new_array,$news);
    array_push($matricule,$news['MATRICULE']);
}
if(!is_object($last) ){
    while($laste = $last->fetch()){
        if(in_array($laste['MATRICULE'],$matricule) ==false){
            array_push($new_array,$laste);
        }
    }
}
$liste =  $new_array;
$i = 0;
while( $i<count($liste) AND $listes=$liste[$i]){
    $i++;
    if(in_array($listes['MATRICULE'],$liste_matri)==false){
        array_push($norm_liste,$listes);
        array_push($liste_matri,$listes['MATRICULE']);
    }

}
return $norm_liste;
}

// filtrage des boursies avec condition 
function fill_bourse($new,$last){
    $new_array = array();
    $matricule = array();

    while($news = $new->fetch()){
        array_push($new_array,$news);
        array_push($matricule,$news['MATRICULE']);
    }

    while($laste = $last->fetch()){
        if(!in_array($matricule,$laste['MATRICULE'])){
            array_push($new_array,$laste);
        }
    }
return $new_array;
}

// @Jeremie=> Cette fonction concern la parte data_bank et permet de recuperer l'extension du fichier et le lien de la page et retourner le dossier dans lequel le fichier doit etre enregistrer
function filter_file($lien,$extension,$fname){
    $locate = " ";
    $video = [".mp4",".avi",".mpg",".m4p",".webm",".ogg",".mp2",".mov"];
    $pdf = [".pdf"];
    $word = [".docx",".doc"];
    $excel = [".xls",".xlsx"];
    $txt = [".txt"];
    $image = [".png",".jpeg",".jpg"];
    $audio = [".mp3"];
    if(in_array(strtolower($extension),$video)){
        $locate = $lien."/video/".$fname;
    }
    elseif(in_array(strtolower($extension),$pdf)){
        $locate = $lien."/pdf/".$fname;
    }
    elseif(in_array(strtolower($extension),$word)){
        $locate = $lien."/word/".$fname;
    }
    elseif(in_array(strtolower($extension),$txt)){
        $locate = $lien."/txt/".$fname;
    }
    elseif(in_array(strtolower($extension),$image)){
        $locate = $lien."/photo/".$fname;
    }elseif(in_array(strtolower($extension),$audio)){
        $locate = $lien."/audio/".$fname;
    }
    else{
        $locate = $lien."/others/".$fname;
    }
    return $locate;
}

//@Jeremie=> Cette fonction permet de formater les cartes pour affichage des carte
function set_card($elements,$obj){
    $video = [".mp4",".avi",".mpg",".m4p",".webm",".ogg",".mp2",".mov"];
    $pdf = [".pdf"];
    $word = [".docx",".doc"];
    $excel = [".xls",".xlsx"];
    $txt = [".txt"];
    $image = [".png",".jpeg",".jpg"];
    $audio = [".mp3"];
    $liste_card = '
        <div class="container">
            <div class="row">';
    $elements = $elements->fetchAll();
    foreach($elements as $elm){
        $get_infos =$obj->query("SELECT PRENOM from etudiant e, inscription i where i.MATRICULE = e.MATRICULE AND i.MATRICULE ='".$elm['MATRICULE']."' ORDER BY ID_INSCRIPTION DESC LIMIT 1")->fetch();
        
        $extension = explode(".",$elm['FILE_NAME']);
        
        $extension = ".".$extension[count($extension)-1];
        if(in_array(strtolower($extension),$video)){
            
        $liste_card .='<div class="card  col-sm-6 col-md-4 col-lg-3 my-2">
           <div class="row">
                <div class="card-header bg-info col-12 text-white">
                    <div class="row">
                        <p class="col-9">'.$elm['TITRE'].'</p>
                        <img class="col-3" src="data-bank/upload-files/video/vlc.png" alt="" width="30px" height="30px">
                    </div>
                </div>
           </div>

            <div class="card-body">
                <div class="row my-3">
                    <video src="data-bank/upload-files/video/'.$elm['FILE_NAME'].'" poster="../../../assets/media/logo_bit.png" controls class="col-12" style="width:100%;height:100%;"></video>
                </div>
            </div>
            <div class="card-footer">
                <div class="file-info">
                    <div class="file-venue">
                        <span>
                            <em>'.$elm['MODULE'].'</em>/<em> '.$elm['CREATE_DATE'].'</em>/
                        <strong>'.$get_infos['PRENOM'].'</strong>

                        </span>
                    </div>
                </div>
                <div class="file-action row mt-3 text-center">
                    <div class="download col-12">
                        <span>
                            <a href="data-bank/upload-files/video/'.$elm['FILE_NAME'].'" role="button" class="w-100 btn btn-primary rounded-pill" download="data-bank/upload-files/video/'.$elm['FILE_NAME'].'" poster="../../../assets/media/logo_bit.png">download</a>
                        </span>
                    </div>
                </div>
            </div>
        </div>';
            
        } 
        elseif(in_array(strtolower($extension),$pdf) or in_array(strtolower($extension),$word) or in_array(strtolower($extension),$excel) or in_array(strtolower($extension),$txt)){
            $liste_card .='
        <div class="card col-sm-6 col-md-4 col-lg-3 my-2">
           <div class="row">
                <div class="card-header bg-primary text-white col-12">
                    <div class="row">
                        <p class="col-9">'.$elm['TITRE'].'</p>
                        ';
                            if(in_array(strtolower($extension),$pdf)){
                                $liste_card.='<img class="col-3" src="data-bank/upload-files/pdf/pdf.png" alt="" width="30px" height="30px">';
                            }
                            elseif(in_array(strtolower($extension),$word)){
                                $liste_card.='<img class="col-3" src="data-bank/upload-files/word/microsoft_word.png" alt="" width="30px" height="30px">';
                            }
                            elseif(in_array(strtolower($extension),$excel)){
                                $liste_card.='<img class="col-3" src="data-bank/upload-files/excel/microsoft_excel.png" alt="" width="30px" height="30px">';
                            }
                            elseif(in_array(strtolower($extension),$txt)){
                                $liste_card.='<img class="col-3" src="data-bank/upload-files/txt/page.png" alt="" width="30px" height="30px">';
                            }
            $liste_card.='
                    </div>
                </div>
           </div>
            <div class="card-body">
                <div class="row">
                    <div class="file-img my-3 text-center">
                        <img class="img-fluid" src="../../../assets/media/logo_bit.png" alt="" style="width:100%">
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="file-info">
                    <div class="file-venue">
                        <span>
                            <em>'.$elm['MODULE'].'</em>/<em> '.$elm['CREATE_DATE'].'</em>/
                        <strong>'.$get_infos['PRENOM'].'</strong>

                        </span>
                    </div>
                </div>
                <div class="file-action row mt-3 text-center">';
                
                if(in_array(strtolower($extension),$pdf)){
                    $liste_card.='
                    <div class="download col-12">
                        <span>
                            <a href="data-bank/upload-files/pdf/'.$elm['FILE_NAME'].'" role="button" class="w-100 btn btn-primary rounded-pill" download="data-bank/upload-files/video/'.$elm['FILE_NAME'].'" poster="../../../assets/media/logo_bit.png">download</a>
                        </span>
                    </div>';
                }
                elseif(in_array(strtolower($extension),$word)){
                    $liste_card.='
                    <div class="download col-12">
                        <span>
                            <a href="data-bank/upload-files/word/'.$elm['FILE_NAME'].'" role="button" class="w-100 btn btn-primary rounded-pill" download="data-bank/upload-files/video/'.$elm['FILE_NAME'].'" poster="../../../assets/media/logo_bit.png">download</a>
                        </span>
                    </div>';
                }
                elseif(in_array(strtolower($extension),$excel)){
                    $liste_card.='
                    <div class="download col-12">
                        <span>
                            <a href="data-bank/upload-files/excel/'.$elm['FILE_NAME'].'" role="button" class="w-100 btn btn-primary rounded-pill" download="data-bank/upload-files/video/'.$elm['FILE_NAME'].'" poster="../../../assets/media/logo_bit.png">download</a>
                        </span>
                    </div>';
                }
                elseif(in_array(strtolower($extension),$txt)){
                    $liste_card.='
                    <div class="download col-12">
                        <span>
                            <a href="data-bank/upload-files/txt/'.$elm['FILE_NAME'].'" role="button" class="w-100 btn btn-primary rounded-pill" download="data-bank/upload-files/video/'.$elm['FILE_NAME'].'" poster="../../../assets/media/logo_bit.png">download</a>
                        </span>
                    </div>';
                }
                    
                $liste_card.='</div>
            </div>
            </div>';
        }
        elseif(in_array(strtolower($extension),$image)){
            $liste_card .='<div class="card col-sm-6 col-md-4 col-lg-3 my-2">
           <div class="row">
                <div class="card-header bg-primary text-white col-12">
                    <div class="row">
                        <p class="col-9">'.$elm['TITRE'].' </p>
                        <img class="col-3" src="data-bank/upload-files/photo/image.png" alt="" width="30px" height="30px">
                    </div>
                </div>
           </div>

            <div class="card-body">
                <div class="row my-3">
                    <img src="data-bank/upload-files/photo/'.$elm['FILE_NAME'].'" style="width:100%;height:100%" alt="'.$elm['FILE_NAME'].'">
                </div>
            </div>
            <div class="card-footer">
                <div class="file-info">
                    <div class="file-venue">
                        <span>
                            <em>'.$elm['MODULE'].'</em>/<em> '.$elm['CREATE_DATE'].'</em>/
                        <strong>'.$get_infos['PRENOM'].'</strong>

                        </span>
                    </div>
                </div>
                <div class="file-action row mt-3 text-center">
                    <div class="download col-12">
                        <span>
                            <a href="data-bank/upload-files/photo/'.$elm['FILE_NAME'].'" role="button" class="w-100 btn btn-primary rounded-pill" download="data-bank/upload-files/photo/'.$elm['FILE_NAME'].'">download</a>
                        </span>
                    </div>
                </div>
            </div>
        </div>';
        }
        elseif(in_array(strtolower($extension),$audio)){
            $liste_card .='<div class="card col-sm-6 col-md-4 col-lg-3 my-2">
           <div class="row">
                <div class="card-header bg-warning col-12 text-light">
                    <div class="row">
                        <p class="col-9">'.$elm['TITRE'].'</p>
                        <img class="col-3" src="data-bank/upload-files/audio/audio.png" alt="audio.png" width="30px" height="30px">
                    </div>
                </div>
           </div>

            <div class="card-body">
                <div class="row my-3">
                    <audio src="data-bank/upload-files/audio/'.$elm['FILE_NAME'].'" controls class="col-12"></audio>
                </div>
                
            </div>
            <div class="card-footer">
                <div class="file-info">
                    <div class="file-venue">
                        <span>
                            <em>'.$elm['MODULE'].'</em>/<em> '.$elm['CREATE_DATE'].'</em>/
                        <strong>'.$get_infos['PRENOM'].'</strong>

                        </span>
                    </div>
                </div>
                <div class="file-action row mt-3 text-center">
                    <div class="download col-12">
                        <span>
                            <a href="data-bank/upload-files/audio/'.$elm['FILE_NAME'].'" role="button" class="w-100 btn btn-primary rounded-pill" download="data-bank/upload-files/audio/'.$elm['FILE_NAME'].'">download</a>
                        </span>
                    </div>
                </div>
            </div>
        </div>';
            
        }
    
    }
    $liste_card.='</div>
</div>';
    return $liste_card;
}





?>