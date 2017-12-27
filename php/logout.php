<?php
	session_start();
	session_unset();
	$_SESSION['Letto']=1;
	header("location: ../index.php?p=login&m=Logout effettuato.");
?>
