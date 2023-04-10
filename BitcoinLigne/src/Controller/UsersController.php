<?php

declare(strict_types=1);

namespace App\Controller;

use Authentication\PasswordHasher\DefaultPasswordHasher;
use Cake\Datasource\ConnectionManager;
use Cake\Event\EventInterface;
use Cake\Mailer\Email;
use Cake\Mailer\Mailer;
use Cake\Mailer\TransportFactory;
use Cake\Validation\Validator;

use App\Controller\NotificationController;
use Cake\Routing\Router;

/* Gestion des utilisateurs et des autorisations d'accès aux pages.
   Remplace la table Membre dans la modélisation par souci de convention 
   avec le plugin utilisé (Authentication) */

class UsersController extends AppController
{

    public function index()
    {
    }


    /* Gestion de droit d'accès aux pages de ce controller */
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        // test d'une connexion active
        $session_active = $this->Authentication->getIdentity();

        if (!is_null($session_active)) {

            $pagePrecedente = $this->referer('/', true);

            /* Redirection d'un utilisateur connecté vers l'accueil 
               si celui-ci tente d'accéder à la connexion, l'inscription 
               ou la récupération via leur URL.
               Cas particulier si l'utilisateur vient de se connecter
               (cf connexion() plus bas) */
            if (!str_contains($pagePrecedente, 'connexion'))
                $this->redirect(['controller' => 'Accueil', 'action' => 'index']);
        } else
            /* Exceptions à l'authentification nécessaire (seulement pour ce contrôleur).
               Permet à un utilisateur non connecté de se connecter, de s'inscrire ou de récupérer
               son mot de passe */
            $this->Authentication->addUnauthenticatedActions(['connexion', 'add', 'recuperation']);
    }


    public function add()
    {
        $user = $this->Users->newEmptyEntity();

        // Récupération des informations du formulaire
        if ($this->request->is('post')) {

            $user = $this->Users->patchEntity($user, $this->request->getData());

            $conn = ConnectionManager::get('default');

            if (!$user->getErrors()) {
                //Récupération de la photo de l'utilisateur
                $pathPhoto = $this->request->getData('pathPhoto_file');

                $nomPhoto = $pathPhoto->getClientFileName();

                //Création du dossier photoProfil si il n'existe pas
                if (!is_dir(WWW_ROOT . 'img' . DS . 'photoProfil'))
                    mkdir(WWW_ROOT . 'img' . DS . 'photoProfil', 0775);

                //Déplacement de la photo dans le répertoire
                $chemin = WWW_ROOT . 'img' . DS . 'photoProfil' . DS . $nomPhoto;
                if ($nomPhoto != "") {
                    $pathPhoto->moveTo($chemin);
                    $user->pathPhoto = 'webroot\img\photoProfil\\' . $nomPhoto;
                }
            }
            //Sauvegarde dans la base de données
            if ($this->Users->save($user)) {

                //Récupération des informations sur la voiture de l'utilisateur.
                $typeVoiture = $_POST['typeVoiture'];
                $immatriculation = $_POST['immatriculation'];
                $id_user = $user->get('idMembre');

                // On vérifie si l'utilisateur à entrer une valeur pour sa voiture
                if ($typeVoiture != null) {
                    //Stockage des données dans la table conducteur
                    $requete = $conn->prepare("INSERT INTO `conducteur` VALUES ('$id_user','$typeVoiture','$immatriculation')");
                    $requete->execute();
                }

                $this->Flash->success(__('Votre compte a bien été créé.'));

                // connecte automatiquement l'utilisateur
                $this->Authentication->setIdentity($user);

                // redirection vers l'accueil
                return $this->redirect(['controller' => 'Accueil', 'action' => 'index']);
            }
            $this->Flash->error(__('Vos informations sont incorrectes. Veuillez réessayer.'));
        }
        $this->set(compact('user'));
    }



    /**
     * Gère la connexion à un compte déjà créé
     */
    public function connexion()
    {
        $this->request->allowMethod(['get', 'post']);

        // récupèration du résultat de la connexion
        $resultat = $this->Authentication->getResult();

        // si le compte existe et que la connexion s'est bien passée
        if ($resultat->isValid()) {

            $this->Flash->success(__('Connexion réussie !'));

            // retourne l'url de la dernière page visitée
            $pageRedirigee = $this->referer('/', true);

            /* Cas où le site a redirigé l'utilisateur non connecté vers la page de connexion
               car celui-ci tentait d'accéder à une page réservée aux membres */
            if (str_contains($pageRedirigee, 'redirect')) {

                $url1 = str_replace('%2F', '/', $pageRedirigee);
                $url2 = str_replace('/connexion?redirect=/BitcoinLigne/BitcoinLigne/', '', $url1);

                // séparation de l'url d'origine (par exemple en Offre et add)
                $parties = explode('/', $url2);

                // la recherche d'un utilisateur se fait via la méthode post, on ne
                // peut pas relancer la recherche à partir de l'url
                if (strcmp($parties[0], 'search') == 0)
                    $redirection = 'Accueil';

                else
                    $redirection = "$parties[0]";

                // dans le cas d'une url type controller/fonction (offre/add)
                if (count($parties) > 1)
                    $redirection .= "/$parties[1]";

                // l'utilisateur a accédé à la page de connexion de lui-même
            } else
                $redirection = 'Accueil';

                /* Le return ne peut pas attendre la fin de la fonction car puisque 
                   la vérification n'a pas pu avoir lieu lors du premier accès à la page, 
                   le site redirigera vers la valeur par défaut de $redirection à l'infini */
               return $this->redirect("http://localhost/BitcoinLigne/BitcoinLigne/$redirection");
        }

        // si une erreur s'est produite, la page ne change pas et un message d'erreur s'affiche
        if ($this->request->is('post') && !$resultat->isValid()) {

            // email entré par l'utilisateur
            $email = $this->request->getData('mail');

            // connexion à la base de données
            $connexion = ConnectionManager::get('default');

            // on cherche si l'email est stocké dans la base de données
            $res = $connexion->query("SELECT count(*) FROM users 
                            WHERE mail = '$email'");

            foreach ($res as $r)
                $nb = $r[0];

            if ($nb == 0)
                $this->Flash->error(__('Cet email n\'est pas reconnu'));

            else
                $this->Flash->error(__('Le mot de passe est incorrect'));
        }
    }



    /**
     * Gère la déconnexion pour un utilisateur connecté
     */
    public function deconnexion()
    {
        // on vérifie que la session de l'utilisateur est toujours active
        $session_active = $this->request->getAttribute('identity');

        if (!is_null($session_active)) {

            $prenom = $session_active->prenom;

            // déconnexion
            $this->Authentication->logout();

            // message de confirmation de la déconnexion
            $this->Flash->success(__('À bientôt ' . $prenom . ' !'));

            // redirection vers la page d'accueil
            return $this->redirect(['controller' => 'Accueil', 'action' => 'index']);
        }
    }


    /* Récupération du mot de passe pour un utlisateur non connecté mais disposant d'un compte */
    public function recuperation()
    {

        if ($this->request->is('post')) {

            // mail entré dans le formulaire
            $mail = $this->request->getData('mail');

            // connexion à la base de données
            $connexion = ConnectionManager::get('default');

            // on cherche si le mail est rattaché à un compte
            $res = $connexion->query("SELECT count(*) FROM users 
                                      WHERE mail = '$mail'");
            foreach ($res as $r)
                $nb = $r[0];

            if ($nb != 1)
                $this->Flash->error(__('Cette adresse mail n\'existe pas'));

            else {

                /* Création d'un nouveau mot de passe aléatoire */

                $minuscules = 'abcdefghijklmnopqrstuvwxyz';
                $majuscules = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $chiffres = '0123456789';
                $speciaux = '@[]!"#()*/:;';

                $nouveau = '';


                /* On utilise random_int() pour définir la position d'un caractère 
                   à insérer dans le mot de passe.
                   C'est la fonction recommandée par la documentation officielle
                   de php pour la gestion d'évènements aléatoires fiables.
                   https://www.php.net/manual/fr/function.random-int.php */

                // cinq minuscules
                for ($i = 0; $i < 5; $i++) {

                    $pos = random_int(0, strlen($minuscules) - 1);
                    $nouveau .= $minuscules[$pos];
                }

                // un chiffre
                $pos = random_int(0, strlen($chiffres) - 1);
                $nouveau .= $chiffres[$pos];

                // une majuscule
                $pos = random_int(0, strlen($majuscules) - 1);
                $nouveau .= $majuscules[$pos];

                // un caractère spécial
                $pos = random_int(0, strlen($speciaux) - 1);
                $nouveau .= $speciaux[$pos];


                /* Envoi par mail */

                // on cherche le prénom de la personne
                $res = $connexion->query("SELECT prenom FROM users 
                                         WHERE mail = '$mail'");

                foreach ($res as $r)
                    $prenom = $r['prenom'];

                // champs du mail
                $origine = 'From: infos.getride@gmail.com';

                $objet = 'Récupération de votre mot de passe GetRide';

                $contenu = "Bonjour " . $prenom . " !\n\n";
                $contenu .= "Voici votre nouveau mot de passe : $nouveau\n\n";
                $contenu .= "Par mesure de sécurité, il vous est conseillé de le changer ";
                $contenu .= "dès votre prochaine connexion (Mon profil/Visualiser son profil/";
                $contenu .= "Modifier votre mot de passe).\n\n";
                $contenu .= "À bientôt !";
                $contenu .= "Cet email a été généré automatiquement, merci de ne pas y répondre.";

                $envoi = mail($mail, $objet, $contenu, $origine);

                if (!$envoi)
                    $this->Flash->error(__('Echec de l\'envoi du mail'));

                // si le mail a pu être envoyé, on change le mot passe dans la base de données
                else {

                    $hash = (new DefaultPasswordHasher)->hash($nouveau);

                    // on met à jour le mot de passe
                    $res = $connexion->query("UPDATE users SET motdePasse = '$hash'
                                            WHERE mail = '$mail'");

                    $this->Flash->success(__('Un mail de récupération vous a été envoyé'));

                    // on redirige vers le formulaire de connexion
                    return $this->redirect(['controller' => 'Users', 'action' => 'connexion']);
                }
            }
        }
    }
}
