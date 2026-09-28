<?php include "yhteys.php";
 
if ($_SERVER["REQUEST_METHOD"] == "POST") { 
    //Asetetaan tilausrivin tiedot
   
    $TilausId = $_POST["tilaus_id"];
    $TuoteId = $_POST["tuote_id"];
    $Maara  = $_POST["maara"];

    try {
        $sql = "SELECT hinta FROM tuotteet WHERE tuote_id = $TuoteId";
        // Execute the SQL query
        $result = $conn->query($sql);
        // Process the result set
        if ($result->rowCount() > 0) {
            // Output data of each row
            while($row = $result->fetch()) { // Ottaa tuotteen nimen ja laittaa sen tekstiin, ja ottaa tuotteen id.n ja säilyttää sen valuena.
            $kplHinta = $row['hinta'];
            }
            unset($result);
        } else {
            echo "<option value='' style='display:none;'></option>"; //Jos ei ole tuotteita, niin näyttää sen valintana
            }
            } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            }
    
    $Hinta = $Maara * $kplHinta;

    $TilausriviId = $_POST["tilausrivi_id"] ?? null;

    
        if (!empty($TilausriviId)) { // Muokkauslomakkeelta tulee tilausrivi_id
                $sql = "UPDATE tilausrivit SET tilaus_id = '$TilausId', tuote_id = '$TuoteId', maara = '$Maara', hinta = '$Hinta' WHERE tilausrivi_id = '$TilausriviId'";
                $conn->exec($sql);
                header("location:tilausrivit.php");
                exit;
        } else {  // Jos lisäyslomakkeelta ei tule tilausrivi_id:tä
    //Insertataan arvot sql tauluun
    try {
        $sql = "INSERT INTO tilausrivit (tilaus_id, tuote_id, maara, hinta)
        VALUES ('$TilausId', '$TuoteId', '$Maara', '$Hinta')";
        $conn->exec($sql);
        header("location:tilausrivit.php");
        } catch(PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
    }
    }

    $Muokauttu = false;
}


//Luodaan funktio, millä saadaan tiedon muokkaaminen suoritettua
function muokkaus($M_asia) { //funktio ottaa asian, muokkaus formissa haetaan
    global $conn;
    
    
    if (isset($_GET['tilausrivi_id'])) { // Se varmistaa onko linkkiä painettu
    $osat = explode('|', $_GET['tilausrivi_id'], 2);
    if (count($osat) !== 2) {
        return;
    }

    [$t_id, $loput] = $osat; // jakaa tunnuksen id:ksi ja toiminnoksi
    if($loput === 'muokkaus'){
         try {
                $sql = "SELECT tilausrivi_id, $M_asia FROM tilausrivit";
                // Execute the SQL query
                $result = $conn->query($sql);
                // Process the result set
                if ($result->rowCount() > 0) {
                    
                while($row = $result->fetch()) {
                    if($t_id == $row['tilausrivi_id']) //ottaa ainoastaa muokattavan rivin asian
                        {
                            echo $row[$M_asia]; //ja tulostaa haettava asia
                        }
                
                }
                
                } else {
                    echo "<h2>Tilausrivejä ei löytynyt</h2>"; //Jos ei löydy, niin tulee virheestä ilmoitus
                }
                } catch(PDOException $e) {
                    echo "Error: " . $e->getMessage();
            }
        
    }
    if($loput == 'poisto'){
        $sql = "DELETE FROM tilausrivit WHERE tilausrivi_id=$t_id";

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
</head>
    <body>
        <div>
        <?php include 'header.php';
        ?> 
        </div>
    
    <div class="container-fluid">
        <div class="row content">
            <div class="col-sm-3 opiskelija_form">
<!--Paikka Tilausrivien lisäämiseen formilla näkyy vasemmalla. Lisäksi jos on painettu muokkaus nappia, niin käytetään formia muokkaukseen -->
                <h1>Lisää tai muokkaa tilausrivejä</h1>
                <br>
                <br>
                <form action="tilausrivit.php" method="POST">
                    <input type="hidden" name="tilausrivi_id" value="<?php muokkaus('tilausrivi_id')?>">
                    <label for="tilaus_id"><h2>Tilaus ID</h2></label>
                    <select name="tilaus_id" required>
                            <?php
                        try {
                            $sql = "SELECT tilaus_id FROM tilaukset";
                            // Execute the SQL query
                            $result = $conn->query($sql);
                            // Process the result set
                            if ($result->rowCount() > 0) {
                                // Output data of each row
                                while($row = $result->fetch()) { // Ottaa tilauksen nimen ja laittaa sen tekstiin, ja ottaa tilauksen id.n ja säilyttää sen valuena.
                                ?>
                                <option value="<?php echo $row['tilaus_id']; ?>" <?php if(muokkaus('tilaus_id') !== "<h2>Tilauksia ei löytynyt</h2>"){echo "selected";} ?>>
                                    <?php echo $row['tilaus_id'];  ?></option>
                                <?php
                                }
                                unset($result);
                            } else {
                                echo "<option value='' style='display:none;'></option>"; //Jos ei ole tilauksia, niin näyttää sen valintana
                            }
                            } catch(PDOException $e) {
                            echo "Error: " . $e->getMessage();
                            }
                            ?>
                    </select>
                    <br>
                    <label for="tuote_id"><h2>Tuotteet</h2></label>
                    <select name="tuote_id" required>
                            <?php
                        try {
                            $sql = "SELECT tuote_id, nimi FROM tuotteet";
                            // Execute the SQL query
                            $result = $conn->query($sql);
                            // Process the result set
                            if ($result->rowCount() > 0) {
                                // Output data of each row
                                while($row = $result->fetch()) { // Ottaa tuotteen nimen ja laittaa sen tekstiin, ja ottaa tuotteen id.n ja säilyttää sen valuena.
                                ?>
                                <option value="<?php echo $row['tuote_id']; ?>" <?php if(muokkaus('tuote_id') !== "<h2>Tuotteita ei löytynyt</h2>"){echo "selected";} ?>>
                                    <?php echo $row['tuote_id'].' | '. $row['nimi'];  ?></option>
                                <?php
                                }
                                unset($result);
                            } else {
                                echo "<option value='' style='display:none;'></option>"; //Jos ei ole tuotteita, niin näyttää sen valintana
                            }
                            } catch(PDOException $e) {
                            echo "Error: " . $e->getMessage();
                            }
                            ?>
                    </select>
                    <br>
                    <label for="maara"><h2>Määrä</h2></label>
                    <input type="number" name="maara" min="0" value="<?php muokkaus('maara') ?>" required>
                    <br>
                    <hr>
                    <br>
                    <input type="submit" value="Lisää">
                </form>
               


            </div>
            <div class="col-sm-9">

                <h1>Tilausrivit</h1>
                <br>
                <br>
                <hr>
                <br>
<!--Listataan tilausrivit oikealle ja lisätään muokkausnappi, mikä johtaa samalle sivulle GET tilausrivi_id tunnuksella -->
                <?php
                
                try {
                    $sql = "SELECT tilausrivit.tilausrivi_id, tilausrivit.tilaus_id, tilausrivit.tuote_id, tilausrivit.maara, tilausrivit.hinta, tuotteet.nimi FROM tilausrivit 
                    LEFT JOIN tuotteet ON tilausrivit.tuote_id = tuotteet.tuote_id";
                    // Execute the SQL query
                    $result = $conn->query($sql);
                    // Process the result set
                    if ($result->rowCount() > 0) {
                        echo "<table class='table table-striped'>
                        <tr>
                        <th><h2>Tilausrivi_id</h2></th>
                        <th><h2>Tilaus_id</h2></th>
                        <th><h2>Tuote_id</h2></th>
                        <th><h2>Tuotteen nimi</h2></th>
                        <th><h2>Määrä</h2></th>
                        <th><h2>Hinta</h2></th>
                        <th><h2>Muokkaa</h2></th>
                        <th><h2>Poista</h2></th>
                        </tr>";
                        // Output data of each row
                        while($row = $result->fetch()) {
                            
                            echo "<tr>";
                            
                            echo "<td><h3>" . $row['tilausrivi_id'] . "</h3></td>";
                            echo "<td><h3>" . $row['tilaus_id'] . "</h3></td>";
                            echo "<td><h3>" . $row['tuote_id'] . "</h3></td>";
                            echo "<td><h3>" . $row['nimi'] . "</h3></td>";
                            echo "<td><h3>" . $row['maara'] . "</h3></td>";
                            echo "<td><h3>" . $row['hinta'] . "</h3></td>";
                            
                            echo "<td><a href='tilausrivit.php?tilausrivi_id=" . $row['tilausrivi_id'] . '|muokkaus' . "' class='muokkaus'>Muokkaa</a></td>";
                            echo "<td><a href='tilausrivit.php?tilausrivi_id=" . $row['tilausrivi_id'] . '|poisto' ."' class='poisto'>Poista</a></td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                        unset($result);
                    } else {
                        echo "<h2>Tilausrivejä ei löytynyt</h2>";
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