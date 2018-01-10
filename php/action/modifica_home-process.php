<?php
require("../connect.php");
require("../utility.php");

foreach($_POST as $key => $value){
	${"p_".$key} = $value;
}
global $error;
$page['id'] = $p_idPalestra;
$page['2p'] = "modifica_home";

if(isset($_POST['Descrizione'])){
	if(preg_match('/[\'^£$%&*()}{@#~<>|=_+¬-]/',$p_text)){
		send_message($page, "Il testo contiene caratteri non ammessi.");
	}else if (preg_match('/[\'^£$%&*()}{@#~<>|=_+¬-]/', $p_slogan)){
		send_message($page, "Lo slogan contiene caratteri non ammessi.");
	}

	$query = "UPDATE Palestra SET ";
	if(empty($p_text)){
		$query .= "Descrizione=NULL ";
	}else{
		$query .= "Descrizione='".$p_text."'";
	}

	if(empty($p_slogan)){
		$query .= ", Slogan=NULL ";
	}else{
		$query .= ", Slogan='".$p_slogan."' ";
	}
	$query .= "WHERE ID_Palestra=".$p_idPalestra;

	$connection->query($query) || die($connection->error);
	unset($page['2p']);
	send_message($page, "Home modificata con successo.");

}else if(isset($_POST['modPropic'])){
	$tmp = explode(".", $_FILES['propic']['name']);
	$type = explode("/",$_FILES['propic']['type']);

	if($_FILES['propic']['size']>pow(2, 20)){
		send_message($page, "L\'immagine supera i 2MiB, scegline una più piccola.");
	}

	if(!getimagesize($_FILES['propic']['tmp_name'])){
		send_message($page, "Sei sicuro di aver caricato un immagine?");
	}

	move_uploaded_file($_FILES["propic"]["tmp_name"], "../../images/palestra/".$_POST['idPalestra']);
	unset($page['2p']);
	send_message($page,"Immagine modificata con successo!");
}
?>
