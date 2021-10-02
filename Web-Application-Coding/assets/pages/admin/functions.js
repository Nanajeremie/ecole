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
//Fonction ajax pour le  paiement des frais de scolarite
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
//on change le prix de la scolarite en fonction de la classe choisie
function get_Montant(montant) {
//alert(montant);
$("#inscription_total").val(montant);
var taux = $("#inscription_fees_rat :selected").val().split('*')[1];
var finalMontant = montant-((montant*taux)/100);
$("#inscription_actu_fees").val(finalMontant);

}
// on applique la bourse choisie sur la scolarite
function get_Bourse(taux) {
	var montant_total = $("#inscription_total").val();
	var finalMontant = montant_total-((montant_total*taux)/100);
	$("#inscription_actu_fees").val(finalMontant);
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

//on change le prix de la scolarite en fonction de la classe choisie
function get_Montant(montant) {
//alert(montant);
$("#inscription_total").val(montant);
var taux = $("#inscription_fees_rat :selected").val().split('*')[1];
var finalMontant = montant-((montant*taux)/100);
$("#inscription_actu_fees").val(finalMontant);

}
// on applique la bourse choisie sur la scolarite
function get_Bourse(taux) {
	var montant_total = $("#inscription_total").val();
	var finalMontant = montant_total-((montant_total*taux)/100);
	$("#inscription_actu_fees").val(finalMontant);
}

function getScholar(scholar_Infos) {
    $("#motivation").val(scholar_Infos.split('#')[6]);
	$("#observation").val(scholar_Infos.split('#')[7]);
	$("#scholar_classe").val(scholar_Infos.split('#')[2]);
    $("#student_data").html('<span class ="text-warning"><b>Modify: </b></span>'+scholar_Infos.split('#')[0]+' '+ scholar_Infos.split('#')[1]);
    // on passe le id de l'etudiant dans un input cache
    $("#hide_id").val(scholar_Infos.split('#')[3]);
	$.post(
		'../../../ajax.php', {
			rate: 'ok',
			matr: scholar_Infos.split('#')[3],
			id_c: scholar_Infos.split('#')[4],
			id_t: scholar_Infos.split('#')[5],
		}, function (data) {
			$('#taux').html(data);
		});

}

function end_all_update(){
	
    $.post(
	
		'../../../ajax.php',{
			end_all_upd:'ok',
            id_student:$("#hide_id").val(),
            motivation:$("#motivation").val(),
            observation:$("#observation").val(),
            selected_taux:$("#taux :selected").val(),
}, function (data){
	
    if(data==1){
        $('#return').css('color', 'green');
        $('#return').html('The scholarship has successfuly updated');
        $('#return').css('padding', '6px');
        $('#return').css('background-color', '#e5ffe0');
    }else{
		if(data=="motivation" || data=="observation"){
			$('#return').css('color', 'red');
			$('#return').css('padding', '6px');
			$('#return').html('Failed to update the scholarsip');
			$('#return').css('background-color', '#f5dbd0');

			$('#'+data).css('border','1px solid red');
		}
		else if(data=="motivation*observation"){
			
			var motivation =data.split('*')[0]; 
			var observation =data.split('*')[1]; 
			$('#return').css('color', 'red');
			$('#return').css('padding', '6px');
			$('#return').html('Failed to update the scholarsip');
			$('#return').css('background-color', '#f5dbd0');

			$('#'+motivation).css('border','1px solid red');
			$('#'+observation).css('border','1px solid red');
		}
        
    }
    
	});
}
// affichage des infos de la bourse de l'etudiant
function get_New_StudentId(studentId){
    // on passe le id de l'etudiant dans un input cache
    $("#hide_id").val(studentId.split('~')[0]);
    // on colle le nom, prenom de l'etudiant sur le header du modal
    $("#student_data").html('<span class ="text-warning"><b>Modify: </b></span>'+studentId.split('~')[1]+' '+ studentId.split('~')[2]);
    //on met le value par defaut de la classe de l'etudiant
    $("#student_class").val(studentId.split('~')[3]);
    //on utilise ajax pour recuperer la liste des bourses disponible et on affiche dans le modal
    $.post(
		'../../../ajax.php',{
			submit_new_Scholar:'ok',
			id_new_student:studentId.split('~')[0],
            actual_taux:studentId.split('~')[4],
}, function (data){
    $("#taux").html(data);
	});
    
}

// la fonction pour la mise a jour de la bourse de l'etudiant 
function end_update(){
	
    $.post(

		'../../../ajax.php',{
			end_update:'ok',
            id_student:$("#hide_id").val(),
            selected_taux:$("#taux :selected").val(),
}, function (data){

    if(data==1){
        $('#return').css('color', 'green');
        $('#return').html('The scholarship has successfuly updated');
        $('#return').css('padding', '6px');
        $('#return').css('background-color', '#e5ffe0');
    }else if(data==0){
        $('#return').css('color', 'red');
        $('#return').css('padding', '6px');
        $('#return').html('Failed to update the scholarsip');
        $('#return').css('background-color', '#f8bccb');
    }
    
	});
}
 
// fonction pour reset le formatage 
function format(id){
	$('#return').html('');
	$('#return').css('padding', '0');
	$('#'+id).css('border','1px solid rgba(0,0,0,0.5)');

}





