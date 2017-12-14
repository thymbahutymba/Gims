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

  //rimozione immagine del profilo
  unlink("../images/".$row['ID_Persona']);

  $query = "DELETE FROM Persona WHERE Email='".$_SESSION['Email']."'";
  $connection->query($query);
  if($connection->error){
    echo "Mysql error: ".$connection->error;
    $connection->close();
    die();
  }
  unset($page);
  session_destroy();
  send_message($page, "Account cancellato con successo.");
?>
