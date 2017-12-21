<?php
	require("../connect.php");
	require("../utility.php");
	$page['id']=$_POST['idPalestra'];

	foreach($_POST as $key => $value){
		${"p_".$key} = $value;
	}

	$p_max = intval($_POST['max']);
	$p_money = floatval($_POST['money']);

	if(!check_string($p_nome)){
		send_message($page, "Il nome del corso contiene caratteri non ammessi.");
	}elseif($p_max<=0){
		send_message($page, "Il corso deve avere almeno un partecipante.");
	}elseif($p_money<0){
		send_message($page, "Il corso deve avere un costo maggiore o uguale di 0.");
	}elseif(isset($p_desc) && preg_match('/[\'^£$%&*()}{@#~<>|=_+¬-]/',$p_desc)){
		send_message($page, "La descrizione contiene caratteri non ammessi.");
	}

	for($i=0; $i<count($p_giorno); ++$i){
		if($p_oraInizio[$i]>$p_oraFine[$i]){
			send_message($page, "Il corso del ".$p_giorno." finisce prima di iniziare.");
		}elseif($p_oraInizio[$i]==$p_oraFine[$i]){
			send_message($page, "L'orario di inizio e di fine del ".$p_giorno." coincidono.");
		}
	}

	$query = "UPDATE Corso C SET C.Nome='".$p_nome."', C.LimiteMassimo=".$p_max;
	$query .= ", C.QuotaIscrizione=".$p_money;
	$query .= ", C.ID_PersonalTrainer=".$p_pt;

	if(!empty($p_desc))
		$query .= ", C.Descrizione='".$p_desc."'";
	$query .= " WHERE C.ID_Corso=".$p_idCorso;

	$connection->query($query) or die($connection->error);

	$query = "DELETE FROM Orario WHERE ID_Corso=".$p_idCorso;
	$connection->query($query) or die($connection->error);

	for($i=0; $i<count($p_giorno); ++$i){
		$query = "INSERT INTO Orario(Giorno, OraInizio, OraFine, ID_Corso) values(
			'".$p_giorno[$i]."','".$p_oraInizio[$i]."','".$p_oraFine[$i]."', ".$p_idCorso.")";
		$connection->query($query) or die($connection->error);
	}

	send_message($page, "Update del corso ".$p_nome." avvenuto con successo.");
?>
