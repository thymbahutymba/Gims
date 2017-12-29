<?php
	check_login();
	$page['id']=$_GET['id'];

	if(session_status() == PHP_SESSION_NONE){
	 session_start();
	}

	$query = "SELECT * FROM Dispone WHERE ID_Persona=(
		SELECT ID_Persona FROM Persona WHERE Email='".$_SESSION['Email']."'
	) AND ID_Palestra=".$_GET['id'];
	$res = $connection->query($query);

	if($res->num_rows===0){
		send_message($page, "Non sei iscritto in questa palestra.");
	}elseif(get_qualifica($connection, $_GET['id'])>Qualifica::Segretario){
		send_message($page, "Non hai i permessi necessari per la gestione dei dipendenti.");
	}

	$query = "SELECT P.ID_Persona, P.Nome, P.Cognome FROM Persona P NATURAL JOIN Dispone D
		WHERE D.ID_Palestra=".$_GET['id']." AND D.Qualifica=".Qualifica::Personal_Trainer;

	$res = $connection->query($query);
	if(!$res->num_rows){
		$page['2p']="assunzioni";
		send_message($page, "Non disponi di Personal Trainer non puoi avviare un corso.");
	}
?>

<div class="mini_form">
	<form method="POST" action="php/action/ins_corso-process.php">
		<ul>
			<li>Nome del corso <input type="text" name="nome" required></li>
			<li>
				<label>Personal Trainer</label>
				<select class="tendina" name="pt" required>
					<?php
						while($row = $res->fetch_assoc()){
							echo "<option value=\"".$row['ID_Persona']."\">".$row['Cognome']." ".$row['Nome']."</option>";
						}
					?>
				</select>
			</li>
			<li>
				<label>Numero massimo iscritti </label>
				<input type="number" name="max" required>
			</li>
			<li>
				<label>Quota Iscrizione </label>
				<input type="number" name="money" step="0.01" required> €
			</li>
			<table class="orari">
				<tr>
					<td>Giorno</td><td>Ora Inizio</td><td>Ora Fine</td>
				</tr>
				<tr class="tupla">
					<td>
						<select class="giorno" name="giorno[]" required>
							<option value="lunedi">Lunedì</option>
							<option value="martedi">Martedì</option>
							<option value="mercoledi">Mercoledì</option>
							<option value="giovedi">Giovedì</option>
							<option value="venerdi">Venerdì</option>
							<option value="sabato">Sabato</option>
						</select>
					</td>
					<td><input type="time" name="oraInizio[]" required></td>
					<td><input type="time" name="oraFine[]" required></td>
				</tr>
			</table>
			<li><input type="hidden" name="idPalestra" value="<?php echo $_GET['id']; ?>"></li>
			<div class="left">
				<input type="button" class="botclick" onclick="aggiungi_orario()" value="Aggiungi Orario"/>
				<input type="button" class="botclick" onclick="rimuovi_orario()" value="Rimuovi Orario"/>
			</div>
			<div class="right">
				<input type="reset" class="botclick" name="Reset" value="Reset">
				<input type="submit" class="botclick" name="Inserisci" value="Inserisci">
			</div>
		</ul>
	</form>
</div>

<script>
<!--
function aggiungi_orario() {
	var riga = document.getElementsByClassName("tupla")[0];
	var cln = riga.cloneNode(true);
	document.getElementsByClassName("orari")[0].appendChild(cln);
}

function rimuovi_orario() {
	var table=document.getElementsByClassName("orari")[0];
	if(table.rows.length>2){
		table.deleteRow(-1);
	}else{
		window.alert("Il corso deve avere almeno un giorno di svolgimento.");
	}
}
-->
</script>
