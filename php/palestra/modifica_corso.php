<style>
.tupla<?php echo $row['ID_Corso'];?>{
	display: none;
	width: 100%;
	background-color: unset;
	border: none;
	margin: auto;
	border-bottom: 3px outset rgba(255, 255, 230, 0.8);
	border-radius: 4px;
}

.tupla<?php echo $row['ID_Corso'];?> .right input.botclick{
	padding: 2%;
	margin: 1%;
	display: inline-block;
}

.tupla<?php echo $row['ID_Corso'];?> ul{
	padding: 0;
}
.tupla<?php echo $row['ID_Corso'];?> ul li{
	list-style: none;
	padding: 1% 0;
}

.tupla<?php echo $row['ID_Corso'];?> ul li label{
	display: inline-block;
	width: 40%;
}

.tupla<?php echo $row['ID_Corso'];?> .right ul li label{
	width: 65%;
}

.tupla<?php echo $row['ID_Corso'];?> .right ul li input[type=number]{
	width: 15%;
	padding: 2%;
}

.tupla<?php echo $row['ID_Corso'];?> ul li input,
.tupla<?php echo $row['ID_Corso'];?> ul li select
{
	padding: 0;
}
</style>
<script src="js/modifica_orario.js"></script>
<?php
$query = "SELECT * FROM Corso WHERE ID_Corso=".$row['ID_Corso'];
$result = $connection->query($query);
$dati = $result->fetch_assoc();
?>

<div class="tupla<?php echo $row['ID_Corso'];?>">
	<form method="POST" action="php/action/mod_corso-process.php">
		<div class="left" style="width: 50%;">
			<ul>
				<li><label>Nome del corso </label><input type="text" name="nome" value="<?php echo $row['Nome']; ?>"></li>
<?php
				$query = "SELECT * FROM Persona WHERE ID_Persona= 
					(SELECT ID_PersonalTrainer FROM Corso WHERE ID_Corso=".$row['ID_Corso'].")";
				$result = $connection->query($query);
				$pt = $result->fetch_assoc();
?>
				<li>
					<label>Personal Trainer</label><select name="pt" required>
<?php
					$query = "SELECT P.ID_Persona, P.Nome, P.Cognome FROM Persona P NATURAL JOIN Dispone D
						WHERE D.ID_Palestra=".$_GET['id']." AND D.Qualifica=".Qualifica::Personal_Trainer;
					$result = $connection->query($query);
					while($tmp = $result->fetch_assoc()){
						if($tmp['ID_persona'] == $pt['ID_Persona']){
							echo "<option value=\"".$pt['ID_Persona']."\" selected>".$pt['Cognome']." ".$pt['Nome']."</option>";
						}else{
							echo "<option value=\"".$tmp['ID_Persona']."\">".$tmp['Cognome']." ".$tmp['Nome']."</option>";
						}
					}
?>
					</select>
				</li>
			</ul>
		</div>
		<div class="right" style="width: 50%;">
			<ul>
				<li>
					<label>Numero massimo iscritti </label>
					<input type="number" name="max" value="<?php echo $dati['LimiteMassimo']; ?>"required>
				</li>
				<li>
					<label>Quota Iscrizione </label>
					<input type="number" name="money" step="0.01" 
						value="<?php echo $dati['QuotaIscrizione'];?>"required> €
				</li>
			</ul>
		</div>
<?php
$query = "SELECT * FROM Orario WHERE ID_Corso=".$dati['ID_Corso'];
$result = $connection->query($query);
?>
		<table class="orari left table<?php echo $row['ID_Corso'];?>">
			<tr>
			<td>Giorno</td><td>Ora Inizio</td><td>Ora Fine</td>
			</tr>
<?php
			while($day = $result->fetch_assoc()){
?>
				<tr class="giornata">
					<td>
						<select class="giorno" name="giorno[]" required>
							<option value="lunedi" <?php if($day['Giorno'] == "lunedi") echo "selected"; ?>>Lunedì</option>
							<option value="martedi" <?php if($day['Giorno'] == "martedi") echo "selected"; ?>>Martedì</option>
							<option value="mercoledi" <?php if($day['Giorno'] == "mercoledi") echo "selected"; ?>>Mercoledì</option>
							<option value="giovedi" <?php if($day['Giorno'] == "giovedi") echo "selected"; ?>>Giovedì</option>
							<option value="venerdi" <?php if($day['Giorno'] == "venerdi") echo "selected"; ?>>Venerdì</option>
							<option value="sabato" <?php if($day['Giorno'] == "sabato") echo "selected"; ?>>Sabato</option>
						</select>
					</td>
					<td><input type="time" name="oraInizio[]" value="<?php echo $day['OraInizio']; ?>"required></td>
					<td><input type="time" name="oraFine[]" value="<?php echo $day['OraFine']; ?>"required></td>
				</tr>
<?php
			}
?>
		</table>
		<div class="right" style="width: 40%; margin-top: 2%;">
				<input type="hidden" name="idPalestra" value="<?php echo $_GET['id']; ?>">
				<input type="button" class="botclick" onclick="aggiungi_orario(<?php echo $row['ID_Corso'];?>)" value="Aggiungi Orario"/>
				<input type="button" class="botclick" onclick="rimuovi_orario(<?php echo $row['ID_Corso']; ?>)" value="Rimuovi Orario"/><br />
				<input type="reset" class="botclick" name="Reset" value="Reset">
				<input type="submit" class="botclick" name="Inserisci" value="Inserisci">
				<input type="button" class="botclick" value="Indietro" onclick="goback(<?php echo $row['ID_Corso']; ?>)">
		</div>
	</form>
</div>

<script>
<!--
	function aggiungi_orario(corso) {
	var riga = document.getElementsByClassName("table"+corso)[0].getElementsByClassName("giornata")[0];
	var cln = riga.cloneNode(true);
	document.getElementsByClassName("table"+corso)[0].appendChild(cln);
}

function rimuovi_orario(corso) {
	var table=document.getElementsByClassName("table"+corso)[0];
	if(table.rows.length>2){
		table.deleteRow(-1);
	}else{
		window.alert("Il corso deve avere almeno un giorno di svolgimento.");
	}
}
-->
</script>
