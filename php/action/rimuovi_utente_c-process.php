<?php
require("../connect.php");
require("../utility.php");

check_login();
$page['id']=$_POST['idPalestra'];
$page['2p']="utenti_corsi";

/*
$query = "SELECT Qualifica FROM Dispone WHERE ID_Persona=".$_SESSION['ID'];
$qual_op = (($connection->query($query))->fetch_assoc())['Qualifica'];
 */
$qual_op = get_qualifica($connection, $_POST['idPalestra']);

/*
$query = "SELECT Qualifica FROM Dispone WHERE ID_Persona=".$_POST['idPersona'];
$qual_rim = (($connection->query($query))->fetch_assoc())['Qualifica'];
 */
if(Qualifica::Segretario<$qual_op)
	send_message($page, "Non disponi dei permessi necessari per rimuovere un utente.");

$query = "SELECT Cognome, Nome FROM Persona WHERE ID_Persona=".$_POST['idPersona'];
$result = ($connection->query($query))->fetch_assoc();

$query = "SELECT Nome FROM Corso WHERE ID_Corso=".$_POST['idCorso'];
$nome = (($connection->query($query))->fetch_assoc())['Nome'];

$query = "DELETE FROM Partecipazione WHERE ID_Persona=".$_POST['idPersona']
	." AND ID_Corso=".$_POST['idCorso'];
$connection->query($query) || die($connection->error);

send_message($page, "Rimozione di ".$result['Cognome']." ".$result['Nome']." dal corso ".$nome." avvenuta con successo.");
?>
