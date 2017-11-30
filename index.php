<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Maps</title>
    <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 100%;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      #floating-panel {
        position: absolute;
        top: 10px;
        z-index: 5;
        background-color: #f111ff;
        padding: 50px;
        border: 1px solid #999;
        text-align: center;
        font-family: 'Roboto','sans-serif';
        line-height: 300px;
        padding-left: 10px;
      }
      #floating-panel {
        position: absolute;
        top: 5px;
        left: 90%;
        margin-left: -180px;
        width: 350px;
        z-index: 5;
        background-color: #ff55f;
        padding: 5px;
        border: 1px solid #999;
      }
      #latlng {
        width: 225px;
      }
    </style>
  </head>
  
  
  
  <body>
    <div id="floating-panel">
			<form action="script.php" method="get">
			<input type="submit" value="Run me now!">
		</form>
      <input id="address" type="text" value="ortschaft eingeben">
      <input id="submit" type="button" value="search">
	  <input id="location" type="button" value="getyourlcation">
    </div>
    <div id="map"></div>
   


   <script>
	
      function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 8,
          center: {lat: 40.731, lng: -73.997}
        });
        var geocoder = new google.maps.Geocoder;
        var infowindow = new google.maps.InfoWindow;
		
		
map.addListener('click', function(e) {
	var	clickedlat=e.latLng.lat();
	var	clickedlng=e.latLng.lng();
	var latarray = [clickedlat, clickedlng];
	
	//Funktion für die Namensauflösung wird aufgerufen
   geocodeLatLng(geocoder, map, infowindow, latarray);
  });//End Map Clicklistener
  
  document.getElementById('submit').addEventListener('click', function() {
          geocodeAddress(geocoder, map, infowindow);
        });//End Button Clicklistener
		
  document.getElementById('location').addEventListener('click', function() {
          geolocation(map, infowindow);
        });//End Button Clicklistener
  
      }//End initMap
	  
      function geocodeLatLng(geocoder, map, infowindow, latarray) { //Array mit lat und lng wird übergeben und infoWindow mit Ortschaft und Koordinaten wird erstellt
        var latlng = {lat: parseFloat(latarray[0]), lng: parseFloat(latarray[1])};
        geocoder.geocode({'location': latlng}, function(results, status) {
          if (status === 'OK') {
			  map.setCenter(latlng);
			  document.getElementById("address").value= results[1].formatted_address;
            if (results[1]) {
              var marker = new google.maps.Marker({
                position: latlng,
                map: map
              });
              infowindow.setContent(results[1].formatted_address +  "<br>" + "  " +latarray);
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
        geocoder.geocode({'address': address}, function(results, status) {
          if (status === 'OK') {
            resultsMap.setCenter(results[0].geometry.location);
            var marker = new google.maps.Marker({
              map: resultsMap,
              position: results[0].geometry.location
            });
			  infowindow.setContent(address +  "<br>" + results[0].geometry.location);
              infowindow.open(map, marker);
          } else {
            alert('Geocode was not successful for the following reason: ' + status);
          }
        });11
		}//End geocodeLatLng function
		
	  function geolocation(map, infoWindow){//Findet den aktuellen Standort
			  if (navigator.geolocation) {
				navigator.geolocation.getCurrentPosition(function(position) {
            var pos = {//Array mit latlng
              lat: position.coords.latitude,
              lng: position.coords.longitude
            };
			var marker = new google.maps.Marker({
              map: map,
              position: pos
            });
				infoWindow.setContent('Your Location');
				infoWindow.open(map, marker);
            map.setCenter(pos);
          }, function() {
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