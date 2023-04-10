<html>

<style>
    hr {
        border-top: 1px solid gray;
    }
</style>

<?php
   	define('DB_SERVER', 'localhost');
	define('DB_USERNAME', 'root');
	define('DB_PASSWORD', '');
	define('DB_NAME', 'getride');
	$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
	if($conn === false){
		die("ERREUR : Impossible de se connecter. " . mysqli_connect_error());
	}
	
	//Recuperer l'id 
	$session_active = $this->request->getAttribute('identity');

	$id = $session_active->idMembre;




?>

<script>
    // FONCTION PARTICIPER
    $(document).on("click", ".participer", function() {
        // ajouter notif
        var el = this;
        var addid = $(this).data('id');
        // AJAX Request
        $.ajax({
            url: 'participer', // Participer.php ne fait que ajouter une notification à l'utilisateur qui postule pour l'instant
            type: 'GET',
            data: {
                id: addid
            },
            success: function(response) {

                //if (response == 1) { // Enleve le HTML
                    $(el).closest('a').css('background-color', 'red');
                    $(el).closest('a').text("Ne plus participer");
                    document.getElementById("participer").classList.replace('participer', 'no_participer');
                //} else {
                    //alert('Mauvais id.');
                //}

            }
        });


    });

    // FONCTION NE PAS PARTICIPER
    $(document).on("click", ".no_participer", function() {
        // ajouter notif
        var el = this;
        var deleteid = $(this).data('id');
        // AJAX Request
        $.ajax({
            url: 'no_participer', // Participer.php ne fait que ajouter une notification à l'utilisateur qui postule pour l'instant
            type: 'GET',
            data: {
                id: deleteid
            },
            success: function(response) {

                //if (response == 1) { // Enleve le HTML
                    $(el).closest('a').css('background-color', '#5bc0de');
                    $(el).closest('a').text("Participer au trajet");
                    document.getElementById("participer").classList.replace('no_participer', 'participer');
                /*} else {
                    alert('Mauvais id.');
                }*/

            }
        });

    });
</script>

<h1 class="text-center font-weight-bold"> Détails de l'offre</h1>

<?php
// DATE EN TITRE

if (sizeof($offre) == 0) {
    echo "<h2>Cette offre n'existe plus ! </h2>";
}else{

$date_depart = $offre[0]["horaireDepart"];
$date_depart_string = ucwords(utf8_encode(strftime("%A %d %B", strtotime(($date_depart)))));
$heure_depart_string = strftime("%Hh%M", strtotime(($date_depart)));

$date_arrivee = $offre[0]["horaireArrivee"];
$date_arrivee_string = ucwords(utf8_encode(strftime("%A %d %B", strtotime(($date_arrivee)))));
$heure_arrivee_string = strftime("%Hh%M", strtotime(($date_arrivee)));

//Heure

echo "<h2 class = 'text-center'>" . $date_depart_string . "</h2>";
echo "</br>"
?>

<body>
    <div class="row">
        <div class="col-md-6">

            <h3>Départ</h3>

            <p class="font-weight-bold">
                De
                <strong><?php echo (ucfirst($offre[0]["nomVilleDepart"])); ?></strong>
                le
                <strong><?php echo ($date_depart_string); ?></strong>
                à
                <strong><?php echo ($heure_depart_string); ?></strong>
            </p>

        </div>

        <br>

        <div class="col-md-4">

            <?php

            if (sizeof($etape) > 0) { // 1 étape ou plus

                echo "<h3>Étapes</h3>";

                echo "<div>";

                echo "<strong>" . ucfirst($etape[0]["ville_nom_simple"]) . "</strong>";

                echo "</br>";

                echo "</div>";
            }

            if (sizeof($etape) > 1) { // 2 étapes ou plus

                for ($i = 1; $i < sizeof($etape); $i++) {

                    if ($i != sizeof($etape) - 1) echo "<p style='margin-left:2.5em;'class='glyphicon glyphicon-arrow-down '></p>";

                    echo "<div>";

                    echo "<strong>" . ucfirst($etape[$i]["ville_nom_simple"]) . "</strong>";

                    echo "</br>";

                    if ($i != sizeof($etape) - 1) echo "<p style='margin-left:2.5em;'class='glyphicon glyphicon-arrow-down '></p>";

                    echo "</div>";
                }
            }
            ?>


        </div>
    </div>

    <br>

    <div style="margin-left:0.38em;">

        <h3>Arrivée</h3>

        <p class="font-weight-bold">
            À
            <strong><?php echo (ucfirst($offre[0]["nomVilleArrivee"])); ?></strong>
            le
            <strong><?php echo ($date_arrivee_string); ?></strong>
            à environ
            <strong><?php echo ($heure_arrivee_string); ?></strong>
        </p>

    </div>


    <hr>

    <div>
        <div class="row">
            <div class="col-md-4">
                <h3>Prix</h3>

                <p>

                    <?php echo ("<strong>" . $offre[0]["prix"] . "€" . "</strong>" . " pour 1 personne"); ?>

                </p>
            </div>

            <div class="col-md-4">
                <h3>Passagers autorisés</h3>

                <p>
                    <?php echo ("<strong>" . $offre[0]["nbPassagersMax"] . " personnes </strong>"); ?>
                </p>
            </div>
        </div>


    </div>

    <hr>

    <div>

        <h3>Conducteur</h3>

        <strong>

            <?php

            echo (ucfirst($offre[0]["nom"]));
            echo " ";
            echo (ucfirst($offre[0]["prenom"]));

            ?>

        </strong>

    </div>

    <div>

        <?php

        if ($offre[0]["note"] != "") {
            echo ("Note : ");
            echo " ";
            echo (ucfirst($offre[0]["note"]));
            echo ("/5");
        }

        ?>


    </div>

    <hr>

    <div>

        <?php // A voir plus tard pour prendre part à un trajet

            $session_active = $this->request->getAttribute('identity');

            if (!is_null($session_active) && $session_active->idMembre != $offre[0]['idConducteur']) {
            // Condition à voir si un utilisateur n'est pas l'auteur de l'offre et si l'utilisateur n'a pas déjà postulé.
            if ($notif_test==0)
            echo '<a id="participer" data-id="' . $offre[0]["idOffre"] . '" class="btn btn-info participer" role="button">Participer au trajet</a>';
            else echo '<a style="background-color:red" id="participer" data-id="' . $offre[0]["idOffre"] . '" class="btn btn-info no_participer" role="button">Ne plus participer</a>';
        }

        ?>

        <a onclick="goBack()" class="btn btn-info" role="button">Retour</a>
	<?php
	
		//Si on est le créateur de l'offre
		if (!is_null($session_active) && $session_active->idMembre == $offre[0]['idConducteur']){
			//Afficher le bouton "editer"
	?>
	<?= $this->Form->postButton(__('Editer votre trajet'), ['action' => 'editer?id='.$_GET['idOffre'].''], ['class'=>'your_class', 'confirm' => __('Voulez-vous confirmer l\'édition de votre trajet?')]) ?>
	
	<?php
		}
    }
	?>

    </div>

	


    </br>


</body>

<script>
    function goBack() {
        window.history.back();
    }
</script>

</html>
