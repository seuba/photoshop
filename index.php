<?php
$txt = $_GET['var'];
$myfile = fopen("file.txt", "w") or die("Unable to open file!");

fwrite($myfile, $txt);

fclose($myfile);
?>
