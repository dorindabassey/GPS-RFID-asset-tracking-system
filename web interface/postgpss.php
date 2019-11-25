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
		$llatitu = test_input($_POST["latitude"]);
		$llongit = test_input($_POST["longitude"]);
	//	$id = 9;
		$latitu = (double)$llatitu;
		$longit = (double)$llongit;
			try {
		$dbh = new PDO("mysql:host=$servername;dbname=$dbname",$username,$password);
		echo "connected to database <br><br><br>";
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // <== add this line
		$sql = "UPDATE users SET latitu='".$latitu."', longit='".$longit."' WHERE id=9";
		$stmts = $dbh->prepare($sql);
		$stmts->execute();
		
		
    // echo a message to say the UPDATE succeeded
        echo $stmts->rowCount() . " records UPDATED successfully";

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

