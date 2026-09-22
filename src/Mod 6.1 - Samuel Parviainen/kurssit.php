<?php include "yhteys.php";

 
if ($_SERVER["REQUEST_METHOD"] == "POST") { 
    //Asetetaan opiskelijan tiedot
   
            
    $Nimi = $_POST["Nimi"];
    $Opeta = $_POST["Opettaja"];

    $Lisatieto = $_POST["Lisatieto"];

    //Insertataan arvot sql tauluun
    try {
        $sql = "INSERT INTO Kurssi (Nimi, Lisätietoa, Opettaja)
        VALUES ('$Nimi', '$Opeta', '$Lisatieto')";
        $conn->exec($sql);
        header("location:kurssit.php");
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
                <h1>Lisää kurssi</h1>
                <br>
                <br>
                <form action="kurssit.php" method="POST">
                    <label for="Nimi"><h2>Nimi</h2></label>
                    <input type="text" name="Nimi" max="50" required>
                    <br>
                    <label for="Opettaja"><h2>Opettaja</h2></label>
                    <input type="text" name="Opettaja" max="80" required>
                    <br>
                    <hr>
                    <br>
                    <label for="Lisatieto"><h2>Lisätietoa</h2></label>
                    <input type="text" name="Lisatieto" max="100" required>
                    <br>
                    <hr>
                    <br>
                    <input type="submit" value="Lisää">
                </form>
               


            </div>
            <div class="col-sm-9">
<!--Paikka kursseille. Näkyy oikealla -->
                <h1>Kurssit</h1>
                <br>
                <br>
                <hr>
                <br>
                <?php
                try {
                    $sql = "SELECT Kurssi_id, Nimi, Lisätietoa, Opettaja FROM Kurssi";
                    // Execute the SQL query
                    $result = $conn->query($sql);
                    // Process the result set
                    if ($result->rowCount() > 0) {
                        echo "<table class='table table-striped'><tr><th><h2>Kurssi_ID</h2></th><th><h2>Nimi</h2></th><th><h2>Lisätietoa</h2></th><th><h2>Opettaja</h2></th></tr>";
                        // Output data of each row
                        while($row = $result->fetch()) {
                            
                            echo "<tr>";
                            
                            echo "<td><h3>" . $row['Kurssi_id'] . "</h3></td>";
                            echo "<td><h3>" . $row['Nimi'] . "</h3></td>";
                            echo "<td><h3>" . $row['Lisätietoa'] . "</h3></td>";
                            echo "<td><h3>" . $row['Opettaja'] . "</h3></td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                        unset($result);
                    } else {
                        echo "<h2>Kursseja ei löytynyt</h2>";
                    }
                    } catch(PDOException $e) {
                    echo "Error: " . $e->getMessage();
                    }
                ?>
                
            <div>
        </div>
    </div>
</body>