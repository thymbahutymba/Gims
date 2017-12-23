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
?>
		<a href="index.php?id=<?php echo $row['ID_Palestra']; ?>">
			<div class="tupla">
<?php			if(file_exists("images/palestra/".$row['ID_Palestra'])){ ?>
					<img class="display_gPropic" src="./images/palestra/<?php echo $row['ID_Palestra']; ?>" alt="propic">
<?php 			}else{ ?>
					<img class="display_gPropic" src="./images/palestra/none.jpg" alt="propic">
<?php 			} ?>
				<div class="left">
					<h2> <?php echo $row['Nome']; ?></h2>
					<p> <?php echo $row['Citta'];?></p>
				</div>
				<div class="valutazione">
<?php
					for($i=0; $i<5;++$i){
						if($i+1<=$valutazione){
?>
							<img src="images/icon/icon_full.png" alt="valutazione" 
								class="img<?php echo $i;?>">
<?php
						}elseif($i+1-$valutazione<1 && $i+1-floor($valutazione)>=0.5){
?>
							<img src="images/icon/icon_semi.png" alt="valutazione" 
								class="img<?php echo $i; ?>">

<?php					}else{
?>
							<img src="images/icon/icon_clear.png" alt="valutazione" 
								class="img<?php echo $i; ?>">
<?php
						}
					}
?>
				</div>
				<div class="right">
<?php
					if(check_open($row['OrarioApertura'], $row['OrarioChiusura'])){
						echo "<h3>Aperta</h3>";
					}else {
						echo "<h3>Chiusa</h3>";
					}
?>
					<p><?php echo date("H:i", strtotime($row['OrarioApertura']))." - "
						.date("H:i", strtotime($row['OrarioChiusura']));?></p>
				</div>
			</div>
		</a>
<?php
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
				<input class="botclick" type="submit" class="botclick" name="conferma" value="Conferma"></li>
			</ul>
		</form>
	</div>
<?php
}
?>
