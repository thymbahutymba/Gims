<div id="form">
  <form method="POST" action="php/action/register-process.php">
    <ul>
      <li><input type="text" name="nome" placeholder="Inserisci il nome" required></li>
      <li><input type="text" name="cognome" placeholder="Inserisci il cognome" required></li>
      <li><input type="date" name="data" required></li>
      <li>
        Maschio<input class="botclick" type="radio" name="sesso" value="Maschio" required>
        Femmina<input class="botclick" type="radio" name="sesso" value="Femmina" required>
      </li>
      <li><input type="email" name="email" placeholder="Inserisci l'email" required></li>
      <li><input type="password" name="psw" placeholder="Inserisci la password" required></li>
      <li><input type="password" name="confirmPsw" placeholder="Ripeti la password" required></li>
      <li><input type="reset" class="botclick" name="Reset" value="Reset">
      <input type="submit" class="botclick" name="Conferma" value="Conferma"></li>
    </ul>
    <small>Possiedi già un account? <a href="index.php?p=login">Accedi</a></small>
  </form>
</div>
