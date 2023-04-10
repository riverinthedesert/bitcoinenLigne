<?= $this->Html->css(['formulaire']) ?>
<?= $this->Html->script(['inscription']) ?>
<!-- Création du formulaire d'inscription -->
<div class="row">
    <div class="column-responsive column-80">
        <div class="membre form content">

            <?= $this->Form->create($user, ['type' => 'file',
                                            'autocomplete' => 'off']); ?>
            <fieldset>
                <legend align="center"><?= __('Inscription') ?></legend>
                <?php

                    echo "<div class=\"identite\"><table id=\"tableau\"><tr><td>";

                    /* Champ pour le nom */
                    echo $this->Form->control('nom', ['pattern' => '[a-zA-Z\-]*',
                                                      'required title' => "Ce champ doit être rempli uniquement avec des lettres",
                                                      'placeholder' => 'Votre nom',
                                                      'label' => 'Nom *']);

                    echo "</td><td>";

                    /* Champ pour le prénom */
                    echo $this->Form->control('prenom', ['pattern' => '[a-zA-Z\-]*',
                                                         'required title' => "Ce champ doit être rempli uniquement avec des lettres",
                                                         'placeholder' => 'Votre prénom',
                                                         'label' => 'Prénom *']);

                    echo "</td></tr></div>";
                    echo "<tr><td>";

                    /* Champ pour le mail */
                    echo $this->Form->control('mail', [ 'autocomplete' => 'off',
                                                        'label' => 'Mail *',
                                                        'placeholder' => "exemple@xyz.com",
                                                        'pattern' => "^(([-\w\d]+)(\.[-\w\d]+)*@([-\w\d]+)(\.[-\w\d]+)*(\.([a-zA-Z]{2,5}|[\d]{1,3})){1,2})$",
                                                        'required title' => "exemple@xyz.xyz"]);

                    echo "</td></tr>";
                    echo "<tr><td>";

                    /* Champ pour le mot de passe */
                    echo "<div class=\"mdp\">";
                    /* Indicateur du format requis */
                    echo "<span id=\"aide-mdp\" class=\"form__tooltip\" data-tooltip=\"Votre mot de passe doit contenir au moins une majuscule, un chiffre et un caractère spécial\">?</span>";
                    echo $this->Form->control('motDePasse', ['type' => 'password',
                                                             'pattern' => '(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z0-9]).{8,30}',
                                                             'required title' => "Votre mot de passe doit contenir au moins une majuscule, un chiffre et un caractère spécial",
                                                             'autocomplete' => 'off',
                                                             'label' => 'Mot de passe *',
                                                             'id' => 'mdp']);

                    /* Affichage de l'oeil pour afficher/masquer le mot de passe */
                    echo "<p id=\"oeil\">";
                    echo $this->Html->image('eye_show.png', ['onClick' => 'Afficher()',
                                                    'fullBase' => false,
                                                    'id' => 'imageOeil']);
                    echo "</p>";

                    echo "</div></td><td>";

                    /* Champ pour la confirmation du mot de passe */
                    echo "<div class=\"cmdp\">";
                    echo $this->Form->control('confirmerMotDePasse', ['type' => 'password',
                                                                      'label' => 'Confirmer le mot de passe*',
                                                                      'id' => 'cmdp']);

                    /* Affichage de l'oeil pour afficher/masquer le mot de passe */
                    echo "<p id=\"oeil\">";
                    echo $this->Html->image('eye_show.png', ['onClick' => 'conf_Afficher()',
                                                             'fullBase' => false,
                                                             'id' => 'cimg']);
                    echo "</p>";

                    echo "</div></td></tr>";
                    echo "<tr><td>";

                    /* Champ pour le téléphone */
                    echo "<div id=\"tel\">";
                    /* Indicateur du format requis */
                    echo "<span id=\"aide-tel\" class=\"form__tooltip\" data-tooltip=\"Votre téléphone doit commencer par 06 ou 07 et doit contenir 10 chiffres\">?</span>";            
                    echo $this->Form->control('telephone', ['autocomplete' => 'off',
                                                            'pattern' => '(^06|07)[0-9]{8}',
                                                            'label' => 'Téléphone *',
                                                            'required title' => 'doit contenir 10 chiffres et commencer par 06 ou 07']);

                    echo "</div>";
                    echo "</td><td>";

                    /* Récupération de la date du jour -18 ans pour la limite d'âge */
                    setlocale(LC_TIME, 'fra_fra');

                    $jour = date("d");
                    $mois = date("m");
                    $anne = date("Y")-18;
                    /* Champ pour la date de naissance */
                    echo "<div class=\"date-naissance\">";
                    echo "<span id=\"aide-naissance\" class=\"form__tooltip\" data-tooltip=\"Vous devez avoir minimum 18 ans.\">?</span>";
                    echo $this->Form->control('naissance', ['type' => 'date',
                                                            'autocomplete' => 'off',
                                                            'label' => 'Date de naissance *',
                                                            'max' => ''.$anne.'-'.$mois.'-'.$jour.'']);

                    echo "</div>";
                    echo "</td></tr>";
                    echo "<tr><td>";

                    /* Champ pour le genre */
                    echo "<label id='radio'>Genre* :</label>";
                    echo $this->Form->radio('genre', 
                    [
                        [ 'value' => 'm', 'text' => 'Homme'],
                        [ 'value' =>'f', 'text' => 'Femme'],
                        [ 'value' =>'a', 'text' => 'Autre'],
                    ]);

                    echo "</td><td>";

                    /* Champ pour la possession ou non d'une voiture */
                    echo "<label id='radio'>Possédez-vous une voiture ? *</label>";
                    echo $this->Form->radio('estConducteur', 
                    [
                        [ 'value' => 'Oui', 'text' => 'Oui', 'onclick' => 'montrerChamp()'],
                        [ 'value' => 'Non', 'text' => 'Non',  'onclick' => 'montrerChamp()'],
                    ]);

                    echo "</td></tr>";
                    echo "<tr><td>";

                    /* Champ pour le modèle de la voiture */
                    echo "<label id=\"labelVoiture\" for=\"voiture\">Modèle de la voiture</label>";
                    echo $this->Form->control('typeVoiture', [
                        'id' => 'voiture',
                        'style' => 'display: none',
                        'placeholder' => 'Ex : Renault Twingo'
                    ]);

                    echo "</td><td>";

                    echo "<div class=\"plaque\">";
                    echo "<label id=\"labelImmatriculation\" for=\"immatriculation\">Plaque d'immatriculation</label>";

                    /* Indicateur du format requis */
                    echo "<span id=\"aide-immatriculation\" class=\"form__tooltip\" data-tooltip=\"Format : 2 lettres - 3 chiffres - 2 lettres\" style=\"display: none\">?</span>";

                    /* Champ pour le numéro de plaque d'immatriculation */
                    echo $this->Form->control('immatriculation', [
                        'id' => 'immatriculation',
                        'style' => 'display: none',
                        'label' => 'Plaque d\'immatriculation',
                        'placeholder' => 'Ex : AB-000-AB',
                        'pattern' => '[A-Z]{2}-[0-9]{3}-[A-Z]{2}',
                    ]);

                    echo "</div>";
                    echo "</td></tr>";
                    echo "<tr><td>";

                    /* Champ pour la photo de profil */
                    echo $this->Form->control('pathPhoto_file', ['type' => 'file', 'label' => 'Photo de profil']);

                    echo "</td></tr></table>";
                ?>
            </fieldset>
            <?= $this->Form->button(__('S\'inscrire')) ?>
            <p>
            <?php echo "* champs obligatoires" ; ?></p>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>

