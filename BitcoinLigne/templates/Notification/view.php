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
            <?= $this->Html->link(__('Edit Notification'), ['action' => 'edit', $notification->idNotification], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Notification'), ['action' => 'delete', $notification->idNotification], ['confirm' => __('Are you sure you want to delete # {0}?', $notification->idNotification), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Notification'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Notification'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="notification view content">
            <h3><?= h($notification->idNotification) ?></h3>
            <table>
                <tr>
                    <th><?= __('Message') ?></th>
                    <td><?= h($notification->message) ?></td>
                </tr>
                <tr>
                    <th><?= __('IdMembre') ?></th>
                    <td><?= $this->Number->format($notification->idMembre) ?></td>
                </tr>
                <tr>
                    <th><?= __('IdNotification') ?></th>
                    <td><?= $this->Number->format($notification->idNotification) ?></td>
                </tr>
                <tr>
                    <th><?= __('IdOffre') ?></th>
                    <td><?= $this->Number->format($notification->idOffre) ?></td>
                </tr>
                <tr>
                    <th><?= __('IdExpediteur') ?></th>
                    <td><?= $this->Number->format($notification->idExpediteur) ?></td>
                </tr>
                <tr>
                    <th><?= __('DateCreation') ?></th>
                    <td><?= h($notification->DateCreation) ?></td>
                </tr>
                <tr>
                    <th><?= __('EstLue') ?></th>
                    <td><?= $notification->estLue ? __('Yes') : __('No'); ?></td>
                </tr>
                <tr>
                    <th><?= __('NecessiteReponse') ?></th>
                    <td><?= $notification->necessiteReponse ? __('Yes') : __('No'); ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
