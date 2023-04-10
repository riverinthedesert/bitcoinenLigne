<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\Rule\IsUnique;


class MembreFavoTable extends Table
{

    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('membreFavo');
        $this->setDisplayField('idMembre');
        $this->setPrimaryKey('idMembre');
    }

    //Vérification des données entrées par l'utilisateur

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('idMembre')
            ->allowEmptyString('idMembre', null, 'create');

        $validator
            ->integer('idMembreFavo')
            ->allowEmptyString('idMembre', null, 'create');


        return $validator;
    }
    // Règles du formulaire
    public function buildRules(RulesChecker $rules): RulesChecker{
        return $rules;
    }
}