<?php
	require("../connect.php");
	require("../utility.php");
	$page['p'] = "login";

	foreach($_POST as $key => $value){
		${"p_".$key} = $value;
	}
	global $error;

	global $error;
	if(!filter_var($p_email)){
		$error = "Email non valida.";
	}elseif(!check_string($p_psw)){
		$error = "La password che hai inserito non è del tipo ammesso.";
	}

	if($error) {
		send_message($page, $error);
	}

	$res = $connection->query("SELECT * FROM Persona WHERE email='".$p_email."'");
	if(! mysqli_num_rows($res)){
		$page['p'] = "register";
		send_message($page, "Email non registrata!");
	}

	$res = $connection->query("SELECT Nome, Password, ID_Persona FROM Persona WHERE email='".$p_email."'");
	if(!$res){
		echo "Mysql error: ".$connection->error;
		$connection->close();
		die();
	}
	$row=$res->fetch_assoc();
	if($row['Password']!=hash('sha256', $p_psw))
	{
		$error = "Password errata!";
	}
	mysqli_free_result($res);

	if($error) { send_message($page, $error); }

	session_start();
	$_SESSION['ID']=$row['ID_Persona'];
	$_SESSION['Email']=$p_email;
	$_SESSION['Login']=1;
	$_SESSION['Nome']=$row['Nome'];
	
	header("location: ../../index.php");
	$connection->close();
	die();
?>
