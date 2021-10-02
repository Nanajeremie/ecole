<?php 
include 'utilities/QueryBuilder.php';
$obj = new QueryBuilder();
if (isset($_POST['submit']))
{
	
	extract($_POST);
	$cookies=array();

	$values = array($username,$password);
	$columns = array('USERNAME','PASSWORD');
	$table='user';
	$sessions=array('ID_USER','USERNAME');
	$return = array('DROITS');
	if (isset($remember) AND !empty($remember ))
	{
  		$cookies=array('USERNAME'=>$username,'PASSWORD'=>$password);
	}
	$connecter=	$obj->Connexion($table, $columns, $values,$return,$cookies,$sessions);
   var_dump($connecter);

	if (count($connecter)>0) {
		echo "connecter avec susses";
	}else{
		echo "connection echoue";
	}
}

 ?>
 <!DOCTYPE html>
 <html>
 <head>
 	<title>Connexion</title>
 </head>
 <body>
 
<form action="#" method="post">
	<input type="text" name="username">
	<input type="password" name="password">
	<input type="checkbox" name="remember">
	<a href="restore_password.php">Password Forgotten</a>
	<button name="submit">Submit</button>
</form>

 </body>
 </html>