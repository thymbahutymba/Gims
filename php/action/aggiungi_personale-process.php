<?php
require("../connect.php");
require("../utility.php");

foreach($_POST as $key => $value){
	${"p_".$key} = $value;
}
global $error;
$page['id'] = $p_idPalestra;
$page['2p'] = "assunzioni";

if(isset($_POST['Conferma'])){
	if(strlen($p_psw)<8){
		$error = "Password troppo corta, min 20 char.";
	}elseif(strlen($p_psw)>20){
		$error= "Password troppo lunga, max 20 char.";
	}elseif(!check_string($p_psw)){
		$error= "La password può contonere: Lettere, Numeri e Underscore.";
	}elseif($p_psw!==$p_confirmpsw){
		$error= "Le password non corrispondono.";
	}elseif(!check_string($p_nome)){
		$error = "Il nome non può contenere caratteri speciali.";
	}elseif(!check_string($p_cognome)){
		$error = "Il cognome non può contenere caratteri speciali.";
	}elseif(!filter_var($p_email)){
		$error = "Email non valida.";
	}

	if($error) { send_message($page, $error); }

	// se l'email esiste
	$query = "SELECT * FROM Persona WHERE Email='".$p_email."'";
	$res = $connection->query($query);

	if($res && $res->num_rows){
		send_message($page, "Per effettuare l\'assunzione o la promozione di un utente usa il form apposito.");
	}else{
		//l'utente non dispone di nessun account.
		check_qualifica($connection, $p_idPalestra, $p_qualifica, $page);
		$query = "INSERT INTO Persona(Nome, Cognome, DataNascita, Email, Password, Sesso)
			values('".$p_nome."','".$p_cognome."','".$p_data."','".$p_email."',
				SHA2('".$p_psw."',256),'".$p_sesso[0]."')";
		$connection->query($query);
		if($connection->error){
			echo "Mysql error: ".$connection->error;
			$connection->close();
			die();
		}
		// inserimento nel personale
		$query = "INSERT INTO Dispone(ID_Persona, ID_Palestra, Qualifica) values(
			".$connection->insert_id.",".$p_idPalestra.",".$p_qualifica.")";
		$connection->query($query);
		if($connection->error){
			echo "Mysql error: ".$connection->error;
			$connection->close();
			die();
		}
		send_message($page, "Assunzione con registrazione avvenuta con successo. Ricorda ad ".$p_nome." di cambiare la Password.");
	}
}else if(isset($_POST['Assumi'])){

	if(!filter_var($p_email)){
		$error = "Email non valida.";
	}
	if($error) { send_message($page, $error); }

	$query = "SELECT ID, Qualifica FROM Dispone WHERE ID_Persona=(
		SELECT ID_Persona FROM Persona WHERE Email='".$p_email."') AND
		ID_Palestra=".$p_idPalestra;
	$res = $connection->query($query);
	if($res && $res->num_rows){
		//controlliamo se stiamo effettuando una promozione
		$row = $res->fetch_assoc();
		if($p_qualifica>$row['Qualifica']){
			send_message($page, $p_nome." ".$p_cognome." dispone di un grado più alto, nessuna promozione effettuata.");
		}elseif($p_qualifica==$row['Qualifica']){
			send_message($page, $p_nome." ".$p_cognome." dispone dello stesso grado, nessuna promozione effettuata.");
		}

		// controllo se sono autorizzato a effettuare una promozione
		check_qualifica($connection, $p_idPalestra, $p_qualifica, $page);

		$query = "UPDATE Dispone SET Qualifica=".$p_qualifica." WHERE
			ID=".$row['ID'];
		$connection->query($query) or die($connection->error);
		
		send_message($page, "Promozione effettuata con successo!");
	}else{
		// In caso di assunzione

		// controllo se l'untente loggato è in grado di effettuare una promozione
		check_qualifica($connection, $p_idPalestra, $p_qualifica, $page);

		$query = "SELECT ID_Persona FROM Persona WHERE Email='".$p_email."'";
		$result = $connection->query($query) or die($connection->error);
		$row = $result->fetch_assoc();

		$query = "INSERT INTO Dispone(ID_Persona, ID_Palestra, Qualifica) values(
			".$row['ID_Persona'].",".$p_idPalestra.",".$p_qualifica.")";
		$connection->query($query) or die($connection->error);

		send_message($page, "Assunzione di ".$p_nome." avvenuta con successo!");
	}
}
?>
