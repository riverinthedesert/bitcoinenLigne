<?php
    namespace App\Controller;
use Cake\Datasource\ConnectionManager;

    class MembreFavoController extends AppController
    {
		

        public function index()
        {
			$membreFavo = $this->paginate($this->MembreFavo);
			$this->set(compact('membreFavo'));
        }
		
		
		
		public function add($idMembre = null,$idMembreFavo = null)
		{
			$redirect = $this->request->getQuery('redirect', [
				'controller' => 'VisuProfil',
				'action' => 'index',
			]);
            $membreFavo = $this->MembreFavo->newEmptyEntity();
			if ($this->request->is('post')) {
                
                $membreFavo = $this->MembreFavo->patchEntity($membreFavo, array(
                    "idMembre" => $idMembre,
                    "idMembreFavo" => $idMembreFavo,
                ));
                
                if ($this->MembreFavo->save($membreFavo)) {
                    $this->Flash->success(__('The user has been saved.'));
                    return $this->redirect($redirect);
                }
                $this->Flash->error(__('The user could not be saved. Please, try again.'));
                return $this->redirect($redirect);
			}
            $this->set(compact('membreFavo'));
		}
        

        public function quit()
		{
			$redirect = $this->request->getQuery('redirect', [
				'controller' => 'VisuProfil',
				'action' => 'index',
			]);
            $this->Flash->error(__('You quit the demande'));
            return $this->redirect($redirect);
        }
    }

?>