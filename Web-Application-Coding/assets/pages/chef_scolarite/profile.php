<?php 
    include('../../../utilities/QueryBuilder.php');
    $title = 'Head of schooling Dashbord';
    $breadcrumb = 'Profile';

    if(isset($_SESSION['profile_info']) && $_SESSION['profile_info'] == 1)
    {
        alert('success' , "Account information updated Successfully.");
        unset($_SESSION['profile_info']);
    }

    $obj = new QueryBuilder();

    $user_info = $obj->Requete("SELECT * FROM user u, administration a WHERE a.ID_USER=u.ID_USER AND u.ID_USER = '".$_SESSION["ID_USER"]."'")->fetch();


    if(isset($_POST["update_auth_info"])):
        if($user_info['PASSWORD']==$_POST['old_password'])
        {
            if($_POST['new_password']==$_POST['re_password'])
            {
                $requete = $obj->Update('user',['PASSWORD','USERNAME'],[$_POST['new_password'],$_POST['username']],array('ID_USER'=>$_SESSION["ID_USER"]));
                if ($requete) 
                {
                    $_SESSION['profile_info'] = 1;
                    echo("<script>location.assign('../refresh.html')</script>");
                }
            }
        }
        
    endif;

    if(isset($_POST['update_user_info'])):
        $requete = $obj->Update('administration',['NOM','PRENOM','EMAIL','PHONE'],[$_POST['first_name'],$_POST['last_name'],$_POST['email'],$_POST['phone']],array('ID_USER'=>$_SESSION["ID_USER"]));
        
        if ($requete) 
        {
            $_SESSION['profile_info'] = 1;
            echo("<script>location.assign('../refresh.html')</script>");
        }
            
    endif;

    include_once ('header.php');
?>

<style>
    .notify_badge{
        position: absolute;
        top: 330px;
        right: 2%;
        opacity: 0.8;
        text-align: center;
    }

    .notify_badge{
        overflow: hidden;
    }

    .notify_badge i{
        transition: all 1.5s ease;
    } 

    .notify_badge:hover i{
        transform: scale(1.5);

    }

</style>

<div class="container-fluid">
    <div class="row wow fadeInDown">
        <div class="col-lg-4">
            <div class="card shadow">
                <button class="notify_badge btn btn-dark rounded-circle" data-toggle="modal" data-target="#profile_pic">
                    <i class="fas fa-camera"></i>
                </button>

                <img class="card-img-top rounded-top" src="<?='..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.$user_info['PROFILE_PIC'] ?>" height="350px">

                <div class="card-body text-center">
                    <h5 class="card-title h6"><strong>Full name : </strong><?=$user_info['NOM']." ".$user_info['PRENOM']?></h5>
                    <p class="card-text"><strong>Email : </strong> <?=$user_info['EMAIL']?></p>

                    <div class="btn-group" role="group">
                        <a class="btn btn-dark rounded-circle mx-2" href="<?='tel:'.$user_info['PHONE']?>"><i class="text-white fas fa-phone"></i></a>  
                        <a class="btn btn-dark rounded-circle mx-2" href="<?='mailto:'.$user_info['EMAIL']?>"><i class="text-white fab fa-google-plus-g"></i></a> 
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-white border-0">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a href="#basic_info" class="nav-link active" id="basic_info" data-target="#basic_info_form" data-toggle="tab">Personal Information</a>
                        </li>
                        <li class="nav-item">
                            <a href="#basic_info" class="nav-link" id="driven_course" data-target="#driven_course_form" data-toggle="tab">Change Password</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <!-- Basic Information -->
                        <div class="tab-pane fade show active" id="basic_info_form">
                            <form action="" method="post">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="first_name">First Name <span class="text-danger"> * </span></label>
                                            <input type="text" class="form-control" name="first_name" id="first_name" value="<?=$user_info['NOM']?>">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="last_name">Last Name <span class="text-danger"> * </span></label>
                                            <input type="text" class="form-control" name="last_name" id="last_name" value="<?=$user_info['PRENOM']?>">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="email">Email <span class="text-danger"> * </span></label>
                                            <input type="email" class="form-control" name="email" id="email" value="<?=$user_info['EMAIL']?>">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="phone">Phone Number <span class="text-danger"> * </span></label>
                                            <input type="tel" class="form-control" name="phone" id="phone" value="<?=$user_info['PHONE']?>">
                                        </div>
                                    </div>

                                    <div class="col-lg-12 my-3 text-center">
                                        <input type="reset" class="btn btn-outline-danger rounded-pill px-4" value="Reset">
                                        <input type="submit" class="btn btn-outline-primary rounded-pill px-4" id="update_user_info" name="update_user_info" >
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- Driven courses -->
                        <div class="tab-pane fade" id="driven_course_form">
                            <form action="" method="post">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="username">Username <span class="text-danger"> * </span></label>
                                            <input oninput="verifer(this.value)"  type="text" onfocus="(this.value='')" class="form-control" name="username" id="username" value="<?= $user_info["USERNAME"] ?>">
                                            <div id="aff" class="center align-content-center text-danger" style="display: none"> This Username already exit</div>
                                        </div>
                                    </div>

                                    <!-- Personnal Information -->
                                    <div class="col-lg-12 text-left">
                                        <h5 class="text-bluesky mt-3">Password Information</h5>
                                        <hr class="bg-gradient-primary">
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="old_password">Old Password <span class="text-danger"> * </span></label>
                                            <input type="password" class="form-control" name="old_password" id="old_password">
                                        </div>
                                        <div id="old_pass_error"></div>
                                    </div>

                                    <div class="col-lg-12"></div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="new_password">New password <span class="text-danger"> * </span></label>
                                            <input oninput="conf(this.value)" type="password" class="form-control" name="new_password" id="new_password" placeholder="*****************">
                                        </div>
                                        <div id="new_pass_error" class='text-danger my-3' style="display: none">You must use at least 8 characters</div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="re_password">Confirm password <span class="text-danger"> * </span></label>
                                            <input oninput="reconf(this.value)" type="password" class="form-control" disabled name="re_password" id="re_password" placeholder="*****************">
                                        </div>
                                        <div id="confirm_pass_error" class='text-danger my-3' style="display: none"> No coincidence </div>
                                    </div>

                                    <div class="col-lg-12 text-center py-3">
                                        <input type="reset" class="btn btn-outline-danger rounded-pill px-4" value="Reset">
                                        <input type="submit" class="btn btn-outline-primary rounded-pill px-4" id="update_auth_info" name="update_auth_info">
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- -------------------------------------------------------------------------------------------------------------------------------------- -->

                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="profile_pic"  tabindex="-1" role="dialog" data-backdrop="static" data-keyboard='false' aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title">Profile Pic</h5>
                    <button type="button" class="close" onclick="window.location.reload()">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="row my-3 text-center">
                        <div class="col-lg-12">
                            <input type="hidden" name="id_user" id="id_user" value="<?= $user_info["ID_USER"] ?>">
                            <div class="form-group rounded text-center dropzone" id="test_dropzone">
                                <div class="dz-message">
                                    <i class="fa fa-download fa-2x text-muted" aria-hidden="true"></i>
                                    <h6 class="text-muted">Drop or click here to upload your picture.</h6>
                                </div>
                                <div id="file_area"></div>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <button type="button" class="btn btn-dark px-4 rounded-pill" onclick="window.location.reload()">Close</button>
                            <button type="button" class="btn btn-primary px-4 rounded-pill" name="update_pic" id="update_pic">Save</button>
                        </div>
                    </div>
                </form>
            
                
            </div>
        </div>
    </div>
</div>

<?php
    include("../footer.php");
?>

<script>
    function verifer(valeur) {
        $.post(
            '../../../ajax.php',
            {
                vveri: 'modul',
                valeur: valeur,
            },
            function (donnees) {
                if(donnees.length>0){
                    document.getElementById('aff').style.display='block';
                }else {
                    document.getElementById('aff').style.display='none';
                }


            });
    }

    function conf (valeur) {

        if (valeur.length < 8)
        {
            $("#new_password").addClass('border border-danger');
            document.getElementById("new_pass_error").style.display='block';
            $("#re_password").attr('disabled');
        }
        else
        {
            $("#new_password").removeClass('border border-danger');
            document.getElementById("new_pass_error").style.display='none';
            $("#re_password").removeAttr('disabled');
        }
    }

    function reconf (valeur) {
        var el =document.getElementById('new_password').value;
        if (valeur !=el)
        {
            $("#re_password").addClass('border border-danger');
            document.getElementById("confirm_pass_error").style.display='block';
        }
        else
        {
            $("#re_password").removeClass('border border-danger');
            document.getElementById("confirm_pass_error").style.display='none';
        }
    }

    

    Dropzone.autoDiscover = false;
    $(document).ready(function () {

        var dropzone = new Dropzone("#test_dropzone", {
                url: '../../../ajax.php',
                clickable: true,
                autoProcessQueue: false,
                paraName: 'file',
                maxFilesize: 3, //MB
                maxFiles: 1,
                acceptedFiles: '.jpg , .png , .jpeg',
                addRemoveLinks: true,
                uploadMultiple: false,
                resizeHeight: 350,
                thumbnailWidth: 400,
                thumbnailHeight: 400,
                init: function () {
                    dzClosure = this;
                    $('#update_pic').on("click", function (e) {
                        e.preventDefault();
                        e.stopPropagation();
                        var erreur = [];

                        //----------------------------------------------------------------------------------------

                        if (dzClosure.getQueuedFiles().length < 1) {
                            $(".dropzone").addClass("border border-danger");
                            erreur['file'] = 'This input can\'t be empty';
                            $("#file_area").html("<div class='text-danger my-2'>"+ erreur['file']+"</div>");
                        }
                        else
                        {
                            $(".dropzone").removeClass("border border-danger");  
                            $("#file_area").html("");                          
                        }

                        if (Object.keys(erreur).length == 0) {
                            dzClosure.processQueue();
                        }
                    });

                    dzClosure.on("sending", function (data, xhr, formData) {
                        $(":input[name]", $("form")).each(function () {
                            formData.append(this.name, $(':input[name=' + this.name + ']', $("form")).val());
                        });
                    })

                    dzClosure.on("complete" , function (file) {
                        dzClosure.removeFile(file);
                    })
                },

                success: function (file, response) {
                    console.log(response)
                    if (response == 'success') {
                        toastr.success("Profile picture updated successfully");
                    }
                    else
                    {
                        toastr.error("An error occured");
                    }
                                        
                },
                error: function (file, response) {
                    toastr.error("An error occured");
                }
        });
    });
</script>
