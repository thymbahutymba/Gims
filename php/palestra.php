<?php
	require("php/utility.php");
	check_login();
?>
<!--
<div class="menu_profilo">
	<ul style="margin: 0 auto; text-align:center; display: inline-block">
		<li><a href="index.php?p=palestra&2p=aggiungi_palestra">Aggiungi</a></li>
	</ul>
</div>
-->
<?php
if(!isset($_GET['2p'])){ 
?>
<div class="center">
	<p>
	In questa pagina visualizzi tutte le palestre di cui sei amministratore, se non
	ne possiedi potrai aggiungerle con l'apposito form cliccando sul tasto aggiungi
	</p>
	<div class="menu_profilo">
		<ul>
		<!--<li><a href="index.php?p=palestra&2p=gestisci">Gestisci</a></li>-->
		<li><a href="index.php?p=palestra&2p=aggiungi_palestra">Aggiungi</a></li>
		</ul>
	</div>
</div>

<div class="palestra">
<?php
	require_once("php/connect.php");
	require_once("php/utility.php");
	if(session_status() == PHP_SESSION_NONE){
		session_start();
	}
	$query = "
		SELECT * FROM Palestra P NATURAL JOIN Dispone D
		WHERE D.ID_Persona=(
			SELECT ID_Persona
			FROM Persona
			WHERE Email='".$_SESSION['Email']."') AND
			D.Qualifica=".Qualifica::Admin;

	$res = $connection->query($query);
	while($res && $row = $res->fetch_assoc()){
		$query = "SELECT AVG(D.Valutazione) as Valutazione FROM Dispone D 
			WHERE D.ID_Palestra=".$row['ID_Palestra']." AND D.Valutazione is not NULL
			GROUP BY D.ID_Palestra";
		$tmp = $connection->query($query);
		$valutazione = 0;
		if($tmp){
			$valutazione = ($tmp->fetch_assoc())['Valutazione'];
		}
		stampa($row);
	} //FINE WHILE 
?>
</div>

<?php
}elseif(isset($_GET['2p']) && $_GET["2p"]=="aggiungi_palestra"){
?>
	<div id="form">
		<form method="POST" action="php/action/newgyms-process.php">
			<ul>
				<li><input type="text" name="nome" placeholder="Nome della Palestra" required></li>
				<li>Orario Apertura: <input class="time" type="time" name="orarioApertura" required></li>
				<li>Orario Chiusura: <input class="time" type="time" name="orarioChiusura" required></li>
				<li><input type="text" name="citta" placeholder="Città della palestra" required></li>
				<li><input type="email" name="email" placeholder="Inserisci l'email" required></li>
				<li><input type="text" name="phone" placeholder="Numero di telefono" required></li>
				<li><input type="reset" class="botclick" name="Reset" value="Reset">
				<input class="botclick" type="submit" name="conferma" value="Conferma"></li>
			</ul>
		</form>
	</div>
<?php
}
?>
