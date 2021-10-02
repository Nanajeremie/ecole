<?php
     $title = 'Timetable';
     $breadcrumb = 'Overview';
 
     include('../../../utilities/QueryBuilder.php');
     include('header.php');
     
     $obj= new QueryBuilder();

     $timetables = $obj->Requete("SELECT * FROM timetable t WHERE t.ID_ANNEE = '". getAnnee(0)['ID_ANNEE'] ."' AND t.ID_CLASSE = (SELECT i.ID_CLASSE FROM inscription i WHERE i.ID_ANNEE = '". getAnnee(0)['ID_ANNEE'] ."' AND i.MATRICULE = (SELECT e.MATRICULE FROM etudiant e WHERE e.ID_USER = '".$_SESSION['ID_USER']."')) ORDER BY t.START_DATE DESC");
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="text-bluesky">Timetable</h6>
                </div>

                <div class="card-body">
                    <table class="table table-bordered table-hover table-responsive-lg table-responsive-md table-responsive-sm" id="table">
                        <thead>
                            <tr>
                                <th class="text-truncate">Week Number</th>
                                <th class="text-truncate">Start Date</th>
                                <th class="text-truncate">Timetable</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                                while($timetable = $timetables->fetch()):
                            ?>
                            <tr>
                                <td class="text-truncate"><?= $timetable['WEEK_NUMBER'] ?></td>
                                <td class="text-truncate"><?= date("M d, Y",strtotime($timetable['START_DATE'])) ?></td>
                                <td><a class="btn btn-dark btn-sm btn-md btn-lg text-truncate" target="_blank" href="<?='..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Timetable'.DIRECTORY_SEPARATOR . $timetable['TIMETABLE_FILE'] ?>">Check timetable</a></td>
                            </tr>
                            <?php
                                endwhile;
                            ?>
                        </tbody>

                        <tfoot>
                            <tr>
                                <th>Week Number</th>
                                <th>Start Date</th>
                                <th>Timetable</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<?php
    include("../footer.php");
?>