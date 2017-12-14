<?php
  $server = "127.0.0.1:3306";
  $username = "root";
  $password = "root";
  $database = "Gims";
  $connection = mysqli_connect($server, $username, $password, $database);
  if ($connection->error){
    echo "Mysql error: ".$connection->error;
    die();
  }
?>
