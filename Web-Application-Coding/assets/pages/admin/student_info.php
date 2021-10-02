<?php
    extract($_GET);

    $title = 'Student overview';
    $breadcrumb = $matricule;

    include('../../../utilities/QueryBuilder.php');
    include('header.php');

    $obj = new QueryBuilder();
    
    $student_info = $obj->Requete("SELECT i.DATE_INSCRIPTION , e.MATRICULE , e.PRENOM , e.NOM , e.SEXE , e.DATE_NAISSANCE , e.CNIB , e.EMAIL , e.TELEPHONE , e.DIPLOME, e.MOYENNE , e.ECOLE_ORIGINE , e.NUM_URGENCE , e.NOM_PERE , e.NOM_MERE , e.PROFESSION_PERE , e.PROFESSION_MERE , e.RECOMMENDATION , u.ID_USER , u.PROFILE_PIC , C.NOM_CLASSE FROM inscription i , etudiant e , classe c , user u WHERE i.MATRICULE = e.MATRICULE AND e.ID_USER = u.ID_USER AND i.ID_CLASSE = c.ID_CLASSE AND i.MATRICULE = '".$matricule."' AND i.ID_ANNEE = '".getAnnee(0)["ID_ANNEE"]."'")->fetch();
    $student_motivation = $obj->Requete("SELECT h.MOTIVATION , h.OBSERVATION , b.TAUX FROM historique_bourse h , bourse b WHERE b.ID_BOURSE = h.ID_BOURSE AND h.MATRICULE = '".$matricule."' AND h.ID_ANNEE = '".getAnnee(0)["ID_ANNEE"]."'")->fetch();
    $school_fees = $obj->Requete("SELECT s.MONTANT_TOTAL , s.MONTANT_PAYE FROM scolarite s , inscription i WHERE s.ID_INSCRIPTION = (SELECT i.ID_INSCRIPTION FROM inscription i WHERE i.MATRICULE = '".$matricule."' AND i.ID_ANNEE = '".getAnnee(0)['ID_ANNEE']."')")->fetch();

    //if the session testimonial for update_info exists
    if(isset($_SESSION['update_info']))
    {
        alert('success', 'The student profile is updated successfully !');
        unset($_SESSION['update_info']);
    }

    //if the user hits the update button
    if(isset($_POST['update_info']))
    {
        extract($_POST);
        //update motivation and observation from the teacher
        $obj->Update('historique_bourse', ['MOTIVATION','OBSERVATION'], [$motivation, $observation], ['MATRICULE'=>$matricule]);
        //fetches the student matricule
        $idUser = $obj->Select('etudiant', ['ID_USER'], ['MATRICULE'=>$matricule]);
        $idUser = $idUser->fetch();
        $idUser = $idUser['ID_USER'];
        //update the student profile
        $obj->Update('etudiant', ['PRENOM', 'NOM' , 'SEXE' , 'DATE_NAISSANCE' , 'CNIB' , 'EMAIL' , 'TELEPHONE' , 'DIPLOME', 'MOYENNE' , 'ECOLE_ORIGINE' , 'NUM_URGENCE' , 'NOM_PERE' , 'NOM_MERE' , 'PROFESSION_PERE' , 'PROFESSION_MERE' , 'RECOMMENDATION'], [$last_name, $first_name, $gender, $birth, $cnib, $email, $phone, $type_bac, $bac_average, $coming_school, $emergency, $fath_name, $moth_name, $father_job, $moth_job, $recommendation], ['ID_USER'=>$idUser]);

        //refreshes the page
        $_SESSION['update_info'] = 1;
        echo("<script>location.assign('../refresh.html')</script>");
    }

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-4">
            <div class="card shadow">
                <img class="card-img-top rounded-top" src="<?='..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.$student_info['PROFILE_PIC']?>" height="350px">
                <div class="card-body text-center">
                    <h5 class="card-title h6"><strong>Full name : </strong> <?= $student_info['PRENOM'] .' '.$student_info['NOM'] ?></h5>
                    <p class="card-text"><strong>Classroom : </strong> <?= $student_info['NOM_CLASSE'] ?></p>
                    <p class="card-text"><strong>Email : </strong> <?= $student_info['EMAIL'] ?></p>

                    <div class="btn-group" role="group">
                        <a class="btn btn-dark rounded-circle mx-2" href="<?= 'tel:'.$student_info['TELEPHONE'] ?>"><i class="text-white fas fa-phone"></i></a>  
                        <a class="btn btn-dark rounded-circle mx-2 " href="<?= 'mailto:'.$student_info['EMAIL'] ?>"><i class="text-white fab fa-google-plus-g"></i></a> 
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col">
                        <span class="text-primary">Student Information : </span><?= $student_info['MATRICULE'] ?>
                        </div>
                        <div class="col text-right">
                            <span class="text-primary">Enrolment Date : </span><?= date("M d, Y | h:i:s a",strtotime($student_info['DATE_INSCRIPTION'])) ?>
                        </div>
                    </div> 
                    
                </div>
                <div class="card-body">
                    <form action="" method="post" id="form">
                        <div class="row">
                            <!-- Personnal Information  -->

                                <div class="col-lg-12 text-left my-2">
                                    <h5 class="text-bluesky mt-3">Personal Information</h5>
                                    <hr class="bg-gradient-primary">
                                </div>

                                    <div class="col-lg-4 p-4">
                                        <label for="matricule" class="pb-3">Matricule</label>
                                        <div class="input-group">
                                            <input type="text" name="matricule" id="matricule" class="form-control" value="<?= $student_info['MATRICULE'] ?>" required disabled>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 p-4">
                                        <label for="last_name" class="pb-3">Last Name</label>
                                        <div class="input-group">
                                            <input type="text" name="last_name" id="last_name" class="form-control" value="<?= $student_info['NOM'] ?>" required>
                                            <div class="input-group-append">
                                                <span class=" input-group-text fas fa-portrait  bg-light"></span>
                                            </div> 
                                        </div>
                                    </div>

                                    <div class="col-lg-4 p-4">
                                        <label for="first_name" class="pb-3">First Name</label>
                                        <div class="input-group">
                                            <input type="text" name="first_name" id="first_name" class="form-control" value="<?= $student_info['PRENOM'] ?>" required>
                                            <div class="input-group-append">
                                                <span class=" input-group-text fas fa-portrait  bg-light"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 p-4">
                                        <div class="form-group">
                                            <label for="birth" class="pb-3">Birthday</label>
                                            <input type="date" name="birth" id="birth" class="form-control" value="<?= $student_info['DATE_NAISSANCE'] ?>" required>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 p-4">
                                        <label for="gender" class="pb-3">Gender</label>
                                        <div class="input-group">                                    
                                            <select class="form-control" name="gender" id="gender" required>
                                                    <option value="<?= $student_info['SEXE'] ?>"><?= $student_info['SEXE'] ?></option>
                                                <?php if ($student_info['SEXE'] == 'Male'): ?>
                                                    <option value="Female">Female</option>
                                                <?php else:?>
                                                    <option value="Male">Male</option>
                                                <?php endif; ?>
                                            </select>
                                            <div class="input-group-append">
                                                <span class=" input-group-text fas fa-venus-mars bg-light"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 p-4">
                                        <label for="cnib" class="pb-3">CNIB</label>
                                        <div class="input-group">
                                            <input type="text" name="cnib" id="cnib" class="form-control" value="<?= $student_info['CNIB'] ?>" required>
                                            <div class="input-group-append">
                                                <span class=" input-group-text fas fa-id-card  bg-light"></span>
                                            </div>
                                        </div>
                                    </div>

                            <!-- ------------------------------------------------------------------------------------------------------ -->

                            <!-- Contact Information -->

                                <div class="col-lg-12 text-left my-2">
                                    <h5 class="text-bluesky mt-3">Contacts</h5>
                                    <hr class="bg-gradient-primary">
                                </div>

                                    <div class="col-lg-4 p-4">
                                        <label for="email" class="pb-3">Email</label>
                                        <div class="input-group">
                                            <input type="email" name="email" id="email" class="form-control" value="<?= $student_info['EMAIL'] ?>" required>
                                            <div class="input-group-append">
                                                <span class=" input-group-text fas fa-envelope  bg-light"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 p-4">
                                        <label for="phone" class="pb-3">Phone Number</label>
                                        <div class="input-group">
                                            
                                            <input type="tel" name="phone" id="phone" class="form-control" value="<?= $student_info['TELEPHONE'] ?>" required>
                                            
                                            <div class="input-group-append">
                                                <span class=" input-group-text fas fa-mobile bg-light"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 p-4">
                                        <label for="emergency" class="pb-3">Emergency Number</label>
                                        <div class="input-group">
                                            <input type="tel" name="emergency" id="emergency" class="form-control" value="<?= $student_info['NUM_URGENCE'] ?>" required>
                                            <div class="input-group-append">
                                                <span class=" input-group-text fas fa-phone-alt  bg-light"></span>
                                            </div>
                                        </div>
                                    </div>

                            <!-- ------------------------------------------------------------------------------------------------------ -->

                            <!-- Education Background -->
                                <div class="col-lg-12 text-left my-2">
                                    <h5 class="text-bluesky mt-3">Education Background</h5>
                                    <hr class="bg-gradient-primary">
                                </div>

                                    <!-- Coming School -->
                                    <div class="col-lg-4 p-4">
                                        <label for="coming_school" class="pb-3">Coming School</label>
                                        <div class="input-group">
                                            <input type="text" name="coming_school" id="coming_school" class="form-control" required value="<?= $student_info['ECOLE_ORIGINE'] ?>">
                                            <div class="input-group-append">
                                                <span class=" input-group-text fas fa-school  bg-light"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Degree Area -->
                                    <!-- LE INPUT DOIT ETRE UN SELECT POUR QUE UN GARDE LE MEME FORMAT DE DONNEE -->
                                    <div class="col-lg-4 p-4">
                                        <label for="type_bac" class="pb-3">Bac Degree</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="type_bac" id="type_bac" value="<?= $student_info['DIPLOME'] ?>" required>
                                            <div class="input-group-append">
                                                <span class=" input-group-text fas fa-user-graduate bg-light"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- LE INPUT DOIT ETRE UN SELECT POUR QUE UN GARDE LE MEME FORMAT DE DONNEE -->

                                    <!-- Average Area -->
                                    <div class="col-lg-4 p-4">
                                        <label for="bac_average" class="pb-3">Bac Average</label>
                                        <div class="input-group">
                                            <input type="number" name="bac_average" id="bac_average" class="form-control" required value="<?= $student_info['MOYENNE'] ?>">
                                            <div class="input-group-append">
                                                <span class=" input-group-text fas fa-user-graduate bg-light"></span>
                                            </div>
                                        </div>
                                    </div>

                            <!-- ------------------------------------------------------------------------------------------------------ -->

                            <!-- Parents Information -->
                                <div class="col-lg-12 text-left my-2">
                                    <h5 class="text-bluesky mt-3">Parents Information</h5>
                                    <hr class="bg-gradient-primary">
                                </div>
          
                                    <!-- Parent Name -->
                                     <div class="col-lg-6 p-4">
                                        <label for="fath_name" class="pb-3">Father Name</label>
                                        <div class="input-group">
                                            <input type="text" name="fath_name" id="fath_name" class="form-control" value="<?= $student_info['NOM_PERE'] ?>" required>
                                            <div class="input-group-append">
                                                <span class=" input-group-text fas fa-user bg-light"></span>
                                            </div>
                                        </div>
                                    </div> 

                                     <!-- Mother Name -->
                                     <div class="col-lg-6 p-4">
                                        <label for="moth_name" class="pb-3">Mother Name</label>
                                        <div class="input-group">
                                            <input type="text" name="moth_name" id="moth_name" class="form-control" value="<?= $student_info['NOM_MERE'] ?>" required>
                                            <div class="input-group-append">
                                                <span class=" input-group-text fas fa-user bg-light"></span>
                                            </div>
                                        </div>
                                    </div> 

                                    <!-- Father Job -->
                                    <div class="col-lg-6 p-4">
                                        <label for="father_job" class="pb-3">Father Job</label>
                                        <div class="input-group">
                                            <input type="text" name="father_job" id="father_job" class="form-control" value="<?= $student_info['PROFESSION_PERE'] ?>" required>
                                            <div class="input-group-append">
                                                <span class=" input-group-text fas fa-user bg-light"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Mother Job -->
                                    <div class="col-lg-6 p-4">
                                        <label for="moth_job" class="pb-3">Mother Job</label>
                                        <div class="input-group">
                                            <input type="text" name="moth_job" id="moth_job" class="form-control" value="<?= $student_info['PROFESSION_MERE'] ?>" required>
                                            <div class="input-group-append">
                                                <span class=" input-group-text fas fa-user bg-light"></span>
                                            </div>
                                        </div>
                                    </div>

                            <!-- ------------------------------------------------------------------------------------------------------ -->

                            <!-- Meeting Information -->
                                <div class="col-lg-12 text-left my-2">
                                    <h5 class="text-bluesky mt-3">Meeting Information</h5>
                                    <hr class="bg-gradient-primary">
                                </div>
                            
                                    <div class="col-lg-6 p-4">
                                        <label for="recommendation" class="pb-3">Recommendation</label>
                                        <div class="input-group">
                                            <input type="text" name="recommendation" id="recommendation" class="form-control" value="<?= $student_info['RECOMMENDATION'] ?>" required>
                                            <div class="input-group-append">
                                                <span class=" input-group-text fas fa-user bg-light"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 p-4">
                                        <label for="scholar_rate" class="pb-3">Scholarship</label>
                                        <div class="input-group">
                                            <input type="text" name="scholar_rate" id="scholar_rate" class="form-control" value="<?= $student_motivation['TAUX'] .' %'?>" disabled required>
                                            <div class="input-group-append">
                                                <span class=" input-group-text fas fa-money-bill bg-light"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 p-4">
                                        <div class="form-group">
                                            <label for="observation" class="pb-3">Observations</label>
                                            <textarea name="observation" id="observation" class="form-control text-justify" rows="10" required style="resize:none;"><?= $student_motivation['OBSERVATION'] ?></textarea>
                                           
                                        </div>
                                    </div>

                                    <div class="col-lg-6 p-4">
                                        <div class="form-group">
                                            <label for="motivation" class="pb-3">Motivations</label>
                                            <textarea name="motivation" id="motivation" class="form-control text-justify" rows="10" required style="resize:none;"><?= $student_motivation['MOTIVATION'] ?></textarea>
                                            
                                        </div>
                                    </div>
                                    <!-- ------------------------------------------------------------------------------------------------------ -->

                                    <!-- Meeting Information -->
                                        <div class="col-lg-12 text-left my-2">
                                            <h5 class="text-bluesky mt-3">Meeting Information</h5>
                                            <hr class="bg-gradient-primary">
                                        </div>
                                    
                                    <div class="col-lg-6 p-4">
                                        <label for="school_fees" class="pb-3">School Fees</label>
                                        <div class="input-group">
                                            <input type="text" name="school_fees" id="school_fees" class="form-control" value="<?= $school_fees['MONTANT_TOTAL'] .' fcfa'?>" disabled required>
                                            <div class="input-group-append">
                                                <span class=" input-group-text fas fa-money-bill bg-light"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 p-4">
                                        <label for="paid" class="pb-3">Already payed</label>
                                        <div class="input-group">
                                            <input type="text" name="paid" id="paid" class="form-control" value="<?= $school_fees['MONTANT_PAYE'] .' fcfa'?>" disabled required>
                                            <div class="input-group-append">
                                                <span class=" input-group-text fas fa-money-bill bg-light"></span>
                                            </div>
                                        </div>
                                    </div>

                            <div class="col-12 col-lg-12 text-center my-5">
                                <button type="reset" class="btn btn-outline-danger px-4 rounded-pill" >Reset</button>
                                <button type="submit" class="btn btn-outline-primary px-4 rounded-pill" name="update_info" id="update_info"> Update </button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
    include("../footer.php");
?>
<script>
var updtStudentInfo = $('#form')

$.validator.addMethod('noSpace', function(value, element)
    {
        return value == '' || value.trim().length != 0;
    }, 'Blank fields are not allowed !')
updtStudentInfo.validate({
    rules:
    {
        matricule:
        {
            required: true,
            noSpace: true
        },
        last_name:
        {
            required: true,
            noSpace: true
        },
        first_name:
        {
            required: true,
            noSpace: true
        },
        birth:
        {
            required: true,
            noSpace: true
        },
        gender:
        {
            required: true
        },
        cnib:
        {
            required: true,
            noSpace: true
        },
        email:
        {
            required: true,
            noSpace: true
        },
        phone:
        {
            required: true,
            noSpace: true
        },
        emergency:
        {
            required: true,
            noSpace: true
        },
        coming_school:
        {
            required: true,
            noSpace: true
        },
        type_bac:
        {
            required: true,
            noSpace: true
        },
        modulus:
        {
            required: true,
            noSpace: true
        },
        fath_name:
        {
            required: true,
            noSpace: true
        },
        moth_name:
        {
            required: true,
            noSpace: true
        },
        father_job:
        {
            required: true,
            noSpace: true
        },
        moth_job:
        {
            required: true,
            noSpace: true
        },
        recommendation:
        {
            required: true,
            noSpace: true
        },
        scholar_rate:
        {
            required: true,
            noSpace: true
        },
        observation:
        {
            required: true,
            noSpace: true
        },
        motivation:
        {
            required: true,
            noSpace: true
        },
        school_fees:
        {
            required: true,
            noSpace: true
        },
        paid:
        {
            required: true,
            noSpace: true
        }
    },
    errorPlacement: function(error, element)
    {
        //element.append('br')
        error.insertAfter(element.parent())
    }
})









</script>