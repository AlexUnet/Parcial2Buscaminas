<?php

session_start();

if ($_GET) {
    $_SESSION['time1'] = time();
    if (isset($_GET['start'])) {
        echo "COMENZANDO";
        $_SESSION['size'] = $_GET['size'];
        $_SESSION['name'] = $_GET['name'];
        InicializeMap();
        CreateMines();
        PlaceMines();
        CreateMineCounters();
        header("Location: game.php");
    } else if (isset($_GET['reset'])) {

        $_SESSION['map'] = null;
        $_SESSION['auxMap'] = null;
        $_SESSION['markMap'] = null;
        $_SESSION['game'] = true;
        $_SESSION['mines'] = 0;
        $_SESSION['des_mines'] = 0;
        $_SESSION['activeMines'] = 0;

        $_SESSION['win'] = false;

        $_SESSION['markMode'] = false;

        InicializeMap();
        CreateMines();
        PlaceMines();
        CreateMineCounters();
        header("Location: game.php");
    }
}


#region Map creation

function InicializeMap()
{
    
    for ($i = 0; $i < $_SESSION['size']; $i++) {
        for ($j = 0; $j < $_SESSION['size']; $j++) {
            $_SESSION['map'][$i][$j] = 0;
            $_SESSION['auxMap'][$i][$j] = false;

            $_SESSION['markMap'][$i][$j] = 0;
        }
    }
    echo " creando mapa \n" . $_SESSION['size'] ;
}

function CreateMines()
{
    
    $_SESSION['mines'] = intval((0.35) * $_SESSION['size'] * $_SESSION['size']);
    $_SESSION['activeMines'] = $_SESSION['mines'];
    echo "creando minas \n" . $_SESSION['mines'];
}

function PlaceMines()
{
    
    $counter = 0;
    $counter = $_SESSION['mines'];
    echo "poniendo minas \n " . $counter;

    while ($counter > 0) {
        for ($i = 0; $i < $_SESSION['size']; $i++) {
            for ($j = 0; $j < $_SESSION['size']; $j++) {
                $mineProb = rand(0, 100);
                if ($counter == 0)
                    break;
                if ($mineProb < 35) {
                    $_SESSION['map'][$i][$j] = -1;
                    $counter--;
                }
            }
            if ($counter == 0)
                    break;
        }
    }
    
}

function CreateMineCounters()
{
    echo "poniendo cuadros vacíos con minas";
    for ($i = 0; $i < $_SESSION['size']; $i++) {
        for ($j = 0; $j < $_SESSION['size']; $j++) {
            $counter = 0;
            if ($_SESSION['map'][$i][$j] == 0) {
                if ($i - 1 >= 0 && $j - 1 >= 0 && $_SESSION['map'][$i - 1][$j - 1] == -1)
                    $counter++;
                if ($i - 1 >= 0 && $_SESSION['map'][$i - 1][$j] == -1)
                    $counter++;
                if ($i - 1 >= 0 && $j + 1 < $_SESSION['size'] && $_SESSION['map'][$i - 1][$j + 1] == -1)
                    $counter++;
                if ($j - 1 >= 0 && $_SESSION['map'][$i][$j - 1] == -1)
                    $counter++;
                if ($j + 1 <  $_SESSION['size'] && $_SESSION['map'][$i][$j + 1] == -1)
                    $counter++;
                if ($i + 1 <  $_SESSION['size'] && $j + 1 < $_SESSION['size'] && $_SESSION['map'][$i + 1][$j + 1] == -1)
                    $counter++;
                if ($i + 1 <  $_SESSION['size'] && $_SESSION['map'][$i + 1][$j] == -1)
                    $counter++;
                if ($i + 1 <  $_SESSION['size'] && $j - 1 >= 0 && $_SESSION['map'][$i + 1][$j - 1] == -1)
                    $counter++;
                $_SESSION['map'][$i][$j] += $counter;
            }
        }
    }
}

#endregion
