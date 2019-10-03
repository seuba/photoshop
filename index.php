<?php
$txt = $_GET['var'];
$txt2 = $_GET['courtid'];
$myfile = fopen("file.txt", "w") or die("Unable to open file!");

fwrite($myfile, $txt);
fwrite($myfile, $txt2);
fclose($myfile);
?>
