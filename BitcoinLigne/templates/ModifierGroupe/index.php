
<?php
//Connection à la bdd

use App\Controller\NotificationController;
use Cake\Datasource\ConnectionManager;
$conn = ConnectionManager::get('default');

// Vérifier la connexion
$session_active = $this->request->getAttribute('identity');

$idGp = $this->request->getQuery('idGroupe');

$isAdmin="SELECT * FROM `groupe` WHERE idGroupe=".$idGp;
$Admin= $conn->execute($isAdmin)->fetchAll('assoc');

if(!is_null($session_active) && $session_active->idMembre==$Admin[0]['idAdmin']){
    
    if (isset($_REQUEST['nom'])){
        $nom = $_REQUEST['nom'];
        if (isset($_REQUEST['comment'])){
            $comment = $_REQUEST['comment'];
            //insertion du groupe avec commentaire
            $conn->update('groupe', [
                'nom' => $nom,
                'commentaire' => $comment ],['idGroupe' => $idGp ]);
            //$query = "INSERT into `groupe` (nom, idAdmin, commentaire) VALUES ('$nom', '$idMembre', '$comment')";
        }
        else {
            
            $conn->update('groupe', [
                'nom' => $nom],
                
                ['idGroupe' => $idGp ]);
            }

            $idMembre = $session_active->idMembre;
            $nomComplet = $session_active->prenom . ' ' . $session_active->nom;
                
            /* Envoi de la notification au controller */
            $ids = array($idMembre, $nomComplet, $idGp);
            NotificationController::notifier('modifGroupe', $ids);

        header('Location: VisuGroupe');
        exit();

        }
        
?>

<div class="container">
    <div class="text-center">
        <h1>Modification du  groupe <?php echo $Admin[0]['nom'];  ?> </h1>
    </div>
</div>

<form method="post" enctype="multipart/form-data">
    <div class="form-group">
            <label>Nom (*):</label>
            <input type="text" name="nom" class="form-control" required id="nom" value="<?php echo $Admin[0]['nom'] ?>">
        </div>
        <div class="form-group">
            <label>Commentaire (optionnels):</label>
            <textarea class="form-control" id="comment" rows="3" name="comment"><?php echo $Admin[0]['commentaire'] ?> </textarea>
    </div>

<div>
    <input type="submit" name="submit" value="Modifier Groupe"/>
    <a href="VisuGroupe" role="button" class="btn btn-warning "><span class="glyphicon glyphicon-remove"></span> Annuler</a>
</div>
</form>

<?php  
} else { //Si l'utilisateur n'est pas admin du groupe, on affiche juste un bouton retour
?>
<div class="container">
    <div class="text-center">
    Vous n'êtes pas administrateur de ce groupe !
    </div>
</div>
<a href="VisuGroupe" role="button" class="btn btn-success "><span class="glyphicon glyphicon-repeat"></span> Retour</a>
<?php
}
?>


