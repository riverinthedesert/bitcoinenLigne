<?php
    namespace App\Controller;

    use Cake\Datasource\ConnectionManager;
    use Cake\ORM\TableRegistry;

    class VisuMesOffreController extends AppController
    {
        public function index()
        {
            $offre = TableRegistry::getTableLocator()->get('Offre');
            $this->set(compact('offre'));
            $users = TableRegistry::getTableLocator()->get('Users');
            $this->set(compact('users'));
            $ville = TableRegistry::getTableLocator()->get('Villes_france_free');
            $this->set(compact('ville'));
            $copassager = TableRegistry::getTableLocator()->get('Copassager');
            $this->set(compact('copassager'));
        }
    }

?>