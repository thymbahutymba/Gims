<?php
require("../connect.php");
require("../utility.php");
if(session_status()==PHP_SESSION_NONE)
	session_start();

if(!isset($_SESSION['Login']) || !$_SESSION['Login']){
	$page['p']='login';
	send_message($page, "Per poterti iscrivere al corso devi prima essere loggato.");
}

/*
 * Controllo sulla correttezza del corso
 */

$query = "SELECT * FROM Corso WHERE ID_Corso=".$_POST['corso'];
$result = $connection->query($query);
if(!$result || !$result->num_rows){
	$page['id']=$_POST['palestra'];
	send_message($page, "Sembra che questo corso non esista.");
}

$query = "SELECT Nome FROM Corso WHERE ID_Corso=".$_POST['corso'];
$result = $connection->query($query);
$nome = ($result->fetch_assoc())['Nome'];

$query = "INSERT INTO Partecipazione(ID_Persona, ID_Corso) ".
	"values(".$_SESSION['ID'].", ".$_POST['corso'].")";
$connection->query($query);
if($connection->error){
	echo "Mysql error: ".$connection->error;
	$connection->close();
	die();
}

$page['id']=$_POST['palestra'];
send_message($page, "Iscrizione al corso ".$nome." avvenuta con successo.");
?>
