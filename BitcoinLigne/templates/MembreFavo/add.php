<?php 
	if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
?>
<!DOCTYPE html>
<html>
    <head>

        <?= $this->Html->css(['ouiounon']) ?>

        <?= $this->fetch('css') ?>

        </head>
    <body>
        <div class="indice">
            <h1>
                <?php echo __('Are you sure you want to add # {0}?', $_SESSION['mailDeProfil']); ?>
            </h1>
        </div>
        <div class="repondre">
            <p1>
            <?= $this->Form->postButton(__('Ajouter dans Favolist'), ['action' => 'add',$_SESSION['mail'] ,$_SESSION['mailDeProfil']]) ?>
            </p1>
            <p2>
            <?= $this->Form->postButton(__('Quitter'), ['action' => 'quit']) ?>
            </p2>
        </div>
    </body>
</html>