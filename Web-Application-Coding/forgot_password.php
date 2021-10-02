<?php
// ------------------TRAITEMENT DU FORMULAIRE DE REINITAILISATION DU MOT DE PASSE OUBLIE
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recover Password</title>
    <link rel="stylesheet" href="assets/library/bootstrap4/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/library/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="icon" href="assets/media/logo_bit.png">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mx-auto my-5">
                <div class="card">
                    <div class="card-header py-3 bg-gradient-primary">
                        <h3 class="text-uppercase text-center text-light">Recover password</h3>
                        <p class="text-center text-light small">Hello there, forgot your password ? Don't worry fill the information below and get access to your work environment</p>
                    </div>
                    <div class="card-body p-lg-5">
                        <form method="post" action="forgot_password.php">
                            <div class="input-group py-3">
                                <input class="form-control" type="email" name="recover_mail" id="recover_mail" placeholder="Enter your email address" required>
                                <div class="input-group-append">
                                    <span class=" input-group-text fas fa-envelope  bg-light text-bluesky"></span>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12 py-3 text-center">
                                    <button class="btn btn-outline-primary w-75 rounded-pill" type="submit">Recover</button>
                                </div>
                                <div class="col-12 text-center">
                                    Already have an account <a class="text-bluesky" href="index.php">Sign in</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>