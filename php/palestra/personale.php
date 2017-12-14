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
<div id="form">
  <form method="POST" action="php/action/aggiungi_personale-process.php">
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
