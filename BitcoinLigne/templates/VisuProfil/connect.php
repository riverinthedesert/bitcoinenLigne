<?php

	$servername = "localhost";
	$username = "id16539350_root";
	$password = "wP5quuM*4vg[v8F1";
	$database = "id16539350_getride";
	$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

echo "Connected successfully";
?>