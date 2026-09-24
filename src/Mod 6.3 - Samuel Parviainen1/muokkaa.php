<?php include "yhteys.php";
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

            $sql = "SELECT Lemmikki.lemmikki_id, Lemmikki.nimi, Lemmikki.laji, Lemmikki.rotu, Lemmikki.syntymäaika, Lemmikki.rekisterinumero, Lemmikki.omistaja, omistaja.Nimi FROM Lemmikki
                LEFT JOIN omistaja ON Lemmikki.omistaja = omistaja.omistaja_id";
                // Execute the SQL query
                $result = $conn->query($sql);
                // Process the result set
                if ($result->rowCount() > 0) {

                while($row = $result->fetch()) {
                if($row['rekisterinumero'])


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
        <div class="container-fluid">
        <form action="lemmikit.php" method="post">

            <label for="Nimi"><h2>Nimi</h2></label>
            <input type="text" name="Nimi" max="50" required>
            <br>
            <hr>
            <br>
            <label for="Laji"><h2>Laji</h2></label>
            <input type="text" name="Laji" max="50" required>
            <br>
            <label for="Rotu"><h2>Rotu</h2></label>
            <input type="text" name="Rotu" max="50" required>
            <br>
            <hr>
            <br>
            <label for="Syntymapvm"><h2>Syntymäpäivä</h2></label>
            <input type="DATE" name="Syntymapvm" required>
            <br>
            <label for="omistaja"><h2>Omistaja</h2></label>
            <select name="omistaja" required>
                <?php
                try {
                    $sql = "SELECT omistaja_id, Nimi FROM omistaja";
                    // Execute the SQL query
                    $result = $conn->query($sql);
                    // Process the result set
                    if ($result->rowCount() > 0) {
                        // Output data of each row
                        while($row = $result->fetch()) { 
                        ?>
                        <option value="<?php echo $row['omistaja_id']; ?>"><?php echo $row['omistaja_id'].' | '.$row['Nimi'] ; ?></option>
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
            <label for="reknro"><h2>Rekisterinumero</h2></label>
            <input type="number" name="reknro" min="0">
            <br>
            <hr>
            <br>
            <input type="submit" value="Lisää">
                </form>
</div>



</body>
