<?php
	session_start();
	require_once("../utility.php");
	require_once("../connect.php");
	$page['p']="profilo";
	$page['2p']="cancella_account";

	if(!check_string($_POST['psw'])){
		send_message($page, "La password contiene caratteri non ammessi!");
	}

	if(!your_psw($connection, $_POST['psw'])){
		send_message($page, "Password Errata!");
	}
	$query = "SELECT ID_Persona FROM Persona WHERE Email='".$_SESSION['Email']."'";
	$res = $connection->query($query);
	$row = $res->fetch_assoc();

	$query = "SELECT * FROM Corso WHERE ID_PersonalTrainer=".$row['ID_Persona'];
	$pt = $connection->query($query);
	
	if($pt && $pt->num_rows){
		unset($page["2p"]);
		send_message($page, "Non puoi rimuovere l\'account, sei il personal trainer di un corso.");
	}

	$query = "SELECT ID_Palestra, count(*) as admin FROM Dispone WHERE Qualifica=".Qualifica::Admin
		." AND ID_Persona=".$row['ID_Persona']." GROUP BY ID_Palestra";
	$tmp = $connection->query($query);
	
	while($admin=$tmp->fetch_assoc()){
		if($admin['admin']==1){
			$query = "DELETE FROM Palestra WHERE ID_Palestra=".$admin['ID_Palestra'];
			$connection->query($query) or die($connection->error);
		}
	}

	//rimozione immagine del profilo
	if(file_exists("../images/".$row['ID_Persona']))
		unlink("../images/".$row['ID_Persona']);

	$query = "DELETE FROM Persona WHERE ID_Persona=".$_SESSION['ID'];
	$connection->query($query) or die($connection->error);
	
	unset($page);
	session_destroy();
	send_message($page, "Account cancellato con successo.");
?>
