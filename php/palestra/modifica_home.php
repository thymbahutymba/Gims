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

<div class="mini_form">
	<form method="POST" action="php/action/modifica_home-process.php">
		<ul>
			<li>Componi la descrizione per la tua palestra con uno slogan principale.</li>
			<li>Slogan <input type="text" name="slogan" value=""></li>
			<li><textarea maxlength="20000" name="text" placeholder="Scrivi una descrizione." required></textarea></li>
			<li><input type="hidden" name="idPalestra" value="<?php echo $_GET['id']; ?>"></li>
			<li>
				<input type="reset" class="botclick" name="Reset" value="Reset">
				<input type="submit" class="botclick" name="Inserisci" value="Conferma">
			</li>
		</ul>
	</form>
</div>
