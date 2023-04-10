<!DOCTYPE html>
<html lang="fr">

<head>
	<title>Se connecter</title>
</head>

<?php
use Cake\Datasource\ConnectionManager;
use Cake\View\Helper\FlashHelper;

$conn = ConnectionManager::get('default');
$session_active = $this->request->getAttribute('identity');
    
$AfficherFormulaire=1;

$session_active = $this->request->getAttribute('identity');
if(!is_null($session_active)){
        if(!isset($_POST['villeDepart']) || !isset($_POST['dateD']) || !isset($_POST['dateA'])){
			$AfficherFormulaire=1;
		}
		else{
			$vD = $_POST['villeDepart'];
			$vA = $_POST['villeArrivee'];


			$requete="SELECT * FROM `villes_france_free` WHERE ville_nom_reel='".$vD."'";
			$reqidG = $conn->execute($requete)->fetchAll('assoc');

			$reqidGB = $conn->execute("SELECT * FROM `villes_france_free` WHERE ville_nom_reel='".$vA."'")->fetchAll('assoc');


			//Date de départ
			setlocale(LC_TIME, 'fra_fra');

			//echo $_POST["dateD"];

			$date = date_create($_POST['dateD']);
			$dateA = date_create($_POST['dateA']);

			$_POST['dateD'] = date_format($date, 'Y-m-d H:i:s');
			$_POST['dateA'] = date_format($dateA, 'Y-m-d H:i:s');

			$jourD = date_format($date, 'd');
			$jourA = date_format($dateA, 'd');

			$moisD = date_format($date, 'm');
			$moisA = date_format($dateA, 'm');

			$anneeD = date_format($date, 'Y');
			$anneeA = date_format($dateA, 'Y');

			$heureD = date_format($date, 'H');
			$heureA = date_format($dateA, 'H');

			
			if(empty($reqidG)){
				?>
					<div class="alert alert-danger" role="alert">
						<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
						<span class="sr-only">Error:</span>
						La ville de départ n'existe pas !
					</div><?php
				//echo "La ville de départ n'existe pas !\n";
				$AfficherFormulaire=1;
			}
			elseif(empty($reqidGB))
			{
				?>
					<div class="alert alert-danger" role="alert">
						<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
						<span class="sr-only">Error:</span>
						La ville de d'arrivée n'existe pas !
					</div><?php
				//echo "La ville de d'arrivée n'existe pas !\n";
				$AfficherFormulaire=1;
            }
            else{
				if($anneeD > $anneeA){
					?>
					<div class="alert alert-danger" role="alert">
						<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
						<span class="sr-only">Error:</span>
						Vous ne pouvez pas arriver avant d'être parti !
					</div><?php
					//echo "Vous ne pouvez pas arriver avant d'être parti !";
					$AfficherFormulaire=1;
				}
				else if($anneeD == $anneeA){
					if($moisD > $moisA){
						?>
					<div class="alert alert-danger" role="alert">
						<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
						<span class="sr-only">Error:</span>
						Vous ne pouvez pas arriver avant d'être parti !
					</div><?php
						//echo "Vous ne pouvez pas arriver avant d'être parti !";
						$AfficherFormulaire=1;
					}
					else if($moisD == $moisA){
						if($jourD > $jourA){?>
							<div class="alert alert-danger" role="alert">
								<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
								<span class="sr-only">Error:</span>
								Vous ne pouvez pas arriver avant d'être parti !
							</div><?php
							//echo "Vous ne pouvez pas arriver avant d'être parti !";
							$AfficherFormulaire=1;
						}
						else if($jourD == $jourA){
							if($heureD > $heureA){
								?>
								<div class="alert alert-danger" role="alert">
									<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
									<span class="sr-only">Error:</span>
									Vous ne pouvez pas arriver avant d'être parti !
								</div><?php
								//echo "Vous ne pouvez pas arriver avant d'être parti !";
								$AfficherFormulaire=1;
							}
							else{
								$AfficherFormulaire=0;
							}
						}
						else{
							$AfficherFormulaire=0;
						}
					}
					else{
						$AfficherFormulaire=0;
					}
				}
				else{
					$AfficherFormulaire=0;
				}
			}

            //Verification des etapes :
            if(!empty($_POST['Etape1'])){
                
                $vE1 = $_POST['Etape1'];

			    $vileE = $conn->execute("SELECT * FROM `villes_france_free` WHERE ville_nom_reel='".$vE1."'")->fetchAll('assoc');

                if(empty($vileE)){?>
					<div class="alert alert-danger" role="alert">
						<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
						<span class="sr-only">Error:</span>
						La ville de l'Etape 1 n'existe pas !
					</div><?php
                    //echo "La ville de l'Etape 1 n'existe pas !\n";
                    $AfficherFormulaire=1;
                }
            }
            if(!empty($_POST['Etape2'])){
                
                $vE2 = $_POST['Etape2'];

			    $vileE2 = $conn->execute("SELECT * FROM `villes_france_free` WHERE ville_nom_reel='".$vE2."'")->fetchAll('assoc');

                if(empty($vileE2)){?>
					<div class="alert alert-danger" role="alert">
						<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
						<span class="sr-only">Error:</span>
						La ville de l'Etape 2 n'existe pas !
					</div><?php
                    //echo "La ville de l'Etape 2 n'existe pas !\n";
                    $AfficherFormulaire=1;
                }
            }
            if(!empty($_POST['Etape3'])){
                $vE3 = $_POST['Etape1'];

			    $vileE3 = $conn->execute("SELECT * FROM `villes_france_free` WHERE ville_nom_reel='".$vE3."'")->fetchAll('assoc');

                if(empty($vile3)){?>
					<div class="alert alert-danger" role="alert">
						<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
						<span class="sr-only">Error:</span>
						La ville de l'Etape 3 n'existe pas !
					</div><?php
                    //echo "La ville de l'Etape 3 n'existe pas !\n";
                    $AfficherFormulaire=1;
                }
            }

		}
		
		
	
    if($AfficherFormulaire==1){
        setlocale(LC_TIME, 'fra_fra');

        $jour = date("d") + 1;
        $mois = date("m");
        $anne = date("Y");
?>
<div class="text-center">
		<h1>Ajouter un solde au bénéficiaire</h1>
		<br/>
		<h3><?php 
		
		$idGroupe = $_GET['idGroupe'];

		$res = $conn->query("SELECT nom FROM groupe  
                                WHERE groupe.idGroupe = '$idGroupe'");

        foreach ($res as $r)
            $nomGroupe = $r['nom'];
		
		echo "Le bénéficiaire est \"$nomGroupe\" <br/>";?></h3>

		<br/>
		<br/>
		<br/>
</div>
<form method="post" action="AjouterUneOffre">
	<div class="form-row">
		

		<div class="form-group">
				<label for="nbPassager"> Commentaires * </label>
				<input name="nbPassager" id="nbPassager" placeholder="Commentaires" type="text" class="form-control" required>
		</div>

		<div class="form-group">
				<label for="comment"> Montant * </label>
				<input name="comment" id="prix" placeholder="Montant" type="number" class="form-control" required>
		</div>

		<div class="form-group">
				<label for="comment"> Frais * </label>
				

				<form>
				<select id="mySelect">
					<option>0,03 (très rapide)</option>
					<option>0,02 (rapide)</option>
					<option>B,01 (normal)</option>
				</select>
				</form>
		</div>

	</div>
	<button type="submit" class="btn btn-primary">Ajouter</button>
	<br/><br/>
	* Champs obligatoires !
</form>

</html>

<?php
	}
	else{
		$vD = $_POST['villeDepart'];
		$vA = $_POST['villeArrivee'];
	
		$reqidG = $conn->execute("SELECT * FROM `villes_france_free` WHERE ville_nom_reel='".$vD."'")->fetchAll('assoc');
	
		$reqidGB = $conn->execute("SELECT * FROM `villes_france_free` WHERE ville_nom_reel='".$vA."'")->fetchAll('assoc');
	
		$c = "";
		if(!empty($_POST['comment']))
			$c = $_POST['comment'];

		$nbP = "1";
		if(!empty($_POST['nbPassager']))
			$nbP = $_POST['nbPassager'];

		$pr = "1";
		if(!empty($_POST['prix']))
			$pr = $_POST['prix'];
			
		$Lieu = "1";
		if(!empty($_POST['prdv']))
			$Lieu = $_POST['prdv'];

        
        
	
		$conn->insert('offre', [
			'horaireDepart' => $_POST['dateD'] ,
			'horaireArrivee' => $_POST['dateA'] ,
			'nbPassagersMax' => $nbP ,
			'idVilleDepart' => $reqidG[0]['ville_id'] ,
			'idVilleArrivee' => $reqidGB[0]['ville_id'] ,
			'idConducteur' => $session_active->idMembre , //id de la personne qui ajoute l'offre
			'prix' => $pr ,
			'idEtape' => 'NULL' ,
			'idGroupe' => $idGroupe ,
			'estPrivee' => 1 ,
			'precisionLieu' => $Lieu ,
			'commentaire' => $c 
			]);
		//$query2 = "INSERT into `groupeMembre` (idGroupe, idUtilisateur) VALUES ('$idGrp', '$idAdmin')";

        $idOffre = $conn->execute("SELECT * FROM `offre`")->fetchAll('assoc');
        $i = 0;
        $idOf = 0;
        foreach($idOffre as $idO){
            $i++;
        }
        if( $idOffre[$i-1]['idOffre'] != NULL)
            $idOf = $idOffre[$i-1]['idOffre'];

            //Vérifier les etapes
        //INSERT INTO `etape`(`idOffre`, `idEtape`, `idVille`) VALUES ('[value-1]','[value-2]','[value-3]')
        if(!empty($_POST['Etape1'])){
                
            $vE1 = $_POST['Etape1'];

            $vileE = $conn->execute("SELECT * FROM `villes_france_free` WHERE ville_nom_reel='".$vE1."'")->fetchAll('assoc');

            $e = $vileE[0]['ville_id'];
            echo $e;
            if($e != NULL)
                $conn->insert('etape', [
                    'idOffre' =>  $idOf ,
                    'idVille' => $e
                    ]);
        }
        if(!empty($_POST['Etape2'])){
            
            $vE2 = $_POST['Etape2'];

            $vileE2 = $conn->execute("SELECT * FROM `villes_france_free` WHERE ville_nom_reel='".$vE2."'")->fetchAll('assoc');
            $e = $vileE2[0]['ville_id'];

            echo $e;
            if($e != NULL)
                $conn->insert('etape', [
                    'idOffre' =>  $idOf ,
                    'idVille' => $e
                ]);
        }
        if(!empty($_POST['Etape3'])){
            
            $vE3 = $_POST['Etape1'];

            $vileE3 = $conn->execute("SELECT * FROM `villes_france_free` WHERE ville_nom_reel='".$vE3."'")->fetchAll('assoc');

            $e = $vileE3[0]['ville_id'];

            echo $e;
            if($e != NULL)
                $conn->insert('etape', [
                    'idOffre' =>  $idOf ,
                    'idVille' => $e
                    ]);
        }

		
        header('Location: VisuOffre'); 
        exit();

	}
}

?>

