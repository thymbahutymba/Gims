<?php
require("../connect.php");
require("../utility.php");

check_login();
$page['id']=$_POST['idPalestra'];
$page['2p']="utenti";

/*
$query = "SELECT Qualifica FROM Dispone WHERE ID_Persona=".$_SESSION['ID'];
$qual_op = (($connection->query($query))->fetch_assoc())['Qualifica'];
 */
$qual_op = get_qualifica($connection, $_POST['idPalestra']);

$query = "SELECT Qualifica FROM Dispone WHERE ID_Persona=".$_POST['idPersona'];
$qual_rim = (($connection->query($query))->fetch_assoc())['Qualifica'];

if($qual_rim<=$qual_op)
	send_message($page, "Non disponi dei permessi necessari per rimuovere un utente.");

$query = "SELECT Cognome, Nome FROM Persona WHERE ID_Persona=".$_POST['idPersona'];
$result = ($connection->query($query))->fetch_assoc();

// Controllo se rimuovo personal trainer a cui è affidato un corso

if($qual_rim==Qualifica::Personal_Trainer){
	$query = "SELECT * FROM Corso C WHERE C.ID_Palestra=".$_POST['idPalestra']
		." AND C.ID_PersonalTrainer=".$_POST['idPersona'];

	$tmp = $connection->query($query);
	if($tmp->num_rows){
		$msg = "Impossibile rimuovere il Personal Trainer ".$result['Cognome']." "
			.$result['Nome']." è responsabile di un corso, affida prima il corso a qualche altro Personal Trainer";
		send_message($page, $msg);
	}
}

// Rimozione utente dai corsi in cui è iscritto

$query = "DELETE FROM Partecipazione ";
$query .= "WHERE ID_Persona=".$_POST['idPersona']." AND ID_Corso IN ";
$query .= "(SELECT ID_Corso FROM Corso WHERE ID_Palestra=".$_POST['idPalestra'].")";

$connection->query($query) || die($connection->error);

// Rimozione utente dalla palestra

$query = "DELETE FROM Dispone WHERE ID_Palestra=".$_POST['idPalestra'].
	" AND ID_Persona=".$_POST['idPersona'];

$connection->query($query) || die($connection->error);

send_message($page, "Rimozione di ".$result['Cognome']." ".$result['Nome']." avvenuta con successo");
?>
