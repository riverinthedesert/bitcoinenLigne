<?= $this->Html->css(['ajoutGroupe']) ?>
<?php
use Cake\Datasource\ConnectionManager;
use Cake\View\Helper\FlashHelper;

$conn = ConnectionManager::get('default');

$session_active = $this->request->getAttribute('identity');
if(!is_null($session_active)){
    if (isset($_REQUEST['nom'])){
        $nom = $_REQUEST['nom'];
        $requete= $conn->prepare("SELECT nom FROM `groupe` WHERE nom='".$nom."'");
        $requete->execute();
        $check = $requete->fetch('assoc');
        if($check['nom'] == null){
            if (isset($_REQUEST['comment'])){
                $comment = $_REQUEST['comment'];
                //insertion du groupe avec commentaire
                $conn->insert('groupe', [
                    'nom' => $nom,
                    'idAdmin' => $idMembre,
                    'commentaire' => $comment ]);
                //$query = "INSERT into `groupe` (nom, idAdmin, commentaire) VALUES ('$nom', '$idMembre', '$comment')";
            }
            else{
                $conn->insert('groupe', [
                    'nom' => $nom,
                    'idAdmin' => $idMembre]);
                //$query = "INSERT into `groupe` (nom, idAdmin) VALUES ('$nom', '$idMembre')";
            }
            // On cherche l'id du créateur de cette offre
            $requete="SELECT idGroupe FROM `groupe` WHERE nom='".$nom."'";
            $reqidG = $conn->execute($requete)->fetchAll('assoc');

            $idGrp = $reqidG[0]["idGroupe"];

            //Faire une modification s'il y a des amis en plus d'ajouté
            $conn->insert('groupeMembre', [
                'idGroupe' => $idGrp,
                'idUtilisateur' => $idMembre]);
            //$query2 = "INSERT into `groupeMembre` (idGroupe, idUtilisateur) VALUES ('$idGrp', '$idAdmin')";
            
            header('Location: VisuGroupe');
            exit();
        }else{
            echo "<p id=\"erreur\">Le nom du groupe est déjà utilisé. Veuillez en choisir un autre.</p>";
        }
    }

?>

<div class="container">
	<div class="text-center">
		<h1>Nouveu bénéficiaires</h1>
	</div>
	
	<form>
		<fieldset>
			<div class="form-group">
				<label for="nom">Nom (*):</label>
				<input type="text" class="form-control" required id="nom" placeholder="Entrer nom" name="nom">
			</div>
            <br/>
			<div class="form-group">
				<label for="comment">Clé:</label>
				<textarea class="form-control" id="comment" name="comment" rows="3"></textarea>
			</div>
			
			<div class="form-group">
				<label for="favoris">Mes favoris :</label>
                <!-- Boucle pour chaque favoris.
                        Affichage de la photo !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
                        Affichage du nom et prenom
                        Affichage d'un bouton pour envoyer une notification à la personne -->
                <?php
                    $membref = 0;

                    $membreFavori="SELECT * FROM `membrefavo` WHERE idMembre=".$idMembre;
                    $mFA = $conn->execute($membreFavori)->fetchAll('assoc');


            

                            ?>
			</div>
            <div>
                <input type="submit" name="submit" value="Ajouter"/>
                <a href="VisuGroupe" role="button" class="btn btn-warning "><span class="glyphicon glyphicon-remove"></span> Annuler</a>
            </div>
        </fieldset>
	</form>
</div>

<?php
}
?>