<?php
  require("../connect.php");
  require("../utility.php");
  $page['id']=$_POST['idPalestra'];
  foreach($_POST as $key => $value){
    ${"p_".$key} = $value;
  }

  $p_max = intval($p_max);
  $p_money = floatval($p_money);

  if(!check_string($p_nome)){
    send_message($page, "Il nome del corso contiene caratteri non ammessi.");
  }elseif($p_max<=0){
    send_message($page, "Il corso deve avere almeno un partecipante.");
  }elseif($p_money<0){
    send_message($page, "Il corso deve avere un costo maggiore o uguale di 0.");
  }

  for($i=0; $i<count($p_giorno); ++$i){
    if($p_oraInizio>$p_oraFine){
      send_message($page, "Il corso del ".$p_giorno." finisce prima di iniziare.");
    }elseif($p_oraInizio==$p_oraFine){
      send_message($page, "L'orario di inizio e di fine del ".$p_giorno." coincidono.");
    }
  }

  $query = "INSERT INTO Corso(Nome, LimiteMassimo, QuotaIscrizione, ID_Palestra, ID_PersonalTrainer)
    values('".$p_nome."','".$p_max."','".$p_money."',".$p_idPalestra.", ".$p_pt.")";
  $connection->query($query) or die($connection->error);
  $last_id = $connection->insert_id;

  for($i=0; $i<count($p_giorno); ++$i){
    $query = "INSERT INTO Orario(Giorno, OraInizio, OraFine, ID_Corso) values(
      '".$p_giorno[$i]."','".$p_oraInizio[$i]."','".$p_oraFine[$i]."', ".$last_id.")";
    $connection->query($query) or die($connection->error);
  }
  send_message($page, "Inserimento del corso avvenuto con successo.");
?>
