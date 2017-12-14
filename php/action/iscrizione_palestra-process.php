<?php
require_once("../connect.php");
require_once("../utility.php");
if(session_status()==PHP_SESSION_NONE)
	session_start();

if(!isset($_SESSION['Login']) || !$_SESSION['Login']){
	$page['p']='login';
	send_message($page, "Per poterti iscrivere alla palestra devi prima essere loggato.");
}

$query = "INSERT INTO Dispone(ID_Palestra, ID_Persona, Qualifica) "
	."values (".$_POST['palestra'].", ".$_SESSION['ID'].", ".Qualifica::Atleta.")";
$connection->query($query);
if($connection->error){
	echo "Mysql error: ".$connection->error;
	$connection->close();
	die();
}

$page['id']=$_POST['palestra'];
send_message($page, "Iscrizione effettuata con successo.");
?>
