<?php
    namespace App\Controller;
    use Cake\Datasource\ConnectionManager;

    class AjoutGroupeController extends AppController
    {
        public function index()
        {
            $conn = ConnectionManager::get('default');
            $this->loadComponent('Paginator');

            $session_active = $this->request->getAttribute('identity');
            if (!is_null($session_active)){
                
                $idMembre=$session_active->idMembre;
                $mail=$session_active->mail;

                $requete="SELECT * FROM `groupemembre` WHERE idUtilisateur=".$idMembre;
                $donnees = $conn->execute($requete)->fetchAll('assoc');
                
                //Transmission de la variable à la vue.
                $this->set(compact('idMembre'));
                $this->set(compact('mail'));
                
            }
        }
    }
?>