<?php
require("../connect.php");
require("../utility.php");

check_login();
$page['id']=$_POST['idPalestra'];
$page['2p']="utenti";


$query = "SELECT Qualifica FROM Dispone WHERE ID_Persona=".$_SESSION['ID'];
$qual_op = (($connection->query($query))->fetch_assoc())['Qualifica'];

$query = "SELECT Qualifica FROM Dispone WHERE ID_Persona=".$_POST['idPersona'];
$qual_rim = (($connection->query($query))->fetch_assoc())['Qualifica'];

if($qual_rim<=$qual_op)
	send_message($page, "Non disponi dei permessi necessari per rimuovere un utente.");

$query = "SELECT Cognome, Nome FROM Persona WHERE ID_Persona=".$_POST['idPersona'];
$result = ($connection->query($query))->fetch_assoc();

$query = "DELETE FROM Dispone WHERE ID_Palestra=".$_POST['idPalestra'].
	" AND ID_Persona=".$_POST['idPersona'];

$connection->query($query) || die($connection->error);

send_message($page, "Rimozione di ".$result['Cognome']." ".$result['Nome']." avvenuta con successo");
?>
