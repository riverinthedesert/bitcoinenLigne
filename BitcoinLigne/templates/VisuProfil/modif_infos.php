<html>
<?php

	use Cake\Datasource\ConnectionManager;

	// connxion à la base de données
	$conn = ConnectionManager::get('default');

    $session_active = $this->request->getAttribute('identity');
	$mail = $session_active->mail;
	$membre = $conn->execute("SELECT * FROM `users` WHERE mail='".$mail."'");


	//$notif = $conn->execute($requete3)->fetchAll('assoc');

	while($i = $membre->fetchAll('assoc')){
		$nom = $i[0]['nom'];
		$prenom = $i[0]['prenom'];
		$dtn = $i[0]['naissance'];
		$tel = $i[0]['telephone'];
		$genre = $i[0]['genre'];
		if($i[0]['genre']=="m"){
			$genre = "Masculin";
		} else if($i[0]['genre']=="f"){
			$genre = "Féminin";
		} else {
			$genre = "Autre";
		}

		$notifsMail = 1;
		if($i[0]['notifMail'] == 1){
			$notifsMail = "Oui";
		} else
			$notifsMail = "Non";
	
		$pathPhoto = $i[0]['pathPhoto'];
		$estConducteur = $i[0]['estConducteur'];
	}
?>


<script>


  function appendMessageToErrorDiv(div, message) {
    div.appendChild(document.createTextNode(message));
    div.appendChild(document.createElement("br"));
  }
  
	function Confirm(){
		var error_div = document.getElementById("submission_errors");
		error_div.innerHTML = "";
		
		var nom = document.getElementById("nom").value;
		var nomRegex = "[a-zA-Z]{1}[a-zA-Z-\\s]+";
		var nomOk = (nom == nom.match(nomRegex));
		if (!nomOk) {
		  appendMessageToErrorDiv(error_div, "Veuillez entrer un nom valide !");
		}
		
		var prenom = document.getElementById("prenom").value;
		var prenomRegex = "[a-zA-Z]{1}[a-zA-Z-\\s]+";
		var prenomOk = (prenom == prenom.match(prenomRegex));
		
		
		var tel = document.getElementById("tel").value;
		var telephoneRegex = "[0]{1}[0-9]{9}";
		var telOk = (tel == tel.match(telephoneRegex));
		if (!telOk) {
			appendMessageToErrorDiv(error_div, "Veuillez entrer un numéro de téléphone valide !");
		}
		var sex = document.getElementById("sex").value;
		if(!(sex == "Masculin" || sex == "Féminin" || sex == "Autre")){
			appendMessageToErrorDiv(error_div, "Veuillez entrer un genre valide !");
		}
		var conducteur = document.getElementById("conducteur").value;
		if(!(conducteur == "Oui" || conducteur == "Non")){
			appendMessageToErrorDiv(error_div, "Veuillez un état de conducteur valide !");
		}
		if (error_div.innerHTML == "") {
			if (confirm("Etes-vous sûr de vouloir modifier vos informations personnelles ?")) {
				document.myForm.submit();
			} else {
				window.location.replace("../visu-profil");
			}
		}
	}
</script>
	
	
	
	<h1 style="text-align:center">Modifier vos données</h1><br/>
	
	<h3 style="text-align:center">Les informations entrées seront modifiées.</h3><br/>

	<div style="margin-left: 20%; margin-right: 35%">

  <form name="myForm" action="confirmInfos" method="post" enctype="multipart/form-data">
		<label for="nom">Modifier votre nom:</label>
		<input type="text" id="nom" name="nom" value="<?php echo $nom?>"><br><br>
		<label for="prenom">Modifier votre prénom:</label>
		<input type="text" id="prenom" name="prenom" value="<?php echo $prenom?>"><br><br>
		<label for="dtn">Modifier votre date de naissance:</label>
		<input type="date" id="dtn" name="dtn" value="<?php echo $dtn?>"><br><br>
		<label for="tel">Modifier votre téléphone:</label>
		<input type="text" id="tel" name="tel" value="<?php echo $tel?>" maxlength="10" pattern="(^06|07)[0-9]{8}"><br><br>
		<label for="sex">Modifier votre sexe:</label>
		<select name="sex" id="sex">

			<?php

				/* Choix des genres. On affiche en premier le genre déjà choisi */

				$genres = array();
				$choix = array('Masculin', 'Féminin', 'Autre');

				array_push($genres, $genre);

				foreach($choix as $c){

					if (!in_array($c, $genres))
						array_push($genres, $c);
				}
			?>

			<option value="<?=$genres[0]?>"><?=$genres[0]?></option>
			<option value="<?=$genres[1]?>"><?=$genres[1]?></option>
			<option value="<?=$genres[2]?>"><?=$genres[2]?></option>
		</select><br><br>
		

		<label for="conducteur">Modifier si vous possédez une voiture:</label>
		<select id="conducteur" name="conducteur">

			<?php

				/* Choix pour le fait d'être conducteur */

				$conduit = array();
				$choix = array('Oui', 'Non');

				array_push($conduit, $estConducteur);

				foreach($choix as $c){

					if (!in_array($c, $conduit))
						array_push($conduit, $c);
				}
			?>

			<option value="<?=$conduit[0]?>"><?=$conduit[0]?></option>
			<option value="<?=$conduit[1]?>"><?=$conduit[1]?></option>
		</select><br><br>
	
		
		<label for="photoDeProfil">Modifier votre photo de profil:</label>

		<input type="file" id="photoDeProfil" name="photoDeProfil" value=""><br><br>

		<label for="activerNotifs">Activer les notifications par mail:</label>
		<select id="activerNotifs" name="activerNotifs">

			<?php

				/* Choix pour le fait de recevoir des notifications par mail */

				$notif = array();
				$choix = array('Oui', 'Non');

				array_push($notif, $notifsMail);

				foreach($choix as $c){

					if (!in_array($c, $notif))
						array_push($notif, $c);
				}
			?>

			<option value="<?=$notif[0]?>"><?=$notif[0]?></option>
			<option value="<?=$notif[1]?>"><?=$notif[1]?></option>
		</select><br><br>



		<input onclick = "Confirm()" type="button" value="Envoyer">
		<div style="color:red;" id="submission_errors"/>
	</form>

	</div>
</html>



