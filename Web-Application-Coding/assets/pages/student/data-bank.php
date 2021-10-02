<?php 
    include('../../../utilities/QueryBuilder.php');
    $title = 'Data Bank Files list';
    $breadcrumb = 'Student'; 
    include_once ('header.php');

    $obj = new QueryBuilder();

    $academic_years_exist = $obj->Select('annee_scolaire', array(), array());
    //if any line of academic year exists in the database
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
    // Requetes pour afficher les fichiers uploader

    if(isset($_GET)){
        if(isset($_GET['p_numver']) and !empty($_GET['p_numver']) and $_GET['p_numver']>=1){
            extract($_GET);
            $p_numver = $p_numver;
            $p_numver2 = $p_numver;
            $p_numver3 = $p_numver;
            $depart = ($p_numver-1)*8;
        }else{$depart = 0;$p_numver=1;$p_numver2=1;$p_numver3=1;}
        
        $find_count_files = $obj->Select('data_bank',[ 'MODULE', 'TITRE', 'FILE_NAME', 'DESCRIPTION']);
        if(is_object($find_count_files)){
            $all_list = $find_count_files->rowCount();
            $all_list = ceil($all_list/8);
        }else{
            $all_list = 0;
        }
        
        $find_files=$obj->Requete("SELECT * from data_bank order by ID_DATA_BANK DESC limit ".$depart." , ".(8)."");
        
    } 
  
?> 
<style> 
        .file-img{
            overflow: hidden;
        } 

        .file-img img{
            transition: all 1.5s ease;
        } 

        .file-img:hover img{
            transform: scale(1.1);

        }

    </style>

<?php
if($all_list >0){
    echo set_card($find_files,$obj->pdo);
}else { ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 d-flex justify-content-center">
                <img src="data-bank/upload-files/photo/cover_file.jpg" width="500px" height="500px"  alt="">
            </div>
        </div>
    </div>
<?php } ?>

<div class="container mt-4">
    <div class="row ">
        <div class="col-12 d-flex justify-content-end">
            <nav aria-label="Something..">
                <ul class="pagination">
                   <?php if($depart<=1){?>
                    <li class="page-item disabled ">
                        <a class="page-link" href="#" tabindex="-1">Previous</a>
                    </li>
                    <?php }else{ $p_numver3--;?>
                    <li class="page-item">
                        <a class="page-link" href="data-bank.php?page=data-bank&p_numver=<?=$p_numver3?>" tabindex="-1">Previous</a>
                    </li>
                    <?php } ?>
                    <?php 
                        for($cpt = 1; $cpt<$all_list+1; $cpt++){?>
                            <li class="page-item" id="<?=$cpt?>">
                                <a class="page-link" href="data-bank.php?page=data-bank&p_numver=<?=$cpt?>" ><?=$cpt?></a>
                            </li>
                        <?php } ?>
                     <?php if($p_numver2==$all_list){?>
                    <li class="page-item disabled ">
                        <a class="page-link" href="#" tabindex="-1">Next</a>
                    </li>
                    <?php }else{ $p_numver2++;?>
                    <li class="page-item">
                        <a class="page-link" href="data-bank.php?page=data-bank&p_numver=<?=$p_numver2;?>" tabindex="-1">Next</a>
                    </li>
                    <?php } ?>
                </ul>
            </nav>
        </div>
    </div>
</div>


<?php
    include('../footer.php');
?>
<script>
// Script pour remplacer l'affichage standar de type="file" 
    $(".custom-file-input").on("change", function() {
    var fileName = $(this).val().split("\\").pop();
    $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });
    
//@jeremie=>fonction pour gerer la pagination

        var depart = <?=json_encode($p_numver)?>;
        $("#"+depart).addClass("disabled");
</script>
