<?php
	require_once("php/connect.php");
	require_once("php/utility.php");

	$valori = array("Amministratori", "Segretari", "Personal Trainer", "Atleti");

	check_login();
	$page['id']=$_GET['id'];

	for($i = 0; $i < 4; ++$i){
		$query = "SELECT * FROM Persona P NATURAL JOIN Dispone D ";
		$query .= "WHERE D.ID_Palestra=".$id." AND D.Qualifica=".$i;
		$query .= " ORDER BY P.Cognome, P.Nome";

		$res = $connection->query($query);

		if($connection->error)
			die($connection->error);
?>

		<div class="center">
			<h3 class="qualifica"><?php echo $valori[$i]; ?></h3>
<?php
			if(!$res->num_rows){
?>				<span>Non sono presenti <?php echo $valori[$i]; ?></span>
<?php
			}else{
				$query = "SELECT Qualifica FROM Dispone WHERE ID_Persona=".$_SESSION['ID'];
				$qualifica = (($connection->query($query))->fetch_assoc())['Qualifica'];
?>
				<table>
					<tr class="start">
						<td>Cognome</td>
						<td>Nome</td>
						<td>Email</td>
					</tr>
<?php
					while($row = $res->fetch_assoc()){
?>
						<tr>
							<td><?php echo $row['Cognome']; ?></td>
							<td><?php echo $row['Nome']; ?></td>
							<td><?php echo $row['Email']; ?></td>
<?php
							if($i && $qualifica<$i){
?>
								<td class="button">
									<form method="POST" action="">
										<input type="hidden" name="idPersona" value="<?php echo $row['ID_Persona']; ?>">
										<input type="submit" class="botclick" name="rimuovi" value="Rimuovi">
									</form>
								</td>
<?php
							}
?>
						</tr>
<?php
					}
?>				</table>
<?php
			}
?>
		</div>
<?php
	}
?>
