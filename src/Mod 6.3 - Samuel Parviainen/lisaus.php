<?php
$reknro = $_POST["rek"];
$nimi = $_POST["nimi"];
$laji = $_POST["Laji"];
$rotu = $_POST["Rotu"];
$pvm = $_POST["pvm"];
$omis = $_POST["Omistaja"];

require "bd_config.php";

try {
  $sql = "INSERT INTO Lemmikki (nimi, laji, rotu, syntymäaika, rekisterinumero, omistaja) VALUES
   ('$nimi'.'$laji'.'$rotu'.'$pvm'.'$reknro'.'$omis')";
  $conn->exec($sql);
  echo "New records inserted successfully";
} catch(PDOException $e) {
  echo "Error: " . $sql . "<br>" . $e->getMessage();
}

$conn = null;
?>