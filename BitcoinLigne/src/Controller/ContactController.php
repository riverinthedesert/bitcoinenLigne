<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\Datasource\ConnectionManager;
use Cake\Event\EventInterface;


/* Gestion d'un formulaire de contact */

class ContactController extends AppController
{

    /* Gestion de droit d'accès aux pages de ce controller */
    public function beforeFilter(EventInterface $event)
    {

        /* Exceptions à l'authentification nécessaire (seulement pour ce contrôleur) */
        $this->Authentication->addUnauthenticatedActions(['formulaire']);
    }



    /* Gestion de l'envoi des éléments du formulaire de contact */
    public function formulaire()
    {

        if ($this->request->is('post')) {

            // données entrées dans le formulaire
            $nom = $this->request->getData('nom');
            $prenom = $this->request->getData('prenom');
            $mail = $this->request->getData('mail');
            $nature = $this->request->getData('nature');
            $message = $this->request->getData('message');

            // connexion à la base de données
            $connexion = ConnectionManager::get('default');

            // on cherche si la personne dispose d'un compte
            $res = $connexion->query("SELECT idMembre FROM users 
                            WHERE mail = '$mail'");

            $idMembre = -1;

            foreach ($res as $r)
                $idMembre = $r['idMembre'];


            /* Envoi par mail */

            // champs du mail
            $origine = "From: infos.getride@gmail.com";

            $objet = $nature;

            switch ($nature) {

                case 'Aide':

                    $objet = 'Demande d\'aide';

                    break;

                case 'Autre':

                    $objet = 'Message';

                    break;
            }


            $objet .= " de la part de $prenom $nom";

            $contenu = "Aujourd'hui, $prenom $nom ";

            if ($idMembre == -1)
                $contenu .= '(non membre)';

            else
                $contenu .= "(identifiant n°$idMembre)";


            $contenu .= " a envoyé ce message : \n\n";
            $contenu .= $message . "\n\n";
            $contenu .= "Cet email a été généré automatiquement, merci de ne pas y répondre.";

            $envoi = mail("infos.getride@gmail.com", $objet, $contenu, $origine);

            if (!$envoi)
                $this->Flash->error(__('Echec de l\'envoi du mail'));

            // si le mail a pu être envoyé
            else
                $this->Flash->success(__('Votre demande a bien été prise en compte'));
        }
    }
}
