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

                <?= menu_item("Dashboard", "index.php", "fas fa-fw fa-tachometer-alt") ?>
                <?= menu_item('Students', 'student.php', 'fas fa-users'); ?>
                <?= menu_item('Teacher', 'edit_teacher.php', 'fas fa-chalkboard-teacher'); ?>
                <!-- Divider -->
                <h4 class="sidebar-heading mt-3">Pre - Insciption Management</h4>
                <hr class="sidebar-divider">

                <!-- Nav Item - Pre - Inscription -->
                <?php menu_item_collapse('Inscription', '#', 'fas fa-users', ['inscription.php','inscription.php','list_pre_inscrit.php'], ['Primaire', 'Secondaire', 'Liste des élèves']); ?>
                
                <!-- Divider -->
                <h4 class="sidebar-heading mt-3">Scholarship Management</h4>
                <hr class="sidebar-divider">

                <!-- Nav Item - Scholarship -->
                <?php menu_item_collapse('Scholarship', '#', 'fas fa-money-bill', ['scholarship_owners.php', 'scholarship_owners_inscrit.php'], ['Pre - Inscripted Owners', 'Final Owners']); ?>

                <!-- Divider -->
                <h4 class="sidebar-heading mt-3">Library Management</h4>
                <hr class="sidebar-divider">
                <?= menu_item('Books', 'library.php', 'fas fa-book'); ?>

                <!-- Divider -->
                <h4 class="sidebar-heading mt-3">School Management</h4>
                <hr class="sidebar-divider">

                <!-- Nav Item - Department -->
                <?= menu_item('Department', 'department.php', 'fas fa-university'); ?>

                <!-- Nav Item - Field -->
                <?= menu_item('Field', 'field.php', 'fas fa-certificate'); ?>

                <!-- Nav Item - Classe -->
                <?= menu_item('Classes', 'allclass.php', 'fas fa-school'); ?>
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
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-2 static-top shadow" id="headHaut">
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
                        <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                             aria-labelledby="alertsDropdown">
                            <a class="dropdown-item d-flex align-items-center" href="./profile.php">Profile</a>
                            <a class="dropdown-item d-flex align-items-center"
                               href="../../../logout.php">Déconnexion</a>

                        </div>
                    </li>
                </ul>
            </nav>
            <!-- Breadcrumb -->
        <div id="sub_body" class="">
           <div id="cloture">
            <div class="container-fluid" id="headBas">
                <div class="row bg-white mx-0 mb-lg-3 pt-3 wow fadeIn">
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
            
            