
<!DOCTYPE html>
<html>
<head>
<style>

</style>
</head>
<body>
<?php

$servername = "localhost";

// REPLACE with your Database name
$dbname = "id11518347_gpslogistics";
// REPLACE with Database user
$username = "id11518347_gpslogistics";
// REPLACE with Database user password
$password = "f a m i l y";

try {
		$dbh = new PDO("mysql:host=$servername;dbname=transpay",$username,$password);

		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // <== add this line
		$sql = "SELECT * FROM users WHERE id = 9";
		$stmt = $dbh->prepare();
	//	$result = $stmt->fetch();
		
 // parse returned data and display them
		if ($dbh->query($sql)) {
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			//	$row_id = $row["id"];
				$row_latitu = $row["latitu"];
				$row_longit = $row["longit"];
				
			}
		//	$result->free();
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
?> 

</body>
</html>