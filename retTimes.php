<?php

	ini_set('auto_detect_line_endings',true);

	$fileName = "LOG";

	$FirstName;
	$LastName;
	$cardnumber = $_POST['CardNumber'];
	list($f_name, $l_name) = explode(" ", $cardnumber);

	foreach (file("members") as $cardname) {
		list($first, $last, $cn_name) = explode(" ", $cardname);

		if($f_name == $first && $l_name == $last){
			$cardnumber = rtrim($cn_name, "\n");
			$FirstName = $f_name;
			$LastName = $l_name;
		}

	}

	$time;

	foreach (file($fileName) as $name) {
		list($cn, $Time) = explode(" ", $name);

		if($cn === $cardnumber){
			$time += $Time;
		}

	}

	$time /= 60;
	$time = round($time, 2);

	if($time < 60){
		echo $FirstName . " " . $LastName . ": " . $time . "min"; 
	}

	if($time >= 60){
		$time /= 60;
		$time = round($time, 2);
		echo $FirstName . " " . $LastName  . ": " .$time . "hrs";
	}

?>
