<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Notification $notification
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $notification->idNotification],
                ['confirm' => __('Are you sure you want to delete # {0}?', $notification->idNotification), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Notification'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="notification form content">
            <?= $this->Form->create($notification) ?>
            <fieldset>
                <legend><?= __('Edit Notification') ?></legend>
                <?php
                    echo $this->Form->control('idMembre');
                    echo $this->Form->control('message');
                    echo $this->Form->control('estLue');
                    echo $this->Form->control('necessiteReponse');
                    echo $this->Form->control('idOffre');
                    echo $this->Form->control('DateCreation');
                    echo $this->Form->control('idExpediteur');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
