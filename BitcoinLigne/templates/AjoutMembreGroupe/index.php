<!-- Affichage de la liste des amis de l'utilisateur
    Le menu se trouve dans layout/default.php -->

<?php
//Connection à la bdd
use Cake\Datasource\ConnectionManager;
$conn = ConnectionManager::get('default');

// Vérifier la connexion
$session_active = $this->request->getAttribute('identity');

//Vérification si un idMembre a été donné
//Si c'est le cas alors ajout d'un membre au groupe
$idMbr = $this->request->getQuery('idMembre');
$idGp = $this->request->getQuery('idGroupe');
if(!empty($idMbr) && !is_null($session_active) && !empty($idGp)){    

    //Ajout du membre au groupe
    $query = "INSERT INTO `groupemembre`(`idGroupe`, `idUtilisateur`) VALUES ('$idGp','$idMbr')";

                //$query = "SELECT * FROM `users` WHERE mail='".$_POST['mailmembre']."'";

    $conn->execute($query);
    //Message d'ajout
    echo "<div class='alert alert-success'>
        L'utilisateur a été ajouté au groupe avec <strong>Succès</strong>
    </div>";
}

//On vérifie que l'utilisateur est bien l'administrateur du groupe
$isAdmin="SELECT * FROM `groupe` WHERE idGroupe=".$idGp;
$Admin= $conn->execute($isAdmin)->fetchAll('assoc');

if(!is_null($session_active) && !empty($idGp) && $session_active->idMembre==$Admin[0]['idAdmin']){
    //Récupération du nom du groupe via son id
    $groupe="SELECT * FROM `groupe` WHERE idGroupe=".$idGp;
    $group = $conn->execute($groupe)->fetchAll('assoc');
    //Récupération des membres favoris de l'utilisateur
    $membreFavo="SELECT * FROM `membrefavo` WHERE idMembre=".$session_active->idMembre;
    $mbFav = $conn->execute($membreFavo)->fetchAll('assoc');

?>

<div class="container">
    <div class="text-center">
        <h1>Qui souhaitez vous ajouter au groupe <?php echo $group[0]['nom'];  ?> ?</h1>
    </div>

    <div class="form-add">
        <!--Ajout d'un utilisateur au groupe via adresse mail-->
        <form name="addonetogroup" method="post" <?php echo "action='AjoutMembreGroupe/confirmation?idGroupe=$idGp' "?> >
            <label for="mailmembre">Saisissez l'adresse mail de la personne que vous voulez ajouter au groupe :</label>
            <input type="text" id="mailmembre" name="mailmembre" placeholder="user@test.com" required>

            <input type="submit" value="Ajouter"><br>
        </form>

            <label for="favoris">Ou ajoutez le depuis votre liste de favoris : </label>
            <!-- Boucle affichage des favoris
            Affichage photo - nom et prénom - mail - bouton de notification -->
        
        <!--Bouton pour retourner à la liste des groupes-->
        <a href="VisuGroupe" role="button" class="btn btn-warning "><span class="glyphicon glyphicon-remove"></span> Annuler</a>
    </div>
</div>

<?php } else { //Si l'utilisateur n'est pas admin du groupe, on affiche juste un bouton retour
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