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

$query = "SELECT Slogan, Descrizione FROM Palestra WHERE ID_Palestra=".$_GET['id'];
$result = $connection->query($query);
$valori = $result->fetch_assoc();
?>

<div class="mini_form mod_home">
	<form method="POST" action="php/action/modifica_home-process.php">
		<ul>
			<li>
				<p>
					Componi la descrizione per la tua palestra con uno slogan principale.<br />
					<i>Se desideri <b>cancellare</b> lo Slogan e/o la Descrizione cancella
						il contenuto dei seguenti campi.</i>
				</p>
			</li>
			<li>Slogan <input type="text" name="slogan" value="<?php if(!empty($valori['Slogan'])) echo $valori['Slogan'];?>"></li>
			<li>
<?php
				if(empty($valori['Descrizione'])){
?>
					<textarea maxlength="20000" name="text" placeholder="Scrivi una descrizione."></textarea>
<?php
				}else{
?>
					<textarea maxlength="20000" name="text"><?php echo $valori['Descrizione'];?></textarea>
<?php
				}
?>
			</li>
			<li><input type="hidden" name="idPalestra" value="<?php echo $_GET['id']; ?>"></li>
			<li>
				<input type="reset" class="botclick" name="Reset" value="Reset">
				<input type="submit" class="botclick" name="Inserisci" value="Conferma">
			</li>
		</ul>
	</form>
</div>
