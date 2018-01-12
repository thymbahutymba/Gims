<?php
require("../connect.php");
require("../utility.php");

if(session_status() == PHP_SESSION_NONE)
	session_start();

check_login();
$page['id']=$_POST['idPalestra'];

/*
 * Controllo sulla correttezza del corso
 */

$query = "SELECT * FROM Corso WHERE ID_Corso=".$_POST['idCorso'];
$result = $connection->query($query);
if(!$result || !$result->num_rows){
	send_message($page, "Sembra che questo corso non esista.");
}

$query = "SELECT Nome FROM Corso WHERE ID_Corso=".$_POST['idCorso'];
$result = $connection->query($query);
$corso = ($result->fetch_assoc())['Nome'];

$query = "SELECT Nome FROM Persona WHERE ID_Persona=".$_SESSION['ID'];
$result = $connection->query($query);
$nome = ($result->fetch_assoc())['Nome'];

$query = "SELECT * FROM Partecipazione WHERE ID_Corso=".$_POST['idCorso']
	." AND ID_Persona=".$_SESSION['ID'];
$result = $connection->query($query);
if($result && !$result->num_rows)
	send_message($page, "Non risulti iscritto a questo corso");

$query = "DELETE FROM Partecipazione WHERE ID_Corso=".$_POST['idCorso']
	." AND ID_Persona=".$_SESSION['ID'];
$connection->query($query) or die($connection->error);

send_message($page, "Cancellazione di ".$nome." dal corso ".$corso." avvenuta con successo.");
?>
