<?php 
include '../../../utilities/QueryBuilder.php';
$title = 'Data Bank';
$breadcrumb = 'File details';

$obj = new QueryBuilder();
include('header.php');
?>
<div class="container">
    <div class="row">
        <div class="col-lg-12 col-sm-12 wow fadeInUp">
           <div class="col-lg-6 col-sm-12">
            <input type="search" class="form-control">
            </div>
        </div>
        
        <div class="mt-3 col-lg-12  col-sm-12 wow fadeInDown">
        <div class="card">
            <div class="text-white card-header bg-gradient-primary">
                <h4>Uploaded file details</h4>
            </div>
            
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6 col-sm-12">
                       <div class="file-img">
                           <img class="img-fluid" src="../../../assets/media/logo_bit.png" alt="">
                       </div>
                    </div>
                    <div class="col-lg-6 col-sm-12">
                        <div class="file-info">
                            <span><strong>Module:</strong></span> <br>
                            <span><strong>Classe:</strong></span> <br>
                            
                            
                            <span><strong>Description:</strong></span> <br>
                            <article>
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Delectus modi cupiditate aliquid officiis totam ratione, voluptates accusantium numquam, cumque sapiente commodi vel ex ipsa alias esse earum veritatis? Nisi, dolorem.
                            </article><br>
                            <span><strong>Size:</strong></span><br>
                           <span class="mb-2">
                           <strong class="text-right">Uploaded by:</strong>
                           </span> 
                           
                           <div class="file-action row mt-3 text-center">
                                <div class="read col-lg-5 col-md-5 col-sm-5 mb-3">
                                    <span>
                                    <a href="#" role="button" class="w-100 btn btn-secondary rounded-pill">read</a>
                                    </span>
                                </div>
                                <div class="download col-lg-7 col-md-7 col-sm-7 mb-3">
                                    <span>
                                        <a href="#" role="button" class="w-100 btn btn-primary rounded-pill">download </a>
                                    </span>
                                </div>
                            </div>
                        </div>
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