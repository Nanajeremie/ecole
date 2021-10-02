<?php
    $title = 'Surveys';
    $breadcrumb = 'Answer Survey';

    include('../../../utilities/QueryBuilder.php');
    include('header.php');
    
    $obj= new QueryBuilder();

    if (isset($_GET['id'])) 
    {   

        $surveys = $obj->Requete("SELECT * FROM survey_set s , module m , classe c , enseigner e , professeur p WHERE s.ID_MODULE = m.ID_MODULE AND m.ID_CLASSE = c.ID_CLASSE AND e.ID_PROFESSEUR = p.ID_PROFESSEUR AND e.ID_MODULE = m.ID_MODULE AND s.ID_SURVEYS = '".$_GET['id']."' AND s.ID_ANNEE = '".getAnnee(0)['ID_ANNEE']."'")->fetch();
        $answers = $obj->Requete("SELECT COUNT(distinct(ID_USER)) as total from answers where survey_id = '".$_GET['id']."' AND ID_ANNEE = '".getAnnee(0)['ID_ANNEE']."'")->fetch();
        $qry = $obj->Requete("SELECT * FROM survey_set where ID_SURVEYS = '".$_GET['id']."' AND ID_ANNEE = '".getAnnee(0)['ID_ANNEE']."'")->fetch();
        
        foreach($qry as $k => $v)
        {
            $$k = $v;
        }
    }


    
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-4 wow slideInLeft">
            <div class="card border-top-primary mt-3 mt-md-3 mt-lg-0">
                <div class="card-header">
                    <h6 class="font-weight-bold">Survey Details</h6>
                </div>

                <div class="card-body">
                    <p><span class="font-weight-bold">Modulus :</span> <?=$surveys['NOM_MODULE']?></p>
                    <p><span class="font-weight-bold">Teacher :</span> <?=$surveys['NOM_PROF']. ' ' .$surveys['PRENOM_PROF']?></p>
                    <p class="text-justify"><span class="font-weight-bold">Description : </span><span class="small"><?= $surveys['SURVEY_DESCRIPTION'] ?></span></p>
                    <p><span class="font-weight-bold">Start Date :</span> <?= date("M d, Y",strtotime($surveys['START_DATE'])) ?></p>
                    <p><span class="font-weight-bold">End Date :</span> <?= date("M d, Y",strtotime($surveys['END_DATE'])) ?></p>
                </div>
            </div>
        </div>

        <div class="col-lg-8 wow slideInRight">
            <div class="card border-top-success my-4 my-md-3 my-lg-0">
                <div class="card-header">
                    <div class="row">
                        <div class="col">
                            <h6 class="font-weight-bold">Survey questionaire</h6>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <form action="" id="answer_survey" method="POST">
                        <div class="row">
                            <input type="hidden" name="survey_id" value="<?= $_GET['id'] ?>">
                        
                            <div class="card-body">
                                <?php 
                                    $question = $obj->Requete("SELECT * FROM questions where SURVEY_ID = '".$ID_SURVEYS ."' AND ID_ANNEE = '". getAnnee(0)['ID_ANNEE'] ."'order by abs(ORDER_BY) asc, abs(ID_QUESTIONS) asc");
                                    while($row=$question->fetch()):	
                                ?>
                                    <div class="card border-left-secondary mb-3 mb-md-4 mb-lg-5">
                                        <div class="card-body">
                                            <h6><?= $row['QUESTION'] ?></h6>	
                                        
                                            <input type="hidden" name="qid[<?= $row['ID_QUESTIONS'] ?>]" value="<?= $row['ID_QUESTIONS'] ?>">	
                                            <input type="hidden" name="type[<?= $row['ID_QUESTIONS'] ?>]" value="<?= $row['TYPE'] ?>">	
                                            
                                            <?php
                                                if($row['TYPE'] == 'radio_opt'):
                                                    foreach(json_decode($row['FRM_OPTION']) as $k => $v):
                                            ?>
                                                    <div class="icheck-primary">
                                                        <input type="radio" id="option_<?= $k ?>" name="answer[<?= $row['ID_QUESTIONS'] ?>]" value="<?= $k ?>" required>
                                                        <label for="option_<?= $k ?>"><?= $v ?></label>
                                                    </div>
                                                    <?php endforeach; ?>
                                            <?php 
                                                elseif($row['TYPE'] == 'check_opt'): 
                                            ?>
                                                <div class="icheck-primary">
                                                    <?php
                                                        foreach(json_decode($row['FRM_OPTION']) as $k => $v):
                                                    ?>
                                                        <div class="checkbox-group required">
                                                            <input type="checkbox" id="option_<?= $k ?>" name="answer[<?= $row['ID_QUESTIONS'] ?>][]" value="<?= $k ?>">
                                                            <label for="option_<?= $k ?>"><?= $v ?></label>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php 
                                                else: 
                                            ?>
                                                    <div class="form-group">
                                                        <textarea name="answer[<?= $row['ID_QUESTIONS'] ?>]" cols="30" rows="4" class="form-control small" placeholder="Write Something Here..." required></textarea>
                                                    </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                <?php endwhile; ?>

                                <div class="col-lg-12 text-center">
                                    <input class="btn btn-outline-dark px-3 rounded-pill" type="button" value="Cancel" onclick="location.href = 'survey_list.php?'">
                                    <input class="btn btn-outline-primary px-3 rounded-pill " type="submit" name="answer_question" id="answer_question" onclick="answer_questions()" value="Submit Answer">
                                </div>

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
    
    function answer_questions() 
    {
        $.ajax({
            url : "../../../ajax.php?action=answer_questions", 
            data : $("#answer_survey").serialize(),
            method : 'post',
            dataType : 'text',
            success : function(data) {
                console.log(data);
                if(data == 'success')
                {
                    location.assign('./survey_list.php?survey_question_answered=1');
                }
                else
                {
                    toastr.error("An error occured");
                }
                
            },
        });       
    }
    
</script>