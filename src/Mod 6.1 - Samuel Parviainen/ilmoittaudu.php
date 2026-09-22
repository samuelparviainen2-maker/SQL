<?php include "yhteys.php";

 
if ($_SERVER["REQUEST_METHOD"] == "POST") { 
    //Asetetaan Suorituksen tiedot
   
            
    $Opiskelija = $_POST["Opiskelija"];
    $Kurssi = $_POST["Kurssi"];
    $Arvosana = $_POST["Arvosana"];
    $Paivamaara = $_POST["Paivamaara"];
    

    //Insertataan arvot sql tauluun
    try {
        $sql = "INSERT INTO Opiskelija_kurssi (opiskelija, kurssi, Arvosana, SuoritusPvm)
        VALUES ('$Opiskelija', '$Kurssi', '$Arvosana', '$Paivamaara')";
        $conn->exec($sql);
        header("location:ilmoittaudu.php");
        } catch(PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
    }

                    
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Opiskelijoiden kurssit</title>
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
<!--Paikka opiskelijoen lisäämiseen kurssiin formilla. Näkyy vasemmalla -->
                <h1>Lisää opiskelija kurssiin</h1>
                <br>
                <br>
                <form action="ilmoittaudu.php" method="POST">
                    <label for="Opiskelija"><h2>Opiskelija</h2></label>
                    <select name="Opiskelija">
                        
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
                    <label for="Kurssi"><h2>Kurssi</h2></label>
                    <select name="Kurssi">
                        


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
                    <label for="Arvosana"><h2>Arvosana</h2></label>
                    <input type="number" name="Arvosana" max="5" min="0" value="0">
                    <br>
                    <label for="Paivamaara"><h2>Päivämäärä</h2></label>
                    <input type="date" name="Paivamaara" required>
                    <br>
                    <hr>
                    <br>
                    <input type="submit" value="Lisää">
                </form>
               


            </div>
            <div class="col-sm-9">
<!--Paikka Opiskelijoille ja kurssissa. Näkyy oikealla -->
                <h1>Opiskelijat kurssilla</h1>
                <br>
                <br>
                <hr>
                <br>
                <?php
                try {
                    $sql = "SELECT Opiskelija_kurssi.Arvosana, Opiskelija_kurssi.Suorituspvm, Opiskelija_kurssi.opiskelija, Opiskelija_kurssi.kurssi, Opiskelija.Etunimi, Opiskelija.Sukunimi, Kurssi.Nimi, Kurssi.Opettaja FROM Opiskelija_kurssi
                    LEFT JOIN Opiskelija ON Opiskelija_kurssi.opiskelija = Opiskelija.Opiskelija_id
                    LEFT JOIN Kurssi ON Opiskelija_kurssi.kurssi = Kurssi.Kurssi_id;
                    ";
                    // Execute the SQL query
                    $result = $conn->query($sql);
                    // Process the result set
                    if ($result->rowCount() > 0) {
                        echo "<table class='table table-striped'>
                        <tr>
                        <th><h2>Opiskelija_id</h2></th>
                        <th><h2>Etunimi</h2></th>
                        <th><h2>Sukunimi</h2></th>
                        <th><h2>Kurssi_id</h2></th>

                        <th><h2>Kurssin nimi</h2></th>
                        <th><h2>Kurssin opettaja</h2></th>
                        <th><h2>SuoritusPvm</h2></th>
                        <th><h2>Arvosana</h2></th>
                        
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
                            echo "<td><h3>" . $row['Suorituspvm'] . "</h3></td>";
                            echo "<td><h3>" . $row['Arvosana'] . "</h3></td>";

                            echo "</tr>";
                        }
                        echo "</table>";
                        unset($result);
                    } else {
                        echo "<h2>Lista on tyhjä kuin opiskelijan tili ennen opintotukea.</h2>";
                    }
                    } catch(PDOException $e) {
                    echo "Error: " . $e->getMessage();
                    }
                ?>
                
            <div>
        </div>
    </div>
</body>