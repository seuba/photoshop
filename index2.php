<?php
$json4 = file_get_contents('php://input'); 
$object = json_decode($json4, true);
$courtid = $object['inArguments'][0]['courtid'];
$var = 'b';

$myfile = fopen("file.txt", "w") or die("Unable to open file!");
fwrite($myfile, $var);
fwrite($myfile, $courtid);
fclose($myfile);

?>
