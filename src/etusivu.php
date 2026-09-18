<?php
$kayttaja = $_POST['kt'];
$salasana = $_POST['salasana'];

$servername = "db";
$username = "root";
$password = "root";
$dbname = "Kayttaja";

try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e){
  die("Could not connect. " . $e->getMessage());
}

try {
  $sql = "SELECT kayttaja_id FROM Kayttaja where kayttajatunnus = '$kayttaja'";
  // Execute the SQL query
  $result = $conn->query($sql);
  // Process the result set
  if ($result->rowCount() > 0) {
    // Output data of each row
    while($row = $result->fetch()) {
      if ($row['salasana'] === $salasana)
        {
            echo "Kirjautuminen onnstui!";
        } else {
            echo "Kirjautuminen epäonnistui";
        }
    }
  } else {
    echo "Kirjautuminen epäonnistui.";
  }
} catch(PDOException $e) {
  echo "Error: " . $e->getMessage();
}

$conn = null;