<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Notification Entity
 *
 * @property int $idMembre
 * @property string $message
 * @property bool $estLue
 * @property bool $necessiteReponse
 * @property int $idNotification
 * @property int|null $idOffre
 * @property \Cake\I18n\FrozenDate $DateCreation
 * @property int|null $idExpediteur
 */
class Notification extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'idMembre' => true,
        'message' => true,
        'estLue' => true,
        'necessiteReponse' => true,
        'idOffre' => true,
        'DateCreation' => true,
        'idExpediteur' => true,
    ];
}
