<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class Villes_france_freeTable extends Table{

    public function initialize(array $config): void
    {
        $this->setPrimaryKey("ville_id");
    }

}

?>