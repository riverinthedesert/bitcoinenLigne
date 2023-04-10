<?php

namespace App\Controller;
use Cake\Datasource\ConnectionManager;

class VisuFavoController extends AppController
{
    public function index()
    {
        $conn = ConnectionManager::get('default');
        $this->loadComponent('Paginator');
        
    }

    public function supprimFavo()
    {
        
        
    }
}