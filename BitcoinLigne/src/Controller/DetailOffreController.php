<?php
    namespace App\Controller;

    use Cake\Event\EventInterface;
    use Cake\Datasource\ConnectionManager;

    class DetailOffreController extends AppController
    {
		
		
		public function editer()
		{

		}
		
		public function confirmEdit()
		{

		}

        public function index()
        {
            setlocale(LC_TIME, 'fr_FR');
            date_default_timezone_set('Europe/Paris');
    
            $conn = ConnectionManager::get('default');
            $this->loadComponent('Paginator');
    
    
            $test_offre = $this->request->getQuery("idOffre");
    
            $id_utilisateur=$this->Authentication->getIdentity()->idMembre;
    
            // BASE DE LA REQUETE
            $requete = "SELECT offre.idOffre,horaireDepart,horaireArrivee,nbPassagersMax,
            ville_depart.ville_nom_simple as nomVilleDepart,ville_arrivee.ville_nom_simple as nomVilleArrivee,nom,prenom
            ,prix,users.noteMoyenne as note,idConducteur
            FROM offre
            INNER JOIN users ON users.idMembre=offre.idConducteur 
            INNER JOIN conducteur ON conducteur.idMembre=offre.idConducteur
            LEFT OUTER JOIN villes_france_free ville_depart ON offre.idVilleDepart=ville_depart.ville_id
            LEFT OUTER JOIN villes_france_free ville_arrivee ON offre.idVilleArrivee=ville_arrivee.ville_id
            WHERE offre.idOffre=";
    
            $requete2 = "SELECT * FROM etape,villes_france_free WHERE etape.idVille=villes_france_free.ville_id AND idOffre=";
    
    
            if ($test_offre != "") { // On rajoute l'id dans le where
                $requete .= $test_offre;
                $requete2 .= $test_offre;
            } else {
                die("");
            }
    
            // On execute la requête
            $offre = $conn->execute($requete)->fetchAll('assoc');
            $etape = $conn->execute($requete2)->fetchAll('assoc');
    
            $requete3 = "SELECT * FROM notification WHERE idOffre=".$test_offre." AND idExpediteur=".$id_utilisateur;
    
            $notif = $conn->execute($requete3)->fetchAll('assoc');
    
            if (empty($notif)){
                $notif_test=0;
            }else $notif_test=1;
            
    
    
            // On transmet les variables à la vue.
            $this->set(compact('offre'));
            $this->set(compact('etape'));
            $this->set(compact('notif_test'));
    
            if ($id_utilisateur>=0){
                $historique="INSERT INTO historiquerecherche VALUES (".$id_utilisateur.",".$test_offre.",NOW())";
                $hist = $conn->execute($historique);
            }
        }

        public function noParticiper(){
            $conn = ConnectionManager::get('default');
            $this->loadComponent('Paginator');
            
            $id_utilisateur=$this->Authentication->getIdentity()->idMembre; // A CHANGE (à remplacer par $_session['id'])
    
            $offre_id = $this->request->getQuery("id"); // GET message
            
            // On cherche l'id du créateur de cette offre
            $requete="SELECT * FROM conducteur,offre WHERE conducteur.idMembre=idConducteur AND offre.idOffre=".$offre_id;
            $requete_offre = $conn->execute($requete)->fetchAll('assoc');
    
            $id_createur_offre = $requete_offre[0]["idMembre"]; // ID CREATEUR OFFRE
    
            $requete="SELECT MAX(idNotification+1) as id FROM notification";
            $notif2 = $conn->execute($requete)->fetchAll('assoc');
            $id=$notif2[0]["id"];
    
            if ($id=="") $id="0"; // ID NEW NOTIF
    
            $requete="DELETE FROM notification WHERE idOffre=".$offre_id." AND idMembre=".$id_createur_offre." AND idExpediteur=".$id_utilisateur;
            $notif = $conn->execute($requete);
    
            $requete="INSERT INTO notification VALUES (".$id_utilisateur.",'Vous avez annulé votre demande de participation !',0,0,".$id.",".$offre_id.",NOW(),NULL)";
            $notif = $conn->execute($requete);
    
            //echo 1;
        }
    
        public function participer(){
            $conn = ConnectionManager::get('default');
            $this->loadComponent('Paginator');
            
            $id_utilisateur=$this->Authentication->getIdentity()->idMembre; // A CHANGE ()
    
            $requete="SELECT * FROM users WHERE idMembre=".$id_utilisateur;
            $requete_id = $conn->execute($requete)->fetchAll('assoc');
    
            $prenom = $requete_id[0]["prenom"];
            $nom = $requete_id[0]["nom"];
    
            $offre_id = $this->request->getQuery("id"); // GET message
            
            // Id nouvelle notif
            $requete="SELECT MAX(idNotification+1) as id FROM notification";
            $notif2 = $conn->execute($requete)->fetchAll('assoc');
            $id=$notif2[0]["id"];
            if ($id=="") $id="0";
    
            // On cherche l'id du créateur de cette offre
            $requete="SELECT * FROM conducteur,offre WHERE conducteur.idMembre=idConducteur AND offre.idOffre=".$offre_id;
            $requete_offre = $conn->execute($requete)->fetchAll('assoc');
    
            $id_createur_offre = $requete_offre[0]["idMembre"];
            
            $requete="INSERT INTO notification VALUES (".$id_utilisateur.", 'Vous avez fait une demande de participation',0,0,".$id.",".$offre_id.",NOW(),NULL)";
            $notif = $conn->execute($requete);
    
            // Id nouvelle notif
            $requete="SELECT MAX(idNotification+1) as id FROM notification";
            $notif2 = $conn->execute($requete)->fetchAll('assoc');
            $id=$notif2[0]["id"];
            if ($id=="") $id="0";
    
            $requete="INSERT INTO notification VALUES (".$id_createur_offre.", 'L\'utilisateur ".ucfirst($nom)." ".ucfirst($prenom)." veut rejoindre votre trajet',0,1,".$id.",".$offre_id.",NOW(),".$id_utilisateur.")";
            $notif = $conn->execute($requete);
    
            //echo 1;
        }
    

    }