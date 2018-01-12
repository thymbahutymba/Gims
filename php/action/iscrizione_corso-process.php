<?php
require("../connect.php");
require("../utility.php");

if(session_status() == PHP_SESSION_NONE)
	session_start();

if(!isset($_SESSION['Login']) || !$_SESSION['Login']){
	$page['p']='login';
	send_message($page, "Per poterti iscrivere al corso devi prima essere loggato.");
}

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
$nome = ($result->fetch_assoc())['Nome'];

$query = "SELECT count(*) as Iscritti FROM Partecipazione WHERE ID_Corso=".$_POST['idCorso'];
$result = $connection->query($query);
$iscritti = ($result->fetch_assoc())['Iscritti'];

$query = "SELECT LimiteMassimo FROM Corso WHERE ID_Corso=".$_POST['idCorso'];
$result = $connection->query($query);
if($iscritti == ($result->fetch_assoc())['LimiteMassimo']){
	send_message($page, "Il corso ha raggiunto il limite massimo, impossibile iscriversi");
}

$query = "INSERT INTO Partecipazione(ID_Persona, ID_Corso) ".
	"values(".$_SESSION['ID'].", ".$_POST['idCorso'].")";

$connection->query($query) or die($connection->error);

send_message($page, "Iscrizione al corso ".$nome." avvenuta con successo.");
?>
