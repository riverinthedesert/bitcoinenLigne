<!-- Affichage des différents groupes
    Le menu se trouve dans layout/default.php -->

<?= $this->Html->css(['ouiounon']) ?>
<?php
	use Cake\Datasource\ConnectionManager;
	$conn = ConnectionManager::get('default');

	$session_active = $this->request->getAttribute('identity');
	if(!is_null($session_active)){
?>

<div class="container">
	<div class="text-center">
		<h1>Mes bénéficiaires</h1>
	</div>
	
	<!-- Boutton pour ajouter un groupe d'ami !-->
	<a href="AjoutGroupe" role="button" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span>  Ajouter un nouveau bénéficiaires </a>

	<p1>
	<?php
		$admOuMembr = 0;
		
		foreach($donnees as $don){
			$admOuMembr++;
			$tabl[] = $don['idGroupe'];
		}



		$idMembre = $this->request->getAttribute('identity')->idMembre;
	?>


		

	</p1>

    <?php
		// Recherche dans la BDD des groupes
		
		
		if($admOuMembr == 0){ 
	?>
			<div class="text-center">
			<br><br>
				<div class="p-3 mb-2 bg-info text-white">Aucun groupe d'amis créé !</div>
			</div>
	<?php	
		}
		else{
			
	?>
	
	<table>
	<!-- Sinon :  -->
		<!-- Boucle pour de :  -->
		<?php 

			$i = 0;

			foreach($tabl as $element){
				if($element != 0){

					if ($i % 2 == 0)
						echo "<tr>";

					echo "<td style='vertical-align:top'>";
					$i++;

					$name="SELECT * FROM `groupe` WHERE idGroupe=".$element;
					$nom = $conn->execute($name)->fetchAll('assoc');

					// on cherche l'admin du groupe
					$res = $conn->query("SELECT idAdmin FROM groupe 
					WHERE idGroupe = '$element'");

					foreach ($res as $r)
						$idAdmin = $r['idAdmin'];
					

					/*$name = $conn->query("SELECT * FROM `groupe` WHERE idGroupe='".$element."'");
					$nom = $name->fetch_assoc();*/
			?>
					<p><br></p>
					
					<div class="panel panel-default" style="width:80%" id="main">
						<!-- Entete :  -->
						<div class="panel panel-success">
							<div class="panel-heading">
							
								<?php 

									$nomGroupe = $nom[0]['nom'];
									echo "<span style='color: roaylblue'><strong>$nomGroupe</strong></span>";
		
								?>
								<div class="pull-right">
									<div class="dropdown">
										<button class="btn btn-default dropdown-toggle" type="button" id="menu1" data-toggle="dropdown">
											<span class="glyphicon glyphicon-menu-hamburger"></span>
										</button>
										<ul class="dropdown-menu" role="menu">
											<li role="presentation"><a role="menuitem" tabindex="-1" <?php echo "href='ModifierGroupe?idGroupe=$element' "?> ><span class="glyphicon glyphicon-pencil"></span>  Modifier le bénéficiaire</a></li>
											<li role="presentation" class="divider"></li>
											<li role="presentation"><a role="menuitem" tabindex="-1" href=<?php echo "/BitcoinLigne/BitcoinLigne/VisuGroupe/supprimerGroupe/$element"?>><p style="color:#FF0000";>Supprimer le bénéficiaire</p></a></li>
										</ul>
									</div>
								</div>

								<?= "<a role='button' style='float: right' class='btn btn-success'
                        href = 'ajouter-offre-privee?idGroupe=$element'>Créer un nouveau solde</a>" ?>


							</div>
						</div>
						<!-- Contenu du panneau : -->
						
						<!-- Commentaire donné (optionnel) -->
						<div class="panel-footer">
							<?php 
								if($nom[0]['commentaire'] != 'NULL')
									echo $nom[0]['commentaire']; 
							?> 
						</div>
					</div> 
		<?php
				echo "</td>";

				if ($i % 2 == 0)
					echo "</tr>";
				

				}
			}
		}
	?>
</table>
</div>
<?php
	}
	else{
		//Retour à la page de connexion
	}
?>
