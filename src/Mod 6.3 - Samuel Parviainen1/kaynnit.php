<?php include "yhteys.php";

if (!isset($conn)) {
    die("Tietokantayhteys ei ole käytettävissä.");
}
 
if ($_SERVER["REQUEST_METHOD"] == "POST") { 
    //Asetetaan lemmikin tiedot
    $Kuvaus = $_POST["Kayn_kuva"];
    list($pvm, $klo) = explode('T', $_POST["Kayn_pvm"]); //jakaa yhden inputin kahteen tietoon, päivä ja kelloaika
    $Laakari = $_POST["Kayn_laakari"];
    $Lemmikki = $_POST["Kayn_lem"];

    //Insertataan arvot sql tauluun
    try {
        $sql = "INSERT INTO Käynti (Käynnin_päivämäärä, Käynnin_kello, Käynnin_kuvaus, Lääkärin_nimi, Käynnin_lemmikki)
        VALUES ('$pvm', '$klo', '$Kuvaus', '$Laakari', '$Lemmikki')";
        $conn->exec($sql);
        header("location:kaynnit.php");
        } catch(PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
    }

                    
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Käynnit eläinlääkäri.fi</title>
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
<!--Paikka Eläinlääkärin käynti formilla. Näkyy vasemmalla -->
                <h1>Lisää eläinlääkärin käynti</h1>
                <br>
                <br>
                <form action="kaynnit.php" method="POST">
                    <label for="Kayn_kuva"><h2>Käynnin kuvaus</h2></label><br>
                    <textarea name="Kayn_kuva" max="200" required></textarea>
                    <br>
                    <label for="Kayn_pvm"><h2>Käynnin päivämäärä ja aika</h2></label>
                    <input type="datetime-local" name="Kayn_pvm" required>
                    <br>
                    <hr>
                    <br>
                    <label for="Kayn_laakari"><h2>Eläinlääkäri</h2></label>
                    <input type="text" name="Kayn_laakari" max="80" required>
                    <br>
                    <label for="Kayn_lem"><h2>Käynnin lemmikki</h2></label>
                    <select name="Kayn_lem" required>
                        <?php
                        try {
                            $sql = "SELECT lemmikki_id, nimi FROM Lemmikki";
                            // Execute the SQL query
                            $result = $conn->query($sql);
                            // Process the result set
                            if ($result->rowCount() > 0) {
                                // Output data of each row
                                while($row = $result->fetch()) { 
                                ?>
                                <option value="<?php echo $row['lemmikki_id']; ?>"><?php echo $row['lemmikki_id'].' | '.$row['nimi'] ; ?></option>
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
                    <hr>
                    <br>
                    <input type="submit" value="Lisää">
                </form>
               


            </div>
            <div class="col-sm-9">
<!--Paikka Käynneille. Näkyy oikealla -->
                <h1 class="Page">Käynnit</h1>
                <br>
                <br>
                <hr>
                <br>
                <?php
                try {
                    $sql = "SELECT Käynti.Käynnin_päivämäärä, Käynti.Käynnin_kello, Lemmikki.nimi, omistaja.Nimi, Käynti.Käynnin_kuvaus, Käynti.Lääkärin_nimi, Käynti.Käynnin_lemmikki, Lemmikki.omistaja FROM Käynti
                    LEFT JOIN Lemmikki ON Käynti.Käynnin_lemmikki = Lemmikki.lemmikki_id
                    LEFT JOIN omistaja ON Lemmikki.omistaja = omistaja.omistaja_id
                    ORDER BY Käynti.Käynnin_päivämäärä, Käynti.Käynnin_kello;";
                    // Execute the SQL query
                    $result = $conn->query($sql);
                    // Process the result set
                    if ($result->rowCount() > 0) {
                        echo "<table class='table table-striped'>
                        <tr>
                        <th><h2>Päivämäärä</h2></th>
                        <th><h2>Kello</h2></th>
                        <th><h2>Lemmikki_id</h2></th>
                        <th><h2>Lemmikki</h2></th>
                        <th><h2>Omistaja_id</h2></th>
                        <th><h2>Omistaja</h2></th>
                        <th><h2>Kuvaus</h2></th>
                        <th><h2>Lääkäri</h2></th>
                        </tr>";
                        // Output data of each row
                        while($row = $result->fetch()) {
                            
                            echo "<tr>";
                            echo "<td><h3>" . $row['Käynnin_päivämäärä'] . "</h3></td>";
                            echo "<td><h3>" . $row['Käynnin_kello'] . "</h3></td>";
                            echo "<td><h3>" . $row['Käynnin_lemmikki'] . "</h3></td>";
                            echo "<td><h3>" . $row['nimi'] . "</h3></td>";
                            echo "<td><h3>" . $row['omistaja'] . "</h3></td>";
                            echo "<td><h3>" . $row['Nimi'] . "</h3></td>";
                            echo "<td><h3>" . $row['Käynnin_kuvaus'] . "</h3></td>";
                            echo "<td><h3>" . $row['Lääkärin_nimi'] . "</h3></td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                        unset($result);
                    } else {
                        echo "<h2>Käyntejä ei löytynyt</h2>";
                    }
                    } catch(PDOException $e) {
                    echo "Error: " . $e->getMessage();
                    }
                ?>
                
            <div>
        </div>
    </div>
</html>