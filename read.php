<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<style>
body{
    text-align: center;
    padding-top: 20px;
    background: #fbfbfb;
	font-family:arial;
	
}
.off{

    width: 200px;
    height: 300px;
    background: url(house.png);
    background: url(house.png);
    background-position: 33px top;
    background-size: 373px;
    background-repeat: no-repeat;
	
	}
	
.on{
 width: 212px;
    height: 300px;
    background: url(house.png);
    background: url(house.png);
    background-position: -158px top;
    background-size: 373px;
    background-repeat: no-repeat;

}
.switch {
  position: relative;
  display: inline-block;
  width: 200px;
  height: 60px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: " OFF";
  height: 52px;
  width: 96px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
  TEXT-ALIGN: center;
  COLOR: #9E9E9E;
  font-weight: bold;
    font-family: arial;
}

input:checked + .slider {
  background-color: #2196F3;
  box-shadow: 0px 1px 36px #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(96px);
  -ms-transform: translateX(96px);
  transform: translateX(96px);
   content: " ON";
   COLOR:#2196F3;
 
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>
</head>
<body>

<center>
<div id="bombeta" class="off"></div>
<form id="form" action="https://fuelseuba.herokuapp.com/read.php">
<label class="switch">
  <input id="interruptor" type="checkbox">
  
  <span class="slider"></span>
</label> 
<input id="segon" type="hidden" name="val">
	
</form>
</center>
<?php
$file = fopen("file.txt","r");
$content = fread($file,filesize("file.txt"));
fclose($file);


if ($content == 'a1'){

?>
	<script>

	$('#segon').val('b1');
		var valor = 'b1';
	$('#interruptor').trigger('click');
	$("#bombeta").toggleClass('on');
	</script>
<?php
}
if ($content == 'b1'){
?>
	<script>
	$('#segon').val('a1');
		var valor = 'a1';
	</script>
<?php }

if (empty($content) || $content !== 'b1' ||  $content !== 'a1'){
?>
<script>
	console.log("empty");
$('#segon').val('a1');
	var valor = 'a1';
</script>
<?php }
?>
<script>

$('input[type=checkbox]').click(function(){
var valor2 = "a";
console.log(valor2);
var targetForm = $('#form');
var urlWithParams = targetForm.attr('action') + "?" + targetForm.serialize();
$("#bombeta").toggleClass('on');
var formData = '{"val":"'+ valor2 +'","courtid":"1"}';

$.ajax({
	type: "POST",
	url: "https://fuelseuba.herokuapp.com/",
	data: formData,
	dataType: "json",
	contentType : "application/json",
	success: function(data){
		
           location.reload();
           
        }
})
	
}); 
</script>

</body>
</html> 
