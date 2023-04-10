<?php



	use Cake\Datasource\ConnectionManager;
	use Cake\Event\EventInterface;
	use Cake\Mailer\Email;

	define('DB_SERVER', 'localhost');
	define('DB_USERNAME', 'root');
	define('DB_PASSWORD', '');
	define('DB_NAME', 'getride');
	
	$session_active = $this->request->getAttribute('identity');
	$mail = $session_active->mail;

	$cookieName = "id";



	
	$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
	// Vérifier la connexion
	if($conn === false){
		die("ERREUR : Impossible de se connecter. " . mysqli_connect_error());
	}
	
	
	
	$query = "SELECT * FROM users WHERE mail='".$mail."';";
	
	$searchQuery = $conn->query($query);
	while($i = $searchQuery->fetch_assoc()){
		$tonId= $i['idMembre'];
		echo "</br>";
	}
	
	
	
	echo "<h1>Profil</h1>";

	
	$id = $_GET["id"];
	
	
	if($tonId != $id){
		
		
		
	setcookie($cookieName);

	$searchQuery = $conn->query("SELECT * FROM `users` WHERE idMembre='".$id."'");
	
	echo"<div class='container'>";

	
	while($i = $searchQuery->fetch_assoc()){
		if($i['pathPhoto']!=null){
			echo "<img src='".$i['pathPhoto']."'  height='100' width='100' >";
		}
		if($i['genre']=='m'){
			echo "<b>M. </b>";
		} else if($i['genre']=='f') {
			echo "<b>Mme </b>";
		} else {
			echo "";
		}
		echo $i['nom']." ".$i['prenom'];
		echo "<br>";
		echo "<b>Mail : </b>".$i['mail'];
		echo "<br>";
		echo "<b>Numéro de téléphone : </b>".$i['telephone']."</b>";
		echo "<br>";
		echo "<b>Date de naissance</b> : ".$i['naissance'];
		echo "<br>";
		echo "<b>Conducteur :</b> ".$i['estConducteur'];
		echo "<br>";
		echo "<b>La note moyenne :</b> "; if($i['noteMoyenne'] == "") echo "Cette utilisateur n'a pas encore de note"; else echo $i['noteMoyenne']. "/5";
		echo "<p><br></p>";
	}
	echo "</div>";
	
	setcookie($cookieName, $id);	
	$queryTestDoublon = "Select count(*) as Nombre from membrefavo where idMembre='".$tonId."' and idMembreFavo = '".$id."'";
	$nombre = 0;
	if($i = $conn->query($queryTestDoublon)->fetch_assoc()){
		$nombre = $i['Nombre'];
	}
		
	if($nombre==0){
?>
	<?= $this->Form->postButton(__('Ajouter dans vos Favoris'), ['action' => 'ajouterFavo',$id], ['class'=>'your_class', 'confirm' => __('Voulez-vous vraiment ajouter cet utilisateur a vos favoris?')]) ?>

<?php
	} else {
		echo "</br>";
		echo "Cet utilisateur est déjà dans vos favoris!";
	}
	} else {
		echo "Ceci est ton profil!";
		echo "</br>";
		echo "Pour accéder à votre profil : Mon Profil / Visualiser son profil.";
	}
?>
