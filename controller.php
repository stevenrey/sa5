<?php

include("phpclass wetaherinfo.php");
include ("phpclass weatherforcast.php");
require_once("inc/config.inc.php");
require_once("inc/functions.inc.php");
?>


<?php
$user = check_user();







$unit = 'metric';

$testclass = new weatherinfo($_POST["Coordlat"], $_POST["Coordlon"], $unit);



//Definition der Varabeln
$cityname = $testclass->getCity();
$weather = $testclass->getWeatherDescription();
$temp = $testclass->getMainTemperatur();
$maxtemp = $testclass->getMainMaxTemp();
$mintemp = $testclass->getMainMinTemp();
$pressure = $testclass->getMainPressure();
$humidity = $testclass->getMainHumidity();
$windspeed = $testclass->getWindSpeed();
$coordlon = $testclass->getCoordLon();
$country = $testclass->getCountry();
$icon = $testclass->getWeatherIcon();
$username =$user['vorname'];
$datetime= time();


//Datenbank wird beschrieben
$statement = $pdo->prepare("INSERT INTO favorits (cityname, weathermain, temperatur, mintemp, maxtemp, pressure, humidity, windspeed, icon, username, country) "
        . "VALUES (:cityname, :weather, :temp, :mintemp, :maxtemp, :pressure, :humidity, :windspeed, :icon, :username, :country)");

$result = $statement->execute(array(':cityname' => $cityname, ':weather' => $weather, ':temp' => $temp,
    ':mintemp' => $mintemp, ':maxtemp' => $maxtemp, ':pressure' => $pressure, ':humidity' => $humidity,
    ':windspeed' => $windspeed, ':icon'=>$icon, ':username'=>$username, ':country'=>$country));

if ($result) {
    echo 'Eintrag erfolgreich hinzugefügt. Sie werden automatisch weitergeleitet <a href="index.php">Zum Login</a>';
    header('Refresh: 2; URL=index.php');
    $showFormular = false;
} else {
    echo 'Beim Abspeichern ist leider ein Fehler aufgetreten<br>';
}
