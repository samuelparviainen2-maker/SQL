<?php include "yhteys.php";
 
if ($_SERVER["REQUEST_METHOD"] == "POST") { 
    // Asetetaan laskun tiedot
    $TilausId = $_POST["tilaus_id"];
    $Laskupaiva = $_POST["laskupaiva"];
    $Erapaiva = $_POST["erapaiva"];
    $Tila = $_POST["tila"];
    $Hyvityslasku = isset($_POST["hyvityslasku"]) ? $_POST["hyvityslasku"] : 0;

    $LaskuId = $_POST["lasku_id"] ?? null;

    if (!empty($LaskuId)) { // Muokkauslomakkeelta tulee lasku_id
        $sql = "UPDATE laskut SET tilaus_id = '$TilausId', laskupaiva = '$Laskupaiva', erapaiva = '$Erapaiva', tila = '$Tila', hyvityslasku = '$Hyvityslasku' WHERE lasku_id = '$LaskuId'";
        $conn->exec($sql);
        header("location:laskut.php");
        exit;
    } else {  // Jos lisäyslomakkeelta ei tule lasku_id:tä
        // Insertataan arvot sql tauluun
        try {
            $sql = "INSERT INTO laskut (tilaus_id, laskupaiva, erapaiva, tila, hyvityslasku)
            VALUES ('$TilausId', '$Laskupaiva', '$Erapaiva', '$Tila', '$Hyvityslasku')";
            $conn->exec($sql);
            header("location:laskut.php");
        } catch(PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }
    }

    $Muokauttu = false;
}

// Luodaan funktio, millä saadaan tiedon muokkaaminen suoritettua
function muokkaus($M_asia) { // funktio ottaa asian, muokkaus formissa haetaan
    global $conn;

    if (isset($_GET['lasku_id'])) { // Se varmistaa onko linkkiä painettu
        $osat = explode('|', $_GET['lasku_id'], 2);
        if (count($osat) !== 2) {
            return '';
        }

        [$t_id, $loput] = $osat; // jakaa tunnuksen id:ksi ja toiminnoksi
        if ($loput === 'muokkaus') {
            try {
                $sql = "SELECT lasku_id, $M_asia FROM laskut";
                $result = $conn->query($sql);
                if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) {
                        if ($t_id == $row['lasku_id']) {
                            return (string) $row[$M_asia];
                        }
                    }
                }
            } catch(PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }

        if ($loput == 'poisto') {
            $sql = "DELETE FROM laskut WHERE lasku_id=$t_id";
            $conn->query($sql);
        }
    }

    return '';
}
?>
<!DOCTYPE html>
<html lang="fi">
<head>
  <title>Laskut</title>
  <meta charset="utf-8">
  <link rel="stylesheet" href="style.css">
</head>
    <body>
        <div>
        <?php include 'header.php'; ?> 
        </div>
    
    <div class="container-fluid">
        <div class="row content">
            <div class="col-sm-3 opiskelija_form formi">
                <!-- Paikka laskujen lisäämiseen formilla näkyy vasemmalla -->
                <h1>Lisää tai muokkaa laskuja</h1>
                <br>
                <br>
                <form action="laskut.php" method="POST">
                    <input type="hidden" name="lasku_id" value="<?php echo muokkaus('lasku_id'); ?>">
                    
                    <label for="tilaus_id"><h2>Tilaus ID</h2></label>
                    <?php $muokattuTilausId = muokkaus('tilaus_id'); ?>
                    <select name="tilaus_id" required>
                        <?php
                        try {
                            $sql = "SELECT tilaus_id FROM tilaukset";
                            $result = $conn->query($sql);
                            if ($result->rowCount() > 0) {
                                while($row = $result->fetch()) {
                                    $onValittu = ($muokattuTilausId !== '' && (string) $row['tilaus_id'] === (string) $muokattuTilausId);
                                ?>
                                <option value="<?php echo $row['tilaus_id']; ?>" <?php if($onValittu){ echo "selected"; } ?>>
                                    <?php echo 'Tilaus ' . $row['tilaus_id']; ?>
                                </option>
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
                    
                    <label for="laskupaiva"><h2>Laskupäivä</h2></label>
                    <input type="date" name="laskupaiva" value="<?php echo muokkaus('laskupaiva'); ?>" required>
                    <br>
                    
                    <label for="erapaiva"><h2>Eräpäivä</h2></label>
                    <input type="date" name="erapaiva" value="<?php echo muokkaus('erapaiva'); ?>" required>
                    <br>
                    
                    <label for="tila"><h2>Tila</h2></label>
                    <input type="text" name="tila" maxlength="255" value="<?php echo muokkaus('tila'); ?>" required>
                    <br>
                    <hr>
                    <br>
                    
                    <label for="hyvityslasku"><h2>Hyvityslasku (0 tai 1)</h2></label>
                    <input type="number" name="hyvityslasku" min="0" max="1" value="<?php echo muokkaus('hyvityslasku'); ?>" required>
                    
                    <br>
                    <hr>
                    <br>
                    <input type="submit" value="Tallenna">
                </form>
            </div>

            <div class="col-sm-9 list">
                <h1>Laskut</h1>
                <br>
                <br>
                <hr>
                <br>
                <!-- Listataan laskut oikealle -->
                <?php
                try {
                    $sql = "SELECT lasku_id, tilaus_id, laskupaiva, erapaiva, tila, hyvityslasku FROM laskut";
                    $result = $conn->query($sql);

                    if ($result->rowCount() > 0) {
                        echo "<table class='table table-striped'>
                        <tr>
                        <th><h2>Lasku_id</h2></th>
                        <th><h2>Tilaus_id</h2></th>
                        <th><h2>Laskupäivä</h2></th>
                        <th><h2>Eräpäivä</h2></th>
                        <th><h2>Tila</h2></th>
                        <th><h2>Hyvityslasku</h2></th>
                        <th><h2>Muokkaa</h2></th>
                        <th><h2>Poista</h2></th>
                        </tr>";
                        
                        while($row = $result->fetch()) {
                            echo "<tr>";
                            echo "<td><h3>" . $row['lasku_id'] . "</h3></td>";
                            echo "<td><h3>" . $row['tilaus_id'] . "</h3></td>";
                            echo "<td><h3>" . $row['laskupaiva'] . "</h3></td>";
                            echo "<td><h3>" . $row['erapaiva'] . "</h3></td>";
                            echo "<td><h3>" . $row['tila'] . "</h3></td>";
                            echo "<td><h3>" . $row['hyvityslasku'] . "</h3></td>";
                            
                            echo "<td><a href='laskut.php?lasku_id=" . $row['lasku_id'] . '|muokkaus' . "' class='muutos'>Muokkaa</a></td>";
                            echo "<td><a href='laskut.php?lasku_id=" . $row['lasku_id'] . '|poisto' ."' class='muutos'>Poista</a></td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                        unset($result);
                    } else {
                        echo "<h2>Laskuja ei löytynyt</h2>";
                    }
                } catch(PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>