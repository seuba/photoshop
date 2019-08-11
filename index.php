<?php
$txt = $_POST['var'];
$myfile = fopen("file.txt", "w") or die("Unable to open file!");

fwrite($myfile, $txt);

fclose($myfile);
?>
