<?php
require("../connect.php");
require("../utility.php");

$query = "SELECT Nome FROM Corso WHERE ID_Corso=".$_POST['idCorso'];
$result = $connection->query($query);
$nome = ($result->fetch_assoc())['Nome'];

$query = "DELETE FROM Corso WHERE ID_Corso=".$_POST['idCorso'];
$connection->query($query);
if($connection->error){
	echo "Mysql error: ".$connection->error;
	die();
}

$page['id'] = $_POST['idPalestra'];
send_message($page, "Eliminazione del corso ".$nome." avvenuta con successo.");
?>
