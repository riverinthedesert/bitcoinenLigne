<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Récupération du mot de passe</title>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-show-password/1.0.3/bootstrap-show-password.min.js"></script>

</head>

<body>
    <div class="container">

        <div style="margin-left: 20%; margin-right: 35%">
            <h1>Récupération</h1>
            <br/>
            <h4>Veuillez indiquer votre adresse e-mail<br/>afin de recevoir votre nouveau mot de passe</h4>
        </div>

        <br>

        <form method="post">

            <div style="margin-left: 20%; margin-right: 40%">
                <label for="email">Adresse e-mail</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                    <input type="email" name="mail" required="required" title="xxx@yyy.zzz" maxlength="50" pattern="^(([-\w\d]+)(\.[-\w\d]+)*@([-\w\d]+)(\.[-\w\d]+)*(\.([a-zA-Z]{2,5}|[\d]{1,3})){1,2})$" placeholder="exemple@email.com" id="mail" class="form-control" />
                </div>

                <br />

                <br />
                <?= $this->Form->submit(__('Envoyer le mot de passe')); ?>

            </div>

        </form>

        <!-- Permet d'afficher/cacher le mot de passe -->
        <script type="text/javascript">
            $('.password').collapse();
        </script>

    </div>

</body>

</html>