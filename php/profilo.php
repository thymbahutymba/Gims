<?php
	require_once("php/connect.php");
	require_once("php/utility.php");
	check_login();
?>

<div id="profilo">
<?php
	$res = $connection->query("SELECT ID_Persona FROM Persona WHERE Email='".$_SESSION['Email']."'");
	if(!$res){
		echo "Mysql error: ".$connection->error;
		$connection->close();
		die();
	}
	$row = $res->fetch_assoc();
	if(file_exists("images/profilo/".$row['ID_Persona'])){
		echo "<img class=\"propic\" src=\"images/profilo/".$row['ID_Persona']."\" alt=\"propic\">";
	}else{
		echo "<img class=\"propic\" src=\"images/profilo/none\" alt=\"propic\">";
	}
?>

	<div class="menu_profilo">
		<ul>
			<li><a href="index.php?p=profilo&2p=modifica_profilo">Modifica Profilo</a></li>
			<li><a href="index.php?p=profilo&2p=cancella_account">Cancella Account</a></li>
		</ul>
	</div>
</div>

<?php
	if(isset($_GET['2p']) && $_GET['2p'] == "modifica_profilo"){
		$query = "SELECT * FROM Persona WHERE Email='".$_SESSION['Email']."'";
		$res = $connection->query($query);

		if($connection->error || !$res){
			echo "Mysql error: ".$connection->error;
			$connection->close();
			die();
		}

		$rows = $res->fetch_assoc();
?>

	<div class="mini_form prof">
		<form method="POST" action="php/action/modifica_profilo-process.php">
			<h2>Modifica Nome e Cognome</h2>
			<ul>
				<li>
					<label>Nome: </label>
					<input type="text" name="nome" placeholder="<?php echo $rows['Nome']; ?>" required>
				</li>
				<li>
					<label>Cognome: </label>
					<input type="text" name="cognome" placeholder="<?php echo $rows['Cognome']; ?>" required>
				</li>
				<li><input type="submit" class="botclick" name="modCn" value="Conferma"></li>
			</ul>
		</form>
	</div>

	<div class="mini_form prof">
		<form action="php/action/modifica_profilo-process.php" method="post" enctype="multipart/form-data">
			<h2>Modifica Immagine del profilo</h2>
			<ul>
				<li><p>
					Scegli l'immagine che vuoi caricare, dimensione massima 2MiB.
				</p></li>
				<li>
					<input type="file" name="propic" accept="image/*">
					<input class="botclick" type="submit" name="modPropic" value="Conferma">
				</li>
			</ul>
		</form>
	</div>

	<div class="mini_form prof">
		<form method="POST" action="php/action/modifica_profilo-process.php">
			<h2>Modifica Email</h2>
			<ul>
				<li><p>Dopo aver modificato l'email sarà necessario rieffettuare il login.</p></li>
				<li>
					<label>Email: </label>
					<input type="email" name="email" placeholder="<?php echo $rows['Email']; ?>" required>
				</li>
				<li><small><i>Per poter modificare l'email è necessario inserire la <b>password</b></i></small></li>
				<li>
					<label>Password: </label>
					<input type="password" name="psw" placeholder="Inserisci la password">
				</li>
				<li><input type="submit" class="botclick" name="modEmail" value="Conferma"></li>
			</ul>
		</form>
	</div>

	<div class="mini_form prof">
		<form method="POST" action="php/action/modifica_profilo-process.php">
			<h2>Modifica Password</h2>
			<ul>
				<li><p>Inserisci la password corrente, poi scegli la nuova password e confermala.</p></li>
				<li>
					<label>Password: </label>
					<input type="password" name="curPsw" placeholder="Password corrente">
				</li>
				<li>
					<label>Nuova: </label>
					<input type="password" name="newPsw" placeholder="Nuova password">
				</li>
				<li>
					<label>Ripeti:</label>
					<input type="password" name="2newPsw" placeholder="Ripeti password">
				</li>
				<li><input type="submit" class="botclick" name="modPsw" value="Conferma"></li>
			</ul>
		</form>
	</div>

<?php
	}elseif(isset($_GET['2p']) && $_GET['2p'] == "cancella_account"){
?>
	<div class="mini_form prof">
		<form method="POST" action="php/action/cancella_account-process.php">
			<h2>Cancella il tuo account</h2>
			<ul>
				<li><p>La cancellazione è permanente e non potrà essere annullata.</p></li>
				<li>Password <input type="password" name="psw"></li>
				<li><input type="submit" class="botclick" name="mod_psw" value="Conferma"></li>
			</ul>
		</form>
	</div>

<?php
	}else{
?>
		<div class="center">
			<p>
				In questa pagina puoi visualizzare tutte le palestre in cui sei amministratore,
				segretario, personal trainer o atleta.
			</p>
		</div>
<?php
		$query = "SELECT * FROM Palestra P NATURAL JOIN Dispone D ";
		$query .= "WHERE D.ID_Persona=".$_SESSION['ID'];
		
		$result = $connection->query($query);
		if($connection->error)
			die($connection->error);
?>

		<div class="palestra">
<?php
			while($result->num_rows && $row=$result->fetch_assoc())
				stampa($row);
?>
		</div>
<?php
	}
	$connection->close();
?>
