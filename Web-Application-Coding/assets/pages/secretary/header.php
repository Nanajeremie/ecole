<?php
    
    include_once('../menu.php');
    $localise=0;
$obj = new QueryBuilder();~
    $school_years = $obj->Select('annee_scolaire', array(), array(), 'ID_ANNEE', 0);
    $picture = $obj->Requete("SELECT PROFILE_PIC as pic FROM user WHERE ID_USER = '".$_SESSION['ID_USER']."'")->fetch();
    $id_annee = intval($obj->Requete("SELECT ID_ANNEE FROM annee_scolaire ORDER BY ID_ANNEE DESC LIMIT 1")->fetch()['ID_ANNEE']);
    if (isset($_GET['page']))
    {
        $page=$_GET['page'];
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

        <link rel="stylesheet" href="../../library/bootstrap4-toggle-3.6.1/css/bootstrap4-toggle.min.css">

        <!-- ---------- Font Awesome css --------- -->
        <link rel="stylesheet" href="../../library/fontawesome/css/all.min.css">

        <!-- ---------- Animate css --------- -->
        <link rel="stylesheet" href="../../library/wow/animate.css">

        <!-- ---------- DataTable css --------- -->

        <link rel="stylesheet" href="../../library/DataTables/css/dataTables.bootstrap4.css">
        <link rel="stylesheet" href="../../library/datatables-buttons/css/buttons.bootstrap4.min.css">        

        <!--Scrol bar -->

        <link rel="stylesheet" href="../../css/scrollbar/jquery.mCustomScrollbar.min.css">
        <link rel="stylesheet" href="../../library/toast/toastr.min.css">
        <link rel="stylesheet" href="../../library/dropzone/dist/dropzone.css">
        
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
                    <img class="img-profile rounded-circle mx-auto d-block" src="<?='..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.$picture['pic'] ?>" width="200px" height="200px">
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
                
                <h4 class="sidebar-heading mt-3">Section inscriptions</h4>
                <hr class="sidebar-divider">

                <!-- Nav Item - Pre - Inscription -->
                <?php menu_item_collapse('Inscription', '#', 'fas fa-users', ['primaire_inscr.php','secondaire_inscr.php','list_pre_inscrit.php'], ['Primaire', 'Secondaire', 'Liste des élèves']); ?>
                <!-- Nav Item - Tuitions -->
                <li class='nav-item'>
                    <a class='nav-link w-auto text-left collapsed' href='#' data-toggle='collapse' data-target='#links' aria-expanded='true'
                    aria-controls='links'>
                        <i class='fas fa-hand-holding-usd d-inline px-2'></i>
                        <span class="d-inline">Scolarités</span>
                    </a>
                    <?php
                        $tab = ['inscription_valide.php', 'old-student-tuition.php', 'valide_other_paiement.php', 'list-payment.php'];
                        $deroulante = "collapse ";
                        foreach ($tab as $value) 
                        {
                            if (stristr($_SERVER['SCRIPT_NAME'], $value)) {
                                $deroulante .= "show";
                            }
                        }

                        function active(string $lien)
                        {
                            $classe = "collapse-item";
                            if (stristr($_SERVER['SCRIPT_NAME'], $lien))
                                $classe .= " active custom-bg-light text-dark";

                            return $classe;
                        }
                    ?>
                    <div id='links' class='<?= $deroulante ?>' aria-labelledby='headingUtilities'
                        data-parent='#accordionSidebar'>
                        <div class='bg-white mx-2 py-2 collapse-inner rounded'>
                            <a class='<?= active('valide_other_paiement.php') ?>' href='valide_other_paiement.php'><i class='fas fa-angle-right pr-2'></i>Payer</a>
                            <a class='<?= active('list-payment.php') ?>' href='list-payment.php?page=list-payment'><i class='fas fa-angle-right pr-2'></i>Historique paiements</a>
                        </div>
                    </div>
                </li>

                <!-- Divider -->
                <h4 class="sidebar-heading mt-3">Gestion de Biblioteque</h4>
                <hr class="sidebar-divider">
                <?php menu_item_collapse('Bibliotheque' , '#' ,'fas fa-book-open' , ['library.php' , 'books_booking.php'] , ['Livres' , 'Souscriptions']) ?>

                <!-- Divider -->
                <h4 class="sidebar-heading mt-3">Gestion de la catine</h4>
                <hr class="sidebar-divider">

                <!-- Nav Item - Canteen -->
                <?php menu_item_collapse('Cantine', '#', 'fas fa-utensils', ['canteen_fees.php', 'pending_bookings.php' ,'canteen_booking.php'], ['Fees', 'Pending bookings' ,'Bookings']); ?>


                <!-- Divider -->
                <h4 class="sidebar-heading mt-3">Gestion de l'institut</h4>
                <hr class="sidebar-divider">

                <!-- Nav Item - Department -->
                <?= menu_item('Classe', 'department.php', 'fas fa-university'); ?>

                <!-- Nav Item - Field -->
                <?= menu_item('Cours', 'field.php', 'fas fa-certificate'); ?>
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
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-2 static-top" id="headHaut">
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
                <form class="form-inline mr-auto col-3" method="POST" id="year" style="display: none">
                    <div class="input-group">

                        <select  onchange="get_year_info(this.value)" class="form-control px-3" name="filter_year" id="filter_year">
                        <?php
                            $i = 0;

                              while ($school_year = $school_years->fetch()) {
                                $anneeScolaire = explode('-',$school_year['DATE_INI'])[0]."-".(explode('-',$school_year['DATE_INI'])[0]+1);
                                  $i++;
                                  if ($i == 1) {
                                      ?>
                                      <option selected value="<?= $school_year['ID_ANNEE'] ?>"><?=$anneeScolaire ?></option>
                                      <?php

                                  } else {
                                      ?>
                                      <option value="<?= $school_year['ID_ANNEE'] ?>"><?=$anneeScolaire  ?></option>
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
                        <h4 class="text-uppercase"><?= $title ?></h4>
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

                    if (annee == <?= json_encode($id_annee) ?>)
                    {
                        var lien_page = <?=json_encode($link)?>;
                        window.open(" " , "_self");
                    }
                    else
                    {
                        
                        $.post(
                            '../../../ajax.php',{

                                shr:'ok',
                                annee:annee,
                                page:pages,
                            }, function (don){
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
            
            