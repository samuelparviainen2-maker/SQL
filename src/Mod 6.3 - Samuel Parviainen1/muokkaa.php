<?php include "yhteys.php";
$lahetetty = false;
if ($_SERVER["REQUEST_METHOD"] == "POST") { 
    //Asetetaan lemmikin uudet tiedot
    $Nimi = $_POST["Nimi"];
    $Laji = $_POST["Laji"];
    $Rotu = $_POST["Rotu"];
    $Syntyma = $_POST["Syntymapvm"];
    $Omistaja = $_POST["omistaja"];
    $Rekisteri = $_POST["reknro"];

    

$sql = "UPDATE Lemmikki SET nimi = '$Nimi', laji = '$Laji', rotu = '$Rotu', syntymäaika = '$Syntyma', omistaja = '$Omistaja'  WHERE rekisterinumero = $Rekisteri";


$lahetetty = true;

if ($conn->query($sql) === TRUE) {
  $vast = "Eläimen tiedot ei päivitetty"; // jostain syystä toimii näin
} else {
  $vast = 'Eläimen tiedot on päivitetty';
}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Lemmikit eläinlääkäri.fi</title>
  <meta charset="utf-8">
</head>
    <body>
        <div>
        <?php include 'header.php';
        ?> 
        </div>



        <?php
        if (isset($_GET['rekisterinumero'])) {
        $rekisterinumero = $_GET['rekisterinumero'];

        try {

            $sql = "SELECT Lemmikki.lemmikki_id, Lemmikki.nimi, Lemmikki.laji, Lemmikki.rotu, Lemmikki.syntymäaika, Lemmikki.rekisterinumero, Lemmikki.omistaja, omistaja.Nimi, omistaja.omistaja_id FROM Lemmikki
                LEFT JOIN omistaja ON Lemmikki.omistaja = omistaja.omistaja_id";
                // Execute the SQL query
                $result = $conn->query($sql);
                // Process the result set
                if ($result->rowCount() > 0) {

                while($row = $result->fetch()) {
                if($row['rekisterinumero'] == $rekisterinumero){
                    
                    ?>
                    <div class="container-fluid">
                    <form action="muokkaa.php" method="post">

                    <label for="Nimi"><h2>Nimi</h2></label>
                    <input type="text" name="Nimi" maxlength="50" value="<?php echo htmlspecialchars($row['nimi']); ?>" required>
                    <br>
                    <hr>
                    <br>
                    <label for="Laji"><h2>Laji</h2></label>
                    <input type="text" name="Laji" max="50" value="<?php echo htmlspecialchars($row['laji']); ?>" required>
                    <br>
                    <label for="Rotu"><h2>Rotu</h2></label>
                    <input type="text" name="Rotu" max="50" value="<?php echo htmlspecialchars($row['rotu']); ?>" required>
                    <br>
                    <hr>
                    <br>
                    <label for="Syntymapvm"><h2>Syntymäpäivä</h2></label>
                    <input type="DATE" name="Syntymapvm" value="<?php echo htmlspecialchars($row['syntymäaika']); ?>" required>
                    <br>
                    <label for="reknro"><h2>Rekisterinumero</h2></label>
                    <input type="number" name="reknro" min="0" value="<?php echo htmlspecialchars($row['rekisterinumero'], ENT_QUOTES, 'UTF-8'); ?>" required readonly>
                    <br>
                    <label for="omistaja"><h2>Omistaja</h2></label>
                    <select name="omistaja" value="<?php echo htmlspecialchars($row['omistaja_id']); ?>" required>
                        <?php
                        try {
                            $sql = "SELECT omistaja_id, Nimi FROM omistaja";
                            // Execute the SQL query
                            $results = $conn->query($sql);
                            // Process the result set
                            if ($results->rowCount() > 0) {
                                // Output data of each row
                                while($ron = $results->fetch()) { 
                                ?>
                                <option value="<?php echo $ron['omistaja_id']; ?>"><?php echo $ron['omistaja_id'].' | '.$ron['Nimi'] ; ?></option>
                                <?php
                                }
                                unset($results);
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
                    <?php
                }


                }
                    
                    unset($result);
                } else {
                    echo "<h2>Lemmikkejä ei löytynyt</h2>";
                }
                } catch(PDOException $e) {
                echo "Error: " . $e->getMessage();
                }

        }
        ?>
        <br><br><br>
        <div class="container-fluid Page">
        <?php
        
        if($lahetetty == true){
            ?> <h1><?php echo $vast;?></h1><?php
        }
        ?>
        </div>



</body>
