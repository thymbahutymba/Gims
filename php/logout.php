<?php
  session_start();
  session_destroy();
  header("location: ../index.php?p=login&m=Logout effettuato.");
 ?>
