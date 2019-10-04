<?php
$data = json_decode(file_get_contents('php://input'));

	$txt = $data->{'var'};
$txt2 = $data->{'courtid'};


;
$myfile = fopen("file.txt", "w") or die("Unable to open file!");

fwrite($myfile, $txt);
fwrite($myfile, $txt2);
fclose($myfile);
?>
