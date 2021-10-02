<?php
    $title = 'Surveys';
    $breadcrumb = 'List';

    include('../../../utilities/QueryBuilder.php');
    include('header.php');
    
    $obj= new QueryBuilder();
    $filieres = $obj->Requete("SELECT ID_FILIERE , NOM_FILIERE FROM filieres")->fetchAll();
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-lg-8">All Surveys</div>
                        <div class="col-lg-4 text-right">
                            <form action="" method="post">
                                <div class="form-group my-auto">
                                    <select name="classroom" id="classroom" class="form-control"  onchange="load_classes(this.value)">
                                        <option value="all">All Classes</option>
                                        <?php
                                            foreach ($filieres as $filiere):
                                        ?>
                                    
                                            <optgroup label="<?= $filiere["NOM_FILIERE"] ?>">
                                                <?php
                                                    $classes = $obj->Requete("SELECT ID_CLASSE , NOM_CLASSE FROM classe WHERE ID_FILIERE = '".$filiere["ID_FILIERE"]."'")->fetchAll();
                                                    foreach ($classes as $class):
                                                ?>
                                                    <option value="<?= $class["ID_CLASSE"] ?>"><?= $class["NOM_CLASSE"] ?></option>
                                                <?php
                                                    endforeach;
                                                ?>
                                            </optgroup>

                                        <?php
                                            endforeach;
                                        ?>
                                    </select>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body">   
                    <div class="row" id="classroom_area">
                       
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php
    include("../footer.php");
?>

<script>
    function load_classes(param) 
    {  
        $.ajax({
            url: "../../../ajax.php?action=load_class&classroom="+param,
            method: "post",
            dataType: "text",
            success: function (response) {
               console.log(response)
               $("#classroom_area").html(response);
            }
        })
    }

    window.onload(load_classes('all'));

</script>