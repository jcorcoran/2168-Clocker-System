<?php

	ini_set('auto_detect_line_endings', true);

	$newline = false;
	$lcount = 0;

	echo '<center>';

	echo '<table border="1">';
	echo '<tr>';
	echo '<th>' . "First Name" . '</th>';
	echo '<th>' . "Last Name" . '</th>';
	echo '<th>' . "Time Spent" . '</th>';
	echo '</tr>';						

	foreach(file("members") as $membername){

		$time = 0;
		$cn_public;
	
		list($firstname, $lastname, $cardnumber) = explode(" ", $membername);
		$cn_public = rtrim($cardnumber, "\n");

		foreach(file("LOG") as $data_entry){
			list($log_cardnumber, $log_time) = explode(" ", $data_entry);
			if($log_cardnumber === $cn_public){
				$time += $log_time;
			}
		}

		$time /= 60;
		$time = round($time, 2);
			
		echo '<tr>';

		echo '<td>' . $firstname . '</td>';
		echo '<td>' . $lastname . '</td>';

		if($time < 60){
			echo '<td>' . $time . " min" . '</td>';	
		}

		if($time >= 60){
			echo '<td>' . $time . " hrs" . '</td>';
		}

		echo '</tr>';
			
	}
			
	echo '</center>';

?>
