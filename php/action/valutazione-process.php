<?php
require("../connect.php");
require("../utility.php");

if(session_status()==PHP_SESSION_NONE){
	session_start();
}
if(!isset($_SESSION['Login']) || !$_SESSION['Login']){
	$page['id']="login";
	send_message($page, "Logga per poter dare una valutazione a una palestra.");
}

$query = "SELECT ID_Persona FROM Persona WHERE Email='".$_SESSION['Email']."'";
$result = $connection->query($query);
$persona = ($result->fetch_assoc())['ID_Persona'];
$query = "SELECT * FROM Dispone WHERE ID_Palestra=".$_GET['pal']." AND ID_Persona=".$persona;
$result=$connection->query($query);

if(!mysqli_num_rows($result)){
	$page = $_GET['pal'];
	send_message($page, "Non sei iscritto in questa palestra. Prima di valutarla effettua l'iscrizione.");
}

$query = "UPDATE Dispone SET Valutazione=".$_GET['val']
	." WHERE ID_Persona=".$persona." AND ID_Palestra=".$_GET['pal'];
$connection->query($query);
if($connection->error){
	echo "Mysql error: ".$connection->error;
	$connection->close();
	die();
}

$page['id']=$_GET['pal'];
send_message($page, "Valutazione modificata");

?>
