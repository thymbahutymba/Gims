<?php
//require("utility.php");
//$page['p']="login";
session_start();
session_unset();
//send_message($page, "Logout effettuato.");
$_SESSION['Letto']=1;
header("location: ../index.php?p=login&m=Logout effettuato.");
?>
