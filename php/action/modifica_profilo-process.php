<?php
	require_once("../utility.php");
	require_once("../connect.php");
	session_start();
	$page['p']="profilo";
	$page['2p']="modifica_profilo";

	foreach($_POST as $key => $value){
		${"p_".$key} = $value;
	}
	global $error;

	if(isset($_POST['modCn'])){
		if(!check_string($p_nome) || !check_string($p_cognome)){
			send_message($page, "Il nome o il cognome contengono caratteri non ammessi.");
		}

		$query = "UPDATE Persona SET Nome='".$p_nome."', Cognome='".$p_cognome."' ";
		$query .= "WHERE Email='".$_SESSION['Email']."'";
		$connection->query($query);
		if($connection->error){
			echo "Mysql error: ".$connection->error;
			$connection->close();
			die();
		}
		$_SESSION['Nome']=$p_nome;
		send_message($page, "Modifica del Nome e Cognome avvenuta con successo!");

	}elseif(isset($_POST['modPropic'])){
		$tmp = explode(".", $_FILES['propic']['name']);
		$type = explode("/",$_FILES['propic']['type']);

		if($_FILES['propic']['size']>pow(2, 20)){
			send_message($page, "L\'immagine supera i 2MiB, scegline una più piccola.");
		}

		if(!getimagesize($_FILES['propic']['tmp_name'])){
			send_message($page, "Sei sicuro di aver caricato un immagine?");
		}

		$query = "SELECT ID_Persona FROM Persona WHERE Email='".$_SESSION['Email']."'";
		$res = $connection->query($query);
		$row = $res->fetch_assoc();

		move_uploaded_file($_FILES["propic"]["tmp_name"], "../../images/profilo/".$row['ID_Persona']);
		unset($page['2p']);
		send_message($page,"Immagine modificata con successo!");

	}elseif(isset($_POST['modEmail'])){
		if(!filter_var($p_email, FILTER_VALIDATE_EMAIL)){
			send_message($page, "Email non valida!");
		}
		if(!check_string($p_psw)){
			send_message($page, "La password contiene caratteri non ammessi, sicuro sia quella giusta?");
		}

		//controllo se la password è corretta
		if(!your_psw($p_psw)){
			send_message($page, "Password errata!");
		}

		$query = "UPDATE Persona SET Email='".$p_email."' ";
		$query .= "WHERE Email='".$_SESSION['Email']."'";
		$res = $connection->query($query);
		if($connection->error){
			echo "Mysql error: ".$connection->error;
			$connection->close();
			die();
		}
		unset($page);
		$page['p']="login";
		session_destroy();
		send_message($page, "Email cambiata con successo, rieffettua il login.");

	}else{
		if(check_string($p_curPsw) && check_string($p_newPsw) && check_string($p_2newPsw)){
			//controllo se la password inserita è corretta
			if(!your_psw($p_curPsw)){
				send_message($page, "Password errata!");
			}
			if($p_newPsw !== $p_c_newPsw){
				send_message($page, "Le password non corrispondono.");
			}elseif(strlen($p_newPsw)<8 || strlen($p_2newPsw)>20){
				send_message($page, "La password deve avere da 8 a 20 caratteri.");
			}

			$query = "UPDATE Persona SET Password=SHA2('".$p_newPsw."',256) ";
			$query .= "WHERE Email='".$_SESSION['Email']."'";
			$connection->query($query);
			if($connection->error){
				echo "Mysql error: ".$connection->error;
				$connection->close();
				die();
			}
			send_message($page, "Password cambiata con successo.");
		}else{
			send_message($page, "Hai inserito caratteri non ammessi nelle password.");
		}
	}
?>
