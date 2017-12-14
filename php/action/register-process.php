<?php
  require("../connect.php");
  require("../utility.php");
  $page['p'] = "register";

  foreach($_POST as $key => $value){
    ${"p_".$key} = $value;
  }
  global $error;
  /*
   *If there are one error into parameter passed by user.
   */
  if(strlen($p_psw)<8){
    $error = "Password troppo corta, min 8 char.";
  }elseif(strlen($p_psw)>20){
    $error= "Password troppo lunga, max 20 char.";
  }elseif(!check_string($p_psw)){
    $error= "La password può contonere: Lettere, Numeri e Underscore.";
  }elseif($p_psw!==$p_confirmPsw){
    $error= "Le password non corrispondono.";
  }elseif(!check_string($p_nome)){
    $error = "Il nome non può contenere caratteri speciali.";
  }elseif(!check_string($p_cognome)){
    $error = "Il cognome non può contenere caratteri speciali.";
  }elseif(!filter_var($p_email)){
    $error = "Email non valida.";
  }

  if($error) { send_message($page, $error); }

  /*
   *If email already present
   */
  $res = $connection->query("SELECT * FROM Persona WHERE Email='".$p_email."'");
  if(mysqli_num_rows($res)){
    $error = "Email già registrata.";
  }

  if($error){ send_message($page, $error); }

  $query = "INSERT INTO Persona(Nome, Cognome, DataNascita, Email, Password, Sesso)
  values('".$p_nome."','".$p_cognome."','".$p_data."','".$p_email."',
  SHA2('".$p_psw."',256),'".$p_sesso[0]."')";
  $connection->query($query) or die("Mysql error: ".$connection->error);

  $page['p']="login";
  send_message($page, "Registrazione avvenuta con successo");
?>
