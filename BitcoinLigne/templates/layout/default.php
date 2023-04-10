<?php

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 * @var \App\View\AppView $this
 */

$cakeDescription = 'Co-Voiturage';




?>
<!DOCTYPE html>
<html>

<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta(
        'icone.ico',
        '/icone.ico',
        ['type' => 'icon']
    ); ?>

    <link href="https://fonts.googleapis.com/css?family=Raleway:400,700" rel="stylesheet">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <?= $this->Html->css(['normalize.min', 'milligram.min', 'cake']) ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<style>
    footer {
        position: static;
        bottom: 0;
        width: 100%;
        padding-top: 100px;
        height: 100px;
    }
</style>

<body>
    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="/BitcoinLigne/BitcoinLigne/Accueil">
                <img src="webroot/img/logo.png" height="40">
                </a>
            </div>
            <ul class="nav navbar-nav">
                <form class="navbar-form navbar-left" action="search" method="post">
                    <div class="input-group input-group-sm">
						   <input name="text" type="text" class="form-control" placeholder="Rechercher personne">
						   
							<div class="input-group-btn">
								<button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
							</div>
                    </div>
                </form>
                
                <li><a href="/BitcoinLigne/BitcoinLigne/AjouterUneOffre"><span class="glyphicon glyphicon-plus-sign"></span> Ajouter une solde</a></li>
                <!-- Notifications : -->

                <?php

                use Cake\Datasource\ConnectionManager;
                $session_active = $this->request->getAttribute('identity');

                     if (!is_null($session_active)) {
                         
                    $conn = ConnectionManager::get('default');

                    $id_utilisateur=$session_active->idMembre; // 0 A REMPLACER AVEC VARIABLE SESSION ID UTILISATEUR

                    $not = $conn->execute("SELECT * FROM notification WHERE idMembre =".$id_utilisateur." AND estLue=0")->fetchAll('assoc');

                    echo '<li><a href="#" class="glyphicon glyphicon-bell dropdown" data-toggle="dropdown">';
                    if (sizeof($not)!=0) echo'<span style="margin-top:-2em;background-color:red;"class="badge">'.sizeof($not).'</span>';
                    echo'</a> <ul class="dropdown-menu">';
                    if (sizeof($not) != 0) {
                        if (sizeof($not) > 5) $limite = 5;
                        else $limite = sizeof($not);
                        for ($i = 0; $i < $limite; $i++) {
                            echo '<li class="text-center"><a href="/BitcoinLigne/BitcoinLigne/notification#'.$not[$i]["message"].'">' . $not[$i]["message"] . '</a></ </li>';
                        }
                    }
               
                    echo '<li role="separator" class="divider"></li>';
                    echo '<li><a href="/BitcoinLigne/BitcoinLigne/notification"><strong>Voir le reste</strong></a></li>'; // Path a changé plus tard !
                    echo '</ul> </li>';
                }

                ?>

                <!-- Afficher que si il y a quelqu'un de connecté -->
                <?php
                if (!is_null($session_active)) {
                ?>
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">Mon Profil<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="/BitcoinLigne/BitcoinLigne/visu-profil">Mon profil</a></li>
                            <li><a href="/BitcoinLigne/BitcoinLigne/VisuGroupe">Mes bénéficiaires</a></li>
                            <li><a href="/BitcoinLigne/BitcoinLigne/VisuFavo">Mes favoris</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">Mes Offres<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="/BitcoinLigne/BitcoinLigne/VisuOffre">Mes soldes</a></li>
                            <li><a href="/BitcoinLigne/BitcoinLigne/VisuMesOffre">Mes soldes en cours</a></li>
							<li><a href="/BitcoinLigne/BitcoinLigne/AnnulationParticipation">Annuler un solde</a></li>
                            <li><a href="/BitcoinLigne/BitcoinLigne/HistoriqueTrajet">Historique de soldes validés</a></li>
                        </ul>
                    </li>
                <?php
                }
                ?>

            </ul>
            <ul class="nav navbar-nav navbar-right">


                <?php

                // on vérifie que quelqu'un est connecté 
                $session_active = $this->request->getAttribute('identity');
               
                if (!is_null($session_active)){

                    // message de bienvenue en haut à droite
                    echo 'Bonjour ' . $session_active->prenom . ' !  ';
            
                ?>
                    <!--Affiche que si une personne est connecté -->
                    
                    <a href="/BitcoinLigne/BitcoinLigne/deconnexion">
                    <button style="margin-right:1em;margin-left:1em;" class="btn btn-danger navbar-btn">Déconnexion</button>
                    </a>
                <?php
                } else {
                ?>
                    <!--afficher s'il n'y personne de connecté-->
                    <li><a href="/BitcoinLigne/BitcoinLigne/inscription"><span class="glyphicon glyphicon-user"></span>S'inscrire</a></li>
                    <li><a href="/BitcoinLigne/BitcoinLigne/connexion"><span class="glyphicon glyphicon-log-in"></span> Se connecter</a></li>
                <?php
                }
                ?>

            </ul>
        </div>
    </nav>
    <main class="main">
        <div class="container">
            <?= $this->Flash->render() ?>
            <?= $this->fetch('content') ?>
        </div>
    </main>


    <footer>
        <div class="text-center" style="background-color:#cecece;">
            <br>
            <label><a href="/BitcoinLigne/BitcoinLigne/CommentCaMarche" style="color:#000000;">Comment ça marche ?</a></label>
            <label><a href="/BitcoinLigne/BitcoinLigne/contact" style="color:#000000;">Nous contacter</a></label>
            <label>Langue : Française</label>
            <br>
        </div>

        <div style="background-color:#c1c1c1;">
            <div class="container">
                <font size="2pt">
                    <p>Le site a été réalisé par des étudiant en informatique. Ce site est accessible à tous et permet d'organiser des soldes de bitcoin.</p>
                </font>
            </div>
        </div>
    </footer>
</body>

</html>