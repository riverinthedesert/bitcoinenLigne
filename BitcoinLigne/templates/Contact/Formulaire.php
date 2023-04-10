<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Formulaire de contact</title>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-show-password/1.0.3/bootstrap-show-password.min.js"></script>

</head>

<script>

/* Permet de compter le nombre de caractères restants du message */
$(function() {

    var message, longueur, restant;

    // réagit lorsqu'une touche est relâchée (https://www.w3schools.com/jquery/event_keyup.asp)
    $('#message').keyup(function() {

        message = $('#message').val();
        longueur = message.length;
        restant = 500 - longueur;

        $('#reste').html(restant);
    });
});

</script>

<body>
    <div class="container">

        <div style="margin-left: 20%; margin-right: 35%">
            <h1>Nous contacter</h1>
            <br/>
            <h4>Un souci, une question ? Faites-le nous savoir</h4>
        </div>

        <br>

        <?php

            $session_active = $this->request->getAttribute('identity');

            $estConnecte = false;

            if (!is_null($session_active)){

                $estConnecte = true;

                $nom = $session_active->nom;
                $prenom = $session_active->prenom;
                $mail = $session_active->mail;
            }
        ?>

        <form method="post">

            <div style="margin-left: 20%; margin-right: 40%">
                <label for="nom">Nom *</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                    <input type="text" name="nom" required="required" maxlength="50" placeholder="Dupont" id="nom" class="form-control" 
                    
                    <?php
                        if ($estConnecte)
                         echo "value=$nom readonly"
                    ?>
                    />

                </div>

                <br />

                <label for="prenom">Prénom *</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                    <input type="text" name="prenom" required="required" maxlength="50" placeholder="Camille" id="prenom" class="form-control" 
                    
                    <?php
                        if ($estConnecte)
                         echo "value=$prenom readonly"
                    ?>
                    
                    />
                </div>

                <br />

                <label for="mail">Adresse e-mail *</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                    <input type="email" name="mail" required="required" maxlength="50" placeholder="exemple@email.com" id="mail" class="form-control" 
                    
                    <?php
                        if ($estConnecte)
                         echo "value=$mail readonly"
                    ?>
                    
                    />
                </div>

                <br />

                <label for="mail">Nature du message *</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-th-list"></i></span>
                    
		            <select name="nature" id="nature" class="form-control">
                        <option value="Aide">Aide</option>
                        <option value="Réclamation">Réclamation</option>
                        <option value="Suggestion">Suggestion</option>
                        <option value="Autre">Autre</option>
		            </select>
                </div>

                <br />

                <label for="message">Message *</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span>
                    <textarea cols="10" rows="5" name="message" required="required" maxlength="500"  id="message" class="form-control" ></textarea>
                    
                </div>
                <span id="reste" name="reste">500</span><span> caractères restants</span>

                <br />

                <br />
                <?= $this->Form->submit(__('Envoyer')); ?>

                <p>
            <?php echo "* champs obligatoires" ; ?></p>

            </div>

        </form>

    </div>

</body>

</html>