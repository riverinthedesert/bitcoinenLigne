<?php 
use Cake\Datasource\ConnectionManager;
$conn = ConnectionManager::get('default');

$session_active = $this->request->getAttribute('identity');

//Enregistrement des identifiant admin et favoris a suppr
$ida = $this->request->getQuery('idA');
$idf = $this->request->getQuery('idF');
if(!empty($ida) && !is_null($session_active) && !empty($idf)){

    $conn->delete('membrefavo', ['idMembre' => $ida,
    'idMembreFavo' => $idf]);

    header('Location: ../VisuFavo');
        exit();
}
?>