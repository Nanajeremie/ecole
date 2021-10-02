<?php
include_once '../../../utilities/QueryBuilder.php';
$title = 'Course formating';
$breadcrumb = 'All courses';
include('header.php');
?>
<div class="container-fluid" id="fenetre">
    <div class="row"> 
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6">
                            <h4 class="text-bluesky">Add Module</h4>
                        </div>
                        <div class="col-6 text-right">
                            <a class="btn btn-dark" role="button" href="allcourses.php">List modules</a>
                        </div>
                    </div>
                </div>
                <div class="card-body py-5">
                    <form action="#" class="row">
                         
                         <div class="form-group col-lg-3">
                           <label for="module">Module<span class="text-danger"> * </span></label>
                            <div class="input-group">
                             <input type="text" class="form-control">
                             <div class="input-group-append">
                                    <span class="input-group-text fas fa-book-reader"></span>
                            </div>
                           </div>
                         
                        </div>
                            
                        <div class="form-group col-lg-3">
                             <label for="semester">Semester<span class="text-danger"> * </span></label>
                             <div class="input-group">
                             <select class="form-control" name="semester">
                                <?php
                                    for($i=1; $i<=6; $i++):
                                 ?>
                                 <option value="s<?= $i;?>">Semester N<?= $i;?></option>
                                 <?php endfor;?>
                             </select>
                             
                             <div class="input-group-append">
                                <span class="input-group-text fas fa-school"></span>
                            </div>
                            </div>
                         </div>
                         
                         <div class="form-group col-lg-3 ">
                           <label for="classe">Classe<span class="text-danger"> * </span></label>
                            <div class="input-group">
                             <select class="form-control" name="classe">
                                 <option value="cs">CS</option>
                                 <option value="ee">EE</option>
                             </select>
                             <div class="input-group-append">
                                <span class="input-group-text fas fa-school"></span>
                            </div>
                            </div>
                         </div>
                         
                         <div class="form-group col-lg-3">
                             <label for="credits">Credits<span class="text-danger"> * </span></label>
                             <div class="input-group">
                                 <input type="text" class="form-control" name="course">
                                 
                                 <div class="input-group-append">
                                    <span class="input-group-text fas fa-school"></span>
                                </div>
                             </div>
                        </div>
                         
                         <div class="form-group col-lg-12">
                             <label class="text-center" for="description">Description<span class="text-danger"> * </span></label>
                             <textarea rows="5" class="form-control" name="description"></textarea>
                         </div>
                         
                         <!-- SUBMIT BUTTON -->
                         <div class="col-lg-12 form-group text-center my-4">
                             <input class="btn btn-primary col-lg-4" type="submit" value="Add">
                        </div>
                        <!-- SUBMIT BUTTON -->
                         
                    </form>    
                 </div>
            </div>
        </div>
    </div>
</div>
<?php
include('../footer.php');
?>