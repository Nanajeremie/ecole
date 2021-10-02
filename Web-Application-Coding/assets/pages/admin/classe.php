<?php
    include '../../../utilities/QueryBuilder.php';
    $title = 'Class';
    $breadcrumb = 'Add new class';
    include('header.php');
?>
<div class="container-fluid" id="fenetre">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6">
                            <h4 class="text-bluesky">Add a new class</h4>
                        </div>
                        <div class="col-6 text-right">
                            <a class="btn btn-dark" role="button" href="allclass.php">Class List</a>
                        </div>
                    </div>
                </div>

                <div class="card-body py-5">
                    <form action="" method="post">
                        <div class="row">

                            <div class="col-lg-4">
                                <label for="filiere">Field</label>
                                <div class="input-group">
                                    <select class="form-control" name="filiere" id="filiere" required>
                                        <option>Select field</option>
                                    <?php 
                                       // foreach($fieds as $field):
                                    ?>
                                        <optgroup label="<?= 'Computer Science Department' ?>">
                                            <option><?= 'Computer Science' ?></option>  
                                        </optgroup>
                                    <?php
                                        //endforeach;
                                    ?>
                                    </select>
                                    <div class="input-group-append">
                                        <span class=" input-group-text fas fa-school"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <label for="classe_nom">Class Name</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="classe_nom" id="classe_nom" placeholder="Enter Class Name" required>
                                 
                                    <div class="input-group-append">
                                        <span class="input-group-text fas fa-school"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <label for="class_fees">School Fees</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="class_fees" id="class_fees" placeholder="Enter Class Name" required>
                                
                                    <div class="input-group-append">
                                        <span class="input-group-text fas fa-money-bill-wave-alt"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12 text-center my-5">
                                <button class="btn btn-primary w-50" type="submit" name="submit_class"> Create class</button>
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