<?php
    namespace App\Controller;

    use App\Controller\AppController;
    use Cake\Datasource\ConnectionManager;

    

    class AjouterNoteController extends AppController
    {
    	 public function index()
    {
    	if(isset($_POST['note']))
        {
        	$conn = ConnectionManager::get('default');

   			$session_active = $this->request->getAttribute('identity');
   			//Récupération de l'identifiant de l'utilisateur en cours
   			$idM = $session_active->idMembre;
    		//Récupération de l'identifiant de l'utilisateur en cours
        	$requeteNote= "INSERT INTO notation values (".$_GET['idOffre'].",".$idM.",".$_POST['note'].",5)";
        	$note = $conn->execute($requeteNote);
        	header('Location: Offre');
        	exit();
        	
        }	

    }

    }
?>