<div style="margin-left:-2em;">
    <div class="row">
        <a style="
        position:fixed;
        cursor:pointer;
        margin-left:85.5em;
        margin-top:34em;
        width:2em;" 
        id="button" type="button" href="#top" class="btn btn-warning">
        <span style="width:0.5em;margin-left:-0.53em;" class="glyphicon glyphicon-menu-up"></span></a>
        <h1 class="col-md-12">Offres filtrées </h1>
        <form style="margin-right:1em;" action="offre/view" method="get" class="pull-left">
            <input type="submit" value="Filtres">
        </form>
        <form action="FiltreAvance" method="get" class="pull-left">
            <input type="submit" value="Filtres Avancés">
        </form>

        <a id="top" style="height:2.8em;margin-left:1em;" href="HistoriqueOffre" class="btn btn-info col" role="button">Historique de recherche</a>
    </div>
    <?php

    if (sizeof($offre_filtres_applied) <= 0) {
        echo "<h3 style='margin-left:0.3em;'>Pas d'offres trouvées ! </h3>";
    } else {


        echo '<table style="margin-left:-4em;">
    <tr>
        <th>N°</th>
        <th style="padding-right:5em;">Date de départ</th>
        <th style="padding-right:5em;">Date d\'arrivée</th>
        <th>Passagers</th>
        <th>Ville départ</th>
        <th>Ville arrivée</th>
        <th>Conducteur</th>
        <th>Note conducteur</th>
        <th>Prix</th>
        <th></th>
    </tr>';

        foreach ($offre_filtres_applied as $item) {

            $date_depart = $item["horaireDepart"];
            $date_depart_string = ucwords(utf8_encode(strftime("%A %d %B %G", strtotime(($date_depart)))));
            $heure_depart_string = strftime("%Hh%M", strtotime(($date_depart)));

            $date_arrivee = $item["horaireArrivee"];
            $date_arrivee_string = ucwords(utf8_encode(strftime("%A %d %B %G", strtotime(($date_arrivee)))));
            $heure_arrivee_string = strftime("%Hh%M", strtotime(($date_arrivee)));

            echo "<tr>";
            echo "<td>";
            echo $item["idOffre"];
            echo "</td>";
            echo "<td>";
            echo $date_depart_string . " <strong>" . $heure_depart_string."</strong>";
            echo "</td>";
            echo "<td>";
            echo $date_arrivee_string . " <strong>" . $heure_arrivee_string."</strong>";
            echo "</td>";
            echo "<td>";
            echo $item["nbPassagersMax"];
            echo "</td>";
            echo "<td>";
            echo ucfirst($item["nomVilleDepart"]);
            echo "</td>";
            echo "<td>";
            echo ucfirst($item["nomVilleArrivee"]);
            echo "</td>";
            echo "<td>";
            echo "<a style='height:2.8em;margin-left:1em;' href='profile?id=".$item["idConducteur"]."' class='btn btn-light col' >".ucfirst($item["nom"]) . " " . ucfirst($item["prenom"]);
            echo "</td>";
            echo "<td>";
            if ($item["noteMoyenne"] != "") echo $item["noteMoyenne"];
            else echo "Aucune note";
            echo "</td>";
            echo "<td>";
            echo "<strong>" . $item["prix"] . "€</strong>";
            echo "</td>";
            $idOffre = $item["idOffre"];
            echo "<td>";
            echo "<a role='button' class='btn btn-info' href = 'Offre/details?idOffre=$idOffre'>Détails</a>";
            echo "</td>";
            echo "</tr>";
        }
        /*  foreach ($utilisateur as $user){
        echo $user["idMembre"];
    }*/
    }
    ?>
    </table></br>
    <div>
        <?php
        if (isset($test_filtre)) {
            if ($test_filtre == "1")
                echo '<form style="margin-left:1em;"  action="Offre" method="get" class="pull-left">
        <input style="background-color:cornflowerblue; border-color:cornflowerblue;" type="submit" value="Enlever les filtres actuels">
    </form>';
        } else {
            echo '<form style="margin-left:1em;"  action="Offre" method="get" class="pull-left">
        <input style="background-color:cornflowerblue; border-color:cornflowerblue;" type="submit" value="Enlever les filtres actuels">
    </form>';
        }
        ?>
    </div>
    <div class="pull-right"><strong>Filtres appliqués : </strong> <?php echo $string_filtre ?></div>
</div>