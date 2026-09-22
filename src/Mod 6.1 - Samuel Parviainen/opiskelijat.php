<?php include "yhteys.php";

 
if ($_SERVER["REQUEST_METHOD"] == "POST") { 
    //Asetetaan opiskelijan tiedot
   
            
    $Enimi = $_POST["Etunimi"];
    $Snimi = $_POST["Sukunimi"];

    $Puh = $_POST["Puhelinnumero"];
    $Sahko = $_POST["Sahkoposti"];

    $Posti = $_POST["Postinumero"];
    $Osoite = $_POST["Osoite"];

    //Insertataan arvot sql tauluun
    try {
        $sql = "INSERT INTO Opiskelija (Etunimi, Sukunimi, Puhelinnumero, Sahkoposti, Postinumero, Osoite)
        VALUES ('$Enimi', '$Snimi', '$Puh', '$Sahko', '$Posti', '$Osoite')";
        $conn->exec($sql);
        header("location:opiskelijat.php");
        } catch(PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
    }

                    
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Etusivu</title>
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
                <h1>Lisää Opiskelija</h1>
                <br>
                <br>
                <form action="opiskelijat.php" method="POST">
                    <label for="Etunimi"><h2>Etunimi</h2></label>
                    <input type="text" name="Etunimi" max="50" required>
                    <br>
                    <label for="Sukunimi"><h2>Sukunimi</h2></label>
                    <input type="text" name="Sukunimi" max="80" required>
                    <br>
                    <hr>
                    <br>
                    <label for="Puhelinnumero"><h2>Puhelinnumero</h2></label>
                    <input type="text" name="Puhelinnumero" required>
                    <br>
                    <label for="Sahkoposti"><h2>Sähköposti</h2></label>
                    <input type="email" name="Sahkoposti" max="150" required>
                    <br>
                    <hr>
                    <br>
                    <label for="Postinumero"><h2>Postinumero</h2></label>
                    <input type="text" name="Postinumero" max="7" required>
                    <br>
                    <label for="Osoite"><h2>Osoite</h2></label>
                    <input type="text" name="Osoite" max="100" required>
                    <br>
                    <hr>
                    <br>
                    <input type="submit" value="Lisää">
                </form>
               


            </div>
            <div class="col-sm-9">
<!--Paikka opiskelijoille. Näkyy oikealla -->
                <h1>Opiskelijat</h1>
                <br>
                <br>
                <hr>
                <br>
                <?php
                try {
                    $sql = "SELECT Opiskelija_id, Etunimi, Sukunimi, Puhelinnumero, Sahkoposti, Postinumero, Osoite FROM Opiskelija";
                    // Execute the SQL query
                    $result = $conn->query($sql);
                    // Process the result set
                    if ($result->rowCount() > 0) {
                        echo "<table class='table table-striped'><tr><th><h2>Opiskelija_ID</h2></th><th><h2>Etunimi</h2></th><th><h2>Sukunimi</h2></th><th><h2>Puhelinnumero</h2></th><th><h2>Sähköposti</h2></th><th><h2>Postinumero</h2></th><th><h2>Osoite</h2></th></tr>";
                        // Output data of each row
                        while($row = $result->fetch()) {
                            
                            echo "<tr>";
                            
                            echo "<td><h3>" . $row['Opiskelija_id'] . "</h3></td>";
                            echo "<td><h3>" . $row['Etunimi'] . "</h3></td>";
                            echo "<td><h3>" . $row['Sukunimi'] . "</h3></td>";
                            echo "<td><h3>" . $row['Puhelinnumero'] . "</h3></td>";
                            echo "<td><h3>" . $row['Sahkoposti'] . "</h3></td>";
                            echo "<td><h3>" . $row['Postinumero'] . "</h3></td>";
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
</body>