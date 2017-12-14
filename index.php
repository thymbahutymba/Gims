<!DOCTYPE html>
<?php date_default_timezone_set("Europe/Rome"); ?>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="author" content="Andrea Stevanato">
		<meta name="description" content="Web service for gyms">
		<link rel="icon" type="image/png" href="images/logo.png" />
		<link rel="stylesheet" type="text/css"	href="css/index.css" />
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
						if(session_status() == PHP_SESSION_NONE){
							session_start();
						}
						if(isset($_SESSION['Login'])){
							echo "<li><a href=\"index.php?p=palestra\">Palestra</a></li>";
							echo "<li><a href=\"php/logout.php\">Logout</a></li>";
							echo "<li><a class=\"nome\" href=\"index.php?p=profilo\">".$_SESSION['Nome']."</a></li>";
						}else{
					?>
						<li><a href="index.php?p=login">Login</a></li>
						<li><a href="index.php?p=register">Register</a><li>
				</ul>
				<?php } ?>
			</nav>
		</header>
		<div id="container">
		<?php
			if(isset($_GET["m"]))
			{
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
				<small>For more details contact
					<a href="mailto:andrea.stevanato.95@hotmail.it">Andrea Stevanato</a>
				</small>
			</address>
			<small>Progetto PWEB</small>
		</footer>
	</body>
</html>
