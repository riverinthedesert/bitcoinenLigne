<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Auth\DefaultPasswordHasher;

/**
 * Membre Entity
 *
 * @property int $idMembre
 * @property string $nom
 * @property string $prenom
 * @property string $motDePasse
 * @property string $mail
 * @property int $telephone
 * @property string $genre
 * @property string|null $pathPhoto
 * @property string $estConducteur
 * @property int|null $idUtilisateurFavo
 * @property int|null $idHistoriqueTrajet
 * @property int|null $idHistoriqueRecherche
 * @property int|null $idNotation
 * @property float|null $noteMoyenne
 */
class VisuProfil extends Entity
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

    //Hash le mot de passe
    protected function _setmotDePasse($motDePasse){
        return (new DefaultPasswordHasher)->hash($motDePasse);
    }
}