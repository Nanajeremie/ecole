<?php
    include('../functions.php');
    include_once('../menu.php');
    $obj = new QueryBuilder();
    $localise=0;
    $school_years = $obj->Select('annee_scolaire', array(), array(), 'ID_ANNEE', 0);
    $picture = $obj->Requete("SELECT PROFILE_PIC as pic FROM user WHERE ID_USER = '".$_SESSION['ID_USER']."'")->fetch();
    if (isset($_GET['page']))
    {
        $page=$_GET['page'];
        $id_annee = getAnnee(0)['ID_ANNEE'];
    }
    $link = $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- ------------- META LINKS ------------- -->
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="Description" content="Enter your description here"/>

        <link rel="icon" href="../../media/logo_bit.png">

        <!-- ---------- STYLESHEETS LINKS --------- -->
        <link rel="stylesheet" href="../../css/style.css">
        <!-- ---------- Bootstrap css --------- -->
        <link rel="stylesheet" href="../../library/bootstrap4/css/bootstrap.min.css">
        <!-- ---------- Font Awesome css --------- -->
        <link rel="stylesheet" href="../../library/fontawesome/css/all.min.css">
        <!-- ---------- Animate css --------- -->
        <link rel="stylesheet" href="../../library/wow/animate.css">
        <!-- ---------- DataTable css --------- -->
        <link rel="stylesheet" href="../../library/DataTable/css/bootstrap-table.css">
        <link rel="stylesheet" href="../../library/DataTables/css/dataTables.bootstrap4.css">
        <!--Scrol bar -->
        <link rel="stylesheet" href="../../css/scrollbar/jquery.mCustomScrollbar.min.css">

        <link rel="stylesheet" href="../../library/dropzone/dist/dropzone.css">
        <link rel="stylesheet" href="../../library/toast/toastr.min.css">

        <!-- Bootstrap-Multiselect css -->
        <link rel="stylesheet" href="../../library/bootstrap-multiselect/css/bootstrap-multiselect.css">
        <link rel="stylesheet" href="../../library/bootstrap-multiselect/css/bootstrap-multiselect.min.css">

        <!-- Jquery Validation Customized errors css --------------->
        <link rel="stylesheet" href="../../css/jquery_validation_customized_errors">
        

        <title><?= $title ?></title>
        <script src="../../library/jquery/jquery.js"></script>
    </head>


<body id="page-top" >
<!-- Page Wrapper -->
<div id="wrapper">
    <!-- Sidebar -->
    <div id="fixeSideBar">
        <ul class="navbar-nav bg-dark sidebar sidebar-dark accordion" id="accordionSidebar">
            
        <!-- Nav Item - User Information -->
        <li class="nav-item no-arrow align-items-center justify-content-center" id="userInfo">
            <a class="nav-link d-block" href="#" id="userDropdown" role="button" data-toggle="dropdown"
               aria-haspopup="true" aria-expanded="false">
                <img class="img-profile rounded-circle mx-auto d-block" src="<?='..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.$picture['pic'] ?>" width="200px"
                     height="200px">
                <span>
                    <p class="mr-2 py-2 text-white text-center">
                    <?php
                        if (isset($_SESSION['USERNAME']))
                            echo $_SESSION['USERNAME'];
                        else
                            echo 'Guest';
                    ?>
                    </p>
                </span>
            </a>
        </li>

        <!-- Sidebar Menu -->
        <div id="fixeHeight" class="comment-scrollbar">
            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Tableau de bord -->

            <?= menu_item("Tableau de bord", "index.php", "fas fa-fw fa-tachometer-alt") ?>
             <h4 class="sidebar-heading mt-3">Gestion de comptes</h4>
                <hr class="sidebar-divider">
                <?php menu_item_collapse('Utilisateurs' , '#' ,'fa fa-poll' , ["edit_user.php" , "add_user.php"] , ["Edit user" , "Add user"]); ?>

            <!-- Divider -->
            <h4 class="sidebar-heading mt-3">Configuration</h4>
                <hr class="sidebar-divider">
                <?= menu_item("Log", "logs.php", "fas fa-fw fa-tachometer-alt") ?>

            <!-- Divider -->
            <hr class="sidebar-divider">
        </div>

        <!-- Sidebar Toggler (Sidebar) -->
        <div class="text-center d-none d-md-inline" id="rounde">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>

    </ul>
    </div>
    
    <!-- End of Sidebar -->

    <!-- Main conteneur -->
    <div id="content-wrapper" class="d-flex flex-column">
        <!-- Main Content -->
        <div id="content">
            <!-- Topbar -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-2 static-top " id="headHaut">
                <button id="sidebarToggleTop" class="btn btn-link d-none d-md-block mr-3">
                    <i class="fa fa-bars text-black-50"></i>
                </button>
                <!-- Sidebar Toggle (Topbar) -->
                <button id="sidebarToggleTop1" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars text-black-50"></i>
                </button>

                <!-- School Year  -->
                <?php
                if(is_object($school_years)){
                ?>
                <form class="form-inline mr-auto" method="POST" id="year" style="display: none">
                    <div class="input-group">

                        <select  oninput="get_year_info(this.value/*,json_encode($page)*/)" class="form-control px-3" name="filter_year" id="filter_year">
                        <?php
                            $i = 0;
                              while ($school_year = $school_years->fetch()) {
                                  $i++;
                                  if ($i == 1) {
                                      ?>
                                      <option selected value="<?= $school_year['ID_ANNEE'] ?>"><?= 'Actual year' ?></option>
                                      <?php

                                  } else {
                                      ?>
                                      <option value="<?= $school_year['ID_ANNEE'] ?>"><?= '' . substr($school_year['DATE_INI'], 0,4) . '-' . substr($school_year['DATE_FIN'], 0,4)  ?></option>
                                      <?php
                                  }
                              }
                              ?>
                        </select>
                        <div class="input-group-append">
                            <button class="btn btn-dark" type="button" name="select_year">
                                <i class="fas fa-filter"></i>
                            </button>
                        </div>
                    </div>

                </form>

                    <?php
                }else{
                    $localise=1;
                   // header("Location:new_year.php");
                }
                ?>
                <!-- Topbar Navbar -->
                <ul class="navbar-nav ml-auto">
                    <!-- Alert Center -->
                    <li class="nav-item dropdown no-arrow mx-1">
                        <!-- <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="far fa-bell text-black-50"></i>
                            <span class="badge badge-danger badge-counter">3+</span>
                        </a> -->
                        <!-- Dropdown - Alerts -->
                        <div class="dropdown-list dropdown-menu dropdown-menu-right shadow wow slideInRight"
                             aria-labelledby="alertsDropdown">
                            <h6 class="dropdown-header">Notifications Center </h6>
                            <?php
                            $i = 0;
                            while ($i < 5) :
                                ?>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-warning">
                                            <i class="fas fa-exclamation-triangle text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">December 2, 2019</div>
                                        Spending Alert: We've noticed unusually high spending for your account.
                                    </div>
                                </a>
                                <?php
                                $i++;
                            endwhile;
                            ?>
                            <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
                        </div>
                    </li>
                    <!-- User Profile -->
                    <li class="nav-item dropdown no-arrow mx-1">
                        <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img class="rounded-circle" src="../../media/user_24px.png" width="40px" height="40px">
                        </a>
                        <!-- Dropdown - Alerts -->
                        <div class="dropdown-list dropdown-menu dropdown-menu-right shadow wow fadeInDown" aria-labelledby="alertsDropdown">
                            <a class="dropdown-item d-flex align-items-center" href="./profile.php">Profile</a>
                            <a class="dropdown-item d-flex align-items-center" href="../../../logout.php">D??connexion</a>

                        </div>
                    </li>
                </ul>
            </nav>
            <!-- Breadcrumb -->
        <div id="sub_body" class="">
           <div id="cloture">
            <div class="container-fluid" id="headBas">
                <div class="row bg-white mx-0 mb-lg-3 pt-3 wow fadeInDown">
                    <div class="col-6">
                        <h3 class="text-uppercase"><?= $title ?></h3>
                    </div>
                    <div class="col-6">
                        <nav class="breadcrumb bg-white">
                            <span class="breadcrumb-item"><?= $title ?></span>
                            <span class="breadcrumb-item text-bluesky"> <?= $breadcrumb ?></span>
                        </nav>
                    </div>
                </div>
            </div>

            <script>

                var localise=<?=json_encode($localise)?>;

                if (localise==1){
                    window.location.replace('new_year.php?nouv=new');
                }

                function get_year_info(annee)
                {
                    
                    var pages = <?=json_encode($page)?>;
                      //console.log(annee);

                        //alert('dsdwee');

                    if (annee == <?= json_encode($id_annee) ?>)
                    {
                        //alert(annee);
                        var lien_page = <?=json_encode($link)?>;
                        window.open(" " , "_self");
                    }
                    else
                    {
                        //alert(annee);
                        $.post(
                            '../../../ajax.php',{

                                shr:'ok',
                                annee:annee,
                                page:pages,
                            }, function (don){
                                //console.log(don.split('*')[0]);
                                if(pages=="list-payment"){
                                    $('#cont').html(don.split('*')[0]);
                                    $('#outcont').html(don.split('*')[1]);
                                }
                               else if(pages=="scholarship_owners"){
                                    $('#scholar').html(don);
                                }
                                else if(pages=="scholarship_owners_inscrit"){
                                    $('#table').html(don);
                                }
                                else if(pages=="valide_other_paiement"){
                                    $('#payment').html(don);
                                }
                            });
                    }
                }
            </script>
            
            