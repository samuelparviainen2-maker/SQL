<?php
include "db_config.php";

try {
  $sql = "SELECT nimi, laji, rotu, syntymäaika, rekisterinumero, omistaja FROM Lemmikki";
  // Execute the SQL query
  $result = $conn->query($sql);
  // Process the result set
  if ($result->rowCount() > 0) {
    echo "<table><tr><th>ID</th><th>Firstname</th><th>Lastname</th></tr>";
    // Output data of each row
    while($row = $result->fetch()) {
        echo "<tr><form method='post' action='paivitus.php'>";
        echo "<td><input type='text' name='nimi' value='" . $row['nimi'] . "' /></td>";
        echo "<td><input type='text' name='laji' value='" . $row['laji'] . "' /></td>";
        echo "<td><input type='text' name='rotu' value='" . $row['rotu'] . "' /></td>";
        echo "<td><input type='text' name='syntymaaika' value='" . $row['syntymäaika'] . "' /></td>";
        echo "<td><input type='text' name='rekisterinumero' value='" . $row['rekisterinumero'] . "' /></td>";
        echo "<td><input type='text' name='omistaja' value='" . $row['omistaja'] . "' /></td>";
        echo "<td><input type='submit' value='Tallenna' /></td>";
        echo "</tr></form>";
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