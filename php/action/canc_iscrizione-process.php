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

$query = "SELECT * FROM Dispone WHERE ID_Persona=".$_SESSION['ID'];
$result = $connection->query($query);
if(!$result || !$result->num_rows){
	send_message($page, "Sembra che non sei iscritto in questa palestra.");
}

// Il personal trainer non può cancellare la propria iscrizione
$query = "SELECT * FROM Corso WHERE ID_PersonalTrainer=".$_SESSION['ID'];
$pt = $connection->query($query);

if($pt && $pt->num_rows){
	unset($page["2p"]);
	send_message($page, "Non puoi cancellare l'iscrizione, sei il personal trainer di un corso.");
}

$query = "SELECT Nome FROM Palestra WHERE ID_Palestra=".$_POST['idPalestra'];
$tmp = $connection->query($query);
$palestra = ($tmp->fetch_assoc())['Nome'];

$query = "SELECT Nome FROM Persona WHERE ID_Persona=".$_SESSION['ID'];
$tmp = $connection->query($query);
$nome = ($tmp->fetch_assoc())['Nome'];

$query = "SELECT ID_Palestra, count(*) as admin FROM Dispone WHERE Qualifica=".Qualifica::Admin
	." AND ID_Persona=".$_SESSION['ID']." AND ID_Palestra=".$_POST['idPalestra']." GROUP BY ID_Palestra";
$tmp = $connection->query($query);
if($tmp && $tmp->num_rows==1){
	$query = "DELETE FROM Palestra WHERE ID_Palestra=".$_POST['idPalestra'];
	$connection->query($query) or die($connection->error);
	
	unset($page);
	send_message($page, "Cancellazione iscrizione e rimozione palesttra ".$palestra." avvenuta con successo");
}

$query = "DELETE FROM Dispone WHERE ID_Persona=".$_SESSION['ID'];
$connection->query($query) or die($connection->error);

$query = "DELETE FROM Partecipazione WHERE ID_Persona=".$_SESSION['ID']
	." AND ID_Corso IN ( SELECT C.ID_Corso FROM Corso C WHERE C.ID_Palestra=".$_POST['idPalestra']." )";
$connection->query($query) or die($connection->error);

send_message($page, "Cancellazione di ".$nome." dalla palestra ".$palestra." avvenuta con successo.");
?>
