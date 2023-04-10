<?php

namespace App\Controller;

use Cake\Datasource\ConnectionManager;

class NotificationController extends AppController
{
    public function index()
    {

        setlocale(LC_TIME, 'fr_FR');
        date_default_timezone_set('Europe/Paris');

        $conn = ConnectionManager::get('default');
        $this->loadComponent('Paginator');

        $id_utilisateur = $this->Authentication->getIdentity()->idMembre;  // A CHANGE AVEC SESSION

        $requete = "SELECT * FROM notification 
            LEFT OUTER JOIN offre ON offre.idOffre=notification.idOffre 
            LEFT OUTER JOIN villes_france_free ON villes_france_free.ville_id=offre.idVilleDepart
            WHERE idMembre=" . $id_utilisateur . " ORDER BY DateCreation DESC";

        $not = $conn->execute($requete)->fetchAll('assoc');
        $this->set(compact('not'));

        $requete = "UPDATE notification SET estLue='1' WHERE idMembre=" . $id_utilisateur;
        $conn->execute($requete);
    }

    public function delete()
    {

        $conn = ConnectionManager::get('default');
        $this->loadComponent('Paginator');

        $id_utilisateur = $this->Authentication->getIdentity()->idMembre; // A CHANGE AVEC SESSION

        $url_id = $this->request->getQuery("id"); // GET message

        $requete = "DELETE FROM notification WHERE idMembre=" . $id_utilisateur . " AND idNotification='" . $url_id . "'";

        $not = $conn->execute($requete);

        echo 1;
    }


    public function accepter()
    {
    }

    public function refuser()
    {
    }


    /**
     * Fait le lien entre le controller d'origine et ce controller
     * 
     * @param $typeAction       l'action d'origine      
     * @param $idsConcernes     les identifiants utiles au traitement de la notification
     *                          (identifiant de créateur, d'offre...)      
     */
    public static function notifier($typeAction, $idsConcernes)
    {

        (new self())->envoyer($typeAction, $idsConcernes);
    }


    /**
     * Ajoute une notification à la base de données après une action
     * 
     * @param $typeAction       l'action d'origine      
     * @param $idsConcernes     les identifiants utiles au traitement de la notification
     *                          (identifiant de créateur, d'offre...)      
     */
    private function envoyer($typeAction, $idsConcernes)
    {

        // message de la notification selon le destinataire
        $messageExp = '';
        $messageDest = '';

        // objet de mail selon le destinataire
        $objetExp = '';
        $objetDest = '';

        // liens d'accès aux détails
        $cheminOffre = 'http://localhost/BitcoinLigne/BitcoinLigne/offre/details?idOffre=';
        $cheminProfil = 'http://localhost/BitcoinLigne/BitcoinLigne/profile?id=';

        // connexion à la base de données
        $connexion = ConnectionManager::get('default');

        // liste des destinataires d'une notification (expéditeur exclu)
        $destinataires = array();

        // nombre de destinataires d'une notification (expéditeur exclu)
        $nbDestinataires = 0;

        // id de l'expéditeur
        $idExpediteur = intval($idsConcernes[0]);

        // nom complet de l'expéditeur
        $nomComplet = $idsConcernes[1];

        // id de l'offre concernée lorsque c'est utile
        $idOffre = -1;


        switch ($typeAction) {

            case 'ajoutOffre':

                $idOffre = intval($idsConcernes[2]);
                $messageExp = 'Votre offre a été créée';

                break;

            case 'annulerOffre':
        
                $idOffre = intval($idsConcernes[2]);

                $messageExp = 'Votre offre a été annulée';
                $messageDest = $nomComplet .' a annulé son offre de trajet';

                // on cherche les participants concernés par l'offre
                $res = $connexion->query('SELECT idMembre FROM copassager
                where copassager.idOffre = ' . $idOffre .
                    ' AND copassager.idMembre <> ' . $idExpediteur);

                // on les stocke dans la liste d'ids
                foreach ($res as $r) {
                    $id = $r['idMembre'];
                    array_push($destinataires, $id);
                }
                
                break;

            case 'ajoutOffrePrivee':

                $idOffre = intval($idsConcernes[2]);
                $idGroupe = intval($idsConcernes[3]);

                $messageExp = 'Votre offre privée a été créée';
                $messageDest = $nomComplet . ' propose une offre de trajet privé';

                // on cherche les membres du groupe privé concernés par l'offre
                $res = $connexion->query('SELECT idUtilisateur FROM groupemembre
                                where groupemembre.idGroupe = ' . $idGroupe .
                    ' AND groupemembre.idUtilisateur <> ' . $idExpediteur);

                // on les stocke dans la liste d'ids
                foreach ($res as $r) {
                    $id = $r['idUtilisateur'];
                    array_push($destinataires, $id);
                }

                break;

            case 'demanderParticipation':
                /*
                $idOffre = intval($idsConcernes[2]);
                
                $messageExp = 'Votre demande de participation a été transmise';
                $messageDest = $nomComplet . ' a demandé à participer à votre trajet';

                // on cherche le créateur de l'offre
                $res = $connexion->query('SELECT idConducteur FROM offre
                                where offre.idOffre = ' . $idOffre);

                // on le stocke dans la liste d'ids
                foreach ($res as $r) {
                    $id = $r['idConducteur'];
                    array_push($destinataires, $id);
                }
                */
                break;

            case 'accepterParticipation':

                break;

            case 'annulerParticipation':
                /*
                $idOffre = intval($idsConcernes[2]);
                $idGroupe = intval($idsConcernes[3]);

                $messageExp = 'Votre participation a été annulée';
                $messageDest = $nomComplet . ' a annulé sa participation';

                // on cherche à savoir si l'offre est privée
                $res = $connexion->query('SELECT estPrivee FROM offre
                                where offre.idOffre = ' . $idOffre);

                foreach ($res as $r)
                    $estPrivee = $r['estPrivee'];

                if ($estPrivee == 0)
                    $messageDest .= ' a votre trajet';

                else {

                    // on cherche les membres du groupe privé
                    $res = $connexion->query('SELECT idUtilisateur FROM groupemembre
                                where groupemembre.idGroupe = ' . $idGroupe .
                        ' AND groupemembre.idUtilisateur <> ' . $idExpediteur);

                    // on les stocke dans la liste d'ids
                    foreach ($res as $r) {
                        $id = $r['idUtilisateur'];
                        array_push($destinataires, $id);
                    }
                }
                */
                break;


            case 'notationTrajetOubliee':

                /*$idOffre = intval($idsConcernes[2]);
                $messageExp = 'N\'oubliez pas de noter le trajet !';*/

                break;

            case 'notationEffectuee':

                /*$idOffre = intval($idsConcernes[2]);
                $messageExp = 'Votre notation a été prise en compte';*/

                break;


            case 'invitationGroupe':

                /*
                $idGroupe = intval($idsConcernes[3]);

                $messageExp = 'Votre invitation a été transmise';
                $messageDest = $nomComplet . ' vous a invité a rejoindre son groupe privé';

                // on cherche les membres du groupe privé
                $res = $connexion->query('SELECT idUtilisateur FROM groupemembre
                                where groupemembre.idGroupe = ' . $idGroupe .
                    ' AND groupemembre.idUtilisateur <> ' . $idExpediteur);

                // on les stocke dans la liste d'ids
                foreach ($res as $r) {
                    $id = $r['idUtilisateur'];
                    array_push($destinataires, $id);
                }
                */
                break;

            case 'modifGroupe':

                $idGroupe = intval($idsConcernes[2]);

                $messageExp = 'Le groupe a été modifié';
                $messageDest = $nomComplet . ' a modifié le groupe';

                // on cherche les membres du groupe privé concernés par l'offre
                $res = $connexion->query('SELECT idUtilisateur FROM groupemembre
                                where groupemembre.idGroupe = ' . $idGroupe .
                    ' AND groupemembre.idUtilisateur <> ' . $idExpediteur);

                // on les stocke dans la liste d'ids
                foreach ($res as $r) {
                    $id = $r['idUtilisateur'];
                    array_push($destinataires, $id);
                }

                break;


            case 'ajoutMembreGroupe':

                /*$idGroupe = intval($idsConcernes[2]);

                $messageExp = 'Le membre a bien été ajouté';
                $messageDest = $nomComplet . ' a été ajouté dans le groupe';

                // on cherche les membres du groupe privé concernés par l'offre
                $res = $connexion->query('SELECT idUtilisateur FROM groupemembre
                                where groupemembre.idGroupe = ' . $idGroupe .
                    ' AND groupemembre.idUtilisateur <> ' . $idExpediteur);

                // on les stocke dans la liste d'ids
                foreach ($res as $r) {
                    $id = $r['idUtilisateur'];
                    array_push($destinataires, $id);
                }
                */
                break;


            case 'supprimerGroupe':

                /*$idGroupe = intval($idsConcernes[2]);

                $messageExp = 'Le groupe a été supprimé';
                $messageDest = $nomComplet . ' a supprimé le groupe';

                // on cherche les membres du groupe privé concernés par l'offre
                $res = $connexion->query('SELECT idUtilisateur FROM groupemembre
                                where groupemembre.idGroupe = ' . $idGroupe .
                    ' AND groupemembre.idUtilisateur <> ' . $idExpediteur);

                // on les stocke dans la liste d'ids
                foreach ($res as $r) {
                    $id = $r['idUtilisateur'];
                    array_push($destinataires, $id);
                }
                */
                break;


            case 'supprimerMembreGroupe':

                $idGroupe = intval($idsConcernes[2]);

                $messageExp = 'Le membre a bien été supprimé';
                $messageDest = $nomComplet . ' a supprimé un membre du groupe';

                // on cherche les membres du groupe privé concernés par l'offre
                $res = $connexion->query('SELECT idUtilisateur FROM groupemembre
                                where groupemembre.idGroupe = ' . $idGroupe .
                    ' AND groupemembre.idUtilisateur <> ' . $idExpediteur);

                // on les stocke dans la liste d'ids
                foreach ($res as $r) {
                    $id = $r['idUtilisateur'];
                    array_push($destinataires, $id);
                }

                break;

            case 'quitterGroupePrive':

                /*$idGroupe = intval($idsConcernes[2]);

                $messageExp = 'Vous avez quitté le groupe';
                $messageDest = $nomComplet . ' a quitté le groupe';

                // on cherche les membres du groupe privé concernés par l'offre
                $res = $connexion->query('SELECT idUtilisateur FROM groupemembre
                                where groupemembre.idGroupe = ' . $idGroupe .
                    ' AND groupemembre.idUtilisateur <> ' . $idExpediteur);

                // on les stocke dans la liste d'ids
                foreach ($res as $r) {
                    $id = $r['idUtilisateur'];
                    array_push($destinataires, $id);
                }
                */
                break;
        }


        $nbDestinataires = count($destinataires);

        // création de la notification pour la personne à l'origine de l'action
        $notification = $this->Notification->newEmptyEntity();

        $notification->set('idMembre', $idExpediteur);
        $notification->set('message', $messageExp);

        // si la notification concerne une offre
        if ($idOffre != -1)
            $notification->set('idOffre', $idOffre);

        $notification->set('idExpediteur', $idExpediteur);

        // sauvegarde de la notification dans la base de données
        if ($this->Notification->save($notification)) {

            // envoi de la notification par mail si l'utilisateur ne l'a pas désactivé
            if ($this->estActiveeParMail($idExpediteur)) {

                $elements = array($idExpediteur, $messageExp);
                $this->envoyerNotifMail($elements);
            }

            $i = 0;

            // s'il existe des destinataires (offre privée...) on les notifie aussi
            while ($i < $nbDestinataires) {

                $notification = $this->Notification->newEmptyEntity();

                $idMembre = $destinataires[$i];

                $notification->set('idMembre', $idMembre);
                $notification->set('message', $messageDest);

                // si la notification concerne une offre
                if ($idOffre != -1)
                    $notification->set('idOffre', $idOffre);

                $notification->set('idExpediteur', $idExpediteur);

                if ($this->Notification->save($notification))
                    $i++;
                else
                    break;

                // envoi de la notification par mail si l'utilisateur ne l'a pas désactivé
                if ($this->estActiveeParMail($idMembre)) {

                    $elements = array($idMembre, $messageDest);
                    $this->envoyerNotifMail($elements);
                }
            }

            if ($i != $nbDestinataires)
                $this->Flash->error(__('Erreur envoi notification'));
            /*return $this->redirect(['action' => 'index']);*/ //rediriger vers détails offre
        }

        $this->set(compact('notification'));
    }



    /**
     * Détermine si un utilisateur a activé ou non les notifications par mail
     * 
     * @param $idMembre     l'identifiant de l'utilisateur
     * @return bool         true si l'utilisateur a activé les notifications
     */
    public function estActiveeParMail($idMembre): bool
    {

        // connexion à la base de données
        $connexion = ConnectionManager::get('default');

        // on cherche si le membre a activé les notifications par mail
        $res = $connexion->query('SELECT notifMail FROM users
                                  where idMembre = ' . $idMembre);

        foreach ($res as $r)
            $notif = $r['notifMail'];

        return $notif == 1 ? true : false;
    }



    /**
     * Envoie une notification par mail à un utilisateur
     * 
     * @param $elements  les informations du mail
     */
    public function envoyerNotifMail($elements)
    {

        // connexion à la base de données
        $connexion = ConnectionManager::get('default');

        $idMembre = intval($elements[0]);
        $message = $elements[1];

        // on cherche l'adresse mail de la personne
        $res = $connexion->query('SELECT mail, prenom FROM users
                                where idMembre = ' . $idMembre);

        foreach ($res as $r) {
            $mail = $r['mail'];
            $prenom = $r['prenom'];
        }

        // champs du mail
        $origine = 'From: infos.getride@gmail.com';
        $contenu = "Bonjour " . $prenom . " !\n\n";
        $contenu .= $message . "\n\n";
        $contenu .= "___\n\nVous pouvez retrouver le contenu de cet email dans l'onglet Notifications de notre site.\n";
        $contenu .= "Si vous le souhaitez, vous pouvez désactiver l'envoi de notifications par mail depuis";
        $contenu .= " votre compte (Mon profil/Modifier ses informations personnelles).\n\n";
        $contenu .= "À bientôt !\n\n";
        $contenu .= "Cet email a été généré automatiquement, merci de ne pas y répondre.";

        $envoi = mail($mail, $message, $contenu, $origine);

        if (!$envoi)
            $this->Flash->error(__('Echec envoi mail'));
    }
}
