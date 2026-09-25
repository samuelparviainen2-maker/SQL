<?php include "yhteys.php";
 
if ($_SERVER["REQUEST_METHOD"] == "POST") { 
    //Asetetaan asiakkaan tiedot
   
            
    $Enimi   = $_POST["Etunimi"];
    $Snimi   = $_POST["Sukunimi"];
    $Osoite  = $_POST["Osoite"];

    $Puh     = $_POST["Puhelinnumero"];
    $Sahko   = $_POST["Sahkoposti"];

    $Aryhma  = $_POST["asiakasryhma"];
    $Historia = $_POST["ostohistoria"];
    $AsiakId = $_POST["asiak_id"] ?? null;


    
        if (!empty($AsiakId)) { // Muokkauslomakkeelta tulee asiakas_id
                $sql = "UPDATE asiakkaat SET etunimi = '$Enimi', sukunimi = '$Snimi', osoite = '$Osoite', puhelinnumero = '$Puh', sahkoposti = '$Sahko', asiakasryhma = '$Aryhma', ostohistoria = '$Historia' WHERE asiakas_id = '$AsiakId'";
                $conn->exec($sql);
                header("location:asiakkaat.php");
                exit;
        } else {  // Lisäyslomakkeelta ei tule asiakas_id:tä
    //Insertataan arvot sql tauluun
    try {
        $sql = "INSERT INTO asiakkaat (etunimi, sukunimi, osoite, puhelinnumero, sahkoposti, asiakasryhma, ostohistoria)
        VALUES ('$Enimi', '$Snimi', '$Osoite', '$Puh', '$Sahko', '$Aryhma', '$Historia')";
        $conn->exec($sql);
        header("location:asiakkaat.php");
        } catch(PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
    }
    }

    $Muokauttu = false;
}


//Luodaan funktio, millä saadaan tiedon muokkaaminen suoritettua
function muokkaus($M_asia) { //funktio ottaa asian, muokkaus formissa haetaan
    global $conn;
    
    
    if (isset($_GET['asiakas_id'])) { // Se varmistaa onko linkkiä painettu
    $osat = explode('|', $_GET['asiakas_id'], 2);
    if (count($osat) !== 2) {
        return;
    }

    [$a_id, $loput] = $osat; // jakaa tunnuksen id:ksi ja toiminnoksi
    if($loput === 'muokkaus'){
         try {
                $sql = "SELECT asiakas_id, $M_asia FROM asiakkaat"; //hakee selectillä ainoastaan tarvittavan asian ja id:n, jotta voidaan ottaa ainoastaan Esim. tietyn asiakkaan etunimen
                // Execute the SQL query
                $result = $conn->query($sql);
                // Process the result set
                if ($result->rowCount() > 0) {
                    
                while($row = $result->fetch()) {
                    if($a_id == $row['asiakas_id']) //ottaa ainoastaa muokattavan asiakkaan asian
                        {
                            echo ($row[$M_asia]); //ja tulostaa tietyn asiakkaan haettava asia
                        }
                
                }
                
                } else {
                    echo "<h2>Asiakkaita ei löytynyt</h2>"; //Jos ei löydy, niin tulee virheestä ilmoitus
                }
                } catch(PDOException $e) {
                    echo "Error: " . $e->getMessage();
            }
        
    }
    if($loput == 'poisto'){
        $sql = "DELETE FROM asiakkaat WHERE asiakas_id=$a_id";

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
<head>
    <body>
        <div>
        <?php include 'header.php';
        ?> 
        </div>
    
    <div class="container-fluid">
        <div class="row content">
            <div class="col-sm-3 opiskelija_form">
<!--Paikka asiakkaiden lisäämiseen formilla näkyy vasemmalla. Lisäksi jos on painettu muokkaus nappia, niin käytetään formia muokkaukseen -->
                <h1>Lisää tai muokkaa asiakas</h1>
                <br>
                <br>
                <form action="asiakkaat.php" method="POST">
                    <input type="hidden" name="asiak_id" value="<?php muokkaus('asiakas_id')//tällä tallennetaan asiakas_id, muokkauksen sql update kohtaan, jotta on oikea asiakas mitää päivitetään?>">
                    <label for="Etunimi"><h2>Etunimi</h2></label>
                    <input type="text" name="Etunimi" max="255" value="<?php muokkaus('etunimi')   //laitetaan muokkaus funktioon ainoastaan se rivi mikä tietokannasta haetaan ?>" required>
                    <br>
                    <label for="Sukunimi"><h2>Sukunimi</h2></label>
                    <input type="text" name="Sukunimi" max="255"  value="<?php muokkaus('sukunimi') ?>" required>
                    <br>
                    <label for="Osoite"><h2>Osoite</h2></label>
                    <input type="text" name="Osoite" value="<?php muokkaus('osoite') ?>" required>
                    <br>
                    <hr>
                    <br>
                    <label for="Puhelinnumero"><h2>Puhelinnumero</h2></label>
                    <input type="text" name="Puhelinnumero" max="20" value="<?php muokkaus('puhelinnumero') ?>" required>
                    <br>
                    <label for="Sahkoposti"><h2>Sähköposti</h2></label>
                    <input type="email" name="Sahkoposti" max="255" value="<?php muokkaus('sahkoposti') ?>" required>
                    <br>
                    <hr>
                    <br>
                    <label for="asiakasryhma"><h2>Asiakasryhmä</h2></label>
                    <input type="text" name="asiakasryhma" max="255" value="<?php muokkaus('asiakasryhma') ?>" required>
                    <br>
                    <label for="ostohistoria"><h2>Osto historia</h2></label>
                    <textarea name="ostohistoria"><?php muokkaus('ostohistoria') ?></textarea>
                    <br>
                    <hr>
                    <br>
                    <input type="submit" value="Lisää">
                </form>
               


            </div>
            <div class="col-sm-9">

                <h1>Asiakkaat</h1>
                <br>
                <br>
                <hr>
                <br>
<!--Listataan asiakkaat oikealle ja lisätään muokkausnappi, mikä johtaa samalle sivulle GET asiakas_id tunnuksella -->
                <?php
                
                try {
                    $sql = "SELECT asiakas_id, etunimi, sukunimi, osoite, puhelinnumero, sahkoposti, asiakasryhma, ostohistoria FROM asiakkaat";
                    // Execute the SQL query
                    $result = $conn->query($sql);
                    // Process the result set
                    if ($result->rowCount() > 0) {
                        echo "<table class='table table-striped'>
                        <tr>
                        <th><h2>Asiakas_id</h2></th>
                        <th><h2>Etunimi</h2></th>
                        <th><h2>Sukunimi</h2></th>
                        <th><h2>Osoite</h2></th>
                        <th><h2>Puhelinnumero</h2></th>
                        <th><h2>Sähköposti</h2></th>
                        <th><h2>Asiakasryhmä</h2></th>
                        <th><h2>Ostohistoria</h2></th>
                        <th><h2>Muokkaa</h2></th>
                        <th><h2>Poista</h2></th>
                        </tr>";
                        // Output data of each row
                        while($row = $result->fetch()) {
                            
                            echo "<tr>";
                            
                            echo "<td><h3>" . $row['asiakas_id'] . "</h3></td>";
                            echo "<td><h3>" . $row['etunimi'] . "</h3></td>";
                            echo "<td><h3>" . $row['sukunimi'] . "</h3></td>";
                            echo "<td><h3>" . $row['osoite'] . "</h3></td>";
                            echo "<td><h3>" . $row['puhelinnumero'] . "</h3></td>";
                            echo "<td><h3>" . $row['sahkoposti'] . "</h3></td>";
                            echo "<td><h3>" . $row['asiakasryhma'] . "</h3></td>";
                            echo "<td><h3>" . $row['ostohistoria'] . "</h3></td>";
                            echo "<td><a href='asiakkaat.php?asiakas_id=" . $row['asiakas_id'] . '|muokkaus' . "' class='muokkaus'>Muokkaa</a></td>";
                            echo "<td><a href='asiakkaat.php?asiakas_id=" . $row['asiakas_id'] . '|poisto' ."' class='poisto'>Poista</a></td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                        unset($result);
                    } else {
                        echo "<h2>Asiakkaita ei löytynyt</h2>";
                    }
                    } catch(PDOException $e) {
                    echo "Error: " . $e->getMessage();
                    }
                ?>
                
            <div>
        </div>
    </div>
</body>