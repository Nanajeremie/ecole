
function rename_input_update(titre) {
	var inp = document.getElementById('id'+titre);
	inp.name='id_user';
}

function rename_input_delete(titre) {
	var inp = document.getElementById('id_del_'+titre);
	inp.name='id_user_del';
}

function rename_input_plan_meeting(titre) {
	var inp = document.getElementById('id_plan_'+titre);
	inp.name='id_user_meet';
}
function getid_bourse(titre) {

	var inp = document.getElementById('br'+titre);
	inp.name='id_bourse';

}

function getid_bourse_delete(titre) {

	var inp = document.getElementById('del_scholarship'+titre);
	
	inp.name='del_scholarship';

}

function rename_input_updt_plan_meeting(titre) {
	var inp = document.getElementById('id_updt_meet_'+titre);
	inp.name='id_user_meet_updt';
}

function rename_input_del_plan_meeting(titre) {
	var inp = document.getElementById('id_del_meet_'+titre);
	inp.name='id_user_meet_del';
}

function Attrib_matricule_to_input(titre)
{
	document.getElementById('matricule').value = titre;
}
function Attrib_old_matricule_to_input(titre) {
	document.getElementById('old_matricule').value = titre;
}




function zeroToPay()
{

console.log('erewer');
	var donner=document.getElementById('inscription_actu_fees');
	if (donner.value==0){
		document.getElementById('inscription_payment').setAttribute('readonly','');
		document.getElementById('inscription_payment').value='0';

	}
}

function getStudentInfo(student_info,name) {
	$("#students_head").html(name);
	$.post(
		'../../../ajax.php',{
			submit_paye:'ok',
			id_inscription:student_info,
}, function (data){
		$('#payement_content').html(data);
	});


}



