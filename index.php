<!DOCTYPE html>
<html lang="en">

<head>
    <title>Buscaminas</title>
</head>

<body>
    <form action="create.php" methot="GET">
        <label>Inserte el tamaño del tablero</label>
        <input type="number" name="size" max="20" min="8" required>
        <label>Inserte sus iniciales</label>
        <input type="text" name="name" max="2" required>
        <button type="submit" name="start" value="true">Submit</button>
    </form>
</body>

</html>

<?php
session_start();
$_SESSION['size'] = 8;
$_SESSION['map'] = null;
$_SESSION['auxMap'] = null;
$_SESSION['markMap'] = null;
$_SESSION['game'] = true;
$_SESSION['mines'] = 0;
$_SESSION['des_mines'] = 0;
$_SESSION['activeMines'] = 0;

$_SESSION['win'] = false;

$_SESSION['markMode'] = false;

$_SESSION['name'] ="";
$_SESSION['time1'] = 0;
$_SESSION['time2'] = 0;

$_SESSION['lastTotalT'] = 900000;
$_SESSION['bestName'] = "";




if ($_GET) {
    $size = $_SESSION['size'];
    session_reset();
    $_SESSION['size'] = $size;

    header("Location: crate.php");
}

?>