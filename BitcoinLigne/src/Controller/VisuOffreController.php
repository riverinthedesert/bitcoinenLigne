<?php
    namespace App\Controller;

    use Cake\Datasource\ConnectionManager;
    use Cake\ORM\TableRegistry;
use DateTime;

class VisuOffreController extends AppController
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

        public function delete($idOffre) {
            /* Autorisation de supprimer */
            $this->request->allowMethod(['post', 'delete']);

            $this->loadModel('Offre');

            $offer = $this->Offre->findByIdoffre($idOffre)->firstOrFail();

            /* Récupération de la date actuelle et de la date de départ de l'offre pour les comparer */
            $today = date('Y-m-d H:i:s');
            $offerbdd = $offer->horaireDepart;

            $dateDifference = date_diff($offerbdd, date_create($today));

            /* Si l'offre commence dans plus de 24h, on la supprime, sinon retour avec un message d'erreur */
            if ($dateDifference->days >= 1) {

                if ($this->Offre->delete($offer)) {
                    $this->Flash->success('L\'offre a été supprimée');
    
                    /* Récupération des données de l'utilisateur connecté */
                    $session_active = $this->Authentication->getIdentity();
    
                    $idMembre = $session_active->idMembre;
                    $nomComplet = $session_active->prenom . ' ' . $session_active->nom;
                        
                    /* Envoi de la notification au controller */
                    $ids = array($idMembre, $nomComplet, $offer->idOffer);
                    NotificationController::notifier('annulerOffre', $ids); 
                    
                    return $this->redirect(['action' => 'index']);
                }

            } else {
                $this->Flash->error('L\'offre ne peut pas être supprimée moins de 24h avant sa date de départ.');
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error('L\'offre n\'a pas pu être modifiée.');
            return $this->redirect(['action' => 'index']);
        }

    }
?>