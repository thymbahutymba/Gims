<div id="search_bar">
	<form method="POST" action="index.php">
		<input class="cerca" type="search" name="text" placeholder="Nome della palestra">
		<select name="citta" required>

<?php
if($_SERVER['REQUEST_METHOD'] == "POST"){
?>
			<option value="" disabled>Città</option>
			<option selected><?php echo $_POST['citta']; ?> </option>
<?php
}else{
?>
			<option value="" disabled selected>Città</option>
<?php
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
		while($row = $result->fetch_assoc())
			stampa($row);
?>
</div>
<?php
	} //FINE IF
}
?>

