<?php 
    include('../../../utilities/QueryBuilder.php');
    $title = 'Admin Dashboard';
    $breadcrumb = 'Home';
    include_once ('header.php');

    $obj = new QueryBuilder();

    $academic_years_exist = $obj->Select('annee_scolaire', array(), array());
    //of any line of academic year exists in the database
    if (is_object($academic_years_exist))
    {
        $academic_years_exist = $academic_years_exist->rowCount();
    }
    //if there is no records in the database
    else
    {
        $academic_years_exist = 0;
    }

    if(array_key_exists('confirm_new_year_creation' , $_POST))
    {
        echo "<script>window.open('new_year.php' , '_self') </script>";
    }

    if(array_key_exists('confirm_end_year_creation' , $_POST))
    {
        echo "<script>window.open('end_year.php' , '_self') </script>";
    }
?>

<div class="container-fluid">
    <div class="row">
  
        <div class="col-lg-6 ml-auto text-right">
            <?php
                //Verifier dans la base de donnee qu'il existe deja ou non une annee scolaire
                $annee_exists = 'vrai';
                //Si il existe pas d'abord une annee scolaire, on permet a l'utilisateur de commencer une nouvelle annee 
                if($academic_years_exist < 0) :
            ?>
                <button type="submit" data-toggle="modal" data-target="#confirmation_new_year" class="btn btn-dark text-capitalize" name="create_new_year_button" id="create_new_year_button">Start new year</button> 
            <?php
                else :
            ?>
                <button type="submit" data-toggle="modal" data-target="#confirmation_end_year" class="btn btn-dark text-capitalize" name="finish_current_year_button" id="finish_current_year_button">Jump to the next year</button> 
            <?php
                endif;
            ?>
        </div>
    </div>
</div>

<!-- Modal de confirmation creation d'une nouvelle annee -->
<div id="confirmation_new_year" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title text-warning" id="my-modal-title"><span class="fas fa-bell text-warning"></span> Welcome On Opensch</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
    
            <div class="modal-body">
                <p>
                    You are about to create your first academic year. We will guide you to design it simply. This step is irreversible.<br>
                    Are you ready to start ??
                </p>
                <form action="" method="post">
                    <div class="col text-right">
                        <button class="btn btn-danger" data-dismiss="modal" aria-label="Close">Cancel</button>
                        <button class="btn btn-success" type="submit" name="confirm_new_year_creation" id="confirm_new_year_creation">Confirm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Fin Modal de confirmation de changement d'annee -->


<!-- Modal de confirmation du passage a une nouvelle annee -->
<div id="confirmation_end_year" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title text-danger" id="my-modal-title"><span class="fas fa-bell text-danger"></span> Alert Warning</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
    
            <div class="modal-body">
                <p>
                    You are about to jump to a new academic. All the information are going to be out date. This step is irreversible.<br>
                    Are you sure to continue ??
                </p>
                <form action="" method="post">
                    <div class="col text-right">
                        <button class="btn btn-danger" data-dismiss="modal" aria-label="Close">Cancel</button>
                        <button class="btn btn-success" type="submit" name="confirm_end_year_creation" id="confirm_end_year_creation">Confirm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Fin Modal de confirmation de changement d'annee -->

<?php
    include('footer.php');
?>