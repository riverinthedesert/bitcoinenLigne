<?php

	use Cake\Datasource\ConnectionManager;
	use Cake\Event\EventInterface;
	use Cake\Mailer\Email;

    $session_active = $this->request->getAttribute('identity');
	$mail = $session_active->mail;
	$id = $session_active->idMembre;

	define('DB_SERVER', 'localhost');
	define('DB_USERNAME', 'root');
	define('DB_PASSWORD', '');
	define('DB_NAME', 'getride');
	$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
	if($conn === false){
		die("ERREUR : Impossible de se connecter. " . mysqli_connect_error());
	}
		//On delete le compte
		$query = "DELETE FROM users WHERE mail='".$mail."';";
		
		$query2 = "DELETE FROM conducteur WHERE idMembre='".$id."';";
		$query3 = "DELETE FROM copassager WHERE idMembre='".$id."';";
		$query4 = "DELETE FROM copassager WHERE idMembre='".$id."';";
		$query5 = "DELETE FROM historiquerecherche WHERE idMembre='".$id."';";
		$query6 = "DELETE FROM historiquetrajet WHERE idMembre='".$id."';";
		$query7 = "DELETE FROM membrefavo WHERE idMembre='".$id."';";
		$query8 = "DELETE FROM notification WHERE idMembre='".$id."';";
		$query9 = "DELETE FROM groupemembre WHERE idUtilisateur='".$id."';";
		$query10 = "DELETE FROM notation WHERE idUtilisateur='".$id."';";
		$query11 = "DELETE FROM groupe WHERE idAdmin='".$id."';";

	
	if ($conn->query($query) === TRUE) {
		$conn->query($query2);
		$conn->query($query3);
		$conn->query($query4);
		$conn->query($query5);
		$conn->query($query6);
		$conn->query($query7);
		$conn->query($query8);
		$conn->query($query9);
		$conn->query($query10);
		$conn->query($query11);

		echo '<script type="text/javascript">
            window.location.replace("deconnexion");
        </script>';
		
	} 
?>