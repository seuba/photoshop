<?php 
$imag = $_GET['imag'];
copy($imag, 'file2.png');
$course = $_GET['namec'];
$width = $_GET['width'];
$height = $_GET['height'];
$start = $_GET['start'];
$end = $_GET['end'];

function CroppedThumbnail($imgSrc,$thumbnail_width,$thumbnail_height) { //$imgSrc is a FILE - Returns an image resource.
    //getting the image dimensions  
    list($width_orig, $height_orig) = getimagesize($imgSrc);   
    $myImage = imagecreatefrompng($imgSrc);
    $ratio_orig = $width_orig/$height_orig;
    
    if ($thumbnail_width/$thumbnail_height > $ratio_orig) {
       $new_height = $thumbnail_width/$ratio_orig;
       $new_width = $thumbnail_width;
    } else {
       $new_width = $thumbnail_height*$ratio_orig;
       $new_height = $thumbnail_height;
    }
    
    $x_mid = $new_width/2;  //horizontal middle
    $y_mid = $new_height/2; //vertical middle
    
    $process = imagecreatetruecolor(round($new_width), round($new_height)); 
    
    imagecopyresampled($process, $myImage, 0, 0, 0, 0, $new_width, $new_height, $width_orig, $height_orig);
    $thumb = imagecreatetruecolor($thumbnail_width, $thumbnail_height); 
    imagecopyresampled($thumb, $process, 0, 0, ($x_mid-($thumbnail_width/2)), ($y_mid-($thumbnail_height/2)), $thumbnail_width, $thumbnail_height, $thumbnail_width, $thumbnail_height);
   
    imagedestroy($process);
    
    return $thumb;
}

//Create the thumbnail
$my_img = CroppedThumbnail($imag, $width, $height);
 $text_colour = imagecolorallocate( $my_img, 255, 255, 255 );
//$line_colour = imagecolorallocate( $my_img, 128, 255, 0 );
$base = ((15 * $width)/100);
$altura = (((50 * $height)/100)+15);
$altura2 = (((50 * $height)/100)+50);
$altura3 = (((50 * $height)/100)+70);
imagestring( $my_img, 5, $base, $altura, 'Webinar Name:'.$course, $text_colour );
imagestring( $my_img, 4, $base, $altura2, 'Start date:'.$start, $text_colour );
imagestring( $my_img, 4, $base, $altura3, 'End date:'.$end, $text_colour );
//imagesetthickness ( $my_img, 5 );
//imageline( $thumb, 30, 45, 165, 45, $line_colour );
// And display the image...
header( "Content-type: image/png" );
imagepng($my_img);


?>