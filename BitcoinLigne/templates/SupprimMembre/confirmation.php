<?php
use Cake\Datasource\ConnectionManager;
$conn = ConnectionManager::get('default');

$session_active = $this->request->getAttribute('identity');

//Vérification si un idMembre a été donné
//Si c'est le cas alors suppression de ce membre du groupe
$idm = $this->request->getQuery('idMembre');
$idg = $this->request->getQuery('idGroupe');
if(!empty($idm) && !is_null($session_active) && !empty($idg)){
    
    $groupe="SELECT * FROM `groupe` WHERE idGroupe=".$idg;
    $group = $conn->execute($groupe)->fetchAll('assoc');

    $groupeMembre="SELECT * FROM `users` WHERE idMembre=".$idm;
    $groupM = $conn->execute($groupeMembre)->fetchAll('assoc');

    ?>

    <div class="container">
        <div class="text-center">
            <h1>Supprimer un membre du groupe : <br><b><?php echo $group[0]['nom'];  ?></b></h1>
        </div>
        <h4>Êtes-vous sûre de vouloir supprimer <b><?php 
            if($groupM[0]['genre'] == "m"){
                echo 'Monsieur ';
            }
            else if($groupM[0]['genre'] == "f"){
                echo 'Madame ';
            }
            echo $groupM[0]['nom'];
            echo ' ';
            echo $groupM[0]['prenom'];
           ?></b> de votre groupe ? </h4>

        <div class="row">
            <div class="col-sm-6"> 
                <div class="text-center">    
                    <a <?php echo "href='../SupprimMembre?idGroupe=$idg&idMembre=$idm' "; ?> role="button" class="btn btn-success "><span class="glyphicon glyphicon-trash"></span> Oui</a>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="text-center">
                    <a <?php echo "href='../SupprimMembre?idGroupe=$idg' "; ?> role="button" class="btn btn-danger "><span class="glyphicon glyphicon-repeat"></span> Non</a>
                </div>
            </div>
        </div>
    </div>

<?php
    }
?>
