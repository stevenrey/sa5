<?php


$apikey = "851bcd046aec31c20df231806ca60676";
$city = 'London';
$url = "api.openweathermap.org/data/2.5/weather?q=$city&appid=$apikey";

//  Initiate curl
$ch = curl_init();
// Disable SSL verification
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
// Will return the response, if false it print the response
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// Set the url
curl_setopt($ch, CURLOPT_URL,$url);
// Execute
$result=curl_exec($ch);
// Closing
curl_close($ch);


$obj = json_decode($result,true);
echo "coordinates";
echo $obj['coord']["lon"];
echo " : ";
echo $obj['coord']["lat"];
echo " ";

echo "weather ";
echo $obj['weather'][0]["main"];
echo " : ";
echo $obj['weather'][0]["description"];

echo $obj['main']["temp"];
echo " : ";
echo $obj['main']["pressure"];
echo " : ";
echo $obj['main']["humidity"];
echo " : ";
echo $obj['main']["temp_min"];
echo " : ";
echo $obj['main']["temp_max"];


echo "wind ";
echo $obj['wind']["speed"];
echo " : ";
echo $obj['wind']["deg"];

echo "wolken ";
echo $obj['clouds']["all"];



/*  foreach ($obj as $key => $value) {
    echo $value["lon"] . "<br>";
  } */

?>

