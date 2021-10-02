<?php
    $title = 'Surveys';
    $breadcrumb = 'View report';

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

    $answer = $obj->Requete("SELECT a.*, q.TYPE FROM questions q , answers a WHERE q.ID_QUESTIONS = a.QUESTION_ID AND a.SURVEY_ID = '". $_GET['id'] ."' AND a.ID_ANNEE = '".getAnnee(0)['ID_ANNEE']."'");
    $ans = array();

    while($row = $answer->fetch())
    {
        if($row["TYPE"] == 'radio_opt')
        {
            $ans[$row['QUESTION_ID']][$row['ANSWER']][] = 1;
        }
        if($row['TYPE'] == 'check_opt'){
            foreach(explode(",", str_replace(array("[","]"), '', $row['ANSWER'])) as $v){
            $ans[$row['QUESTION_ID']][$v][] = 1;
            }
        }
        if($row['TYPE'] == 'textfield_s'){
            $ans[$row['QUESTION_ID']][] = $row['ANSWER'];
        }
    }
?>

<div class="container-fluid">
    <div class="row">
        
        <div class="col-lg-4">
            <div class="card border-top-primary my-5 my-md-3 my-lg-0">
                <div class="card-header"><p class="font-weight-bold">Survey Detail</p></div>
                <div class="card-body">
                    <p><span class="font-weight-bold">Modulus :</span> <?=$surveys['NOM_MODULE']?></p>
                    <p><span class="font-weight-bold">Teacher :</span> <?=$surveys['NOM_PROF']. ' ' .$surveys['PRENOM_PROF']?></p>
                    <p class="text-justify"><span class="font-weight-bold">Description : </span><span class="small"><?= $surveys['SURVEY_DESCRIPTION'] ?></span></p>
                    <p><span class="font-weight-bold">Start Date :</span> <?= date("M d, Y",strtotime($surveys['START_DATE'])) ?></p>
                    <p><span class="font-weight-bold">End Date :</span> <?= date("M d, Y",strtotime($surveys['END_DATE'])) ?></p>
                    <p class="text-primary"><span class="font-weight-bold">Have Taken :</span> <?= $answers['total'] ?></p>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card border-top-success my-5 my-md-3 my-lg-0">
                <div class="card-header">
                    <div class="row">
                        <div class="col"><p class="font-weight-bold">Survey report</p></div>
                        <div class="col text-right">
                            <button class="btn btn-dark" onclick="print" id="print_me"><i class="fas fa-print"></i> Print</button>
                        </div>
                    </div>
                </div>
                <div class="card-body" id="survey_report">
                    <?php 
                        $question = $obj->Requete("SELECT * FROM questions where SURVEY_ID = '".$ID_SURVEYS ."' AND ID_ANNEE = '". getAnnee(0)['ID_ANNEE'] ."'order by abs(ORDER_BY) asc, abs(ID_QUESTIONS) asc");
                        while($row=$question->fetch()):	
                    ?>
                        <div class="card border-left-primary my-3 my-md-4 my-lg-4 px-3 px-sm-4 px-lg-4 pt-2">
                            <h5><?= $row['QUESTION'] ?></h5>	
                            <div class="col-md-12">
                            <input type="hidden" name="qid[<?= $row['ID_QUESTIONS'] ?>]" value="<?= $row['ID_QUESTIONS'] ?>">	
                            <input type="hidden" name="type[<?= $row['ID_QUESTIONS'] ?>]" value="<?= $row['TYPE'] ?>">	
                                
                                <?php if($row['TYPE'] != 'textfield_s'):?>
                                    <ul>
                                        <?php 
                                            foreach(json_decode($row['FRM_OPTION']) as $k => $v): 
                                                
                                                $prog = 0;
                                                if(isset($ans[$row['ID_QUESTIONS']][$k]))
                                                {
                                                    try {
                                                        $prog = (count($ans[$row['ID_QUESTIONS']][$k]) / $answers['total']) * 100;
                                                    } 
                                                    catch (\Throwable $th) {
                                                        //throw $th;
                                                    }
                                                }

                                                    
                                                $prog = round($prog,2);
                                                
                                        ?>
                                        <li class="py-2">
                                            <div class="d-block w-100">
                                                <b><?= $v ?></b>
                                            </div>
                                            <div class="d-flex w-100">
                                                <span class=""><?= isset($ans[$row['ID_QUESTIONS']][$k]) ? count($ans[$row['ID_QUESTIONS']][$k]) : 0 ?>/<?= $answers['total'] ?></span>
                                                
                                                <div class="mx-1 col-sm-8">
                                                    <div class="progress w-100">
                                                        <div class="progress-bar <?php if($prog < 10){echo("bg-danger");} ?> progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $prog ?>%">
                                                            <span class="sr-only"><?= $prog ?>%</span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <span class="badge badge-info"><?php echo $prog ?>%</span>
                                            </div>
                                        </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php else: ?>
                                    <?php if(isset($ans[$row['ID_QUESTIONS']])): ?>
                                        <?php foreach($ans[$row['ID_QUESTIONS']] as $val): ?>
                                            <div class="card my-2">
                                                <div class="card-body">
                                                    <p><?= $val ?></p>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>	
                        </div>
					<?php endwhile; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
    include("../footer.php");
?>

<script>        
    print('print_me', 'survey_report');
</script>