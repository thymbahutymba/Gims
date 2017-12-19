<div class="home_palestra">
<?php
	require("php/connect.php");
	require("php/utility.php");
	$page['id'] = $id = $_GET['id'];
?>
	<a href="index.php?id=<?php echo $_GET['id']?>">
<?php
	if(file_exists("images/palestra/".$_GET['id'])){
		echo "<img class=\"propic_palestra\" src=\"images/palestra/".$_GET['id']."\" alt=\"propic\">";
	}else{
		echo "<img class=\"propic_palestra\" src=\"images/palestra/none\" alt=\"propic\">";
	}
?>
	</a>
	<div class="menu_profilo">
		<ul>
		<?php
			if(isset($_SESSION['Login'])){
				//controlliamo la qualifica dell'utente loggato
				$query = "SELECT Qualifica FROM Dispone WHERE ID_Persona=(
					SELECT ID_Persona FROM Persona WHERE Email='".$_SESSION['Email']."'
					) AND ID_Palestra=".$id;
				$res = $connection->query($query);
				if($res->num_rows){
					$row = $res->fetch_assoc();

					if($row['Qualifica']<=Qualifica::Segretario){
						echo "<li><a href=\"index.php?id=$id&2p=personale\">Personale</a></li>";
						echo "<li><a href=\"index.php?id=$id&2p=inserisci_corso\">Inserisci Corso</a></li>";
					}
					if($row['Qualifica']== Qualifica::Admin){
						echo "<li><a href=\"index.php?id=$id&2p=modifica_home\">Modifica Home</a></li>";
					}
				}
			}else{
			}
		?>
		</ul>
	</div>
</div>
<?php
if(isset($_GET['2p'])){
	include_once("php/palestra/".$_GET['2p'].".php");
}else{
	$query = "SELECT * FROM Palestra WHERE ID_Palestra=".$id;
	$res = $connection->query($query);
	$row = $res->fetch_assoc();
?>
	<div class="center">
		<div cass="top">
			<div class="left">
				<h1><?php echo $row['Nome']; ?></h1>
				<h3><?php echo $row['Citta']; ?> </h3>
				<span>Contattaci all' <b><i><a href="mailto:<?php echo $row['Email']; ?>">Email</a></i></b>
					oppure chiamaci al <?php echo $row['Telefono']; ?></span>
				<div class="slogan">
<?php
					if($row['Slogan'] !== NULL && $row['Descrizione']!==NULL){
?>
						<h3><?php echo $row['Slogan']; ?></h3>
						<p><?php echo $row['Descrizione']; ?></p>
<?php				}else{
?>						<p>Questa palestra non è ancora provvista di uno slogan e di una
							descrizione.</p>
<?php
					}
?>
				</div>
			</div>
			<div class="right">
<?php
/*
 * Campo per inserire la propria valutazione, nel caso sia stata gia` inserita
 * verra` modificata
 */

//				if(session_status() == PHP_SESSION_NONE){
//					session_start();
//				}
				$query="";
				if(isset($_SESSION['Login']) && $_SESSION['Login']){
					$query = "
						SELECT D.Valutazione as Valutazione
						FROM Dispone D
						WHERE D.ID_Palestra=".$row['ID_Palestra']." AND
						D.ID_Persona= (
						SELECT P.ID_Persona FROM Persona P WHERE P.Email='".$_SESSION['Email']."')";
				}else{
					$query = "
						SELECT AVG(Valutazione) as Valutazione
						FROM Dispone
						WHERE Valutazione is not NULL AND ID_Palestra=".$row['ID_Palestra']."
						GROUP BY ID_Palestra";
				}
				require("php/connect.php");
				$val=0;
				$res = $connection->query($query);
				if($res)
					$val = ($res->fetch_assoc())['Valutazione'];

				if(isset($_SESSION['Login']) && $_SESSION['Login']){
?>
					<div class="valutazione" onmouseout="restore(<?php echo $val; ?>)">
<?php
						if(!$val){
?>							<i class="tmp">Valuta la palestra:</i><br />
<?php
						}else{
?>							<i class="tmp">La tua valutazione è:</i><br />
<?php
						}
						for($i=0; $i<5;++$i){
							if($i<$val){
?>
								<img src="images/icon/icon_full.png" alt="valutazione" 
									class="img<?php echo $i; ?>"
									onmouseover="seleziona(<?php echo $i; ?>)"
									onclick="valuta(<?php echo ($i+1).", ".$row['ID_Palestra']; ?>)">

<?php						}else{ 
?>
								<img src="images/icon/icon_clear.png" alt="valutazione" 
									class="img<?php echo $i; ?>"
									onmouseover="seleziona(<?php echo $i; ?>)"
									onclick="valuta(<?php echo ($i+1).", ".$row['ID_Palestra']; ?>)">
<?php
							}
						}
				}else{
?>
					<div class="valutazione">
							<i class="tmp">Valutazione media:</i><br />
<?php
							for($i=0;$i<5;++$i){
								if($i+1<$val){
?>
									<img src="images/icon/icon_full.png" alt="valutazione" 
									class="img<?php echo $i; ?>">
<?php
								}elseif($i+1-$val<1 && $i+1-floor($val)>=0.5){
?>
									<img src="images/icon/icon_semi.png" alt="valutazione" 
										class="img<?php echo $i; ?>">
<?php
								}else{ 
?>
									<img src="images/icon/icon_clear.png" alt="valutazione" 
									class="img<?php echo $i; ?>">
<?php
								}
							}
						}
?>
					</div>
<?php
					if(check_open($row['OrarioApertura'], $row['OrarioChiusura'])){
						echo "<h3>Aperta</h3>";
					}else {
						echo "<h3>Chiusa</h3>";
					}
?>
					<span><?php echo date("H:i", strtotime($row['OrarioApertura']))." - "
					.date("H:i", strtotime($row['OrarioChiusura']));?></span>
<?php
					if(isset($_SESSION['Login']) && $_SESSION['Login']){
						$query = "SELECT count(*) as num FROM Dispone WHERE ID_Persona=".$_SESSION['ID']
							." AND ID_Palestra=".$row['ID_Palestra'];
						$res = $connection->query($query);
						$tmp = $res->fetch_assoc()['num'];
						if($tmp){ 
?>
							<p>Sei già iscritto</p>
<?php					}else{
?>							<form method="POST" action="php/action/iscrizione_palestra-process.php">
								<input type="hidden" name="palestra" value="<?php echo $row['ID_Palestra']; ?>">
								<input type="submit" class="botclick" name"Iscriviti" value="Iscriviti">
							</form> 
<?php
						}
					}
?>
			</div> <!--fine div right-->
		</div> <!-- fine div top -->
	</div>
<?php
	$query = "SELECT * FROM Corso WHERE ID_Palestra=".$id;
	$res = $connection->query($query);

	if($res && $res->num_rows){
?>
		<div class="center corso">
<?php
			while($row = $res->fetch_assoc()){
?>
				<div class="tupla">
<?php
					$query = "SELECT Nome, Cognome FROM Persona WHERE ID_Persona=".$row['ID_PersonalTrainer'];
					$tmp = $connection->query($query);
					$result = $tmp->fetch_assoc();
?>
					<div class="left">
						<h2><?php echo $row['Nome']; ?> </h2>
						<span>Personal Trainer: <b> <?php echo $result['Nome']." ".$result['Cognome']; ?> </b></span> 
						<p>
<?php 
						if(is_null($row['Descrizione'])){
							echo "Questo corso non è ancora provvisto di una descrizione";
						}else{
							echo $row['Descrizione'];
						}
?>
						</p>
					</div>
					<div class="right" style="width:23%; text-align: right;">
<?php
						if(isset($_SESSION['Login']) && $_SESSION['Login']){
							$query = "SELECT * FROM Partecipazione WHERE ID_Corso=".$row['ID_Corso']
								." AND ID_Persona=".$_SESSION['ID'];
							$tmp = $connection->query($query);
							if($tmp && !$tmp->num_rows){
						
?>
								<form method="POST" action="php/action/iscrizione_corso-process.php">
									<input type="hidden" name="palestra" value="<?php echo $row['ID_Palestra']; ?>">
									<input type="hidden" name="corso" value="<?php echo $row['ID_Corso']; ?>">
									<input type="submit" class="botclick" name"Iscriviti" value="Iscriviti" style="padding: 3%; margin:1% 0;">
								</form>
<?php
							}
						}
?>
						<small><p>
						Iscritti: 
<?php
						$query = "SELECT count(*) as Iscritti FROM Partecipazione WHERE ID_Corso=".$row['ID_Corso'];
						$tmp = $connection->query($query);
						$result = $tmp->fetch_assoc();
						echo $result['Iscritti']."/".$row['LimiteMassimo'];
?>
						<br />
						Quota di iscrizione: 
						<?php echo $row['QuotaIscrizione']; ?>€
<?php
						$query = "SELECT * FROM Orario WHERE ID_Corso=".$row['ID_Corso'];
						$tmp = $connection->query($query);
						while($day = $tmp->fetch_assoc()){
							$stamp = "";
							if($day['Giorno'] === "lunedi"){
								$stamp = "Lunedì";
							}elseif($day['Giorno'] === "martedi"){
								$stamp = "Martedì";
							}elseif($day['Giorno'] === "mercoledi"){
								$stamp = "Mercoledì";
							}elseif($day['Giorno'] === "giovedi"){
								$stamp = "Giovedì";
							}elseif($day['Giorno'] === "venerdi"){
								$stamp = "Venerdì";
							}elseif($day['Giorno'] === "sabato"){
								$stamp = "Sabato";
							}
?>
							<br /><?php echo $stamp;?>:
<?php 						echo date("H:i", strtotime($day['OraInizio']))." - ".
								date("H:i", strtotime($day['OraFine']));
						}
?>
						</small></p>
					</div>
				</div>
<?php
			} 
?>
		</div> <!-- fine div center -->
<?php
	} // Fine if $res
} // fine else
?>


<script>
<!--
function valuta(valutazione, ID_Palestra) {
	for(i=0;i<5;++i)
		document.getElementsByClassName('img'+i)[0].style.display="none";

	var item = document.getElementsByClassName('tmp')[0];
	var txt = document.createTextNode("La tua valutazione è "+valutazione);
	item.replaceChild(txt, item.childNodes[0]);

	var xhr= new XMLHttpRequest();
	var data= "val="+valutazione+"&pal="+ID_Palestra;
	xhr.open("GET", "php/action/valutazione-process.php?"+data, true);
	xhr.send();
}

function seleziona(valutazione) {
	for (i=0; i<5; i++){
		if (i<=valutazione)
			document.getElementsByClassName('img'+i)[0].src = "images/icon/icon_full.png";
		else
			document.getElementsByClassName('img'+i)[0].src = "images/icon/icon_clear.png";
	}
}
function restore(valutazione){
	for(i=4;i>=0;--i){
		if(i>=valutazione)
			document.getElementsByClassName('img'+i)[0].src="images/icon/icon_clear.png";
		else
			document.getElementsByClassName('img'+i)[0].src="images/icon/icon_full.png";
	}
}
-->
</script>
