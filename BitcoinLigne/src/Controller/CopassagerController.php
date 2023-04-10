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

/* Gestion des utilisateurs et des autorisations d'accès aux pages.
   Remplace la table Membre dans la modélisation par souci de convention 
   avec le plugin utilisé (Authentication) */

class UsersController extends AppController
{

    public function index()
    {
        $copassager = $this->paginate($this->Copassager);
        $this->set(compact('copassager'));
    }
}
