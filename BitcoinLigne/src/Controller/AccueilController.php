<?php
    namespace App\Controller;

    use Cake\Event\EventInterface;

    class AccueilController extends AppController
    {
        public function index()
        {
            
        }

        
        /* Permet aux utilisateurs non connectés d'afficher la page d'accueil */
        public function beforeFilter(EventInterface $event)
        {
            parent::beforeFilter($event);
            
            $this->Authentication->addUnauthenticatedActions(['index']);
        }
    }