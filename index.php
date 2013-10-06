<?php

	ini_set('auto_detect_line_endings', true);

	$newline = false;
	$lcount = 0;
	$filename = "LOG";

	echo '  <!DOCTYPE html>
		<html>
		<body>
		<center>
		<table border="1">
		<tr><th>' . "First Name" . '</th><th>' . "Last Name" . '</th><th>' . "Time Spent" . '</th></tr>' . "\n";

	foreach(file("members") as $membername){

		$time = 0;
		$cn_public;

		list($firstname, $lastname, $cardnumber) = explode(" ", $membername);
		$cn_public = rtrim($cardnumber, "\n");

		//Trim non-printable characters
		$firstname = preg_replace( '/[^[:print:]]/', '',$firstname);
		$lastname = preg_replace( '/[^[:print:]]/', '',$lastname);

		foreach(file($filename) as $data_entry){
			list($log_cardnumber, $log_time) = explode(" ", $data_entry);
			if($log_cardnumber === $cn_public){
				$time += $log_time;
			}
		}

		$time /= 60;
		$time = round($time, 2);

		echo '    <tr><td>' . $firstname . '</td> <td>' . $lastname . '</td>';

		if($time < 60){
			//Display time in minutes
			echo '<td>' . $time . " min" . '</td>';	
		} else {
			// Display time in hours
			echo '<td>' . ($time / 60) . " hrs" . '</td>';
		}

		echo '</tr>' . "\n";
	}
			
	echo '  </table>
		</center>
		</body>
		</html>';
?>
