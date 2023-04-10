<?php
    namespace App\Controller;

use App\Model\Entity\MembreFavo;
use Cake\Datasource\ConnectionManager;

    class VisuProfilController extends AppController
    {
		

        public function index()
        {
			$visuProfil = $this->paginate($this->VisuProfil);
			$this->set(compact('visuProfil'));
        }
		
		
		public function view(){
			$this->Form->postButton(__('button1'), ['action' => 'supprimer']);
			$this->Form->postButton(__('button1'), ['action' => 'modifPass']);
			$this->Form->postButton(__('button1'), ['action' => 'modifInfos']);

		}
		
		public function supprimer(){

		}
		
		public function modifPass(){

		}
		
		public function confirmation()
		{
	
		}
		
		public function confirmInfos()
		{

		}
		

			
		
		public function modifInfos()
		{
	
		}
		public function ajouterFavo($idMembre = null,$idMembreFavo = null)
		{
			if ($this->request->is('post')) {
				$redirect = $this->request->getQuery('redirect', [
					'controller' => 'MembreFavo',
					'action' => 'add',
				]);
				return $this->redirect($redirect);

			}else{
				$this->Flash->error(__('The user could not be saved. Please, try again.'));
			}
		}
		
		 public function deconnexion()
		{
			// on vérifie que la session de l'utilisateur est toujours active
			$session_active = $this->request->getAttribute('identity');
    
			if (!is_null($session_active)){

				$prenom = $session_active->prenom;

				// déconnexion
				$this->Authentication->logout();
				
				// message de confirmation de la déconnexion
				$this->Flash->success(__('À bientôt ' . $prenom .' !'));

				// redirection vers la page d'accueil
				return $this->redirect(['controller' => 'Accueil', 'action' => 'index']);
			}
		}
			
    }
	
?>