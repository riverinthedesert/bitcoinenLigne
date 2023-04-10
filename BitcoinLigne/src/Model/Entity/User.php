<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Auth\DefaultPasswordHasher;

/**
 * User Entity
 *
 * @property int $idMembre
 * @property string $nom
 * @property string $prenom
 * @property string $motDePasse
 * @property string $mail
 * @property int $telephone
 * @property string $genre
 * @property string $pathPhoto
 * @property string $estConducteur
 * @property int|null $idUtilisateurFavo
 * @property int|null $idHistoriqueTrajet
 * @property int|null $idHistoriqueRecherche
 * @property int|null $idNotation
 * @property float|null $noteMoyenne
 */
class User extends Entity
{

    protected $_accessible = [
        'nom' => true,
        'prenom' => true,
        'motDePasse' => true,
        'mail' => true,
        'telephone' => true,
        'naissance' => true,
        'genre' => true,
        'pathPhoto' => true,
        'estConducteur' => true,
        'idUtilisateurFavo' => true,
        'idHistoriqueTrajet' => true,
        'idHistoriqueRecherche' => true,
        'idNotation' => true,
        'noteMoyenne' => true,
    ];

    // si l'entité est exportée, le mot de passe ne sera pas affiché 
    protected $_hidden = [
        'password',
    ];

    //Hash le mot de passe
    protected function _setmotDePasse($motDePasse){
        return (new DefaultPasswordHasher)->hash($motDePasse);
    }
}
