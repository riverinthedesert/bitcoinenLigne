<html>
	<head>
		<title>Navigation</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	</head>
	
	
	<?php
	define('DB_SERVER', 'localhost');
	define('DB_USERNAME', 'root');
	define('DB_PASSWORD', '');
	define('DB_NAME', 'getride');
	
	$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
	// Vérifier la connexion
	if($conn === false){
		die("ERREUR : Impossible de se connecter. " . mysqli_connect_error());
	}

	$core="fonctionnalités :";
	echo $core;
	?>	
	

	<?= $this->Form->postButton(__('Offre + Tri'), ['action' => 'Offre']) ?>
	<?= $this->Form->postButton(__('VisuGroupe + AjoutGroupe'), ['action' => 'VisuGroupe'])?>
	<?= $this->Form->postButton(__('VisuProfil + Modif MDP + Supprimer compte'), ['action' => 'VisuProfil']) ?>
	<?= $this->Form->postButton(__('Accueil'), ['action' => 'accueil']) ?>


</html>

