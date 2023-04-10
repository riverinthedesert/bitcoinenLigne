<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\App;
use Cake\Event\EventInterface;

class MenuController extends AppController
{
	
	/*
	 * page basic
	 */
    public function index(){
    }

	
	/* Permet aux utilisateurs non connectés d'afficher le menu */
	public function beforeFilter(EventInterface $event)
	{
		parent::beforeFilter($event);
		
		$this->Authentication->addUnauthenticatedActions(['index']);
	}
	
	/*
	 * deplacer à page VisuGroupe
	 */
	public function visuGroupe(){
		$redirect = $this->request->getQuery('redirect', [
				'controller' => 'VisuGroupe',
				'action' => 'index',
			]);

			return $this->redirect($redirect);

        return $this->redirect(['action' => 'index']);
	}
	
	/*
	 * deplacer à page Offre
	 */
	public function offre(){
		$redirect = $this->request->getQuery('redirect', [
				'controller' => 'Offre',
				'action' => 'index',
			]);

			return $this->redirect($redirect);

        return $this->redirect(['action' => 'index']);
	}
	
	/*
	 * deplacer à page recettes
	 */
	public function visuProfil(){
		
		$redirect = $this->request->getQuery('redirect', [
				'controller' => 'VisuProfil',
				'action' => 'index',
			]);

			return $this->redirect($redirect);

        return $this->redirect(['action' => 'index']);
	}
	
	/*
	 * deplacer à page accueil
	 */
	public function accueil(){
		
		$redirect = $this->request->getQuery('redirect', [
				'controller' => 'Accueil',
				'action' => 'index',
			]);

			return $this->redirect($redirect);

        return $this->redirect(['action' => 'index']);
	}


	
}
?>