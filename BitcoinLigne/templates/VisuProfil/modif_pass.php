<html>
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
	
	
	if(isset($_COOKIE['password'])){
		if($_COOKIE['password']=='query'){
			echo "<font color='red'> <b> Erreur dans la Database !</font> </b>";
		} else if ($_COOKIE['password']=='regex'){
			echo "<font color='red'> <b> Votre mot de passe doit avoir 8 charactères, une majuscule, un chiffre, une minuscule</b> </font>";
		} else if ($_COOKIE['password']=='confirm'){
			echo "<font color='red'> <b> Mot de passe de confirmation incorrect! </b> </font>";
	
		} else if($_COOKIE['password']=='hash') {
			echo "<font color='red'> <b> Ancien mot de passe incorrect! </b> </font>";
		} else {
			//Reset du cookie;
			setcookie('password');
		}
	}
	?>
	
	
	
	

<script src="https://lig-membres.imag.fr/donsez/cours/exemplescourstechnoweb/js_securehash/md5.js"></script>
<script>


  function appendMessageToErrorDiv(div, message) {
    div.appendChild(document.createTextNode(message));
    div.appendChild(document.createElement("br"));
  }
  
	function Confirm(){
		 if (confirm("Voulez-vous vraiment changer votre mot de passe ?")) {
			document.myForm.submit();
		 }
	}
</script>
  <form name="myForm" action="confirmation" method="POST">
		<label for="fname">Ancien mot de passe:</label><br>
		<input type="password" id="oldpass" name="oldpass" required><br>
		<label for="lname">Nouveau mot de passe:</label><br>
		<input type="password" id="newpass" name="newpass" required>
		<label for="lname">Confirmation nouveau mot de passe:</label><br>
		<input type="password" id="confpass" name="confpass" required>
		<input onclick = "Confirm()" type="button" value="Envoyer">
		<div style="color:red;" id="submission_errors"/>
	</form>
</html>