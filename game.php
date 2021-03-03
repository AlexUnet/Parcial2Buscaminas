<?php

session_start();

#region MapPainting

function PrintUI()
{
    echo " TIEMPO: ". ($_SESSION['time2']  - $_SESSION['time1'] . " ");
    echo " PLAYER: " . $_SESSION['name'] . "   ";
    echo " MINAS TOTALES" . $_SESSION['mines'] . " ";
    echo "     MINAS ACTIVAS " . $_SESSION['activeMines'] . "\n";
    echo "     MINAS DESACTIVADAS " . $_SESSION['des_mines'];
    echo $_SESSION['markMode'] ? "   Modo Marcar" : "";
}

function PrintMap()
{
    for ($i = 0; $i < $_SESSION['size']; $i++) {
        echo "<tr>";
        for ($j = 0; $j < $_SESSION['size']; $j++) {
            PrintBox($i, $j);
        }
        echo "</tr>";
    }
}

function PrintBox($i, $j)
{
    $value = $_SESSION['map'][$i][$j];
    if ($_SESSION['auxMap'][$i][$j] === true) {
        switch ($value) {
            case -2:
                echo "<td class='casilla' onclick='touch($i,$j)'>║</td>";
                break;
            case -1:
                echo "<td class='casilla' onclick='touch($i,$j)'>*</td>";
                break;
            case 0:
                echo "<td class='casilla' style='background-color:lightgray' onclick='touch($i,$j)'></td>";
                break;
            case 1:
                echo "<td class='caja' style='color:green; background-color:lightgray' onclick='touch($i,$j)'>" . $_SESSION['map'][$i][$j] . "</td>";
                break;
            case 2:
                echo "<td class='caja' style='color:blue; background-color:lightgray' onclick='touch($i,$j)'>" . $_SESSION['map'][$i][$j] . "</td>";
                break;
            case 3:
                echo "<td class='caja' style='color:yellow; background-color:lightgray' onclick='touch($i,$j)'>" . $_SESSION['map'][$i][$j] . "</td>";
                break;
            case 4:
                echo "<td class='caja' style='color:orange; background-color:lightgray' onclick='touch($i,$j)'>" . $_SESSION['map'][$i][$j] . "</td>";
                break;
            case 5:
                echo "<td class='caja' style='color:red; background-color:lightgray' onclick='touch($i,$j)'>" . $_SESSION['map'][$i][$j] . "</td>";
                break;
            default:
                echo "<td class='caja' style='color:black; background-color:lightgray' onclick='touch($i,$j)'>" . $_SESSION['map'][$i][$j] . " </td>";
                break;
        }
    } else {
        echo "<td class='casilla' style='color:black' onclick='touch($i,$j)'></td>";
    }
}

#endregion

$_SESSION['mark'] = false;
if ($_GET) {
    $_SESSION['time2'] = time();

    if (isset($_GET['markMode'])) {
        $_SESSION['markMode'] = !$_SESSION['markMode'];
    }else if ($_SESSION['game']) {
        $i = $_GET['i'];
        $j = $_GET['j'];

        if ($_SESSION['map'][$i][$j] == -1 && !$_SESSION['markMode']) {
            $_SESSION['auxMap'][$i][$j] = true;
            $_SESSION['game'] = false;
            //header("Location:game.php");
        }
        if ($_SESSION['markMode'])
            Mark($i, $j);
        else
            Check($i, $j);
    }
    //header("Location:game.php");
}

function Mark($i, $j)
{
    if ($_SESSION['map'][$i][$j] == -2) {
        $_SESSION['map'][$i][$j] = $_SESSION['markMap'][$i][$j];
        $_SESSION['auxMap'][$i][$j] = false;
        $_SESSION['des_mines']--;
        $_SESSION['activeMines']++;
    } else {
        $_SESSION['markMap'][$i][$j] = $_SESSION['map'][$i][$j];
        $_SESSION['map'][$i][$j] = -2;
        $_SESSION['auxMap'][$i][$j] = true;

        $_SESSION['des_mines']++;
        $_SESSION['activeMines']--;
    }
}

$_SESSION['test'] = false;
function Check($i, $j)
{
    if ($_SESSION['test'])
        return;
    if ($i < 0 || $i >= $_SESSION['size'])
        return;
    if ($j < 0 || $j >= $_SESSION['size'])
        return;
    if ($_SESSION['map'][$i][$j] == -1) {
        $_SESSION['test'] = true;
        return;
    }
    if ($_SESSION['auxMap'][$i][$j] == true)
        return;

    $_SESSION['auxMap'][$i][$j] = true;
    check($i + 1, $j);
    check($i - 1, $j);
    check($i, $j + 1);
    check($i, $j - 1);
}

function CheckWin()
{
    for ($i = 0; $i < $_SESSION['size']; $i++) {
        for ($j = 0; $j < $_SESSION['size']; $j++) {
            if ($_SESSION['map'][$i][$j] == -1)
                return false;
        }
    }
    return true;
}

function SaveData()
{
    $_SESSION['totalT'] = ($_SESSION['time2']  - $_SESSION['time1']);
    if($_SESSION['totalT'] < $_SESSION['lastTotalT']){
        $_SESSION['lastTotalT'] = $_SESSION['totalT'];
        $_SESSION['bestName'] = $_SESSION['name'];
        echo "" . $_SESSION['name'] . " hizo un nuevo tiempo/ tiempo: " . $_SESSION['lastTotalT'] . " ";
    }else{
        echo " Buen juego el mejor tiempo es " . $_SESSION['bestName'] . " con " . $_SESSION['lastTotalT'];
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Buscaminas - Juego</title>
    <style media="screen">
        .casilla {
            width: 20px;
            height: 20px;
            background-color: darkgray;
            margin: 0 auto;
            text-align: center;
            color: black;
            font-family: 'Terminal';
        }

        body {
            background-color: darkslateblue;
        }
    </style>
</head>

<body>
    <div id="">
        <table id="">
            <p id="time"><?php $_SESSION['time'] ?></p>
            <?php
            PrintUI();
            PrintMap();
            if (!$_SESSION['game']) {
                echo "FIN DEL JUEGO";
            }
            if (CheckWin()) {
                echo "WIN";
                SaveData();
            }

            ?>
        </table>

        <form action="create.php" method="GET">
            <input type="submit" name="reset" value="reiniciar">
        </form>

        <form action="game.php" method="GET">
            <input type="submit" name="markMode" value="marcar">
        </form>

        <form class="" name="theform" action="index.html" method="post"></form>

    </div>
    <script type="text/javascript">
        function touch(i, j, click) {
            document.theform.action = "game.php?i=" + i + "&j=" + j + "&click=" + click;
            document.theform.submit();
        }
    </script>

</body>

</html>