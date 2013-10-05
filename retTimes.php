<?php

        ini_set('auto_detect_line_endings',true);

        $fileName = "LOG";

        $cardnumber = $_POST['CardNumber'];


        $time;

        foreach (file($fileName) as $name) {
                list($CardNumber, $Time) = explode(" ", $name);

                if($CardNumber == $cardnumber){
                        $time += $Time;
                }

        }

        $time /= 60;
        $time = round($time / 10) * 10;

        echo $time . " min";

?>

