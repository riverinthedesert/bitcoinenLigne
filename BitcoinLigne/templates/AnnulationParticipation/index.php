<?php

echo "<h1>Soldes annulables: </h1>";

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
	$queryRecuperationMesOffres = "Select * from offre where idConducteur = '".$id."'";
	$getOffre = $conn->query($queryRecuperationMesOffres);
	while ($i = $getOffre->fetch_assoc()){	
		$queryRecupCoPassager = "Select * from copassager where idOffre = '".$i['idOffre']."'";
		//echo $queryRecupCoPassager;
		//$queryRecuperationMesNotifications = "Select * from notification where idOffre = '".$i['idOffre']."' AND idExpediteur='".$id."' AND message LIKE '%accept%'";
		//$getOffres = $conn->query($queryRecuperationMesNotifications);
		
		$getMembres = $conn->query($queryRecupCoPassager);
		while($j = $getMembres->fetch_assoc()){
			echo "</br>";
			echo "</br>";
			echo "<b>Solde n°".$j['idOffre'].":</b>";
			$idOffre = $j['idOffre'];
			echo "</br>";
			echo "Annuler la solde de ";
			$queryRecuperationNom = "Select * from users where idMembre = '".$j['idMembre']."'";
			$idMembre = $j['idMembre'];
			$getNomMembre = $conn->query($queryRecuperationNom);
			if ($k = $getNomMembre->fetch_assoc()){
				echo "<b>".$k['nom']." ".$k['prenom']."</b>";
			}
			echo " pour le IBAN destinataire : ";
			
			
			//Get l'id du trajet a annulé:
			$queryRecuperationTrajet = "Select * from offre where idOffre ='".$j['idOffre']."'";
			$getTrajet = $conn->query($queryRecuperationTrajet);
			if($l = $getTrajet->fetch_assoc()){
				$queryRecuperationVilleDepart = "Select * from villes_france_free where ville_id = '".$l['idVilleDepart']."'";
				$queryRecuperationVilleArrivee = "Select * from villes_france_free where ville_id = '".$l['idVilleArrivee']."'";
				$getVilleDepart = $conn->query($queryRecuperationVilleDepart);
				$getVilleArrivee = $conn->query($queryRecuperationVilleArrivee);
				while($m = $getVilleDepart->fetch_assoc()){
					while($n = $getVilleArrivee->fetch_assoc()){
						echo "<b>".$m['ville_nom_reel']."</b>";
						echo "-";
						echo "<b>".$n['ville_nom_reel']."</b>";
					}
				}
			}
			//Ajout d'un petit bouton pour tej la personne;
			echo "</br>";
			echo "<a href='AnnulationParticipation/supprimer?idOffre=".$idOffre."&idExpediteur=".$idMembre."'>Annuler ce solde</a>";
		}
	}
?>

