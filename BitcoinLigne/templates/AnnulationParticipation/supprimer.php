<?php

echo "supprimer la personne";


	$idOffre = $_GET['idOffre'];
	$idExpediteur = $_GET['idExpediteur'];
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
	echo "Mon id: ".$id."</br>";
	echo $idOffre;
	echo $idExpediteur;

	//Recupération du nom avec cet id.
	$queryNom="Select * from users where idMembre = '".$id."'";
	$getNomMembre = $conn->query($queryNom);
	if ($k = $getNomMembre->fetch_assoc()){
		echo $k['nom']." ".$k['prenom'];
		$Nom = $k['nom'];
		$Prenom = $k['prenom'];
	}
	
	$queryRecuperationTrajet = "Select * from offre where idOffre ='".$idOffre."'";
	$getOffre = $conn->query($queryRecuperationTrajet);
	if($l = $getOffre->fetch_assoc()){
		$queryRecuperationVilleDepart = "Select * from villes_france_free where ville_id = '".$l['idVilleDepart']."'";
		$queryRecuperationVilleArrivee = "Select * from villes_france_free where ville_id = '".$l['idVilleArrivee']."'";
		$getVilleDepart = $conn->query($queryRecuperationVilleDepart);
		$getVilleArrivee = $conn->query($queryRecuperationVilleArrivee);
		if($m = $getVilleDepart->fetch_assoc()){
			$nomVilleDepart = $m['ville_nom_reel'];

		}
		
		if($n = $getVilleArrivee->fetch_assoc()){
			$nomVilleArrivee = $n['ville_nom_reel'];
		}

	}	
	
	//Ajout notification pour notifier la personne qu'elle a été ejecté du trajet
	$message = "L\'utilisateur ".$Prenom." ".$Nom." vous a ejecter du trajet ".$nomVilleDepart."-".$nomVilleArrivee;
	$now = date_create()->format('Y-m-d H:i:s');

	
	
	$queryNotification = "INSERT INTO NOTIFICATION(idMembre, message, estLue, necessiteReponse,idNotification, idOffre,DateCreation,idExpediteur)
			VALUES('$idExpediteur','$message','0','0','0','$idOffre','$now','$id');
			";

	echo $queryNotification;
	
	if($conn->query($queryNotification) === TRUE){
		echo "Insertion avec succés";
	} else {
		echo "Insertion echec";
	}

	
	//Suppression du fait que la personne participe au trajet	
	$queryDeleteCoPassager = "Delete from copassager where idMembre = '".$idExpediteur."' and idOffre = '".$idOffre."'";
	$conn->query($queryDeleteCoPassager);
	
	//Ajout d'une place au trajet
	$queryGetNombrePassagerMaxOffre = "Select * From offre where idOffre='".$idOffre."'";
	
	$nbPassager = $conn->query($queryGetNombrePassagerMaxOffre);
	

	if($i = $nbPassager->fetch_assoc()){		
		$nbPassagerMax = $i['nbPassagersMax'];
	}
	
	$nbPassagerMax = $nbPassagerMax + 1;
	
	$queryModifyNbPassager = "UPDATE Offre
			SET nbPassagersMax = '".$nbPassagerMax."'
			WHERE idOffre='".$idOffre."' 
	";
		
	if($conn->query($queryModifyNbPassager) === TRUE){
		echo "Modification valeur nbPassager avec succés";
		echo "</br>";
	} else {
		echo "Modification nombre de place dans le trajet -> echec";
		echo "</br>";
	}
	
	
	
	echo '<script type="text/javascript">
				window.location.replace("http://localhost/BitcoinLigne/BitcoinLigne/AnnulationParticipation");
			</script>';
	




?>