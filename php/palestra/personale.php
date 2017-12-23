<?php
	require_once("php/utility.php");
	require_once("php/utility.php");

	if(session_status() == PHP_SESSION_NONE){
	 session_start();
	}
	check_login();
	$page['id']=$_GET['id'];

	$query = "SELECT * FROM Dispone WHERE ID_Persona=(
		SELECT ID_Persona FROM Persona WHERE Email='".$_SESSION['Email']."'
	) AND ID_Palestra=".$_GET['id'];
	$res = $connection->query($query);

	if($res->num_rows===0){
		send_message($page, "Non sei iscritto in questa palestra.");
	}elseif(get_qualifica($connection, $_GET['id'])>Qualifica::Segretario){
		send_message($page, "Non hai i permessi necessari per l\'inserimento dei corsi.");
	}
?>

<script src="js/personale.js"></script>
<div id="form">
	<div class="existing_user">
		<form method="POST" action="php/action/aggiungi_personale-process.php">
			<input type="button" class="botclick" value="Nuovo Utente" onclick="cambia_form()"> 
			<p><i>Seleziona l'email dell'utente per assumerlo, se è registrato
			</i></p> 
			<ul class="ul">
				<li>
					<select id="select" name="email" onchange="compila_form(<?php echo $_GET['id']; ?>)">
						<option selected disabled>Email</option>
<?php
						$query = "SELECT * FROM Persona WHERE ID_Persona<>".$_SESSION['ID'];
						$query .= " ORDER BY Email";
						$result = $connection->query($query);
						while($row = $result->fetch_assoc()){
?>
							<option value="<?php echo $row['Email']; ?>"><?php echo $row['Email']; ?></option>
<?php
						}
						$result = $connection->query($query);
						while($row = $result->fetch_assoc()){
?>
						<input class="<?php echo $row['Email']; ?>" type="hidden" name="nome" value="<?php echo $row['Nome']; ?>">
						<input class="<?php echo $row['Email']; ?>" type="hidden" name="cognome" value="<?php echo $row['Cognome']; ?>">
						<input class="<?php echo $row['Email']; ?>" type="hidden" name="data" value="<?php echo $row['DataNascita']; ?>">
						<input class="<?php echo $row['Email']; ?>" type="hidden" name="sesso" value="<?php echo $row['Sesso']; ?>">
<?php
						}
?>
					</select>
				</li>
				<li><input type="text" name="nome" placeholder="Inserisci il nome" required disabled></li>
				<li><input type="text" name="cognome" placeholder="Inserisci il cognome" required disabled></li>
				<li><input type="date" name="data" required disabled></li>
				<li>
					Maschio<input class="botclick" type="radio" name="sesso" value="Maschio" required disabled>
					Femmina<input class="botclick" type="radio" name="sesso" value="Femmina" required disabled>
				</li>
				<li>
					<select name="qualifica" required>
						<option value="<?php echo Qualifica::Personal_Trainer; ?>">Personal Trainer</option>
						<option value="<?php echo Qualifica::Segretario; ?>">Segretario</option>
						<option value="<?php echo Qualifica::Admin; ?>">Admin</option>
					</select>
				</li>
				<li><input type="hidden" name="idPalestra" value="<?php echo $_GET['id']; ?>"></li>
				<li><input type="reset" class="botclick" name="Reset" value="Reset">
					<input type="submit" class="botclick" name="Assumi" value="Assumi"></li>
			</ul>
		</form>
	</div>
	<div class="new_user">
		<form method="POST" action="php/action/aggiungi_personale-process.php">
			<input type="button" class="botclick button" value="Utente già iscritto" onclick="cambia_form()">
			<p><i>Crea un nuovo account per il tuo dipendente</i></p> 
			<ul>
				<li><input type="text" name="nome" placeholder="Inserisci il nome" required></li>
				<li><input type="text" name="cognome" placeholder="Inserisci il cognome" required></li>
				<li><input type="date" name="data" required></li>
				<li>
					<select name="qualifica">
						<option value="<?php echo Qualifica::Personal_Trainer; ?>">Personal Trainer</option>
						<option value="<?php echo Qualifica::Segretario; ?>">Segretario</option>
						<option value="<?php echo Qualifica::Admin; ?>">Admin</option>
					</select>
				</li>
				<li>
					Maschio<input class="botclick" type="radio" name="sesso" value="Maschio" required>
					Femmina<input class="botclick" type="radio" name="sesso" value="Femmina" required>
				</li>
				<li><input type="email" name="email" placeholder="Inserisci l'email" required></li>
				<li><input type="password" name="psw" placeholder="Inserisci la password" required></li>
				<li><input type="password" name="confirmpsw" placeholder="Ripeti la password" required></li>
				<li><input type="hidden" name="idPalestra" value="<?php echo $_GET['id']; ?>"></li>
				<li><input type="reset" class="botclick" name="Reset" value="Reset">
				<input type="submit" class="botclick" name="Conferma" value="Conferma"></li>
			</ul>
		</form>
	</div>
</div>
