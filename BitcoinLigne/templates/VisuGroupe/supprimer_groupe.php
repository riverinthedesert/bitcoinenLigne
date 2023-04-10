<!-- Demande de confirmation de suppression -->
<div class="row">
    <div class="column-responsive column-80">
        <div class="membre form content">
            <?= $this->Form->create() ?>
           <h2 align="center"><b>Etes vous sur de vouloir supprimer ce groupe ?</b></h2>
           <p align="center"> <input type="submit" name="Oui" value="Oui" />
            <input align="right" type="submit" name="Non" value="Non" /></p>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
