<?php
    include '../../../utilities/QueryBuilder.php';
    $title = 'Surveys';
    $breadcrumb = 'Create new survey';
    $obj= new QueryBuilder();
    $class=$obj->Select('classe',[],[]);
    $module= $obj->Select('module',[],[]);
    include('header.php');

    if(isset($_SESSION['new_survey']) && $_SESSION['new_survey'] == 1)
    {
        alert('success' , "Survey created Successfully.");
        unset($_SESSION['new_survey']);
    }
    
    if(isset($_POST['new_survey'])){
        $requete = $obj->Insert('survey_set',array('ID_MODULE','START_DATE','END_DATE','SURVEY_DESCRIPTION','ID_ANNEE'),array($_POST['modulus'],$_POST['start_date'],$_POST['end_date'],$_POST['description'],getAnnee(0)['ID_ANNEE']));
        if ($requete) 
        {
            $_SESSION['new_survey'] = 1;
            echo("<script>location.assign('../refresh.html')</script>");
        }
    }
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6">
                            <h4 class="text-bluesky">New Survey</h4>
                        </div>
                        <div class="col-6 text-right">
                            <a href="./surveys.php" class="btn btn-dark rounded-pill">See all surveys</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="" method="post">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="row border-right border-border-left-dark">

                                    <!-- Modulus -->
                                    <div class="col-lg-6 py-3">
                                        <div class="group-form">
                                            <label for="class" class="font-weight-bold">Class <span class="text-danger"> *</span></label>
                                            <?php try {?>
                                                    <select onchange="getModules(this.value,0)" type="text" class="form-control" name="classe" >
                                                        <option value="" disabled selected>Select The Module</option>
                                                        <?php   while ($cla=$class->fetch()){?>
                                                          <option value="<?= $cla['ID_CLASSE']?>"><?= $cla['NOM_CLASSE']?></option>
                                                        <?php  } ?>
                                                    </select>
                                                <?php }catch (Exception $e){?>

                                                <div>Sorry, No registered class yet</div>
                                           <?php };?>

                                        </div>
                                    </div>

                                    <div class="col-lg-6 py-3">
                                        <div class="group-form" id="container-select">
                                            <label for="modulus" class="font-weight-bold">Modulus <span class="text-danger"> *</span></label>

                                            <?php try {?>
                                                <select type="text" class="form-control" name="modulus" id="modulus" disabled>

                                                </select>
                                            <?php }catch (Exception $e){?>

                                                <div>Sorry, No registered moduls yet</div>
                                            <?php };?>



                                        </div>
                                    </div>

                                    <!-- Start Date -->
                                    <div class="col-lg-12 py-3">
                                        <div class="group-form">
                                            <label for="start_date" class="font-weight-bold">Start Date <span class="text-danger"> *</span></label>
                                            <input type="date" class="form-control" name="start_date" id="start_date" required>
                                        </div>
                                    </div>

                                    <!-- End Date -->
                                    <div class="col-lg-12 py-3">
                                        <div class="group-form">
                                            <label for="end_date" class="font-weight-bold">End Date <span class="text-danger"> *</span></label>
                                            <input type="date" class="form-control" name="end_date" id="end_date" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="row">
                                    <div class="col-lg-12 py-3">
                                        <div class="group-form">
                                            <label for="description" class="font-weight-bold">Description <span class="text-danger"> *</span></label>
                                            <textarea class="form-control" name="description" id="description" style="height:240px;"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12 text-center">
                                <button type="reset" class="btn btn-outline-danger rounded-pill">Reset</button>
                                <button type="submit" class="btn btn-outline-success rounded-pill" name="new_survey" id="new_survey">Create survey</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
    include('../footer.php');
?>

<script>
    // Ajax pour selectionner les modules de la classe
    function getModules(id,prof_id) {
        console.log(id);
        $.post(
            '../../../ajax.php',
            {
                getMd: 'modul',
                id_classe: id,
                id_prof:prof_id,
            },
            function (donnees) {
                if (donnees != 'none') {
                    $('#modulus').html(donnees);
                    $('#modulus').removeAttr('disabled');
                }
                else
                {
                    $('#modulus').html('<option disabled selected> Empty modules or modules not assign to teacher </option>');
                }
                
                
                
                console.log(donnees);
            });
    }



</script>
