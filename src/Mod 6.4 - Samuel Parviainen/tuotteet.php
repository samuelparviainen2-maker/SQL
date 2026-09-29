<?php include "yhteys.php";
 
if ($_SERVER["REQUEST_METHOD"] == "POST") { 
    //Asetetaan asiakkaan tiedot
   
            
    $Nimi   = $_POST["nimi"];
    $Kuvaus   = $_POST["kuvaus"];
    $Hinta  = $_POST["hinta"];

    $Kuva     = $_POST["kuva"];
    $Varasto   = $_POST["varasto"];

    $Ryhma  = $_POST["ryhma"];

    $TuoteId = $_POST["tuote_id"] ?? null;


    
        if (!empty($TuoteId)) { // Muokkauslomakkeelta tulee tuote_id
                $sql = "UPDATE tuotteet SET nimi = '$Nimi', kuvaus = '$Kuvaus', hinta = '$Hinta', kuva = '$Kuva', varastotilanne = '$Varasto', tuoteryhma = '$Ryhma' WHERE tuote_id = '$TuoteId'";
                $conn->exec($sql);
                header("location:tuotteet.php");
                exit;
        } else {  // Jos lisäyslomakkeelta ei tule tuote_id:tä
    //Insertataan arvot sql tauluun
    try {
        $sql = "INSERT INTO tuotteet (nimi, kuvaus, hinta, kuva, varastotilanne, tuoteryhma)
        VALUES ('$Nimi', '$Kuvaus', '$Hinta', '$Kuva', '$Varasto', '$Ryhma')";
        $conn->exec($sql);
        header("location:tuotteet.php");
        } catch(PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
    }
    }

    $Muokauttu = false;
}


//Luodaan funktio, millä saadaan tiedon muokkaaminen suoritettua
function muokkaus($M_asia) { //funktio ottaa asian, muokkaus formissa haetaan
    global $conn;
    
    
    if (isset($_GET['tuote_id'])) { // Se varmistaa onko linkkiä painettu
    $osat = explode('|', $_GET['tuote_id'], 2);
    if (count($osat) !== 2) {
        return;
    }

    [$t_id, $loput] = $osat; // jakaa tunnuksen id:ksi ja toiminnoksi
    if($loput === 'muokkaus'){
         try {
                $sql = "SELECT tuote_id, $M_asia FROM tuotteet"; //hakee selectillä ainoastaan tarvittavan asian ja id:n, jotta voidaan ottaa ainoastaan Esim. tietyn asiakkaan etunimen
                // Execute the SQL query
                $result = $conn->query($sql);
                // Process the result set
                if ($result->rowCount() > 0) {
                    
                while($row = $result->fetch()) {
                    if($t_id == $row['tuote_id']) //ottaa ainoastaa muokattavan asiakkaan asian
                        {
                            echo ($row[$M_asia]); //ja tulostaa tietyn asiakkaan haettava asia
                        }
                
                }
                
                } else {
                    echo "<h2>Tuotteita ei löytynyt</h2>"; //Jos ei löydy, niin tulee virheestä ilmoitus
                }
                } catch(PDOException $e) {
                    echo "Error: " . $e->getMessage();
            }
        
    }
    if($loput == 'poisto'){
        $sql = "DELETE FROM tuotteet WHERE tuote_id=$t_id";

    if ($conn->query($sql) === TRUE) {
    } else {
    }

    }
    }
}




?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Etusivu</title>
  <meta charset="utf-8">
  <link rel="stylesheet" href="style.css">
<head>
    <body>
        <div>
        <?php include 'header.php';
        ?> 
        </div>
    
    <div class="container-fluid">
        <div class="row content">
            <div class="col-sm-3 opiskelija_form formi">
<!--Paikka Tuotteiden lisäämiseen formilla näkyy vasemmalla. Lisäksi jos on painettu muokkaus nappia, niin käytetään formia muokkaukseen -->
                <h1>Lisää tai muokkaa tuotteita</h1>
                <br>
                <br>
                <form action="tuotteet.php" method="POST">
                    <input type="hidden" name="tuote_id" value="<?php muokkaus('tuote_id')//tällä tallennetaan asiakas_id, muokkauksen sql update kohtaan, jotta on oikea asiakas mitää päivitetään?>">
                    <label for="nimi"><h2>Nimi</h2></label>
                    <input type="text" name="nimi" max="255" value="<?php muokkaus('nimi')   //laitetaan muokkaus funktioon ainoastaan se rivi mikä tietokannasta haetaan ?>" required>
                    <br>
                    <label for="kuvaus"><h2>Tuotteen kuvaus</h2></label>
                    <textarea name="kuvaus"><?php muokkaus('kuvaus') ?></textarea>
                    <br>
                    <label for="hinta"><h2>Hinta</h2></label>
                    <input type="number" name="hinta" max="10000" min="0" step="0.01"  value="<?php muokkaus('hinta') ?>" required>
                    <br>
                    <label for="kuva"><h2>Kuva linkki</h2></label>
                    <input type="text" name="kuva" value="<?php muokkaus('kuva') ?>" required>
                    <br>
                    <hr>
                    <br>
                    <label for="varasto"><h2>Varastotilanne</h2></label>
                    <input type="number" name="varasto" value="<?php muokkaus('varastotilanne') ?>" required>
                    <br>
                    <label for="ryhma"><h2>Tuoteryhmä</h2></label>
                    <input type="text" name="ryhma" max="255" value="<?php muokkaus('tuoteryhma') ?>" required>
                    
                    <br>
                    <hr>
                    <br>
                    <input type="submit" value="Lisää">
                </form>
               


            </div>
            <div class="col-sm-9 list">

                <h1>Tuotteet</h1>
                <br>
                <br>
                <hr>
                <br>
<!--Listataan tuotteet oikealle ja lisätään muokkausnappi, mikä johtaa samalle sivulle GET tuote_id tunnuksella -->
                <?php
                
                try {
                    $sql = "SELECT tuote_id, nimi, kuvaus, hinta, kuva, varastotilanne, tuoteryhma FROM tuotteet";
                    // Execute the SQL query
                    $result = $conn->query($sql);
                    // Process the result set
                    if ($result->rowCount() > 0) {
                        echo "<table class='table table-striped'>
                        <tr>
                        <th><h2>Tuote_id</h2></th>
                        <th><h2>Nimi</h2></th>
                        <th><h2>Kuvaus</h2></th>
                        <th><h2>Hinta</h2></th>
                        <th><h2>Kuva</h2></th>
                        <th><h2>Varastotilanne</h2></th>
                        <th><h2>Tuoteryhmä</h2></th>
                        <th><h2>Muokkaa</h2></th>
                        <th><h2>Poista</h2></th>
                        
                        </tr>";
                        // Output data of each row
                        while($row = $result->fetch()) {
                            
                            echo "<tr>";
                            
                            echo "<td><h3>" . $row['tuote_id'] . "</h3></td>";
                            echo "<td><h3>" . $row['nimi'] . "</h3></td>";
                            echo "<td><h3>" . $row['kuvaus'] . "</h3></td>";
                            echo "<td><h3>" . $row['hinta'] . "</h3></td>";
                            echo "<td><h3><img src=' ". $row['kuva'] ." '></h3></td>";
                            echo "<td><h3>" . $row['varastotilanne'] . "</h3></td>";
                            echo "<td><h3>" . $row['tuoteryhma'] . "</h3></td>";
                            
                            echo "<td><a href='tuotteet.php?tuote_id=" . $row['tuote_id'] . '|muokkaus' . "' class='muutos'>Muokkaa</a></td>";
                            echo "<td><a href='tuotteet.php?tuote_id=" . $row['tuote_id'] . '|poisto' ."' class='muutos'>Poista</a></td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                        unset($result);
                    } else {
                        echo "<h2>Tuotteita ei löytynyt</h2>";
                    }
                    } catch(PDOException $e) {
                    echo "Error: " . $e->getMessage();
                    }
                ?>
                
            <div>
        </div>
    </div>
</body>