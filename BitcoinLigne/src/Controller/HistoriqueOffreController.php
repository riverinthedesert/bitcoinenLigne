<?php
    namespace App\Controller;

    use Cake\Event\EventInterface;
    use Cake\Datasource\ConnectionManager;

    class HistoriqueOffreController extends AppController
    {
        public function index()
        {
            setlocale(LC_TIME, 'fr_FR');
            date_default_timezone_set('Europe/Paris');

            $conn = ConnectionManager::get('default');
            $this->loadComponent('Paginator');

            $id_utilisateur=$this->Authentication->getIdentity()->idMembre;

            // BASE DE LA REQUETE
            $requete = "SELECT offre.idOffre,horaireDepart,
            ville_depart.ville_nom_simple as nomVilleDepart,
            ville_arrivee.ville_nom_simple as nomVilleArrivee,
            nom,prenom,dateRecherche

            FROM offre
            INNER JOIN users ON users.idMembre=offre.idConducteur 
            INNER JOIN conducteur ON conducteur.idMembre=offre.idConducteur
            INNER JOIN historiquerecherche ON historiquerecherche.idOffre=offre.idOffre
            LEFT OUTER JOIN villes_france_free ville_depart ON offre.idVilleDepart=ville_depart.ville_id
            LEFT OUTER JOIN villes_france_free ville_arrivee ON offre.idVilleArrivee=ville_arrivee.ville_id
            WHERE historiquerecherche.idMembre =".$id_utilisateur;

            if ($this->request->getQuery("date")=="1"){
                $requete.=" ORDER BY dateRecherche DESC";
            }



            // On execute la requête
            $historique = $conn->execute($requete)->fetchAll('assoc');
            // On transmet les variables à la vue.
            $this->set(compact('historique'));
        }

        public function deleteHist(){
            $conn = ConnectionManager::get('default');
            $this->loadComponent('Paginator');
    
            $id_utilisateur=$this->Authentication->getIdentity()->idMembre;
    
            $requete="DELETE FROM historiquerecherche WHERE idMembre=".$id_utilisateur;
            $confirm = $conn->execute($requete);
    
            header('Location: ../HistoriqueOffre'); 
            exit();
        }

    }