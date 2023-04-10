<?php

namespace App\Controller;

use Cake\Datasource\ConnectionManager;

class OffreController extends AppController{

    

	public function editer()
    {

    }
	
	public function confirmEdit()
    {

    }

    public function view()
    {

    }


    public function view2()
    {
	

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


    public function details()
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

    



    public function index(){

        $conn = ConnectionManager::get('default');
        $this->loadComponent('Paginator');

        setlocale(LC_TIME, 'fr_FR');
        date_default_timezone_set('Europe/Paris');
    

        $string_filtre = "";

        $test_filtre="1";

        
        $id_utilisateur=$this->Authentication->getIdentity()->idMembre;

        // SELECT ALL UTILISATEUR
        $conducteur = $conn->execute('SELECT * FROM conducteur')->fetchAll('assoc');
        $test_depart=$this->request->getQuery("depart");
        $test_tri=$this->request->getQuery("tri");
        $test_privee=$this->request->getQuery("privee");


        // On teste si l'un des champs obligatoires de view2 a été rempli
        $test_view2 = $this->request->getQuery("villeDepart");
        

          // BASE DE LA REQUETE

          if ($test_privee!="1"){

          $requete = "SELECT idOffre,horaireDepart,horaireArrivee,nbPassagersMax, idConducteur,
          ville_depart.ville_nom_simple as nomVilleDepart,ville_arrivee.ville_nom_simple as nomVilleArrivee,nom,prenom
          ,prix,noteMoyenne
          FROM offre
          INNER JOIN users ON users.idMembre=offre.idConducteur 
          INNER JOIN conducteur ON conducteur.idMembre=users.idMembre
          LEFT OUTER JOIN villes_france_free ville_depart ON offre.idVilleDepart=ville_depart.ville_id 
          LEFT OUTER JOIN villes_france_free ville_arrivee ON offre.idVilleArrivee=ville_arrivee.ville_id
          WHERE estPrivee=0 AND  horaireDepart > (NOW() + INTERVAL 1 DAY) ";

          }else{
          $string_filtre.= " Offres privées |";
          $requete =  "SELECT idOffre,horaireDepart,horaireArrivee,nbPassagersMax,idConducteur,
          ville_depart.ville_nom_simple as nomVilleDepart,ville_arrivee.ville_nom_simple as nomVilleArrivee,nom,prenom
          ,prix,noteMoyenne
          FROM offre
          INNER JOIN users ON users.idMembre=offre.idConducteur 
          INNER JOIN conducteur ON conducteur.idMembre=users.idMembre
          INNER JOIN groupemembre ON groupemembre.idGroupe=offre.idGroupe
          LEFT OUTER JOIN villes_france_free ville_depart ON offre.idVilleDepart=ville_depart.ville_id 
          LEFT OUTER JOIN villes_france_free ville_arrivee ON offre.idVilleArrivee=ville_arrivee.ville_id
          WHERE estPrivee=1 AND groupemembre.idUtilisateur=".$id_utilisateur." AND horaireDepart > (NOW() + INTERVAL 1 DAY) ";
          }
        
        if(!isset($test_view2))
        {    
            // FILTRE HEURE_DEPART
            if ($test_depart == "6") {
                $string_filtre .= " Départ de 6h à 12h |";
                $requete .= " AND (DATE_FORMAT(horaireDepart ,'%H:%i:%s')>= '06:00:00' 
                AND DATE_FORMAT(horaireDepart ,'%H:%i:%s') <= '12:00:00')";
            } else if ($test_depart == "12") {
                $string_filtre .= " Départ de 12h à 18h |";
                $requete .= " AND (DATE_FORMAT(horaireDepart ,'%H:%i:%s')>= '12:00:01' 
                AND DATE_FORMAT(horaireDepart ,'%H:%i:%s') <= '18:00:00')";
            } else if ($test_depart == "18") {
                $string_filtre .= " Départ à 18h ou plus |";
                $requete .= " AND (DATE_FORMAT(horaireDepart ,'%H:%i:%s')>= '18:00:01' 
                AND DATE_FORMAT(horaireDepart ,'%H:%i:%s') <= '23:59:59') 
                OR (DATE_FORMAT(horaireDepart ,'%H:%i:%s')>= '00:00:00' 
                AND DATE_FORMAT(horaireDepart ,'%H:%i:%s') <= '05:59:59')";
            }
    
            // FILTRE CONCERNANT LE ORDER BY (on test les paramètres URL)
            
            if ($test_tri == "1") { // A FAIRE APRES LES FILTRES AND
                $string_filtre .= " Trié par prix le plus bas |";
                $requete .= " ORDER BY prix ASC";
            } else if ($test_tri == "2") {
                $string_filtre .= " Trié par horaire de départ le plus tôt |";
                $requete .= " ORDER BY DATE_FORMAT(horaireDepart,'%H:%i:%s') ASC";
            } else if ($test_tri == "3") {
                $string_filtre .= " Trié par note de l'utilisateur |";
                $requete .= " ORDER BY noteMoyenne DESC";
            }
            // On execute la requête
            $offre_filtres_applied = $conn->execute($requete)->fetchAll('assoc');
    
            if ($string_filtre == "") { // Si il n'y a aucune filtre : Aucun
                $string_filtre = " Aucun";
                $test_filtre="0";
            }
            // On transmet les variables à la vue.
            $this->set(compact('string_filtre'));
            $this->set(compact('offre_filtres_applied'));
            $this->set(compact('conducteur'));
            $this->set(compact('test_filtre'));
    

        }
        else // Filtres avancées 
        {

            $prix=$this->request->getQuery("prix");
            $passagers=$this->request->getQuery("nombrePassagersMax");


    

            $requete .= " AND  ville_depart.ville_nom_simple LIKE '%".$this->request->getQuery("villeDepart")."%' AND ville_arrivee.ville_nom_simple LIKE '%".$this->request->getQuery("villeDarrivee")."%'";
            if ($prix!=""){
                $requete.=" AND prix <= ".$prix;
            }
            if ($passagers!=""){
                $requete.=" AND nbPassagersMax >= ".$passagers;
            }
            // On execute la requête
            $offre_filtres_applied = $conn->execute($requete)->fetchAll('assoc');

            if ($string_filtre==""){ // Si il n'y a aucune filtre : Aucun
                $string_filtre=" Aucun";
            }
            // On transmet les variables à la vue.
            $this->set(compact('string_filtre'));
            $this->set(compact('offre_filtres_applied'));
            $this->set(compact('conducteur'));
        }           
    

     

       /* $offre = $this->Paginator->paginate($this->Offre->find());

        $offre_prix_bas = 
        $this->Paginator->paginate($this->Offre->find('all', array(
            'order' => "prix ASC"
        )));
        */


    }

    
}