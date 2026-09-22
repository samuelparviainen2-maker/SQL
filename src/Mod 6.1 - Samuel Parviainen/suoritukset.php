<?php include "yhteys.php";

 
if ($_SERVER["REQUEST_METHOD"] == "POST") { 
    //Asetetaan Suorituksen tiedot
   
            
    $Opiskelija = $_POST["Opi_id"];
    $Kurssi = $_POST["Kurs_id"];
    $Suoritus = $_POST["Suor"];
    $Arvosana = $_POST["Arvo"];
    $Paivamaara = $_POST["Paiva"];
    

    //Insertataan arvot sql tauluun
    try {
        $sql = "INSERT INTO Suoritukset (opiskelija, kurssi, Suoritus, arvosana, Päivämäärä)
        VALUES ('$Opiskelija', '$Kurssi', '$Suoritus', '$Arvosana', '$Paivamaara')";
        $conn->exec($sql);
        header("location:suoritukset.php");
        } catch(PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
    }

                    
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Suoritukset</title>
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
<!--Paikka suoritusten lisäämiseen formilla. Näkyy vasemmalla -->
                <h1>Lisää suoritus</h1>
                <br>
                <br>
                <form action="suoritukset.php" method="POST">
                    <label for="Opi_id"><h2>Opiskelija</h2></label>
                    <select name="Opi_id">
                        
                            <?php
                        try {
                            $sql = "SELECT Opiskelija_id, Etunimi, Sukunimi FROM Opiskelija";
                            // Execute the SQL query
                            $result = $conn->query($sql);
                            // Process the result set
                            if ($result->rowCount() > 0) {
                                // Output data of each row
                                while($row = $result->fetch()) { // Ottaa opiskelijan nimen ja laittaa sen tekstiin, ja ottaa opiskelijan id.n ja säilyttää sen valuena.
                                ?>
                                <option value="<?php echo $row['Opiskelija_id']; ?>"><?php echo $row['Opiskelija_id'].' | '.$row['Etunimi'] .' '. $row['Sukunimi'];  ?></option>
                                <?php
                                }
                                unset($result);
                            } else {
                                echo "<option value='' style='display:none;'></option>"; //Jos ei ole opiskelijoita, niin näyttää sen valintana
                            }
                            } catch(PDOException $e) {
                            echo "Error: " . $e->getMessage();
                            }
                            ?>
                    </select>



                    <br>
                    <label for="Kurs_id"><h2>Kurssi</h2></label>
                    <select name="Kurs_id">
                        


                        <?php
                        try {
                            $sql = "SELECT Kurssi_id, Nimi FROM Kurssi";
                            // Execute the SQL query
                            $result = $conn->query($sql);
                            // Process the result set
                            if ($result->rowCount() > 0) {
                                // Output data of each row
                                while($row = $result->fetch()) { // Ottaa kurssin nimen ja laittaa sen tekstiin, ja ottaa kurssin id.n ja säilyttää sen valuena.
                                ?>
                                <option value="<?php echo $row['Kurssi_id']; ?>"><?php echo $row['Kurssi_id'].' | '.$row['Nimi'] ; ?></option>
                                <?php
                                }
                                unset($result);
                            } else {
                                echo "<option value='' style='display:none;'></option>"; //Jos ei ole kursseja, niin näyttää sen valintana
                            }
                            } catch(PDOException $e) {
                            echo "Error: " . $e->getMessage();
                            }
                            ?>
                    </select>
                    <br>
                    <hr>
                    <br>
                    <label for="Suor"><h2>Suoritus</h2></label>
                    <input type="text" name="Suor" max="300" required>
                    <br>
                    <label for="Arvo">Arvosana</label>
                    <input type="number" name="Arvo" max="5" min="1" required>
                    <br>
                    <hr>
                    <br>
                    <label for="Paiva">Päivämäärä</label>
                    <input type="date" name="Paiva" required>
                    <br>
                    <hr>
                    <br>
                    <input type="submit" value="Lisää">
                </form>
               


            </div>
            <div class="col-sm-9">
<!--Paikka Suorituksille. Näkyy oikealla -->
                <h1>Suoritukset</h1>
                <br>
                <br>
                <hr>
                <br>
                <?php
                try {
                    $sql = "SELECT Suoritukset.opiskelija, Suoritukset.kurssi, Suoritukset.Suoritus, Suoritukset.arvosana, Suoritukset.Päivämäärä, Opiskelija.Etunimi, Opiskelija.Sukunimi, Kurssi.Nimi, Kurssi.Opettaja FROM Suoritukset
                    LEFT JOIN Opiskelija ON Suoritukset.opiskelija = Opiskelija.Opiskelija_id
                    LEFT JOIN Kurssi ON Suoritukset.kurssi = Kurssi.Kurssi_id;
                    ";
                    // Execute the SQL query
                    $result = $conn->query($sql);
                    // Process the result set
                    if ($result->rowCount() > 0) {
                        echo "<table class='table table-striped'>
                        <tr>
                        <th><h2>Opiskelija_id</h2></th>
                        <th><h2>Opiskelijan etunimi</h2></th>
                        <th><h2>Opiskelijan sukunimi</h2></th>
                        <th><h2>Kurssi_id</h2></th>
                        <th><h2>Kurssin nimi</h2></th>
                        <th><h2>Kurssin opettaja</h2></th>
                        <th><h2>Suoritus</h2></th>
                        <th><h2>Arvosana</h2></th>
                        <th><h2>Päivämäärä</h2></th>
                        </tr>";
                        // Output data of each row
                        while($row = $result->fetch()) {
                            
                            echo "<tr>";
                            
                            echo "<td><h3>" . $row['opiskelija'] . "</h3></td>";
                            echo "<td><h3>" . $row['Etunimi'] . "</h3></td>";
                            echo "<td><h3>" . $row['Sukunimi'] . "</h3></td>";
                            echo "<td><h3>" . $row['kurssi'] . "</h3></td>";
                            echo "<td><h3>" . $row['Nimi'] . "</h3></td>";
                            echo "<td><h3>" . $row['Opettaja'] . "</h3></td>";
                            echo "<td><h3>" . $row['Suoritus'] . "</h3></td>";
                            echo "<td><h3>" . $row['arvosana'] . "</h3></td>";
                            echo "<td><h3>" . $row['Päivämäärä'] . "</h3></td>";

                            echo "</tr>";
                        }
                        echo "</table>";
                        unset($result);
                    } else {
                        echo "<h2>Suorituksia ei löydy</h2>";
                    }
                    } catch(PDOException $e) {
                    echo "Error: " . $e->getMessage();
                    }
                ?>
                
            <div>
        </div>
    </div>
</body>