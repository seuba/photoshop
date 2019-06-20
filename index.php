<?php
$myfile = fopen("file.txt", "w") or die("Unable to open file!");
$txt = "Albert\n";
fwrite($myfile, $txt);
$txt = "Seuba\n";
fwrite($myfile, $txt);
fclose($myfile);
?>
