<?php include "yhteys.php";

if (!isset($conn)) {
    die("Tietokantayhteys ei ole käytettävissä.");
}
 
if ($_SERVER["REQUEST_METHOD"] == "POST") { 
    //Asetetaan opiskelijan tiedot
   
            
    $Nimi = $_POST["Nimi"];
    $Puh = $_POST["Puhelinnumero"];
    $Osoite = $_POST["Osoite"];

    //Insertataan arvot sql tauluun
    try {
        $sql = "INSERT INTO omistaja (Nimi, Puhelinnumero, Osoite)
        VALUES ('$Nimi', '$Puh', '$Osoite')";
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
            <div class="col-sm-3 opiskelija_form">
<!--Paikka opiskelijoiden lisäämiseen formilla. Näkyy vasemmalla -->
                <h1>Lisää Lemmikki</h1>
                <br>
                <br>
                <form action="omistajat.php" method="POST">
                    <label for="Nimi"><h2>Nimi</h2></label>
                    <input type="text" name="Nimi" max="80" required>
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
<!-- ÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄ-->
                    <label for="reknro">Rekisterinumero</label>
                    <input type="number" name="reknro">
                    <input type="submit" value="Lisää">
                </form>
               


            </div>
            <div class="col-sm-9">
<!--Paikka Omistajille. Näkyy oikealla -->
                <h1>Omistajat</h1>
                <br>
                <br>
                <hr>
                <br>
                <?php
                try {
                    $sql = "SELECT omistaja_id, Nimi, Puhelinnumero, Osoite FROM omistaja";
                    // Execute the SQL query
                    $result = $conn->query($sql);
                    // Process the result set
                    if ($result->rowCount() > 0) {
                        echo "<table class='table table-striped'><tr><th><h2>Omistaja_id</h2></th><th><h2>Nimi</h2></th><th><h2>Puhelinnumero</h2></th><th><h2>Osoite</h2></th></tr>";
                        // Output data of each row
                        while($row = $result->fetch()) {
                            
                            echo "<tr>";
                            
                            echo "<td><h3>" . $row['omistaja_id'] . "</h3></td>";
                            echo "<td><h3>" . $row['Nimi'] . "</h3></td>";
                            echo "<td><h3>" . $row['Puhelinnumero'] . "</h3></td>";
                            echo "<td><h3>" . $row['Osoite'] . "</h3></td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                        unset($result);
                    } else {
                        echo "<h2>Opiskelijoita ei löytynyt</h2>";
                    }
                    } catch(PDOException $e) {
                    echo "Error: " . $e->getMessage();
                    }
                ?>
                
            <div>
        </div>
    </div>
</html>