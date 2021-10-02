<?php
    include_once '../../../utilities/QueryBuilder.php';
    $title = 'Academic Year Configuration';
    $breadcrumb = 'End Current Academic Year';
    include_once('header.php');

    $obj = new QueryBuilder();
$actel=$obj->Requete('SELECT date(NOW()) AS actuel FROM user')->fetch()['actuel'];
    if (isset($_POST['submit_payment_schedul']))
    {
        extract($_POST);
     //update of the current year: add of the end date this current year
        $obj->Update('annee_scolaire', array("DATE_FIN"), array($date_fin), array("ID_ANNEE"=>getAnnee(0)['ID_ANNEE']));
        ////insertion of a new school year
        $insert_new_year = $obj->Inscription('annee_scolaire', array('DATE_INI', 'DATE_FIN_INSCRIPTION', 'FIN_VERSEMENT_1', 'FIN_VERSEMENT_2', 'FIN_VERSEMENT_3' ), array($date_debut, $fin_inscription, $limit_premier_vers,$limit_deuxiem_vers,$limit_troisiem_vers), array('DATE_INI'=>$date_debut));
        //printing messages
        if ($insert_new_year==1)
        {
            //if the insertion of the new academic year is successfuly inserted
           alert('success', 'Academic Year changed successfuly !');
        }
        else
        {
            //otherwise
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

                                        <div class="col-lg-4">
                                            <div class="form-group my-3">
                                                <label for="date_fin">End Date <span class="text-danger"> * </span></label>
                                                <input  value="<?=$actel?>"  class="form-control" type="date" name="date_fin" id="date_fin" required readonly>
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group my-3">
                                                <label for="date_debut">Start Date <span class="text-danger"> * </span></label>
                                                <input min="<?=$actel?>" onchange="generercont(this.value)"  class="form-control" type="date" name="date_debut" id="date_debut" required>
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
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
                                                <input class="form-control" onchange="genererDeu(this.value)" type="date" name="limit_deuxiem_vers" id="limit_deuxiem_vers" required>
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
