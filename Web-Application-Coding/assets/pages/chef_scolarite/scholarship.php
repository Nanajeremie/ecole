<?php
    include '../../../utilities/QueryBuilder.php';
    $title = 'Reduction';
    $breadcrumb = 'All Reductions';

    $obj = new QueryBuilder();
    $all_bourse = $obj->Select('bourse', array(), array(), 'TAUX',1);
    
    if (isset($_POST['update_scholarship'])) {
        extract($_POST);
        $select_Scholar = $obj->Select('bourse',['TAUX'],array("TAUX"=>$_POST['scholarship' . $id_bourse]));
        if(!is_object($select_Scholar)){
            $q = $obj->Update('bourse', array('TAUX'), array($_POST['scholarship' . $id_bourse]), array('ID_BOURSE' => $id_bourse));
            if($q == 1)
            {
                $_SESSION['update_sch'] = 1;
            }
        }else{
            $_SESSION['update_sch'] = 2;
        }
        Refresh(0);
    }

    //when the user hits the 'create new reduction button' to create a reduction
    if (isset($_POST['submit_scholarship'])) 
    {
        extract($_POST);
        //insetion of the new reduction amount to the database if it doesnt already exist
        $q = $obj->Inscription('bourse', array('TAUX'), array($scholarship_rate), array('TAUX'=>$scholarship_rate));
        if($q == 1)
        {
            $_SESSION['new_sch'] = 1;
        }
        //reloading the page
        Refresh();
    }

    //if the user wants to delete the scholarship
    if (isset($_POST['delete_scholarship'])) 
    {
        extract($_POST);
        $e=$obj->Delete('bourse', array("ID_BOURSE"=>$del_scholarship));
        if($e == 1)
        {
            $_SESSION['del_sch'] = 1;
        }
        
        //reloading the page
        Refresh();
    }

    include('header.php');

    if(isset($_SESSION['new_sch']) && $_SESSION['new_sch'] == 1)
    {
        alert('success', 'Scholarship Created Successfully.');
        $_SESSION['new_sch'] = 0;
    }

    else if(isset($_SESSION['update_sch']) && $_SESSION['update_sch'] == 1)
    {
        alert('success', 'Scholarship Updated Successfully.');
        $_SESSION['update_sch'] = 0;
    }
    else if(isset($_SESSION['update_sch']) && $_SESSION['update_sch'] == 2)
    {
        alert('danger', 'This scholarship already existe.');
        $_SESSION['update_sch'] = 0;
    }

    else if(isset($_SESSION['del_sch']) && $_SESSION['del_sch'] == 1)
    {
        alert('danger', 'Scholarship Deleted Successfully.');
        $_SESSION['del_sch'] = 0;
    }
?>


<div class="container-fluid" id="fenetre">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6">
                            <h4 class="text-bluesky">All Reduction's Rates</h4>
                        </div>
                    </div>
                </div>

                <div class="card-body py-2">
                    <div id="toolbar">
                        <select class="form-control dt-tb">
                            <option value="">Export Basic</option>
                            <option value="all">Export All</option>
                            <option value="selected">Export Selected</option>
                        </select>
                    </div>

                    <script type="text/javascript">
                        sc = [];
                        //console.log(sc);
                    </script>

                    <?php
                        if (is_object($all_bourse)) 
                        {
                    ?>
                        <table id="table" data-toggle="table" data-show-columns="true" data-show-export="true" data-toolbar="#toolbar">
                            <thead>
                                <tr>
                                    <th data-field="rate" data-editable="true">Reduction Rate</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                    $update = 'update';
                                    $delete = 'delete';
                                    while ($the_bourse = $all_bourse->fetch()) 
                                    {
                                        $update .= $the_bourse['ID_BOURSE'];
                                        $delete .= $the_bourse['ID_BOURSE'];
                                ?>
                                        <!-- script to append the value of the scholarship in js  -->
                                        <script type="text/javascript">
                                            sc.push(parseInt(<?= json_encode($the_bourse['TAUX']);?>));
                                        </script>
                                    <tr>    
                                        <td><?= $the_bourse['TAUX'] . ' %'; ?></td>
                                    </tr>
                                <?php
                                    }
                                ?>
                            </tbody>

                            <tfoot>
                                <tr>
                                    <th data-field="rate" data-editable="true">Reduction Rate</th>
                                </tr>
                            </tfoot>
                        </table>
                    <?php
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>


<?php
    include('../footer.php');
?>
