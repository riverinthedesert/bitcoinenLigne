<html>

<h1 class="text-center">Mes notifications</h1>




<script>
    $(document).ready(function() {
        // Delete 
        $('.delete').click(function() {
            var el = this;

            var deleteid = $(this).data('id'); // Par quoi on identifie la notif
            var confirmalert = confirm("Etes vous sûr ?");
            if (confirmalert == true) {
                // AJAX Request
                $.ajax({
                    url: 'notification/delete',
                    type: 'GET',
                    data: {
                        id: deleteid
                    },
                    success: function(response) {

                        if (response == 1) { // Enleve le HTML
                            $(el).closest('div').fadeOut(200, function() {
                                $(this).remove();
                            });
                        } else {
                            alert('Mauvais id.');
                        }

                    }
                });
            }

        });

    });
</script>

<div class="list-group">

    <?php
    if (sizeof($not) != 0) {
        foreach ($not as $item) {
			$idOffre = $item["idOffre"];
			$idConducteur = $item["idConducteur"];
            $date_creation = $item["DateCreation"]; // Date en pleine lettre
            $date_creation_string = ucwords(utf8_encode(strftime("%A %d %B %G", strtotime(($date_creation)))));

            $date_depart = $item["horaireDepart"]; // Date en pleine lettre
            $date_depart_string = ucwords(utf8_encode(strftime("%A %d %B", strtotime(($date_depart)))));

            if ($item["estLue"] == "0") { // Pas encore lu 
                echo '<div id="' . $item["idNotification"] . '" class="list-group-item list-group-item-action ">';
                echo $item["message"];
            } else { // lu
                echo '<div id="' . $item["idNotification"] . '" class="list-group-item list-group-item-action list-group-item-info ">';
                echo "<p style='margin-right:1em;' class='glyphicon glyphicon-eye-open'></p>";
                echo $item["message"];
            }
            echo "<a data-id='" . $item["idNotification"] . "'style='text-decoration:none;cursor:pointer;'class='glyphicon glyphicon-remove float-right delete'></a>";
            echo "<p style='margin-right:2em;'class='float-right'>" . $date_creation_string . "</p>";

            if ($item["necessiteReponse"] == "1") { // A voir plus tard pour accepter/refuser une demande
                echo '</br>';
                echo '<a class="btn btn-primary" href="http://localhost/BitcoinLigne/BitcoinLigne/Notification/accepter?id='.$idOffre.'" role="button">Accepter</a> ';
                echo '<a class="btn btn-danger" href="http://localhost/BitcoinLigne/BitcoinLigne/Notification/refuser?id='.$idOffre.'" role="button">Refuser</a> ';
				
            }
            if ($item["idOffre"] != "") { // Détails offre dans notification + lien
                echo  '<span style="margin-left:1em;"><a style="cursor:pointer;" href="offre/details?idOffre=' . $item["idOffre"] . '" ">Offre</a> du <strong>' . $date_depart_string . '</strong></span> départ à <strong>' . ucfirst($item["ville_nom_simple"]) . '</strong>';
            }
            echo '</div>';
        }
    }else{
        echo "</br></br><h3 class ='text-center'>Pas de notification pour le moment !</h3>";
    }

    ?>

</div>

</html>