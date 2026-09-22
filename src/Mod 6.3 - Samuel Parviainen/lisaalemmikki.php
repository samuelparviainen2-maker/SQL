<?php
require_once "header.php";
?>

<html>
<head>


    <title>Lisää lemmikki</title>
</head>
<body>


    <form action="lisaus.php" method="post">
        <label for="rek">Rekisterinumero</label>
        <input type="text" name="rek">
        <br>
        <label for="nimi">Nimi</label>
        <input type="text" name="nimi">
        <br>
        <label for="Laji">Laji</label>
        <input type="text" name="Laji">
        <br>
        <label for="pvm">Syntymäaika</label>
        <input type="date" name="pvm">
        <br>
        <label for="Rotu">Rotu</label>
        <input type="text" name="Rotu">
        <br>
        <label for="Omistaja">Omistaja</label>
        <input type="text" name="Omistaja">
        <br>
        <hr>
        <br>
        <input type="submit" value="Lähetä">
    </form>
</body>
</html>