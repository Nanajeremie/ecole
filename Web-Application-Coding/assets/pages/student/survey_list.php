<?php
    $title = 'Surveys';
    $breadcrumb = 'View Survey';

    include('../../../utilities/QueryBuilder.php');
    include('header.php');  

      if(isset($_GET['survey_question_answered']) && $_GET['survey_question_answered'] == 1)
    {
        alert('success' , "Thank you for taking this survey!");
        $_GET['survey_question_answered'] = 0;
    }

    $obj= new QueryBuilder();

    $answers = $obj->Requete("SELECT distinct(SURVEY_ID) from answers where ID_USER = '". $_SESSION['ID_USER'] ."' AND ID_ANNEE = '".getAnnee(0)['ID_ANNEE']."'");
   
    $ans = array();
    
    while($row=$answers->fetch())
    {
        $ans[$row['SURVEY_ID']] = 1;
    }

    if(isset($_SESSION['question_answered']) && $_SESSION['question_answered'] == 1)
    {
        alert('success', 'Survey Completed Successfully ! Thank you.');
        unset($_SESSION['question_answered']);
    }

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">   
                    <div class="row">
                        <?php 
                            $survey = $obj->Requete("SELECT * FROM survey_set s, module m , classe c, inscription i, etudiant e where e.ID_USER = '" .$_SESSION["ID_USER"]. "' AND i.MATRICULE = e.MATRICULE AND m.ID_MODULE = s.ID_MODULE AND c.ID_CLASSE = m.ID_CLASSE AND i.ID_CLASSE = m.ID_CLASSE AND '".date('Y-m-d')."' between date(start_date) and date(end_date) order by rand() AND s.ID_ANNEE = '".getAnnee(0)['ID_ANNEE']."'");
                            while($row=$survey->fetch()):
                        ?>

                        <div class="col-lg-4 col-md-6 mb-5 mb-md-5 mb-lg-0">
                            <div class="card">
                                <div class="card-header">
                                    <?= $row['NOM_MODULE'] ?>
                                </div>
                                <div class="card-body">
                                    <p class="text-justify"><?= $row['SURVEY_DESCRIPTION'] ?></p>
                                
                                    <div class="row">
                                        <div class="col-lg-12 text-center my-3">
                                            <?php if(!isset($ans[$row['ID_SURVEYS']])): ?>
                                                <a href="<?= 'answer_survey.php?id='.$row['ID_SURVEYS'].'' ?>" class="btn btn-dark">Take the survey</a>
                                            <?php else: ?>
                                                <p class="btn btn-success">Done</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php
                            endwhile;
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
    include('../footer.php');
?>