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
	
	
	$nom = $_POST['nom'];
	$prenom = $_POST['prenom'];
	$dtn = $_POST['dtn'];
	$tel = $_POST['tel'];
	$sex = $_POST['sex'];
	if($sex=="Masculin"){
		$sex = "m";
	} else if($sex=="Féminin"){
		$sex = "f";
	} else {
		$sex = "a";
	}
	$estConducteur = $_POST['conducteur'];

	$notifMail = $_POST['activerNotifs'];

	if($notifMail=="Oui"){
		$notifMail = 1;
	} else
		$notifMail = 0;


	echo "</br>";
	echo $_FILES["photoDeProfil"]["name"];
	echo "</br>";



	$target_dir = "\webroot\img\photoProfil\\";
	$target_file = $target_dir . basename($_FILES["photoDeProfil"]["name"]);





	$pathPhoto = $this->request->getData('photoDeProfil');

	$nomPhoto = $pathPhoto->getClientFileName();


	//Déplacement de la photo dans le répertoire
	$chemin = WWW_ROOT . 'img' . DS . 'photoProfil' . DS . $nomPhoto;
	if ($nomPhoto != "") {
		$pathPhoto->moveTo($chemin);
		//$user->pathPhoto = 'webroot\img\photoProfil\\' . $nomPhoto;
		$photo = "webroot/img/photoProfil/".$nomPhoto;
		
		$query =("Update `users` 
		set nom = '".$nom."',
		prenom = '".$prenom."',
		mail = '".$mail."',
		naissance = '".$dtn."',
		telephone = '".$tel."',
		genre = '".$sex."',
		pathPhoto = '".$photo."',
		estConducteur = '".$estConducteur."',
		notifMail = '".$notifMail."'
		WHERE mail='".$mail."'");
		
	}
	else
		$query =("Update `users` 
			set nom = '".$nom."',
			prenom = '".$prenom."',
			mail = '".$mail."',
			naissance = '".$dtn."',
			telephone = '".$tel."',
			genre = '".$sex."',
			estConducteur = '".$estConducteur."',
			notifMail = '".$notifMail."'
			WHERE mail='".$mail."'");
	


	$queryUpdate = $conn->query($query);
	echo '<script type="text/javascript">
			window.location.replace("../visu-profil");
		</script>';

?>