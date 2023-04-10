<?php
	//Connection database
	define('DB_SERVER', 'localhost');
	define('DB_USERNAME', 'root');
	define('DB_PASSWORD', '');
	define('DB_NAME', 'getride');
	$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
	if($conn === false){
		die("ERREUR : Impossible de se connecter. " . mysqli_connect_error());
	}


	$hd = $_GET['hd'];

	$hd = str_replace("T", " ",$hd);
	$ha = $_GET['ha'];
	$ha = str_replace("T", " ",$ha);

	$nbpassagers = $_GET['nbpassagers'];
	$VilleDepart = $_GET['VilleDepart'];
	$VilleArrivee = $_GET['VilleArrivee'];
	$prix = $_GET['prix'];
//	$idEtape = $_GET['idEtape'];
	//$idGroupe = $_GET['idGroupe'];
	$precisionLieu = $_GET['precisionLieu'];
	$commentaire = $_GET['commentaire'];
	$idOffre = $_GET['idOffre'];



	$queryRecuperationVilleDepart = "Select * from villes_france_free where ville_nom_reel = '".$VilleDepart."'";
	$queryRecuperationVilleArrivee = "Select * from villes_france_free where ville_nom_reel = '".$VilleArrivee."'";

	echo $queryRecuperationVilleArrivee;
	echo $queryRecuperationVilleDepart;
	$getIdVilleDepart = $conn->query($queryRecuperationVilleDepart);
	$getIdVilleArrivee = $conn->query($queryRecuperationVilleArrivee);


	if($m = $getIdVilleDepart->fetch_assoc()){	
		$idVilleDepart = $m['ville_id'];
	}

	if($k = $getIdVilleArrivee->fetch_assoc()){	
		$idVilleArrivee = $k['ville_id'];
	}
	echo $hd;
	echo "</br>";
	echo $ha;
	echo "</br>";

	echo $nbpassagers;
	echo "</br>";

	echo "id VD ".$idVilleDepart;
	echo "</br>";

	echo "id VA ".$idVilleArrivee;
	echo "</br>";

	echo $prix;
	echo "</br>";

	echo $idEtape;
	echo "</br>";

	/*echo $idGroupe;
	echo "</br>";*/

	echo $precisionLieu;
	echo "</br>";


	echo $_GET['idOffre'];
	echo "</br>";


	echo $commentaire;

			
			$compteur = 0;
			
	$queryFinale = "Update offre
		set horaireDepart = '".$hd."',
		horaireArrivee = '".$ha."',
		nbPassagersMax = '".$nbpassagers."',
		idVilleDepart = '".$idVilleDepart."',
		idVilleArrivee = '".$idVilleArrivee."',
		prix = '".$prix."' ";
		
	/*if(!($idEtape == NULL) or $idEtape != ""){
		$queryFinale = $queryFinale.",idEtape = '".$idEtape."'";
		$compteur = $compteur + 1;
	} */

	/*if(!($idGroupe == NULL) or $idGroupe != ""){
		$queryFinale = $queryFinale.",idGroupe = '".$idGroupe."'";
			$compteur = $compteur + 1;
	}*/

	if(!($precisionLieu == NULL) or $precisionLieu != ""){
		$queryFinale = $queryFinale.",precisionLieu = '".$precisionLieu."'";
			$compteur = $compteur + 1;
	}

	if(!($commentaire == NULL) or $commentaire != ""){
		$queryFinale = $queryFinale.",commentaire = '".$commentaire."'";
			$compteur = $compteur + 1;
	}

	if($compteur == 0){
		$queryFinale = $queryFinale."";
	}
	$queryFinale = $queryFinale.";";


	echo $queryFinale;
		
		
	$queryUpdate = $conn->query($queryFinale);
	echo '<script type="text/javascript">
			window.location.replace("http://localhost/BitcoinLigne/BitcoinLigne/DetailOffre?idOffre='.$idOffre.'");
		</script>';

?>