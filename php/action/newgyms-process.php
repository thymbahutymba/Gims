<?php
  require("../connect.php");
  require("../utility.php");
  $page['p'] = "palestra";

  foreach($_POST as $key => $value){
    ${"p_".$key} = $value;
  }
  global $error;

  if(!check_string($p_nome)){
    $error="Il nome della palestra contiene caratteri non consentiti.";
  }elseif (!check_string($p_citta)){
    $error="La città contiene caratteri non consentiti.";
  }elseif (!filter_var($p_email)){
    $error="Email della palestra non valida.";
  }elseif(preg_match_all("/[0-9]/", $p_phone)!=strlen($p_phone)){
    $error="Il numero contiene caratteri non validi. ";
  }

  if($error){ send_message($page, $error); }

  if(session_status() == PHP_SESSION_NONE){
    session_start();
  }
  $query = "SELECT ID_Persona FROM Persona WHERE Email='".$_SESSION['Email']."'";
  $res = $connection->query($query);
  $row = $res->fetch_assoc();

  $query = "INSERT INTO Palestra(Nome, OrarioApertura, OrarioChiusura, Email,
  Telefono, Citta) values ('".$p_nome."', '".$p_orarioApertura."', '".$p_orarioChiusura."',
  '".$p_email."', '".$p_phone."', '".$p_citta."')";
  $connection->query($query);
  if($connection->error){
    echo "Mysql error: ".$connection->error;
    die();
  }

  $last_id = $connection->insert_id;
  $query = "INSERT INTO Dispone(ID_Persona, ID_Palestra, Qualifica) values (
    ".$row['ID_Persona'].",".$last_id.",'Admin')";
  $connection->query($query);
  if($connection->error){
    echo "Mysql error: ".$connection->error;
    die();
  }
  send_message($page, "Creazione della palestra avvenuta con successo.");
 ?>
