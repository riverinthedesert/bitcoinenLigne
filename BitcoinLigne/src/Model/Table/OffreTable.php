<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class OffreTable extends Table{

    public function initialize(array $config): void
    {
        $this->setPrimaryKey("idOffre");
    }

}

?>