<?php
  require("php/utility.php");
  check_login();

  if($_SESSION['Admin']!='Admin'){
    $error = "Eh... Volevii!";
    header("location: index.php?m=".$error);
  }

 ?>
