<?php 
    include_once('../../../utilities/QueryBuilder.php');
    $title = 'Academic Year Configuration';
    $breadcrumb = 'New Academic Year';
    include_once 'header.php';
    $ok=0;

    $obj = new QueryBuilder();
    $moment=$obj->Requete('SELECT date(NOW()) as moment FROM annee_scolaire')->fetch();
    //if the button for the new schedule is clicked
    if (isset($_POST['submit_payment_schedul']))
    {
       extract($_POST);
       $insertion_status = $obj->Inscription('annee_scolaire', array('DATE_INI','DATE_FIN_INSCRIPTION','FIN_VERSEMENT_1','FIN_VERSEMENT_2','FIN_VERSEMENT_3'), array($date_debut, $fin_inscription, $limit_premier_vers, $limit_deuxiem_vers, $limit_troisiem_vers), array('DATE_INI'=>$date_debut));
       if ($insertion_status==1)
       {
           alert('success', 'Academic Year created successfuly !');
           sleep(2);
           $ok=1;
          // Redirect("index.php");
       }
       else
       {
            alert('danger', 'Academic Year already exists!');
       }
    }
?>

    <div class="container-fluid" id="fenetre">
        <div class="row">   

            <div class="col-lg-12">
                <div class="card shadow">
                    <form action="" method="post">
                        <div class="card-header">
                            <ul class="nav nav-tabs">
                                <li class="nav-item">
                                    <a href="#year" class="nav-link active" id="year_tab" data-target="#year_form" data-toggle="tab">Step 1 : Academic Year</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#payment_schedule" class="nav-link disabled" id="paiement_schedul_tab" data-target="#payment_schedule_form" data-toggle="tab">Step 2 : Payment Schedule</a>
                                </li>
                            </ul>
                        </div>

                        <div class="card-body">
                            <div class="tab-content">

                                <!-- Academic Year -->
                                <div class="tab-pane fade show active" id="year_form">
                                    <div class="row">
                                        <div class="col-lg-12 text-left">
                                            <h5 class="text-bluesky mt-3">Academic Year</h5>
                                            <hr class="bg-gradient-primary">
                                        </div>
                                        
                                        <div class="col-lg-6">
                                            <div class="form-group my-3">
                                                <label for="date_debut">Start Date <span class="text-danger"> * </span></label>
                                                <input min="<?=$moment['moment'] ?>"  onchange="generercont(this.value)" class="form-control" type="date" name="date_debut" id="date_debut" required>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group my-3">
                                                <label for="fin_inscription">Registration deadline<span class="text-danger"> * </span></label>
                                                <input onchange="genererIns(this.value)" class="form-control" type="date" name="fin_inscription" id="fin_inscription">
                                            </div>
                                        </div>
                                                    
                                        <div class="col-lg-12 text-right">
                                            <button class="btn btn-outline-primary text-capitalize my-3 w-25" name="submit_year" id="submit_year" type="button">Next</button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Payment Schedule -->
                                <div class="tab-pane fade" id="payment_schedule_form">
                                    <div class="row">
                                        <div class="col-lg-12 text-left">
                                            <h5 class="text-bluesky mt-3">Payment Pediods</h5>
                                            <hr class="bg-gradient-primary">
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group my-3">
                                                <label for="premier_vers">Delay First (1 <sup>st</sup>) Payment <span class="text-danger"> * </span></label>
                                                <input onchange="genererPre(this.value)" class="form-control" type="date" name="limit_premier_vers" id="premier_vers" required>
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group my-3">
                                                <label for="limit_deuxiem_vers">Delay Seconde (2 <sup>nd</sup>) Payment <span class="text-danger"> * </span></label>
                                                <input onchange="genererDeu(this.value)" class="form-control" type="date" name="limit_deuxiem_vers" id="limit_deuxiem_vers" required>
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group my-3">
                                                <label for="limit_troisiem_vers">Delay Third (3 <sup>rd</sup>) Payment <span class="text-danger"> * </span></label>
                                                <input class="form-control" type="date" name="limit_troisiem_vers" id="limit_troisiem_vers" required>
                                            </div>
                                        </div>

                                        <div class="col-lg-12 text-right">                                            
                                            <button class="btn btn-outline-dark text-capitalize my-3 w-25" name="submit_payment_prev" id="submit_payment_prev" type="button">Previous</button>
                                            <button class="btn btn-success text-capitalize my-3 w-25" name="submit_payment_schedul" id="submit_payment_schedul" type="submit">Submit Delays</button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
<?php
    include('../footer.php');
?>

<script>

    var ok=<?=json_encode($ok)?>;
    if (ok==1){
        window.location.replace('index.php');
    }

$(document).ready(function(){
    $('#submit_year').click(function(){
        $('#year_tab').removeClass('active');
        $('#year_tab').addClass('disabled');
        $('#year_form').removeClass('show active');

        $('#paiement_schedul_tab').removeClass('disabled');
        $('#paiement_schedul_tab').addClass('active');
        $('#payment_schedule_form').addClass('show active');
    }); 
    $('#submit_payment_prev').click(function(){
        $('#paiement_schedul_tab').removeClass('active');
        $('#paiement_schedul_tab').addClass('disabled');
        $('#payment_schedule_form').removeClass('show active');

        $('#year_tab').removeClass('disabled');
        $('#year_tab').addClass('active');
        $('#year_form').addClass('show active');
    })
})
    function generercont(date) {
        document.getElementById('fin_inscription').min=date;
        document.getElementById('premier_vers').min=date;
        document.getElementById('limit_deuxiem_vers').min=date;
        document.getElementById('limit_troisiem_vers').min=date;
    }
    function genererIns(date) {
        document.getElementById('premier_vers').min=date;
        document.getElementById('limit_deuxiem_vers').min=date;
        document.getElementById('limit_troisiem_vers').min=date;
    }
    function genererPre(date) {
        document.getElementById('limit_deuxiem_vers').min=date;
        document.getElementById('limit_troisiem_vers').min=date;
    }
    function genererDeu(date) {
        document.getElementById('limit_troisiem_vers').min=date;
    }

</script>