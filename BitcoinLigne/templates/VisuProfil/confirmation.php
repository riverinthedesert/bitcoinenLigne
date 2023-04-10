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
	while($i = $membre->fetch_assoc()){
		$ancienPass = $i['motDePasse'];
	}
	$oldPass = $_POST["oldpass"];
	$confpass = $_POST["confpass"];
	$newPass = $_POST["newpass"];
	//Creation du cookie pour les messages d'erreurs.
	$cookieName = "password";
	setcookie($cookieName);



	if (password_verify($oldPass, $ancienPass)) {		
		$passIsGood = 0;
		//Regex pour le nouveau pass
		
		
		$uppercase = preg_match('@[A-Z]@', $confpass);
		$lowercase = preg_match('@[a-z]@', $confpass);
		$number    = preg_match('@[0-9]@', $confpass);
		$specialChars = preg_match('@[^\w]@', $confpass);

	if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($confpass) < 8) {
			//Si cela ne suit pas la regex, on redirige vers la page de modification de mot de passe
			setcookie($cookieName, "regex");
			echo "<script type='text/javascript'>
				document.location = 'visu-profil/modif-pass'; 
			</script>";
		} else {
			$passIsGood = 1;
		}
		if($newPass==$confpass){
			if($passIsGood){
				$newPass = password_hash($newPass, PASSWORD_DEFAULT);
				//Requête pour modifier le password
				$query = "Update users set motDePasse = '$newPass' where mail IN 
				(SELECT mail FROM users where mail='".$mail."');";
				if ($conn->query($query) === TRUE) {
					echo "<script type='text/javascript'>
						document.location = 'http://localhost/BitcoinLigne/BitcoinLigne/visu-profil';
					</script>";
					//Reset du cookie
					setcookie($cookieName);
				} else {
					//Problème query
					echo "<script type='text/javascript'> 
						document.location = 'http://localhost/BitcoinLigne/BitcoinLigne/visu-profil/modif-pass'; 
					</script>";
					setcookie($cookieName, "query");
				}
			} else {
				//Problème regex
				echo "<script type='text/javascript'>
			document.location = 'http://localhost/BitcoinLigne/BitcoinLigne/visu-profil/modif-pass'; 
				</script>";
				setcookie($cookieName, "regex");
			}
			
		} else {
			//nouveau mot de passe != mot de passe de confirmation
			echo "<script type='text/javascript'>
				document.location = 'http://localhost/BitcoinLigne/BitcoinLigne/visu-profil/modif-pass'; 
			</script>";
			setcookie($cookieName, "confirm");

		}
	} else {
		//Ancien mot de passe n'est pas égale au mot de passe dans la database
		echo "<script type='text/javascript'> 
			document.location = 'http://localhost/BitcoinLigne/BitcoinLigne/visu-profil/modif-pass'; 
		</script>";
		setcookie($cookieName, "hash");
	}
?>
