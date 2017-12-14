<div id="search_bar">
	<form method="POST" action="index.php">
		<input class="cerca" type="search" name="text" placeholder="Nome della palestra">
		<select name="citta" required>

<?php
if($_SERVER['REQUEST_METHOD'] == "POST"){
	echo "<option disabled=\"disabled\">Città</option>";
	echo "<option selected>".$_POST['citta']."</option>";
}else{
	echo "<option disabled=\"disabled\" selected>Città</option>";
}
require("connect.php");
$query = "SELECT DISTINCT Citta FROM Palestra";
if($res = $connection->query($query)){
	while( $tmp = $res->fetch_array() ){
		if($_SERVER['REQUEST_METHOD']=="POST" && $tmp['Citta']==$_POST['citta']){
			continue;
		}
		echo "<option value=\"".$tmp['Citta']."\">".$tmp['Citta']."</option>";
	}
}
$connection->close();
?>

		</select>
		<input type="submit" class="botclick" name="cercaPalestra" value="Cerca">
	</form>
</div>

<!--
Stampa del risultato in seguito alla ricerca
-->

<?php
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['cercaPalestra'])){
?>
<div class="palestra">
<?php
	require("php/utility.php");
	require("php/connect.php");
	$result = search_p($connection);
	if(!mysqli_num_rows($result)){
		header("location: index.php?m=La ricerca non ha prodotto risultati.");
	}else{
		while($row = $result->fetch_assoc()){
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
					<?php if(file_exists("images/palestra/".$row['ID_Palestra'])){ ?>
						<img class="display_gPropic" src="./images/palestra/<?php echo $row['ID_Palestra']; ?>" alt="propic">
					<?php }else{ ?>
						<img class="display_gPropic" src="./images/palestra/none.jpg" alt="propic">
					<?php } ?>
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

<?php						}else{
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
	} //FINE IF
}
?>

