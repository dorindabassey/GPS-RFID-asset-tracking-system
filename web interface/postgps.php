<?php

$servername = "localhost";

// REPLACE with your Database name
$dbname = "id11518347_gpslogistics";
// REPLACE with Database user
$username = "id11518347_gpslogistics";
// REPLACE with Database user password
$password = "f a m i l y";

// Keep this API Key value to be compatible with the ESP32 code provided in the project page. 
// If you change this value, the ESP32 ske\tch needs to match
$api_key_value = "AIzaSyDFfuTmxYsemTjthPUWKyOwLyAPT2tlogg";

//$api_key= $name = $email = $userid = $useramount = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") { 
 $api_key = test_input($_POST["api_key"]);
    if($api_key == $api_key_value) {
		$latitu = test_input($_POST["latitude"]);
		$longit = test_input($_POST["longitude"]);
		
		try {
		$dbh = new PDO("mysql:host=$servername;dbname=$dbname",$username,$password);
		echo "connected to database <br><br><br>";
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // <== add this line
		$sql = "INSERT INTO users (latitu, longit)
		//VALUES (name, email, userid, useramount)";
		VALUES ('" . $latitu . "', '" . $longit . "')";
//		VALUES ('".$_POST["name"]."','".$_POST["email"]."','".$_POST["password"]."','".$_POST["contact"]."')";
		if ($dbh->query($sql)) {
		echo "<script type= 'text/javascript'>alert('New lat and long Inserted Successfully');</script>";
		}
		else{
		echo "<script type= 'text/javascript'>alert('Data not successfully Inserted.');</script>";
		}

		$dbh = null;
		}
		catch(PDOException $e)
		{
		echo $e->getMessage();
		}
    }
    else {
        echo "Wrong API Key provided.";
    }

}
else {
    echo "No data posted with HTTP POST.";
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
