<?php
$data = $_SERVER['QUERY_STRING'];

list($CardNumber, $TimeSpent) = explode('=',$data);

$filename = "LOG";
$file = $filename;

$current .= $CardNumber . " " . $TimeSpent . "\n";

file_put_contents($file,$current, FILE_APPEND | LOCK_EX);
?>

<html>
<center>Restricted Page!</center>
<center>This page is restricted to the 2168 Clocker application.</center>
<center>Because you have visited the page data logs have been added with unwant$
</html>
