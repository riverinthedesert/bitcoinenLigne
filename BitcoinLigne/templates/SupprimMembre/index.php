<?php

use App\Controller\NotificationController;
use Cake\Datasource\ConnectionManager;
$conn = ConnectionManager::get('default');

$session_active = $this->request->getAttribute('identity');

//Vérification si un idMembre a été donné
//Si c'est le cas alors suppression de ce membre du groupe
$idMbr = $this->request->getQuery('idMembre');
$idGp = $this->request->getQuery('idGroupe');
if(!empty($idMbr) && !is_null($session_active) && !empty($idGp)){    

    //Supprimer le membre du groupe
    $conn->delete('groupemembre', ['idUtilisateur' => $idMbr,
                                    'idGroupe' => $idGp]);
        //$query = "DELETE FROM `groupemembre` WHERE idUtilisateur='".$_GET['idMembre']."' AND idGroupe='".$_GET['idGroupe']."'";
    
    //Lancer la requete sur la bdd
    //$res = mysqli_query($conn, $query);

    /* Partie notification */
    $idMembre = $session_active->idMembre;
    $nomComplet = $session_active->prenom . ' ' . $session_active->nom;
        
    /* Envoi de la notification au controller */
    $ids = array($idMembre, $nomComplet, $idGp);
    NotificationController::notifier('supprimerMembreGroupe', $ids);

        echo "<div class='alert alert-success'>
            L'utilisateur a été retiré du groupe avec <strong>Succes</strong>
        </div>";
}


if(!is_null($session_active) && !empty($idGp)){
        $groupe="SELECT * FROM `groupe` WHERE idGroupe=".$idGp;
        $group = $conn->execute($groupe)->fetchAll('assoc');

        $groupeMembre="SELECT * FROM `groupemembre` WHERE idGroupe=".$idGp;
        $groupM = $conn->execute($groupeMembre)->fetchAll('assoc');
    ?>

    <div class="container">
        <div class="text-center">
            <h1>Supprimer un membre du groupe : <br><b><?php echo $group[0]['nom'];  ?></b></h1>
        </div>
        <h4>Qui souhaitez-vous retirer de votre groupe privé ?</h4>
                
    </div>



<?php
    $i = 0;
    foreach($groupM as $GM){
        $membre="SELECT * FROM `users` WHERE idMembre =".$GM['idUtilisateur'];
        $me = $conn->execute($membre)->fetchAll('assoc');
        
        foreach($me as $m){
            $mail=$session_active->mail;
            if($m['mail'] != $mail){
                $idG = $_GET['idGroupe'];
                $idM = $m['idMembre'];
    
                if($i == 0){
                    $i++;
?>              <div class="row">
                        <div class="col-sm-4">
                            <label for="nom" ><?php echo $m['nom']; echo ' '; echo $m['prenom']; ?></label>
                            <a <?php echo "href='SupprimMembre/confirmation?idGroupe=$idG&idMembre=$idM' "; ?> role="button" class="btn btn-danger "><span class="glyphicon glyphicon-trash"></span> Supprimer</a>
                        </div>
<?php
                }
                else if($i == 3){
                    $i = 0;
?>                  <div class="col-sm-4">
                        <label for="nom" ><?php echo $m['nom']; echo ' '; echo $m['prenom']; ?></label>
                            <a <?php echo "href='SupprimMembre/confirmation?idGroupe=$idG&idMembre=$idM' "; ?> role="button" class="btn btn-danger "><span class="glyphicon glyphicon-trash"></span> Supprimer</a>
                        </div>
                    </div>
                    <br>
                    <br>
<?php       }
                else{
                    $i++;
?>
                    <div class="col-sm-4">
                        <label for="nom" ><?php echo $m['nom']; echo ' '; echo $m['prenom']; ?></label>
                        <a <?php echo "href='SupprimMembre/confirmation?idGroupe=$idG&idMembre=$idM' "; ?> role="button" class="btn btn-danger "><span class="glyphicon glyphicon-trash"></span> Supprimer </a>
                    </div>
<?php
                }
            }
        }
    }
    if($i == 3){

    ?> 
    </div>       
<?php
    }}
?>

<div class="text-right">
        <a href='VisuGroupe' role="button" class="btn btn-success "><span class="glyphicon glyphicon-repeat"></span> Retour</a>
    </div>


