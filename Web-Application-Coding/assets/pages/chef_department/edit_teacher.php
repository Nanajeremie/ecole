<?php
    include '../../../utilities/QueryBuilder.php';
    $title = 'Manage Teachers';
    $breadcrumb = 'Add new teacher';

    $obj = new QueryBuilder();

    //selects all classes in the database
    $classes = $obj->Select('classe');
    

    //selects all teachers
    $teachers = $obj->Select('professeur p, user u', [], ['u.ID_USER'=>'p.ID_USER']);

    include('header.php');
?>
    <style> 
        .inner{
            overflow: hidden;
        }

        .inner img{
            transition: all 1.5s ease;
        } 

        .inner:hover img{
            transform: scale(1.5);

        }

    </style>

    <div class="container-fluid">
        <div class="row">

            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <form action="" method="post">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group my-3">
                                        <select type="text" class="form-control"  id="class" name="class" required onchange=ChooseClass()>
                                        <option value=0>All classes</option>
                                        <?php
                                            while($class = $classes->fetch())
                                            {
                                                ?>
                                                    <option value=<?= $class['ID_CLASSE']?>><?= $class['NOM_CLASSE']?></option>
                                                <?php
                                            } 
                                        ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-body" id="showTeachers">
                        <div class="row">
                        <?php
                            if (is_object($teachers)):
                                while($teacher = $teachers->fetch())
                                {
                        ?>
                                <div class="col-lg-3 py-3">
                                    <div class="card shadow">
                                        <div class="inner">
                                            <img class="card-img-top rounded" src="../../media/<?= empty($teacher['PROFILE_PIC']) ? 'user_24px.png': $teacher['PROFILE_PIC']?>" height="250px">
                                        </div>
                                        <div class="card-body text-center">
                                            <h5 class="card-title font-weight-bold"><?= $teacher['NOM_PROF']." ".$teacher['PRENOM_PROF']?></h5>
                                            <p class="card-text text-justify"><strong>Grade : </strong><?= $teacher['GRADE']?></p>
                                            <a href="teacher_info.php?prof=<?= $teacher['ID_PROFESSEUR']?>" role="button" class="btn btn-outline-primary rounded-pill">See more</a>
                                        </div>
                                    </div>
                                </div>
                        <?php
                                }
                            else:
                                echo("<div class='col-lg-12'><p class='text-center'>There is no registered teacher</p></div>");
                            endif;
                        ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


<?php
    include('../footer.php');
?>

<script>

function ChooseClass()
{
    
    $('#showTeachers').text("")
    $.post(
        '../../../ajax.php',
        {
            idClass: $("#class").val()
        }, function (data)
        {
            $('#showTeachers').html(data)
        })
}

</script>