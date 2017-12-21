<?php
	require("../connect.php");
	require("../utility.php");

	foreach($_POST as $key => $value){
		${"p_".$key} = $value;
	}
	global $error;
	$page['id'] = $p_idPalestra;
	$page['2p'] = "modifica_home";

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

	$connection->query($query);
	if($connection->error){
		echo "Mysql error: ".$connection->error;
	}
	unset($page['2p']);
	send_message($page, "Home modificata con successo.");
?>
