<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

class Offre extends Entity
{
    protected $_accessible = [
        "*" => true,
        "idOffre" => false,
        "slug" => false
    ];

}

?>