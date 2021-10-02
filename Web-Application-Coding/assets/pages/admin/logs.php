<?php 
    include('../../../utilities/QueryBuilder.php');
    $title = 'Log';
    $breadcrumb = 'Log Tree';
    include_once ('header.php');

    $all_logs_vars = LogRead("..\..\..\..\Web-Application-Coding\LOG");
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 ">
            <div class="card">
                <div class="card-body">
                
                    <table class="table table-bordered table-hover table-responsive-lg table-responsive-md table-responsive-sm" id="table">
                        <thead>
                             <tr>
                                <th>No</th>
                                <th>Date</th>
                                <th>Author</th>
                                <th>Action</th>
                                <th>Activity</th>
                                <th>Database Table</th>
                            </tr> 
                        </thead>
                        <tbody>
                            <?php
                            for($i = count($all_logs_vars)-1 ; $i >= 0 ; $i--):
                            ?>
                                <tr>
                                    <?php
                                    
                                    for ($j=0; $j < count($all_logs_vars[$i]) ; $j++):
                                    ?>
                                        <td><?= $all_logs_vars[$i][$j]?></td>
                                    <?php 
                                    endfor;
                                    ?>
                                </tr>
                            <?php
                            endfor;
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Date</th>
                                <th>Author</th>
                                <th>Action</th>
                                <th>Activity</th>
                                <th>Database Table</th>
                            </tr> 
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
    include('../footer.php');
?>