<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>My Google Map</title>
  <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
<style>
#map{
	height:400px;
	width:100%;
}
</style>

</head>
<?php include 'getfromdb.php'; ?>

 <body>
 <div class="container">                                        
<h2> <div class="well  text-center"   > My Google Map</div></h2>                        
</div>
<div id = "longit"></div>
<div id = "latit"></div>
 <div id="map"></div>
 <script>
 var longi;
 var latt;
	function changesfxn(){
	//map options
	//	var longi = "7.7777"
	//	var latt = "8.8888"
		longi = <?php echo $row_longit; ?>;
		latt = <?php echo $row_latitu?>;
	//	document.getElementById("longit").innerHTML = "longitude:" + longi;
	//	document.getElementById("latit").innerHTML = "latitude:" + latt;
	
		}
	function initMap(){
		setInterval(changesfxn, 1000);
		var options = {
			zoom:14,
			center:{lat:latt,lng:longi}
		//	center:{lat:5.0408,lng:7.9198}
		}
		//new map
		var map = new google.maps.Map(document.getElementById('map'), options);
		
		// add marker, this marker would be called from the gps tracker
		var marker = new google.maps.Marker({
		position:{lat:latt,lng:longi},
		//position:{lat:5.0408,lng:7.9198},
		map:map,
		icon: 'img/truck.jpg'
		});
		
		// Get proper error message based on the code.
		const getPositionErrorMessage = code => {
		  switch (code) {
			case 1:
			  return 'Permission denied.';
			case 2:
			  return 'Position unavailable.';
			case 3:
			  return 'Timeout reached.';
		  }
		}
		// Get user's location
		  if ('geolocation' in navigator) {
			navigator.geolocation.getCurrentPosition(
			  position => {
				console.log(`Lat: ${latt} Lng: ${longi}`);

				// Set marker's position.
				marker.setPosition({
				  lat: latt,
				  lng: longi
				});

				// Center map to user's position.
				map.panTo({
				  lat: latt,
				  lng: longi
				});
			  },
			  err => alert(`Error (${err.code}): ${getPositionErrorMessage(err.code)}`)
			);
		  } else {
			alert('Geolocation is not supported by your browser.');
		  }
		var infoWindow = new google.maps.InfoWindow({
			content: '<h1>Delivery truck</h1>'
		});
		
		marker.addListener('click', function(){
		infoWindow.open(map, marker);
		});
	}
 </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDFfuTmxYsemTjthPUWKyOwLyAPT2tlogg&callback=initMap"
    async defer></script>
  
  
  
  
  </body>
  </html>