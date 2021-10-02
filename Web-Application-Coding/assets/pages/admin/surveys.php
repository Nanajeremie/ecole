<?php
    $title = 'Surveys';
    $breadcrumb = 'Manage Surveys';

    include('../../../utilities/QueryBuilder.php');

    include('./header.php');


    if(isset($_SESSION['upt_survey']) && $_SESSION['upt_survey'] == 1)
    {
        alert('success' , "Survey created Successfully.");
        unset($_SESSION['upt_survey']);
    }

    $obj= new QueryBuilder();

    $surveys = $obj->Requete("SELECT s.ID_SURVEYS, c.NOM_CLASSE,m.NOM_MODULE,s.SURVEY_DESCRIPTION,s.START_DATE,s.END_DATE FROM survey_set s , module m , classe c WHERE s.ID_MODULE = m.ID_MODULE AND m.ID_CLASSE = c.ID_CLASSE AND s.ID_ANNEE='".getAnnee(0)['ID_ANNEE']."'");
    if (isset($_POST['update_survey'])){
        $requete = $obj->Update('survey_set',array('SURVEY_DESCRIPTION','START_DATE','END_DATE'), array($_POST['description'],$_POST['start_date'],$_POST['end_date']), array('ID_SURVEYS'=>$_POST['sid']));
        if ($requete) 
        {
            $_SESSION['upt_survey'] = 1;
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
                            <h4 class="text-bluesky">All surveys</h4>
                        </div>
                        <div class="col-6 text-right">
                            <a href="./add_survey.php" class="btn btn-dark rounded-pill">New survey</a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <table id="table" class="table table-bordered table-responsive" width="100%">
                        <colgroup>
                            <col width="2%">
                            <col width="20%">
                            <col width="25%">
                            <col width="12%">
                            <col width="12%">
                            <col width="40%">
                        </colgroup>

                        <thead>
                            <tr>
                                <th class="text-truncate">Action</th>
                                <th class="text-truncate">Class</th>
                                <th class="text-truncate">Modulus</th>
                                <th class="text-truncate">Start Date</th>
                                <th class="text-truncate">End Date</th>
                                <th class="text-truncate">Description</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php while($row=$surveys->fetch()):?>
                            <tr>
                                <td>
                                    <div class="btn-group">
                                        <a onclick="getSur(this.id)" id="<?=$row["ID_SURVEYS"]?>" role="button" class="btn border" data-toggle="modal" data-target="#edit_survey"><i class="fas fa-pen text-success"></i></a>
                                        <a href=<?='view_survey.php?id=' .$row["ID_SURVEYS"]?> role="button" class="btn border"><i class="fas fa-eye text-dark"></i></a>
                                        <!-- <a role="button" class="btn border" data-toggle="modal" data-target="#delete_survey"><i class="fas fa-trash text-danger"></i></a> -->
                                    </div>
                                </td>
                                <td class="text-truncate"><?= $row['NOM_CLASSE'] ?></td>
                                <td class="text-truncate"><?= $row['NOM_MODULE'] ?></td>
                                <td class="text-truncate"><?= date("M d, Y", strtotime($row['START_DATE'])) ?></td>
                                <td class="text-truncate"><?= date("M d, Y",strtotime($row['END_DATE'])) ?></td>
                                <td class="small text-justify"><?= $row['SURVEY_DESCRIPTION'] ?></td>
                            </tr>
                            <?php endwhile ?>
                        </tbody>

                        <tfoot>
                            <tr>
                                <th class="text-truncate">Action</th>
                                <th class="text-truncate">Class</th>
                                <th class="text-truncate">Modulus</th>
                                <th class="text-truncate">Start Date</th>
                                <th class="text-truncate">End Date</th>
                                <th class="text-truncate">Description</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!----------------------------------------------------- Update Survey Modal ------------------------------------------------->
            <div class="modal fade" id="edit_survey" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard='false' aria-hidden="true">
                <div class="modal-dialog modal-xl" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-gradient-primary">
                            <h5 class="modal-title text-light">Edit the survey</h5>
                            <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <form action="" method="post">
                                <div class="row" id="sur_b">
                                    <!-- Reserver a ajax-->
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!----------------------------------------------------- Delete Survey Modal ------------------------------------------------->
            <div class="modal fade" id="delete_survey" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard='false' aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-gradient-danger">
                            <h5 class="modal-title text-light">Confirmation</h5>
                                <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                        </div>
                        <div class="modal-body">
                           <form action="" method="post">
                                <div class="row">
                                    <div class="col-lg-12">
                                        Are you sure you want to delete this survey ? Remember that this action is irreversible.
                                    </div>
                                    <input hidden id="sup" name="sup" value=""/>
                                    <div class="col-lg-12 text-center my-3">
                                        <button type="reset" class="btn btn-outline-dark px-4 rounded-pill" data-dismiss="modal">Reset</button>
                                        <button type="submit" class="btn btn-outline-danger px-4 rounded-pill" name="confirm_delete_survey" id="confirm_delete_survey">Delete the Survey</button>
                                    </div>
                                </div>
                           </form>
                        </div>
                    </div>
                </div>
            </div>    

        </div>
    </div>
</div>

<?php
    include('../footer.php');
?>

<script>
    function getSur(id) {
        $.post(
            '../../../ajax.php',{
                surv:'ok',
                id_surv:id,
            }, function (content){
                $('#sur_b').html(content);
            });

    }
</script>
