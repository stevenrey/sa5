<?php
class weatherinfo{
	//Deklaration der Membervariabeln
	public $coordLon =Null;
	public $coordLat =Null;
	
	public $weatherMain =Null;
	public $weatherDescription =Null;
	
	public $mainTemperatur =Null;
	public $mainMaxTemp =Null;
	public $mainMinTemp =Null;
	public $mainPressure =Null;
	public $mainHumidity =Null;
	
	public $windSpeed =Null;
	public $windDeg =Null;
	
	public $cloudsAll =Null;
	
	private $apikey = "851bcd046aec31c20df231806ca60676";
	private $url = "api.openweathermap.org/data/2.5/weather?q=$city&appid=$apikey";
	public $city = Null;
	
	
	function __construct() {//Konstruktor der Klasse weatherinfo
		$echo "konstruktor wird durchalufen";
		
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
		
		
	}
	
	
	
}//End Class weatherinfo





?>