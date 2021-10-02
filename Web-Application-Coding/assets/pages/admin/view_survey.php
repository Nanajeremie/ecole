<?php
    $title = 'Surveys';
    $breadcrumb = 'View Survey';

    include('../../../utilities/QueryBuilder.php');
    include('header.php');
    
    $obj= new QueryBuilder();

    if (isset($_GET['id'])) 
    {   
        $surveys = $obj->Requete("SELECT * FROM survey_set s , module m , classe c , enseigner e , professeur p WHERE s.ID_MODULE = m.ID_MODULE AND m.ID_CLASSE = c.ID_CLASSE AND e.ID_PROFESSEUR = p.ID_PROFESSEUR AND e.ID_MODULE = m.ID_MODULE AND s.ID_SURVEYS = '".$_GET['id']."' AND s.ID_ANNEE = '".getAnnee(0)['ID_ANNEE']."'")->fetch();
        $answers = $obj->Requete("SELECT COUNT(distinct(ID_USER)) as total from answers where survey_id = '".$_GET['id']."' AND ID_ANNEE = '".getAnnee(0)['ID_ANNEE']."'")->fetch();
        $qry = $obj->Requete("SELECT * FROM survey_set where ID_SURVEYS = '".$_GET['id']."' AND ID_ANNEE = '".getAnnee(0)['ID_ANNEE']."'")->fetch();
        
        foreach($qry as $k => $v)
        {
            $$k = $v;
        }
    }
    
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-4 wow slideInLeft my-5 my-md-3 my-lg-0">
            <div class="card border-top-primary">
                <div class="card-header">
                    <h6>Survey Details</h6>
                </div>
                <div class="card-body">
                    <p><span class="font-weight-bold">Modulus :</span> <?=$surveys['NOM_MODULE']?></p>
                    <p><span class="font-weight-bold">Teacher :</span> <?= (isset($surveys['NOM_PROF']) && isset($surveys['PRENOM_PROF'])) ? $surveys['PRENOM_PROF']. ' ' .$surveys['PRENOM_PROF'] : ''?></p>
                    <p class="text-justify"><span class="font-weight-bold">Description : </span><span class="small"><?= $surveys['SURVEY_DESCRIPTION'] ?></span></p>
                    <p><span class="font-weight-bold">Start Date :</span> <?= date("M d, Y",strtotime($surveys['START_DATE'])) ?></p>
                    <p><span class="font-weight-bold">End Date :</span> <?= date("M d, Y",strtotime($surveys['END_DATE'])) ?></p>
                    <p><span class="font-weight-bold">Have Taken :</span> <?= $answers['total'] ?></p>
                </div>
            </div>
        </div>

        <div class="col-lg-8 wow slideInRight my-5 my-md-3 my-lg-0">
            <div class="card border-top-success">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6 my-auto">
                            <h6>Survey questionaire</h6>
                        </div>
                        
                        <div class="col-6 text-right my-auto">
                            <a role="button" class="btn btn-dark rounded-pill h6" data-toggle="modal" data-target="#new_question">New Question</a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <form action="" method="post">
                        
                        <?php 
                            $question = $obj->Requete("SELECT * FROM questions where SURVEY_ID = '".$ID_SURVEYS ."' AND ID_ANNEE = '". getAnnee(0)['ID_ANNEE'] ."'order by abs(ORDER_BY) asc, abs(ID_QUESTIONS) asc");
                            while($row=$question->fetch()):	
                        ?>
                            <div class="card border-left-primary my-3 my-md-4 my-lg-4 px-3 px-sm-4 px-lg-4 pt-2">
                                <div class="row">
                                    <div class="col-10 col-md-10 col-lg-10">
                                        <h5><?= $row['QUESTION'] ?></h5>
                                    </div>

                                    <div class="col-2 col-md-2 col-lg-2 text-right">	
                                        <div class="dropdown">
                                            <a class="fa fa-ellipsis-v text-dark" href="javascript:void(0)" data-toggle="dropdown" aria-expanded="false"></a>
                                            <div class="dropdown-menu mr-5">
                                                <a class="dropdown-item text-dark" id="edit_question_button" data-toggle="modal" data-target="#edit_question" onclick="getTheId(<?= htmlspecialchars(json_encode($row['ID_QUESTIONS'])) ?> , 'qid') ; get_editable_data(<?= htmlspecialchars(json_encode($row['ID_QUESTIONS'])) ?>)">Edit</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item text-dark" role="button" data-toggle="modal" data-target="#delete_question" onclick="getTheId(<?= htmlspecialchars(json_encode($row['ID_QUESTIONS'])) ?> , 'getId')">Delete</a>
                                            </div>
                                        </div>	
                                    </div>	                                	

                                    <div class="col-lg-12">
                                        <input type="hidden" name="qid[]" value="<?= $row['ID_QUESTIONS'] ?>">	
                                        <?php
                                            if($row['TYPE'] == 'radio_opt'):
                                                foreach(json_decode($row['FRM_OPTION']) as $k => $v):
                                        ?>
                                                    <div class="icheck-primary">
                                                        <input type="radio" id="option_<?php echo $k ?>" name="answer[<?php echo $row['ID_QUESTIONS'] ?>]" value="<?php echo $k ?>">
                                                        <label for="option_<?php echo $k ?>"><?php echo $v ?></label>
                                                    </div>
                                        <?php   endforeach; ?>

                                        <?php 
                                            elseif($row['TYPE'] == 'check_opt'): 
                                                foreach(json_decode($row['FRM_OPTION']) as $k => $v):
                                        ?>
                                                    <div class="icheck-primary">
                                                        <input type="checkbox" id="option_<?php echo $k ?>" name="answer[<?php echo $row['ID_QUESTIONS'] ?>][]" value="<?php echo $k ?>">
                                                        <label for="option_<?php echo $k ?>"><?php echo $v ?></label>
                                                    </div>
                                        <?php   endforeach; ?>

                                        <?php else: ?>
                                            <div class="form-group">
                                                <textarea name="answer[<?php echo $row['ID_QUESTIONS'] ?>]" cols="30" rows="4" class="form-control" placeholder="Write Something Here..."></textarea> 
                                            </div>                                     
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>	
					    					
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- --------------------------------------------------- Survey New Question ------------------------------------------------------>

<div class="modal fade" id="new_question" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard='false' aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary">
                <h5 class="modal-title text-light">Question</h5>
                <button type="button" onclick="document.location.reload(true)" class="close text-light" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>


            <div class="modal-body">
                <form action="" id="manage_question" name="manage_question" method="post">
                    <div class="row">
                        <div class="col-lg-6 border-right">

                            <input type="hidden" name="id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : '' ?>">

                            <div class="form-group py-lg-4">
                                <label for="" class="control-label">New Question <span class="text-danger"> * </span></label>
                                <textarea name="question" id="question_name" cols="30" rows="4" class="form-control" onkeyup="valider($(this))"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="" class="control-label">Question Answer Type <span class="text-danger"> * </span></label>
                                <select name="type" id="type" class="custom-select custom-select-sm" onchange="valider($(this))">
                                    <option value="null" disabled selected>Please Select here</option>
                                    <option value="radio_opt">Single Answer/Radio Button</option>
                                    <option value="check_opt">Multiple Answer/Check Boxes</option>
                                    <option value="textfield_s">Text Field/ Text Area</option>
                                </select>
                            </div> 

                        </div>

                        <div class="col-lg-6 py-lg-4">
                            <b>Preview</b>

                            <div class="preview my-2">

                                <?php if(!isset($ID_QUESTIONS)): ?>
                                    <span class="py-2 text-muted">Select Question Answer type first.</span>
                                <?php else: ?>

                                    <div class="callout callout-info card border-left-primary">
                                        <?php if(isset($TYPE)): 
                                        
                                            $opt= $TYPE =='radio_opt' ? 'radio': 'checkbox';
                                                if ($TYPE != "textfield_s"):
                                        ?>
                                                    <table width="100%" class="table table-borderless">
                                                        <colgroup>
                                                            <col width="10%">
                                                            <col width="80%">
                                                            <col width="10%">
                                                        </colgroup>

                                                        <thead>
                                                            <tr class="">
                                                                <th class="text-center"></th>

                                                                <th class="text-center">
                                                                    <label for="" class="control-label">Label</label>
                                                                </th>
                                                                <th class="text-center"></th>
                                                            </tr>
                                                        </thead>

                                                        <tbody>
                                                            <?php  
                                                                $i = 0;
                                                                foreach(json_decode($FRM_OPTION) as $k => $v):
                                                                $i++;
                                                            ?>
                                                                    <tr>
                                                                        <td class="text-center">
                                                                            <div class="icheck-primary d-inline" data-count = '<?php echo $i ?>'>
                                                                                <input type="<?php echo $opt ?>" id="<?php echo $opt ?>Primary<?php echo $i ?>" name="<?php echo $opt ?>">
                                                                                <label for="<?php echo $opt ?>Primary<?php echo $i ?>"></label>
                                                                            </div>
                                                                        </td>

                                                                        <td class="text-center">
                                                                            <input type="text" class="form-control form-control-sm check_inp"  name="label[]" value="<?php echo $v ?>">
                                                                        </td>
                                                                        <td class="text-center"></td>
                                                                    </tr>
                                                            <?php   
                                                                endforeach; 
                                                            ?>
                                                        </tbody>
                                                    </table>

                                                    <div class="row">
                                                        <div class="col-sm-12 text-center">
                                                            <button class="btn btn-outline-dark rounded-pill px-4" type="button" onclick="<?php echo $TYPE ?>($(this))"><i class="fa fa-plus"></i> Add</button>
                                                        </div>
                                                    </div>
                                            
                                            <?php else: ?>
                                                    <textarea name="frm_opt" cols="30" rows="10" class="form-control" disabled="" placeholder="Write Something here..."></textarea>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="col-lg-12 text-center my-3">
                            <button type="reset" class="btn btn-outline-dark px-4 rounded-pill" onclick="document.location.reload(true)">Reset</button>
                            <input type="button" class="btn btn-outline-primary px-4 rounded-pill" onclick="save_question_function()" name="add_question" id="add_question" value="Add question">
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<!-- --------------------------------------------------- Survey Update Question ------------------------------------------------------>

<div class="modal fade" id="edit_question" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard='false' aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary">
                <h5 class="modal-title text-light">Modify Question</h5>
                <button type="button" onclick="document.location.reload(true)" class="close text-light" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" id="edit_question_form" name="edit_question_form" method="post">
                    <div class="row">
                        <div class="col-lg-6 border-right">

                            <input type="hidden" name="id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : '' ?>">

                            <div class="form-group py-lg-4">
                                <label for="" class="control-label">New Question <span class="text-danger"> * </span></label>
                                <textarea name="edit_question_name" id="edit_question_name" cols="30" rows="4" class="form-control"></textarea>
                            </div>

                            <input hidden type="text" name="qid" id="qid">

                            <div class="form-group">
                                <label for="" class="control-label">Question Answer Type <span class="text-danger"> * </span></label>
                                <select name="edit_type" id="edit_type" class="custom-select custom-select-sm">
                                    <option value="null" disabled>Please Select here</option>
                                    <option value="radio_opt" id="radio_opt">Single Answer/Radio Button</option>
                                    <option value="check_opt" id="check_opt">Multiple Answer/Check Boxes</option>
                                    <option value="textfield_s" id="textfield_s">Text Field/ Text Area</option>
                                </select>
                            </div> 

                        </div>

                        <div class="col-lg-6 py-lg-4">
                            <b>Preview</b>

                            <div class="preview my-2">
                                

                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 text-center my-3">
                            <button type="reset" class="btn btn-outline-dark px-4 rounded-pill" onclick="document.location.reload(true)">Reset</button>
                            <input type="button" class="btn btn-outline-primary px-4 rounded-pill" onclick="edit_question_function()" name="edit_question" id="edit_question" value="Update question">
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<!-- ------------------------------------------------------ Delete question ----------------------------------------------------- -->

<div class="modal fade" id="delete_question" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard='false' aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-danger">
                <h5 class="modal-title text-light">Deletion Confirmation</h5>
                <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="post" id="delete_question_form">
                    <div class="row">
                        <div class="col-lg-12">
                            Are you sure you want to delete this question from the survey ? Remember that this action is irreversible.
                        </div>
                        <input id="getId" name="question_id" hidden>
                        <div class="col-lg-12 text-center my-3">
                            <input type="button" class="btn btn-outline-dark px-4 rounded-pill" data-dismiss="modal" value="Reset">
                            <input type="button" class="btn btn-outline-danger px-4 rounded-pill" onclick="delete_question_function()" name="confirm_delete_question" id="confirm_delete_question" value="Delete the question">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> 

<!-- ---------------------------------------------------------------------------------------------------------------------------- -->

<div id="check_opt_clone"  style="display: none">
	<div class="callout callout-info">
      <table width="100%" class="table">
      	<colgroup>
      		<col width="10%">
      		<col width="80%">
      		<col width="10%">
      	</colgroup>
      	<thead>
	      	<tr class="">
		      	<th class="text-center"></th>

		      	<th class="text-center">
		      		<label for="" class="control-label">Label</label>
		      	</th>
		      	<th class="text-center"></th>
	     	</tr>
     	</thead>
     	<tbody>
     		<tr class="">
		      	<td class="text-center">
		      		<div class="icheck-primary d-inline" data-count = '1'>
			        	<input type="checkbox" id="checkboxPrimary1" checked="">
			        	<label for="checkboxPrimary1">
			        	</label>
			        </div>
		      	</td>

		      	<td class="text-center">
		      		<input type="text" class="form-control form-control-sm check_inp" name="label[]">
		      	</td>
		      	<td class="text-center"></td>
	     	</tr>
	     	<tr class="">
		      	<td class="text-center">
		      		<div class="icheck-primary d-inline" data-count = '2'>
			        	<input type="checkbox" id="checkboxPrimary2" >
			        	<label for="checkboxPrimary2">
			        	</label>
			        </div>
		      	</td>

		      	<td class="text-center">
		      		<input type="text" class="form-control form-control-sm check_inp" name="label[]">
		      	</td>
		      	<td class="text-center"></td>
	     	</tr>
     	</tbody>
      </table>
      <div class="row">
      <div class="col-sm-12 text-center">
      	<button class="btn btn-outline-dark rounded-pill px-4" type="button" onclick="new_check($(this))"><i class="fa fa-plus"></i> Add</button>
      </div>
      </div>
    </div>
</div>

<div id="radio_opt_clone" style="display: none">
	<div class="callout callout-info">
      <table width="100%" class="table">
      	<colgroup>
      		<col width="10%">
      		<col width="80%">
      		<col width="10%">
      	</colgroup>
      	<thead>
	      	<tr class="">
		      	<th class="text-center"></th>

		      	<th class="text-center">
		      		<label for="" class="control-label">Label</label>
		      	</th>
		      	<th class="text-center"></th>
	     	</tr>
     	</thead>
     	<tbody>
     		<tr class="">
		      	<td class="text-center">
		      		<div class="icheck-primary d-inline" data-count = '1'>
			        	<input type="radio" id="radioPrimary1" name="radio" checked="">
			        	<label for="radioPrimary1">
			        	</label>
			        </div>
		      	</td>

		      	<td class="text-center">
		      		<input type="text" class="form-control form-control-sm check_inp"  name="label[]">
		      	</td>
		      	<td class="text-center"></td>
	     	</tr>
	     	<tr class="">
		      	<td class="text-center">
		      		<div class="icheck-primary d-inline" data-count = '2'>
			        	<input type="radio" id="radioPrimary2" name="radio" >
			        	<label for="radioPrimary2">
			        	</label>
			        </div>
		      	</td>

		      	<td class="text-center">
		      		<input type="text" class="form-control form-control-sm check_inp"  name="label[]">
		      	</td>
		      	<td class="text-center"></td>
	     	</tr>
     	</tbody>
      </table>
      <div class="row">
      <div class="col-sm-12 text-center">
      	<button class="btn btn-outline-dark rounded-pill px-4" type="button" onclick="new_radio($(this))"><i class="fa fa-plus"></i> Add</button>
      </div>
      </div>
    </div>
</div>

<div id="textfield_s_clone" style="display: none">
	<div class="callout callout-info card-body">
		<textarea name="frm_opt" id="" cols="30" rows="10" class="form-control" disabled=""  placeholder="Write Something here..."></textarea>
	</div>
</div>

<?php
    include("../footer.php");
?>

<script>

    //--------------------------------------- Add new radio or checkbox button backend ----------------------------------------------------=

    function new_check(_this){
		var tbody=_this.closest('.row').siblings('table').find('tbody')
		var count = tbody.find('tr').last().find('.icheck-primary').attr('data-count')
			count++;
		console.log(count)
		var opt = '';
			opt +='<td class="text-center pt-1"><div class="icheck-primary d-inline" data-count = "'+count+'"><input type="checkbox" id="checkboxPrimary'+count+'"><label for="checkboxPrimary'+count+'"> </label></div></td>';
			opt +='<td class="text-center"><input type="text" class="form-control form-control-sm check_inp" name="label[]"></td>';
			opt +='<td class="text-center"><a href="javascript:void(0)" onclick="$(this).closest(\'tr\').remove()"><span class="fa fa-times" ></span></a></td>';
		var tr = $('<tr></tr>')
		tr.append(opt)
		tbody.append(tr)
	}

	function new_radio(_this){
		var tbody=_this.closest('.row').siblings('table').find('tbody')
		var count = tbody.find('tr').last().find('.icheck-primary').attr('data-count')
			count++;
		console.log(count)
		var opt = '';
			opt +='<td class="text-center pt-1"><div class="icheck-primary d-inline" data-count = "'+count+'"><input type="radio" id="radioPrimary'+count+'" name="radio"><label for="radioPrimary'+count+'"> </label></div></td>';
			opt +='<td class="text-center"><input type="text" class="form-control form-control-sm check_inp" name="label[]"></td>';
			opt +='<td class="text-center"><a href="javascript:void(0)" onclick="$(this).closest(\'tr\').remove()"><span class="fa fa-times" ></span></a></td>';
		var tr = $('<tr></tr>')
		tr.append(opt)
		tbody.append(tr)
	}

    //---------------------------------------- Append the choose type to the preview ----------------------------------------------------=

	function check_opt(){
		var check_opt_clone = $('#check_opt_clone').clone()
		$('.preview').html(check_opt_clone.html())
	}

	function radio_opt(){
		var radio_opt_clone = $('#radio_opt_clone').clone()
		$('.preview').html(radio_opt_clone.html())
	}

	function textfield_s(){
		var textfield_s_clone = $('#textfield_s_clone').clone()
		$('.preview').html(textfield_s_clone.html())
	}

    //-------------------------------------------------------------------------------------------------------------------------------------=

    function valider(str) {
        if (str.val() != '') {
            str.removeClass('border border-danger')
        }
        else
        {
            str.addClass('border border-danger')
        }
    }
    
    //---------------------------------------------------Change the type on select option change ------------------------------------------=

    $('[name="type"]').change(function(){
		window[$(this).val()]()
	})

    $('[name="edit_type"]').change(function(){
		window[$(this).val()]()
	})

    //-------------------------------------------------------------------------------------------------------------------------------------=

    function getTheId(target_id , id) {  
        $("#"+id).val(target_id);
    }
   
    function get_editable_data(id){
        $.ajax({
            url: "../../../ajax.php?action=get_editable_data&qid="+id,
            method: "post",
            data: {
                    'question_id' : $('#'+id).val()
                  },
            dataType: "json",
            success: function (response) {
                
                $("#edit_question_name").val(response['QUESTION']);
                
                if (response['TYPE'] == 'textfield_s') {
                    $("#textfield_s").attr('selected', 'selected');
                    let textarea = "<div class='callout callout-info card border-left-primary'><textarea name='frm_opt' cols='30' rows='10' class='form-control' disabled='' placeholder='Write Something here...'></textarea></div>";
                    $('.preview').html(textarea);
                }

                else if (response['TYPE'] == 'radio_opt') {
                    $("#radio_opt").attr('selected', 'selected');
                    let choice = '<div class="callout callout-info card border-left-primary"> \
                                    <table width="100%" class="table table-borderless">\
                                    <colgroup>\
                                        <col width="10%"><col width="80%"><col width="10%">\
                                    </colgroup>\
                                    <thead>\
                                        <tr class=""> \
                                            <th class="text-center"></th> \
                                            <th class="text-center"><label for="" class="control-label">Label</label></th> \
                                            <th class="text-center"></th> \
                                        </tr>\
                                    </thead>\
                                    <tbody>\
                            ';
                    var i = 0;
                    Object.keys(JSON.parse(response['FRM_OPTION'])).forEach(key => {
                        i++;
                        choice += '\
                                    <tr>\
                                        <td class="text-center">\
                                            <div class="icheck-primary d-inline" data-count = ' + i + '> \
                                                <input type="radio" id="radioPrimary'+ i + '" name="radio"> \
                                                <label for="radioPrimary'+ i + '"></label> \
                                            </div>\
                                        </td>\
                                        <td class="text-center"> \
                                            <input type="text" class="form-control form-control-sm check_inp"  name="label[]" value="'+ JSON.parse(response['FRM_OPTION'])[key] +'"> \
                                        </td> \
                                        <td class="text-center"></td> \
                                    </tr> \
                                ';
                    });

                    choice += '</tbody> \
                            </table> \
                            <div class="row"> \
                                <div class="col-sm-12 text-center"> \
                                    <button class="btn btn-outline-dark rounded-pill px-4 my-2" type="button" onclick="new_radio($(this))"><i class="fa fa-plus"></i> Add</button> \
                                </div> \
                            </div> \
                            </div> \
                            ';
                    $('.preview').html(choice);
                }

                else if (response['TYPE'] == 'check_opt') {
                    $("#check_opt").attr('selected', 'selected');
                    let choice = '<div class="callout callout-info card border-left-primary"> \
                                    <table width="100%" class="table table-borderless">\
                                    <colgroup>\
                                        <col width="10%"><col width="80%"><col width="10%">\
                                    </colgroup>\
                                    <thead>\
                                        <tr class=""> \
                                            <th class="text-center"></th> \
                                            <th class="text-center"><label for="" class="control-label">Label</label></th> \
                                            <th class="text-center"></th> \
                                        </tr>\
                                    </thead>\
                                    <tbody>\
                            ';
                    var i = 0;
                    Object.keys(JSON.parse(response['FRM_OPTION'])).forEach(key => {
                        i++;
                        choice += '\
                                    <tr>\
                                        <td class="text-center">\
                                            <div class="icheck-primary d-inline" data-count = ' + i + '> \
                                                <input type="checkbox" id="checkboxPrimary'+ i + '" name="checkbox"> \
                                                <label for="checkboxPrimary'+ i + '"></label> \
                                            </div>\
                                        </td>\
                                        <td class="text-center"> \
                                            <input type="text" class="form-control form-control-sm check_inp"  name="label[]" value="'+ JSON.parse(response['FRM_OPTION'])[key] +'"> \
                                        </td> \
                                        <td class="text-center"></td> \
                                    </tr> \
                                ';
                    });

                    choice += '</tbody> \
                            </table> \
                            <div class="row"> \
                                <div class="col-sm-12 text-center"> \
                                    <button class="btn btn-outline-dark rounded-pill px-4 my-2" type="button" onclick="new_check($(this))"><i class="fa fa-plus"></i> Add</button> \
                                </div> \
                            </div> \
                            </div> \
                            ';
                    $('.preview').html(choice);
                
                }
                
            }
        });
    }

    //-------------------------------------------------------------------------------------------------------------------------------------=
    

    function save_question_function() {
        $.post("../../../ajax.php?action=add_question", $("#manage_question").serialize(),
            function (data , textStatus, jqXHR) {
                console.log(data);
                if(data == 'success')
                {
                    toastr.success("Question added successfully");  
                    $('textarea , select').val('');  
                    $('.preview').html("<span class='py-2 text-muted'>Select Question Answer type first.</span>");
                }
                else
                {
                    toastr.error("An error occured");
                }
                
            },
    
        );     
       
    }
    
    function edit_question_function() {  
        $.post("../../../ajax.php?action=edit_question", $("#edit_question_form").serialize(),
            function (data, textStatus, jqXHR) {
                console.log(data);
                if(data == 'success')
                {
                    toastr.success("Question modified successfully");    
                    setTimeout(function(){ location.reload(true) },1000)
                }
                else
                {
                    toastr.error("An error occured");
                }
            },
        );
    }

    function delete_question_function() {  
        $.post("../../../ajax.php?action=delete_question", $("#delete_question_form").serialize(),
            function (data, textStatus, jqXHR) {
                console.log(data);
                if(data == 'success')
                {
                    toastr.success("Question deleted successfully");    
                    setTimeout(function(){ location.reload(true) },1000)
                }
                else
                {
                    toastr.error("An error occured");
                }
            },
        );
    }

    //-------------------------------------------------------------------------------------------------------------------------------------=

</script>

