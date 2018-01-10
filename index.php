<!DOCTYPE html>
<?php
date_default_timezone_set("Europe/Rome");

$value = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
setcookie("url", $value);

if(session_status() == PHP_SESSION_NONE)
	session_start();
?>

<html>
	<head>
		<meta charset="utf-8">
		<meta name="author" content="Andrea Stevanato">
		<meta name="description" content="Web service for gyms">
		<link rel="icon" type="image/png" href="images/logo.png" />
		<link rel="stylesheet" type="text/css" href="css/index.css" />
		<title>Gims is motivation sought</title>
	</head>

	<body>
		<header>
			<a href="index.php">
				<img src="images/homepage.png" alt="Immagine">
			</a>
			<h1>Gims is motivation sought</h1>
			<nav id="navbar">
				<ul>
					<?php
						if(isset($_SESSION['Login'])){
							echo "<li><a href=\"index.php?p=palestra\">Palestra</a></li>";
							echo "<li><a href=\"php/logout.php\">Logout</a></li>";
							echo "<li><a class=\"nome\" href=\"index.php?p=profilo\">".$_SESSION['Nome']."</a></li>";
						}else{
					?>
						<li><a href="index.php?p=login">Login</a></li>
						<li><a href="index.php?p=register">Register</a><li>
<?php					} 
?>
				</ul>
			</nav>
		</header>
		<div id="container">
		<?php
			if(isset($_GET["m"]) && !$_SESSION['Letto'])
			{
				$_SESSION['Letto']=1;
				echo "<script type=\"text/javascript\">window.alert('".$_GET["m"]."');</script>";
			}
			if(isset($_GET["id"])){
				include_once("php/home_gyms.php");
			}else{
				if(isset($_GET["p"]))
				{
					include_once("php/".$_GET["p"].".php");
					unset($_GET["p"]);
				}else{
					include("php/search.php");
				}
			}
		?>
		</div>
		<footer>
			<address>
				<small>Autore del progetto
					<a href="mailto:andrea.stevanato.95@hotmail.it">Andrea Stevanato</a>
				</small>
			</address>
			<small>
				<a href="progetto.html">Progetto PWEB</a></small>
		</footer>
	</body>
</html>
