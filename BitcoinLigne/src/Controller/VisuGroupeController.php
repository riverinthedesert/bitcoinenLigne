<?php
    namespace App\Controller;
    use Cake\Datasource\ConnectionManager;
    use Cake\Error\Debugger;

    class VisuGroupeController extends AppController
    {
        public function index()
        {
            $conn = ConnectionManager::get('default');
            $this->loadComponent('Paginator');
            
            $session_active = $this->request->getAttribute('identity');
            if (!is_null($session_active)){
                // affichage du prénom
                $mail=$session_active->mail;
                
                $idMembre=$session_active->idMembre;


                $requete="SELECT * FROM `groupemembre` WHERE idUtilisateur=".$idMembre;
                $donnees = $conn->execute($requete)->fetchAll('assoc');
                
                //Transmission de la variable à la vue.
                $this->set(compact('donnees'));
                
            }
            
        }


        public function quitterGroupe($idGroupe)
        {
            // on récupère l'id de l'utilisateur a supprimé
            $session_active = $this->request->getAttribute('identity');
            $idUser = $session_active->idMembre;

            //si l'utilisateur a bien confirmé qu'il voulait quitter le groupe
            if (isset($_POST['Oui'])) {
                //on supprime l'utilisateur dans le groupe choisi
                $conn = ConnectionManager::get('default');
                $requete=$conn->prepare("DELETE FROM `groupemembre` WHERE idUtilisateur=".$idUser." AND idGroupe='".$idGroupe."'");
                $requete->execute();

            
                /* Partie notifications */
                $nomComplet = $session_active->prenom . ' ' . $session_active->nom;
                    
                /* Envoi de la notification au controller */
                $ids = array($idUser, $nomComplet, $idGroupe);
                NotificationController::notifier('quitterGroupePrive', $ids);

                
                $this->Flash->success(__('Vous avez bien quitté le groupe.'));

                /* Redirection de l'utilisateur sur sa page de groupe */
                return $this->redirect(['controller' => 'VisuGroupe', 'action' => 'index']);

            }else if(isset($_POST['Non'])){
                
                /* Si il ne veut pas quitter le groupe, on le renvoit juste sur la page des groupes */
                return $this->redirect(['controller' => 'VisuGroupe', 'action' => 'index']);
            }

            
        }

        public function supprimerGroupe($idGroupe){

            /* on récupère l'id de l'utilisateur qui veut supprimer le groupe */
            $session_active = $this->request->getAttribute('identity');
            $idUser = $session_active->idMembre;
            $conn = ConnectionManager::get('default');

            /* on récupère l'id de l'admin dans $check */
            $requete= $conn->prepare("SELECT idAdmin FROM `groupe` WHERE idGroupe='".$idGroupe."'");
            $requete->execute();
            $check = $requete->fetch('assoc');

            /* On regarde si l'utilisateur est l'administrateur du groupe */
            if($check['idAdmin'] == $idUser){
                /* si l'utilisateur a bien confirmé qu'il voulait quitter le groupe */
                if (isset($_POST['Oui'])) {

                    /* Suppression du groupe de la table groupemembre */
                    $requete= $conn->prepare("DELETE FROM `groupemembre` WHERE  idGroupe='".$idGroupe."'");
                    $requete->execute();

                    /* Suppression du groupe de la table groupe*/ 
                    $requete= $conn->prepare("DELETE FROM `groupe` WHERE  idGroupe='".$idGroupe."'");
                    $requete->execute();

                    /* Partie notifications */
                    $nomComplet = $session_active->prenom . ' ' . $session_active->nom;
                        
                    /* Envoi de la notification au controller */
                    $ids = array($idUser, $nomComplet, $idGroupe);
                    NotificationController::notifier('quitterGroupePrive', $ids);

                    
                    $this->Flash->success(__('Vous avez bien supprimé le groupe.'));
                    /* Redirection de l'utilisateur sur sa page de groupe */
                    return $this->redirect(['controller' => 'VisuGroupe', 'action' => 'index']);
                }else if(isset($_POST['Non'])){
                    
                    /* Si il ne veut pas supprimer le groupe, on le renvoit juste sur la page des groupes */
                    return $this->redirect(['controller' => 'VisuGroupe', 'action' => 'index']);
                }

            }else{
                $this->Flash->error(__('Vous ne pouvez pas faire ça, vous n\'êtes pas l\'administrateur du groupe.'));
                return $this->redirect(['controller' => 'VisuGroupe', 'action' => 'index']);
            }
        }

        public function creerTrajet()
        {
            $redirect = $this->request->getQuery('redirect', [
				'controller' => 'AjouterOffrePrivee',
				'action' => 'index',
			]);

			return $this->redirect($redirect);

            return $this->redirect(['action' => 'index']);
        }
    }
