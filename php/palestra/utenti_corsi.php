<?php
require_once("php/connect.php");
require_once("php/utility.php");

check_login();
$page['id']=$_GET['id'];

$query = "SELECT * FROM Dispone WHERE ID_Persona=".$_SESSION['ID'];
$result = $connection->query($query);
if(!$result || !$result->num_rows)
	send_message($page, "Non sei iscritto in questa palestra");

if(get_qualifica($connection, $_GET['id'])>Qualifica::Personal_Trainer)
	send_message($page, "Non hai i permessi per la gestione degli utenti");
$query = "SELECT ID_Corso, Nome FROM Corso WHERE ID_Palestra=".$_GET['id'];
$result = $connection->query($query);
?>

<div class="center">
	<form method="POST" action="">
		<select name="corso" class="select_corso" onchange="this.form.submit()">
<?php
			while($row=$result->fetch_assoc()){
?>
				<option value="<?php echo $row['ID_Corso'];?>"
<?php 				if(isset($_POST) && isset($_POST['corso']) && $_POST['corso']==$row['ID_Corso'])
						echo "selected";
?>
				><?php echo $row['Nome']?></option>
<?php
			}
?>
		</select>
	</form>
<?php
$qualifica = get_qualifica($connection, $_GET['id']);
$query="SELECT * FROM Persona Per NATURAL JOIN Partecipazione Par WHERE ";
if(isset($_POST) && isset($_POST['corso'])){
	$query .= "Par.ID_Corso=".$_POST['corso'];
}else{
	$query .= "Par.ID_Corso = (SELECT C.ID_Corso FROM Corso C WHERE C.ID_Palestra=".$_GET['id'].
		" LIMIT 1)";
}
$utenti = $connection->query($query);
if($utenti && !$utenti->num_rows){
?>
	<h3>Non ci sono utenti iscritti a questo corso</h3>
<?php
}else{

?>
	<table class="utenti">
		<tr class="start">
			<td>Cognome</td>
			<td>Nome</td>
			<td>Email</td>
		</tr>
<?php
			while($row = $utenti->fetch_assoc()){
?>
				<tr>
					<td><?php echo $row['Cognome']; ?></td>
					<td><?php echo $row['Nome']; ?></td>
					<td><?php echo $row['Email']; ?></td>
<?php
					if($qualifica<=Qualifica::Segretario){
?>
						<td class="button">
							<form method="POST" action="php/action/rimuovi_utente_c-process.php">
								<input type="hidden" name="idCorso" value="<?php echo $row['ID_Corso']; ?>">
								<input type="hidden" name="idPersona" value="<?php echo $row['ID_Persona']; ?>">
								<input type="hidden" name="idPalestra"value="<?php echo $_GET['id']; ?>">
								<input type="submit" class="botclick" name="rimuovi" value="Rimuovi">
							</form>
						</td>
<?php
					}
?>
				</tr>
<?php
			}
?>	</table>
<?php
}
?>
</div>
