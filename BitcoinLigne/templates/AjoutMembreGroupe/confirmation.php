<?php
//Connection à la bdd
use Cake\Datasource\ConnectionManager;
$conn = ConnectionManager::get('default');

$session_active = $this->request->getAttribute('identity');
$session_active->idMembre;

//On vérifie si l'on passe par une adresse mail ou un id utilisateur
$idm = $this->request->getQuery('idMembre');
$idg = $this->request->getQuery('idGroupe');

if(!empty($_POST["mailmembre"])) {
    //Si l'on passe par un mail on récupère l'id associée
    $query = "SELECT * FROM `users` WHERE mail='".$_POST['mailmembre']."'";
    $idmembre = $conn->execute($query)->fetchAll('assoc');
    if(!empty($idmembre)) {
        $idm = $idmembre[0]['idMembre'];
    } else { //Cas où l'adresse mail est invalide
        ?>
        <div class="container">
            <div class="text-center">
            Cette adresse est invalide ou ne correspond à personne !
            </div>
            <a <?php echo "href='../AjoutMembreGroupe?idGroupe=$idg'" ?> role="button" class="btn btn-warning "><span class="glyphicon glyphicon-repeat"></span> Retour</a>
        </div>
    <?php 
    }
}

//Vérifier que l'utilisateur n'est pas déjà dans le groupe
$query = "SELECT * FROM `groupemembre` WHERE idGroupe=".$idg;
$groupMember = $conn->execute($query)->fetchAll('assoc');
$isIn = false;
foreach($groupMember as $gm) {
    if($gm['idUtilisateur']==$idm)
        $isIn=True;
}

if ($isIn) { //Si l'utilisateur à ajouter fait déjà partie du groupe : message d'erreur
    ?>
    <div class="container">
        <div class="text-center">
        Cet utilisateur fait déjà partie du groupe !
        </div>
        <a <?php echo "href='../AjoutMembreGroupe?idGroupe=$idg'" ?> role="button" class="btn btn-warning ">
        <span class="glyphicon glyphicon-repeat"></span> Retour</a>
    </div>
    <?php 
} else if (!empty($idm) && !is_null($session_active) && !empty($idg)) { //Sinon on l'ajoute et on affiche un message de confirmation

    $groupeMembre="SELECT * FROM `users` WHERE idMembre=".$idm;
    $groupM = $conn->execute($groupeMembre)->fetchAll('assoc');

//On demande confirmation à l'utilisateur
?>
    <div class="container">
        <div class="text-center">
            <h1>Ajouter un membre au groupe :</b></h1>
        </div>
        <h4>Êtes-vous sûre de vouloir ajouter 
        <?php 
            if($groupM[0]['genre'] == "m"){
                echo 'Monsieur ';
            }
            else if($groupM[0]['genre'] == "f"){
                echo 'Madame ';
            }
            echo $groupM[0]['nom'];
            echo ' ';
            echo $groupM[0]['prenom']; ?> <b>
        <!--Boutons de validation et d'annulation-->
        <a <?php echo "href='../AjoutMembreGroupe?idGroupe=$idg&idMembre=$idm' "; ?> role="button" class="btn btn-success ">
        <span class="glyphicon glyphicon-plus"></span> Oui</a>
        
        <a <?php echo "href='../AjoutMembreGroupe?idGroupe=$idg' "; ?> role="button" class="btn btn-danger ">
        <span class="glyphicon glyphicon-repeat"></span> Non</a>

<?php 
}
?>