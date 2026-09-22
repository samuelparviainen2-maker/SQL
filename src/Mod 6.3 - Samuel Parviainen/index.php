<?php
require_once 'header.php';
require_once 'db_config.php';
?>
<a href="lisaalemmikki.php">Lisää uusi lemmikki</a>
<?php

try {
  $sql = "SELECT rekisterinumero, nimi, laji, rotu FROM Lemmikki";
  // Execute the SQL query
  $result = $conn->query($sql);
  // Process the result set
  if ($result->rowCount() > 0) {
    echo "<table><tr><th>ID</th><th>Firstname</th><th>Lastname</th></tr>";
    // Output data of each row
    while($row = $result->fetch()) {
      echo "<tr>";
      echo "<td>" . $row['rekisterinumero'] . "</td>";
      echo "<td>" . $row['nimi'] . "</td>";
      echo "<td>" . $row['laji'] . "</td>";
      echo "<td>" . $row['rotu'] . "</td>";
      echo "<td> <a href='muokkaalemmikki.php ? rekisterinumero=".$row['rekisterinumero']."'>Muokkaa</a></td>";

      echo "</tr>";
    }
    echo "</table>";
    unset($result);
  } else {
    echo "No records found.";
  }
} catch(PDOException $e) {
  echo "Error: " . $e->getMessage();
}

$conn = null;
?>