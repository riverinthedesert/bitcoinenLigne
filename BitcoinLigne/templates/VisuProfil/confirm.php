<?php
   	define('DB_SERVER', 'localhost');
	define('DB_USERNAME', 'root');
	define('DB_PASSWORD', '');
	define('DB_NAME', 'getride');
	$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
	if($conn === false){
		die("ERREUR : Impossible de se connecter. " . mysqli_connect_error());
	}
	use Cake\Datasource\ConnectionManager;
	use Cake\Event\EventInterface;
	use Cake\Mailer\Email;
    $session_active = $this->request->getAttribute('identity');
	$mail = $session_active->mail;
	$membre = $conn->query("SELECT * FROM `users` WHERE mail='".$mail."'");
	
	
	$nom = $_GET['nom'];
	$prenom = $_GET['prenom'];
	$dtn = $_GET['dtn'];
	$tel = $_GET['tel'];
	$sex = $_GET['sex'];
	if($sex=="Masculin"){
		$sex = "m";
	} else if($sex=="Féminin"){
		$sex = "f";
	} else {
		$sex = "a";
	}
	$estConducteur = $_GET['conducteur'];

	
	$query =("Update `users` 
	set nom = '".$nom."',
	prenom = '".$prenom."',
	dtn = '".$dtn."',
	genre = '".$sex."',
	estConducteur = '".$estConducteur."',
	WHERE mail='".$mail."'");
	echo $query;
	$queryUpdate = $conn->query($query);
?>