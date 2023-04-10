<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\Rule\IsUnique;

class VisuProfilTable extends Table
{

    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('users');
        $this->setDisplayField('idMembre');
        $this->setPrimaryKey('idMembre');
    }

    //Vérification des données entrées par l'utilisateur

    public function validationDefault(Validator $validator): Validator
    {
        $test = '[A-Za-z\n]';
        $validator
            ->integer('idMembre')
            ->allowEmptyString('idMembre', null, 'create');

        $validator
            ->scalar('nom')
            ->maxLength('nom', 50)
            ->requirePresence('nom', 'create')
            ->notEmptyString('nom')
            ->setProvider('nom', $test);
            
            

        $validator
            ->scalar('prenom')
            ->maxLength('prenom', 50)
            ->requirePresence('prenom', 'create')
            ->notEmptyString('prenom');
            

        $validator
            ->scalar('motDePasse')
            ->maxLength('motDePasse', 30)
            ->minLength('motDePasse', 8)
            ->requirePresence('motDePasse', 'create')
            ->notEmptyString('motDePasse');

        $validator
            ->add('confirmerMotDePasse', 'no-misspelling', [
                'rule' => ['compareWith', 'motDePasse'],
                'message' => 'Les mot de passe sont différents',
            ]);

        $validator
            ->scalar('mail')
            ->maxLength('mail', 50)
            ->requirePresence('mail', 'create')
            ->notEmptyString('mail');

        $validator
            ->integer('telephone')
            ->maxLength('telephone', 10)
            ->minLength('telephone', 10)
            ->requirePresence('telephone', 'create')
            ->notEmptyString('telephone');

        $validator
            ->scalar('naissance')
            ->requirePresence('naissance', 'create')
            ->notEmptyString('naissance');

        $validator
            ->scalar('genre')
            ->maxLength('genre', 5)
            ->requirePresence('genre', 'create')
            ->notEmptyString('genre');

        $validator
            ->scalar('pathPhoto')
            ->maxLength('pathPhoto', 255)
            ->allowEmptyString('pathPhoto');

        $validator
            ->scalar('estConducteur')
            ->maxLength('estConducteur', 3)
            ->requirePresence('estConducteur', 'create')
            ->notEmptyString('estConducteur');



        return $validator;
    }
    // Règles du formulaire
    public function buildRules(RulesChecker $rules): RulesChecker{
        $rules->add($rules->isUnique(['mail'], 'L\'adresse mail est déjà utilisée.', 'color:red' ));
        $rules->add($rules->isUnique(['nom', 'prenom'], 'Votre nom est déjà associé à un compte.'));
        return $rules;
    }
}