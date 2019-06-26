<?php
$file = fopen("file.txt","r");
$content = fread($file,filesize("file.txt"));
fclose($file);

echo $content;
?>
