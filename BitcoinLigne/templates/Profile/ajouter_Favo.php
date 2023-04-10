<?php
	use Cake\Datasource\ConnectionManager;
	use Cake\Event\EventInterface;
	use Cake\Mailer\Email;

    $session_active = $this->request->getAttribute('identity');
	$mail = $session_active->mail;
	
	define('DB_SERVER', 'localhost');
	define('DB_USERNAME', 'root');
	define('DB_PASSWORD', '');
	define('DB_NAME', 'getride');
	$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
	if($conn === false){
		die("ERREUR : Impossible de se connecter. " . mysqli_connect_error());
	}
	
	$query = "SELECT * FROM users WHERE mail='".$mail."';";
	
	$searchQuery = $conn->query($query);
	while($i = $searchQuery->fetch_assoc()){
		$id= $i['idMembre'];
		echo "</br>";
	}
	
	$cookieId =  $_COOKIE['id'];
	
	
	$queryFav = "INSERT INTO membrefavo(idMembre, idMembreFavo)
	VALUES('".$id."','".$cookieId."')";
	

	

	if ($conn->query($queryFav) === TRUE) {
		echo '<script type="text/javascript">
			window.location.replace("http://localhost/BitcoinLigne/BitcoinLigne/profile?id='.$cookieId.'");
		</script>';
	} else {
		echo '<script type="text/javascript">
			window.location.replace("http://localhost/BitcoinLigne/BitcoinLigne/profile?id='.$cookieId.'");
		</script>';
	}

?>