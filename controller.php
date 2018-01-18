<?php

include("phpclass wetaherinfo.php");
include ("phpclass weatherforcast.php");
require_once("inc/config.inc.php");
require_once("inc/functions.inc.php");
?>


<?php

$user = check_user();
$unit = setUnit();
$idifexists;


//Sucht in der Datenbank ob der Ort bereits eingetragen ist
$statement = $pdo->prepare("Select id, username FROM favorits WHERE cityname='" . $_POST['address'] . "'");
$result = $statement->execute();
$row = $statement->fetch();

if ($row['id'] == Null) {
    $unit = setUnit();
    $testclass = new weatherinfo($_POST["Coordlat"], $_POST["Coordlon"], $unit);

//Definition der Varabeln
    $cityname = $_POST['address'];
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
    $username = $user['vorname'];
    $datetime = time();
    $sunrise = $testclass->getSunrise();
    $sunset = $testclass->getSunset();
    $coordlat = $_POST["Coordlat"];
    $coordlon = $_POST["Coordlon"];

//Datenbank wird beschrieben
    $statement = $pdo->prepare("INSERT INTO favorits (cityname, weathermain, temperatur, mintemp, maxtemp, pressure, humidity, windspeed, icon, username, country, sunrise, sunset, coordlat, coordlon, unit) "
            . "VALUES (:cityname, :weather, :temp, :mintemp, :maxtemp, :pressure, :humidity, :windspeed, :icon, :username, :country, :sunrise, :sunset, :coordlat, :coordlon, :unit)");

    $result = $statement->execute(array(':cityname' => $cityname, ':weather' => $weather, ':temp' => $temp,
        ':mintemp' => $mintemp, ':maxtemp' => $maxtemp, ':pressure' => $pressure, ':humidity' => $humidity,
        ':windspeed' => $windspeed, ':icon' => $icon, ':username' => $username, ':country' => $country,
        ':sunrise' => $sunrise, ':sunset' => $sunset, ':coordlat' => $coordlat, ':coordlon' => $coordlon, ':unit' => $unit));

    if ($result) {
        echo 'Eintrag erfolgreich hinzugefügt. Sie werden automatisch weitergeleitet <a href="index.php">Zum Login</a>';
        header('Refresh: 2; URL=mapscreen.php');
        $showFormular = false;
    } else {
        echo 'Beim Abspeichern ist leider ein Fehler aufgetreten<br>';
    }
} else {//Wenn der Eintrag schon vorhanden ist, wird geprüft ob der User schon einen Eintrag hat
    $unit = setUnit();
    echo "eintrag bereits gefunden und wurde nicht nochmals hinzugefügt";
    
    if (strpos($row['username'], $user['vorname']) !== false) {
        echo "eintrag bereits vorhanden";
    } else {
        $trimmed = $row['username'] & $_POST['address'];
        $sql = "UPDATE favorits SET homebase = '" . $trimmed . "'  WHERE id='" . $row['id'] . "'";
        $statement = $pdo->prepare($sql);
        $result = $statement->execute();
    }
}


function setUnit() {
    if (isset($_POST["metricswitch"]) == 'Yes') {
        return 'imperial';
    } else {
        return 'metric';
    }
}

//Setzt die Einheit auf °F oder °C

function refreshData($id){
    
    
$testclass = new weatherinfo($_POST["Coordlat"], $_POST["Coordlon"], $unit);
    $data = [
    'name' => $name,
    'surname' => $surname,
    'sex' => $sex,
    'id' => $id,
];
$sql = "UPDATE favorites SET name=:name, surname=:surname, sex=:sex WHERE id=:id";
$stmt= $dpo->prepare($sql);
$stmt->execute($params);
}

