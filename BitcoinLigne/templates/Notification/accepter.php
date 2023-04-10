<?php

	
	use Cake\Datasource\ConnectionManager;
	use Cake\Event\EventInterface;
	use Cake\Mailer\Email;

	
	//Connexion database
	
	define('DB_SERVER', 'localhost');
	define('DB_USERNAME', 'root');
	define('DB_PASSWORD', '');
	define('DB_NAME', 'getride');
	$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
	if($conn === false){
		die("ERREUR : Impossible de se connecter. " . mysqli_connect_error());
	}
	
	//Recuperer l'id 
	$session_active = $this->request->getAttribute('identity');

	$id = $session_active->idMembre;
	$idOffre = $_GET['id'];


	$queryGetNombrePassagerMaxOffre = "Select * From offre where idOffre='".$idOffre."'";
	
	$nbPassager = $conn->query($queryGetNombrePassagerMaxOffre);
	
	$error = 0;

	if($i = $nbPassager->fetch_assoc()){		
		$nbPassagerMax = $i['nbPassagersMax'];
	}
	
	if($nbPassagerMax > 0){
		

		$query = "SELECT * FROM `notification` WHERE idMembre='".$id."' AND idOffre='".$idOffre."'";


		//recuperer la notification
		$notification = $conn->query($query);
		if($i = $notification->fetch_assoc()){		
			$idExpediteur = $i['idExpediteur'];
			$idMembre = $i['idMembre'];
			$idOffre = $i['idOffre'];
		}
		
		

		
		/*echo "id Expediteur: ".$idExpediteur;
		echo "</br>";
		echo "id Membre: ".$idMembre;
		echo "</br>";
		echo "id Offre: ".$idOffre;
		echo "</br>";*/


		//Recuperer le nom en fonction de son id
		$queryName = "SELECT * FROM `users` WHERE idMembre='".$id."'";
		$name = $conn->query($queryName);

		if($i = $name->fetch_assoc()){		
			$nom = $i['nom'];
			$prenom = $i['prenom'];
		}
		$message = "L\'utilisateur ".$nom." ".$prenom." vous a accepté dans le trajet";



		//echo $message;
		
		//Date actuel
		$now = date_create()->format('Y-m-d H:i:s');

		//Notifier l'utilisateur associé
		$queryNotification = "INSERT INTO NOTIFICATION(idMembre, message, estLue, necessiteReponse,idNotification, idOffre,DateCreation,idExpediteur)
			VALUES('$idExpediteur','$message','0','0','0','$idOffre','$now','$idMembre');
			
			
			";
		//echo "</br>";

		echo $queryNotification;
		
		if($conn->query($queryNotification) === TRUE){
			//echo "Insertion avec succés";
		} else {
			//echo "Insertion echec";
			$error = $error + 1;
		}



		//Modification update table pour ne pas pouvoir accepter plusieurs fois.
		$queryModify = "UPDATE NOTIFICATION 
			SET necessiteReponse = 0
		WHERE idMEMBRE='".$idMembre."' AND idOffre='".$idOffre."' AND idExpediteur='".$idExpediteur."'
		
		";
		
		echo $queryModify;
		if($error == 0){
			if($conn->query($queryModify) === TRUE){
				//echo "Modification valeur necessiteReponse avec succés";
			//	echo "</br>";
			} else {
				//echo "Modification echec";
				//echo "</br>";
				$error = $error + 1;
			}
		}
		
		
		
		
		//On diminue le nombre de passager
		$nbPassagerMax = $nbPassagerMax - 1;
		//echo $nbPassagerMax;
		//echo "</br>";

		
		//Le nombre de passager diminue
		$queryModifyNbPassager = "UPDATE Offre
			SET nbPassagersMax = '".$nbPassagerMax."'
		WHERE idOffre='".$idOffre."' 
		";
		
		
		
		//echo $queryModifyNbPassager;
		//echo "</br>";
		if($error == 0){
			if($conn->query($queryModifyNbPassager) === TRUE){
				//echo "Modification valeur nbPassager avec succés";
			//	echo "</br>";
			} else {
				//echo "Modification echec";
			//	echo "</br>";
				$error = $error + 1;
			}
		}
		
		
		//Insertion de l'id dans la table copassager
		$queryCoPassager = "INSERT INTO copassager(idMembre, idOffre)
			VALUES('$idExpediteur','$idOffre')";
		echo "</br>";
		echo $queryCoPassager;

		if($conn->query($queryCoPassager) === TRUE){
			echo "good query";
			echo $idExpediteur;
			echo $idOffre;
		} else {
			echo "bad query";
		}

		
		
		if($error == 0){
			//Tout s'est bien passé
			
			//Retour à l'offre
			echo '<script type="text/javascript">
				window.location.replace("http://localhost/BitcoinLigne/BitcoinLigne/notification/");
			</script>'; 
		} else {
			echo "Une erreur est arrivée!";
		}
	} else {
		//message d'erreur si nombre passager disponible = 0
		echo "Erreur: l'offre ne peut plus recevoir de passager";
		
		if($error == 0){
			//Tout s'est bien passé
			
			echo '<script type="text/javascript">
				window.location.replace("http://localhost/BitcoinLigne/BitcoinLigne/notification/");
			</script>';

		} else {
			echo "une erreur est arrivée!";
		}
	}
?>