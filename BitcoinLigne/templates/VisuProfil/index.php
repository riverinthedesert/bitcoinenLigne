<?php 
	define('DB_SERVER', 'localhost');
	define('DB_USERNAME', 'root');
	define('DB_PASSWORD', '');
	define('DB_NAME', 'getride');
	$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
	// Vérifier la connexion
	if($conn === false){
		die("ERREUR : Impossible de se connecter. " . mysqli_connect_error());
	}

	use Cake\Datasource\ConnectionManager;
	use Cake\Event\EventInterface;
	use Cake\Mailer\Email;

    $session_active = $this->request->getAttribute('identity');
	$mail = $session_active->mail;
	$idMembre = $session_active->idMembre;
		
?>
<div class="container">
	<div class="text-center">
		<h1>Mon Profil</h1>
	</div>
	<!-- Recherche dans la BDD-->
	<div class="input-group">
        <?php
            $membre = $conn->query("SELECT * FROM `users` WHERE mail='".$mail."'");
            while($i = $membre->fetch_assoc()){
				if($i['pathPhoto'] != ""){
					?>
					<img src="<?php echo $i['pathPhoto'];?>"  height="100" width="100" >
					<?php
				}else{
					?>
					<img src="data:image/png;base64,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"  height="100" width="100" >
					<?php
				}
				
                if($i['genre'] == "m"){
                    echo 'Monsieur ';
                }
                else if ($i['genre'] == "f"){
                    echo 'Madame ';
                }
                echo $i['nom'];
                echo ' ';
                echo $i['prenom'];
				
				
				
			
			$_SESSION['idMembre'] = $i['idMembre'];

			$membreDeProfil = $conn->query("SELECT * FROM `users` WHERE mail='".$mail."'");
			while($nuplet = $membreDeProfil->fetch_assoc())
			{ 
				$idMembreProfil =  $nuplet['idMembre'];
				$mailDeProfil = $nuplet['mail'];
			}

        ?>
	</div>
	<div class="input-group">
	    <p><b>Votre email : </b></p>
        <?php
            echo $i['mail'];
        ?>
	</div>
	<div class="form-group">
        <p><br><b>Votre numéro de téléphone : </b></p>
        <?php
            echo $i['telephone'];
        ?>
		
		<p><br><b>Votre date de naissance : </b></p>
        <?php
            echo $i['naissance'];
        ?>

		
		<p><br><b>Votre moyenne : </b></p>
        <?php
			$requete=$conn->query("SELECT * FROM `notation` WHERE idUtilisateur='".$_SESSION['idMembre']."'");
			$moyenne = 0;
			$nbNote = 0;
			while($note = $requete->fetch_assoc()){
				$moyenne =  $moyenne + $note['note'];
				$nbNote++;
			}
			if($nbNote != 0){
				$moyenne = round($moyenne/$nbNote,1);
			}
			if($moyenne == 0)
				echo "Vous n'avez pas encore eu de note.";
			else
            	echo $moyenne."/5";
        ?>
	</div>
	&emsp;&emsp;
	
	
	<br><br>
	
	<?= $this->Form->postButton(__('Modifier ses informations personnelles'), ['action' => 'ModifInfos'], ['class'=>'your_class', 'confirm' => __('Voulez-vous vraiment modifier vos informations personnelles?')]) ?>
	<?= $this->Form->postButton(__('Modifier votre mot de passe'), ['action' => 'modifPass'], ['class'=>'your_class', 'confirm' => __('Voulez-vous vraiment changer votre mot de passe?')]) ?>
	<?= $this->Form->postButton(__('Supprimer votre compte'), ['action' => 'supprimer'], ['class'=>'your_class', 'confirm' => __('Voulez-vous confirmer la suppression de compte?')]) ?>
	

<?php
		echo " <b style='color:red;font-size:14px;font-family:arial ;'> Attention: La suppression du compte est définitive !  </b>"
?>
</div>
<?php
    }

?>