<?php
session_start();
require_once("inc/config.inc.php");
require_once("inc/functions.inc.php");
include("templates/header.inc.php")
?>

<html>
    <head>
        <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
        <meta charset="utf-8">
        <title>Maps</title>
        <style>
            /* Always set the map height explicitly to define the size of the div
             * element that contains the map. */
            #map {
                height: 94%;
            }
            /* Optional: Makes the sample page fill the window. */
            html, body {
                height: 100%;
                margin: 0;
                padding: 0;
            }

            #latlng {
                width: 225px;
            }

            .panel-group{
                float: right;
                width: 340px;
                border: 3px solid #73AD21;
                padding: 5px;
                height: 92%;

            }
            .panel-body-favorits{
                overflow-y:scroll;
                position:absolute;
                height:70%;
                bottom:0;
                border: 3px solid #73AD21;
            }

            .panel-body-form{
                 height:20%;
                position:absolute;
                border: 3px solid #FF0000;
            }
            table#weather-table th, td{

            }

            p {
                font-weight: bold;
                font-size: 16px;
            }
            .box {
                width: 300px;
                height: 160px;
                background-color: #0C5D8A;
                /*        background-color: transparent;*/
                color: #fff;
                margin: 3em auto;
                border: 3px;
                border-color: #000000;
                padding-left: 10px;
                padding-top: 5px;
            }

            .rounded {
                -webkit-border-radius: 10px;
                -moz-border-radius: 10px;
                border-radius: 10px;
            }
        </style>
    </head>



    <body>

        <div class="panel-group">

            <div class="panel panel-default">
                <div class="panel-body-form">
                    <form action="controller.php" method="post">
                        <input type="submit" value="Add to favorits!">
                        <input id="address" type="text" name="address" value="ortschaft eingeben">
                        <input id="coordlon" type="text" name="Coordlon" value="Coordlon ">
                        <input id="coordlat" type="text" name="Coordlat" value="Coordlat">
                        <input id="submit" type="button" value="search">
                        <input id="location" type="button" value="getyourlcation">
                    </form>

                    <form action="homebase.php" method="post">
                        <input type="submit" value="set homebase">
                    </form>
                </div>

                <div class="panel-body-favorits">
                    <?php
                    require_once("inc/config.inc.php");
                    require_once("inc/functions.inc.php");

//Überprüfe, dass der User eingeloggt ist
//Der Aufruf von check_user() muss in alle internen Seiten eingebaut sein
                    $user = check_user();

                    $statement = $pdo->prepare("SELECT * FROM favorits ORDER BY id DESC");
                    $result = $statement->execute();
                    $count = 1;
                    while ($row = $statement->fetch()) {
                        echo'<div class="rounded box"  >';
                        echo' <table id="weather-table" >';
                        echo '<tr>';
                        echo '<td colspan="2"><p>' . $row['weathermain'] . '</p></td>';
                        echo'   </tr>';
                        echo' <tr>';
                        echo' <td rowspan="2" background="/images/' . $row['icon'] . '.png" height="120" width="120"><img id="weather-icon" src=/images/remove.png height="25" width="25" onclick="test()"></td>';
                        echo' </tr>';
                        echo' <tr>';
                        echo' <td colspan="2">';
                        echo'  <table id="weather-table-details" onclick="hallo()">';
                        echo '<tr>';
                        echo '<td colspan="2"><p>' . $row['cityname'] . '</p></td>';
                        echo '  </tr>';
                        echo ' <tr>';
                        echo ' <td>Temperatur:</td>';
                        echo '<td style="padding-left: 7px">' . $row['temperatur'] . ' C°</td>';
                        echo ' </tr>';
                        echo '    <tr>';
                        echo ' <td>Luftfechtigkeit:</td>';
                        echo ' <td style="padding-left: 7px">' . $row['humidity'] . ' %</td>';
                        echo ' </tr>';
                        echo ' <tr>';
                        echo '  <td>Luftdruck:</td>';
                        echo '   <td style="padding-left: 7px"> ' . $row['pressure'] . ' hPa</td>';
                        echo ' </tr>';
                        echo ' </table>';
                        echo ' </td>';
                        echo '  </tr>';
                        echo '  </table>';
                        echo '  </div>';
                    }
                    ?>


                </div>
            </div>
        </div>

        <div id="map"></div>












        <script>
            var map;
            var markers = [];
            var geocoder;

            function initMap() {
                map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 8,
                    center: {lat: 40.731, lng: -73.997}
                });
                geocoder = new google.maps.Geocoder;
                var infowindow = new google.maps.InfoWindow;


                map.addListener('click', function (e) {

                    var clickedlat = e.latLng.lat();
                    var clickedlng = e.latLng.lng();
                    var latarray = [clickedlat, clickedlng];

                    document.getElementById("coordlat").value = e.latLng.lat();
                    document.getElementById("coordlon").value = e.latLng.lng();

                    //Funktion für die Namensauflösung wird aufgerufen
                    geocodeLatLng(geocoder, map, infowindow, latarray);
                });//End Map Clicklistener

                document.getElementById('submit').addEventListener('click', function () {
                    geocodeAddress(geocoder, map, infowindow);
                });//End Button Clicklistener

                document.getElementById('location').addEventListener('click', function () {
                    geolocation(map, infowindow);
                });//End Button Clicklistener

            }//End initMap

       // Adds a marker to the map and push to the array.
      function addMarker(location) {
        var marker = new google.maps.Marker({
          position: location,
          map: map
        });
        markers.push(marker);
      }

function hallo(){
     alert("hallos 123");
}

function test(){
     alert("test 123");
}
      // Sets the map on all markers in the array.
      function setMapOnAll(map) {
        for (var i = 0; i < markers.length; i++) {
          markers[i].setMap(map);
        }
      }

      // Removes the markers from the map, but keeps them in the array.
      function clearMarkers() {
        setMapOnAll(null);
      }

      // Shows any markers currently in the array.
      function showMarkers() {
        setMapOnAll(map);
      }

      // Deletes all markers in the array by removing references to them.
      function deleteMarkers() {
        clearMarkers();
        markers = [];
      }

            function geocodeLatLng(geocoder, map, infowindow, latarray) { //Array mit lat und lng wird übergeben und infoWindow mit Ortschaft und Koordinaten wird erstellt
                var latlng = {lat: parseFloat(latarray[0]), lng: parseFloat(latarray[1])};
                deleteMarkers();
                geocoder.geocode({'location': latlng}, function (results, status) {
                    if (status === 'OK') {
                        map.setCenter(latlng);
                        document.getElementById("address").value = results[1].formatted_address;
                        if (results[1]) {
                            var marker = new google.maps.Marker({
                                position: latlng,
                                map: map
                            });
                            markers.push(marker);
                            infowindow.setContent(results[1].formatted_address + "<br>" + "  " + latarray);
                            infowindow.open(map, marker);
                        } else {
                            window.alert('No results found');
                        }
                    } else {
                        window.alert('Geocoder failed due to: ' + status);
                    }
                });
            }//End goecodeLatLng function

            function geocodeAddress(geocoder, resultsMap, infowindow) { //Ortschaft wird gesucht und Marker auf Karte platziert
                var address = document.getElementById('address').value;
                geocoder.geocode({'address': address}, function (results, status) {
                    if (status === 'OK') {
                        resultsMap.setCenter(results[0].geometry.location);
                        document.getElementById("coordlat").value = results[0].geometry.location.lat();
                        document.getElementById("coordlon").value = results[0].geometry.location.lng();
                        var marker = new google.maps.Marker({
                            map: resultsMap,
                            position: results[0].geometry.location
                        });
                        infowindow.setContent(address + "<br>" + results[0].geometry.location);
                        infowindow.open(map, marker);
                    } else {
                        alert('Geocode was not successful for the following reason: ' + status);
                    }
                });
            }//End geocodeLatLng function

            function geolocation(map, infoWindow) {//Findet den aktuellen Standort
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function (position) {
                        var pos = {//Array mit latlng
                            lat: position.coords.latitude,
                            lng: position.coords.longitude
                        };

                        document.getElementById("coordlat").value = position.coords.latitude;
                        document.getElementById("coordlon").value = position.coords.longitude;

                        var marker = new google.maps.Marker({
                            map: map,
                            position: pos
                        });
                        infoWindow.setContent('Your Location');
                        infoWindow.open(map, marker);
                        map.setCenter(pos);

                    }, function () {
                        handleLocationError(true, infoWindow, map.getCenter());
                    });
                } else {
                    // Browser doesn't support Geolocation
                    handleLocationError(false, infoWindow, map.getCenter());
                }
            }

            function handleLocationError(browserHasGeolocation, infoWindow, pos) { //Fehlerbehandlung von geolocation
                infoWindow.setPosition(pos);
                infoWindow.setContent(browserHasGeolocation ?
                        'Error: The Geolocation service failed.' :
                        'Error: Your browser doesn\'t support geolocation.');
            }

        </script>
        <script async defer
                src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDU_3XGa4TSpXcgPck1KGeIHOWCji9Ez8I&callback=initMap">
        </script>


    </body>
</html>