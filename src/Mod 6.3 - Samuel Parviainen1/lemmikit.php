<?php include "yhteys.php";

if (!isset($conn)) {
    die("Tietokantayhteys ei ole käytettävissä.");
}
 
if ($_SERVER["REQUEST_METHOD"] == "POST") { 
    //Asetetaan lemmikin tiedot
    $Nimi = $_POST["Nimi"];
    $Laji = $_POST["Laji"];
    $Rotu = $_POST["Rotu"];
    $Syntyma = $_POST["Syntymapvm"];
    $Omistaja = $_POST["omistaja"];
    $Rekisteri = $_POST["reknro"];

    //Insertataan arvot sql tauluun
    try {
        $sql = "INSERT INTO Lemmikki (Nimi, laji, rotu, syntymäaika, omistaja, rekisterinumero)
        VALUES ('$Nimi', '$Laji', '$Rotu', '$Syntyma', '$Omistaja', '$Rekisteri')";
        $conn->exec($sql);
        header("location:lemmikit.php");
        } catch(PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
    }

                    
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Lemmikit eläinlääkäri.fi</title>
  <meta charset="utf-8">
<head>
    <body>
        <div>
        <?php include 'header.php';
        ?> 
        </div>
    
    <div class="container-fluid">
        <div class="row content">
            <div class="col-sm-3 form">
<!--Paikka lemmikin lisäämiseen formilla. Näkyy vasemmalla -->
                <h1>Lisää Lemmikki</h1>
                <br>
                <br>
                <form action="lemmikit.php" method="POST">
                    <label for="Nimi"><h2>Nimi</h2></label>
                    <input type="text" name="Nimi" max="50" required>
                    <br>
                    <hr>
                    <br>
                    <label for="Laji"><h2>Laji</h2></label>
                    <input type="text" name="Laji" max="50" required>
                    <br>
                    <label for="Rotu"><h2>Rotu</h2></label>
                    <input type="text" name="Rotu" max="50" required>
                    <br>
                    <hr>
                    <br>
                    <label for="Syntymapvm"><h2>Syntymäpäivä</h2></label>
                    <input type="DATE" name="Syntymapvm" required>
                    <br>
                    <label for="omistaja"><h2>Omistaja</h2></label>
                    <select name="omistaja" required>
                        <?php
                        try {
                            $sql = "SELECT omistaja_id, Nimi FROM omistaja";
                            // Execute the SQL query
                            $result = $conn->query($sql);
                            // Process the result set
                            if ($result->rowCount() > 0) {
                                // Output data of each row
                                while($row = $result->fetch()) { 
                                ?>
                                <option value="<?php echo $row['omistaja_id']; ?>"><?php echo $row['omistaja_id'].' | '.$row['Nimi'] ; ?></option>
                                <?php
                                }
                                unset($result);
                            } else {
                                echo "<option value='' style='display:none;'></option>"; 
                            }
                            } catch(PDOException $e) {
                            echo "Error: " . $e->getMessage();
                            }
                            ?>
                    </select>
                    <br>
                    <label for="reknro"><h2>Rekisterinumero</h2></label>
                    <input type="number" name="reknro" min="0">
                    <br>
                    <hr>
                    <br>
                    <input type="submit" value="Lisää">
                </form>
               


            </div>
            <div class="col-sm-9">
<!--Paikka lemmikeille. Näkyy oikealla -->
                <h1 class="Page">Lemmikit tietokannassa</h1>
                <br>
                <br>
                <hr>
                <br>
                <?php
                try {
                    $sql = "SELECT Lemmikki.lemmikki_id, Lemmikki.nimi, Lemmikki.laji, Lemmikki.rotu, Lemmikki.syntymäaika, Lemmikki.rekisterinumero, Lemmikki.omistaja, omistaja.Nimi FROM Lemmikki
                    LEFT JOIN omistaja ON Lemmikki.omistaja = omistaja.omistaja_id";
                    // Execute the SQL query
                    $result = $conn->query($sql);
                    // Process the result set
                    if ($result->rowCount() > 0) {
                        echo "<table class='table table-striped'>
                        <tr>
                        <th><h2>Lemmikki_id</h2></th>
                        <th><h2>Nimi</h2></th>
                        <th><h2>Laji</h2></th>
                        <th><h2>Rotu</h2></th>
                        <th><h2>Syntymäaika</h2></th>
                        <th><h2>Rekisterinumero</h2></th>
                        <th><h2>Omistaja_id</h2></th>
                        <th><h2>Omistaja</h2></th>
                        <th><h2>Muokkaa</h2></th>
                        
                        </tr>";
                        // Output data of each row
                        while($row = $result->fetch()) {
                            
                            echo "<tr>";
                            
                            echo "<td><h3>" . $row['lemmikki_id'] . "</h3></td>";
                            echo "<td><h3>" . $row['nimi'] . "</h3></td>";
                            echo "<td><h3>" . $row['laji'] . "</h3></td>";
                            echo "<td><h3>" . $row['rotu'] . "</h3></td>";
                            echo "<td><h3>" . $row['syntymäaika'] . "</h3></td>";
                            echo "<td><h3>" . $row['rekisterinumero'] . "</h3></td>";
                            echo "<td><h3>" . $row['omistaja'] . "</h3></td>";
                            echo "<td><h3>" . $row['Nimi'] . "</h3></td>";
                            echo "<td><a href='muokkaa.php?rekisterinumero=" . $row['rekisterinumero'] . "'>Muokkaa</a></td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                        unset($result);
                    } else {
                        echo "<h2>Lemmikkejä ei löytynyt</h2>";
                    }
                    } catch(PDOException $e) {
                    echo "Error: " . $e->getMessage();
                    }
                ?>
                
            <div>
        </div>
    </div>
</html>