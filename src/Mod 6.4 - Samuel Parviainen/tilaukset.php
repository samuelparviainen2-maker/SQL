<?php include "yhteys.php";
 
if ($_SERVER["REQUEST_METHOD"] == "POST") { 
    //Asetetaan asiakkaan tiedot
   
            
    $AsiakasId = $_POST["asiakas_id"];
    $Tilauspaiva = $_POST["tilauspaiva"];
    $Toimituspaiva = $_POST["toimituspaiva"];

    $Tila = $_POST["tila"];
    $Lisahuomautukset = $_POST["lisahuomautukset"];

    $TilausId = $_POST["tilaus_id"] ?? null;


    
        if (!empty($TilausId)) { // Muokkauslomakkeelta tulee tilaus_id
                $sql = "UPDATE tilaukset SET asiakas_id = '$AsiakasId', tilauspaiva = '$Tilauspaiva', toimituspaiva = '$Toimituspaiva', tila = '$Tila', lisahuomautukset = '$Lisahuomautukset' WHERE tilaus_id = '$TilausId'";
                $conn->exec($sql);
                header("location:tilaukset.php");
                exit;
        } else {  // Jos lisäyslomakkeelta ei tule tilaus_id:tä
    //Insertataan arvot sql tauluun
    try {
        $sql = "INSERT INTO tilaukset (asiakas_id, tilauspaiva, toimituspaiva, tila, lisahuomautukset)
        VALUES ('$AsiakasId', '$Tilauspaiva', '$Toimituspaiva', '$Tila', '$Lisahuomautukset')";
        $conn->exec($sql);
        header("location:tilaukset.php");
        } catch(PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
    }
    }

    $Muokauttu = false;
}


//Luodaan funktio, millä saadaan tiedon muokkaaminen suoritettua
function muokkaus($M_asia) { //funktio ottaa asian, muokkaus formissa haetaan
    global $conn;

    if (isset($_GET['tilaus_id'])) { // Se varmistaa onko linkkiä painettu
        $osat = explode('|', $_GET['tilaus_id'], 2);
        if (count($osat) !== 2) {
            return '';
        }

        [$t_id, $loput] = $osat; // jakaa tunnuksen id:ksi ja toiminnoksi
        if ($loput === 'muokkaus') {
            try {
                $sql = "SELECT tilaus_id, $M_asia FROM tilaukset"; //hakee selectillä ainoastaan tarvittavan asian ja id:n, jotta voidaan ottaa ainoastaan Esim. tietyn asiakkaan etunimen
                $result = $conn->query($sql);
                if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) {
                        if ($t_id == $row['tilaus_id']) {
                            return (string) $row[$M_asia];
                        }
                    }
                }
            } catch(PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }

        if ($loput == 'poisto') {
            $sql = "DELETE FROM tilaukset WHERE tilaus_id=$t_id";
            $conn->query($sql);
        }
    }

    return '';
}




?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Etusivu</title>
  <meta charset="utf-8">
  <link rel="stylesheet" href="style.css">
</head>
    <body>
        <div>
        <?php include 'header.php';
        ?> 
        </div>
    
    <div class="container-fluid">
        <div class="row content">
            <div class="col-sm-3 opiskelija_form formi">
<!--Paikka Tilausten lisäämiseen formilla näkyy vasemmalla. Lisäksi jos on painettu muokkaus nappia, niin käytetään formia muokkaukseen -->
                <h1>Lisää tai muokkaa tilauksia</h1>
                <br>
                <br>
                <form action="tilaukset.php" method="POST">
                    <input type="hidden" name="tilaus_id" value="<?php echo muokkaus('tilaus_id'); //tällä tallennetaan tilaus_id, muokkauksen sql update kohtaan, jotta on oikea tilaus mitä päivitetään?>">
                    <label for="asiakas_id"><h2>Asiakas ID</h2></label>
                    <?php $muokattuAsiakasId = muokkaus('asiakas_id'); ?>
                    <select name="asiakas_id" required>
                            <?php
                        try {
                            $sql = "SELECT asiakas_id, etunimi, sukunimi FROM asiakkaat";
                            $result = $conn->query($sql);
                            if ($result->rowCount() > 0) {
                                while($row = $result->fetch()) { // Ottaa asiakkaan nimen ja laittaa sen tekstiin, ja ottaa asiakkaan id.n ja säilyttää sen valuena.
                                    $onValittu = ($muokattuAsiakasId !== '' && (string) $row['asiakas_id'] === (string) $muokattuAsiakasId);
                                ?>
                                <option value="<?php echo $row['asiakas_id']; ?>" <?php if($onValittu){ echo "selected"; } ?>>
                                    <?php echo $row['asiakas_id'].' | '.$row['etunimi'] .' '. $row['sukunimi'];  ?></option>
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
                    <label for="tilauspaiva"><h2>Tilauspäivä</h2></label>
                    <input type="date" name="tilauspaiva" value="<?php echo muokkaus('tilauspaiva'); ?>" required>
                    <br>
                    <label for="toimituspaiva"><h2>Toimituspäivä</h2></label>
                    <input type="date" name="toimituspaiva" value="<?php echo muokkaus('toimituspaiva'); ?>">
                    <br>
                    <label for="tila"><h2>Tila</h2></label>
                    <input type="text" name="tila" max="255" value="<?php echo muokkaus('tila'); ?>" required>
                    <br>
                    <hr>
                    <br>
                    <label for="lisahuomautukset"><h2>Lisähuomautukset</h2></label>
                    <textarea name="lisahuomautukset"><?php echo muokkaus('lisahuomautukset'); ?></textarea>
                    
                    <br>
                    <hr>
                    <br>
                    <input type="submit" value="Lisää">
                </form>
               


            </div>
            <div class="col-sm-9 list">

                <h1>Tilaukset</h1>
                <br>
                <br>
                <hr>
                <br>
<!--Listataan tilaukset oikealle ja lisätään muokkausnappi, mikä johtaa samalle sivulle GET tilaus_id tunnuksella -->
                <?php
                
                try {
                    $sql = "SELECT tilaukset.tilaus_id, tilaukset.asiakas_id, tilaukset.tilauspaiva, tilaukset.toimituspaiva, tilaukset.tila, tilaukset.lisahuomautukset, asiakkaat.etunimi, asiakkaat.sukunimi FROM tilaukset
                    LEFT JOIN asiakkaat ON tilaukset.asiakas_id = asiakkaat.asiakas_id";
                    // Execute the SQL query
                    $result = $conn->query($sql);
                    // Process the result set
                    if ($result->rowCount() > 0) {
                        echo "<table class='table table-striped'>
                        <tr>
                        <th><h2>Tilaus_id</h2></th>
                        <th><h2>Asiakas_id</h2></th>
                        <th><h2>Asiakas nimi</h2></th>
                        <th><h2>Tilauspäivä</h2></th>
                        <th><h2>Toimituspäivä</h2></th>
                        <th><h2>Tila</h2></th>
                        <th><h2>Lisähuomautukset</h2></th>
                        <th><h2>Muokkaa</h2></th>
                        <th><h2>Poista</h2></th>
                        
                        </tr>";
                        // Output data of each row
                        while($row = $result->fetch()) {
                            
                            echo "<tr>";
                            
                            echo "<td><h3>" . $row['tilaus_id'] . "</h3></td>";
                            echo "<td><h3>" . $row['asiakas_id'] . "</h3></td>";
                            echo "<td><h3>" . $row['etunimi'] ." ". $row['sukunimi']. "</h3></td>";
                            echo "<td><h3>" . $row['tilauspaiva'] . "</h3></td>";
                            echo "<td><h3>" . $row['toimituspaiva'] . "</h3></td>";
                            echo "<td><h3>" . $row['tila'] . "</h3></td>";
                            echo "<td><h3>" . $row['lisahuomautukset'] . "</h3></td>";
                            
                            echo "<td><a href='tilaukset.php?tilaus_id=" . $row['tilaus_id'] . '|muokkaus' . "' class='muutos'>Muokkaa</a></td>";
                            echo "<td><a href='tilaukset.php?tilaus_id=" . $row['tilaus_id'] . '|poisto' ."' class='muutos'>Poista</a></td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                        unset($result);
                    } else {
                        echo "<h2>Tilauksia ei löytynyt</h2>";
                    }
                    } catch(PDOException $e) {
                    echo "Error: " . $e->getMessage();
                    }
                ?>
                
            <div>
        </div>
    </div>
</body>
</html>