<?php
use Cake\Datasource\ConnectionManager;

    $conn = ConnectionManager::get('default');

    $session_active = $this->request->getAttribute('identity');
    //Récupération de l'identifiant de l'utilisateur en cours
    $idM = $session_active->idMembre;
?>
<div class="container">
    <div style="margin-left:-1em;">
        <div class="text-center"> 
            <h1 class="col-md-12">Historique de soldes validés</h1>
        </div>
        <?php 


        //Recherche et excution des trajets effectué par l'utilisateur en cours
        $requete="SELECT * FROM `historiquetrajet` WHERE idMembre='".$idM."'";
        $historique = $conn->execute($requete)->fetchAll('assoc');
    


        if (empty($historique)){
            echo "<h3> Aucun trajet effectué ! </h3>";
        }else{
            
            echo "<table style='margin-left:-2em;'>
                    <tr>
                        <th>IBANExpéditeur</th>
                        <th>IBANDestinataire </th>
                        <th>Date d'evoie</th>
                        <th>NomExpéditeur</th>
                        <th>NomDestinataire</th>
                    </tr>";
            foreach($historique as $histo){
                $idOf = $histo["idOffre"]; //Identifiant de l'offre dans laquelle il a participé
                //Récupération de l'offre (avec ses détails)
                $offre = $conn->execute("SELECT * FROM `offre` WHERE idOffre='".$idOf."'")->fetchAll('assoc');

                //Récupération de l'identifiant de la ville
                $idVD = $offre[0]["idVilleDepart"];
                $idVA = $offre[0]["idVilleArrivee"];

                //Récupération du nom de la ville correspondante
                $villeDepart = $conn->execute("SELECT * FROM `villes_france_free` WHERE ville_id='".$idVD."'")->fetchAll('assoc');
                $villeArrivee = $conn->execute("SELECT * FROM `villes_france_free` WHERE ville_id='".$idVA."'")->fetchAll('assoc');

                //Récupération des données du conducteur
                $idConducteur = $offre[0]["idConducteur"];
                $conducteur = $conn->execute("SELECT * FROM `users` WHERE idMembre='".$idConducteur."'")->fetchAll('assoc');
                echo "<tr>";
                    echo "<td>".$villeDepart[0]["ville_nom"]."</td>";
                    echo "<td>".$villeArrivee[0]["ville_nom"]."</td>";

                        $timestamp = strtotime($offre[0]['horaireDepart']); 
                        $newDate = date("N-m-d-Y", $timestamp);
                        list($jour, $month, $day, $year) = explode("-", $newDate);
                        $months = array("janvier", "février", "mars", "avril", "mai", "juin", "juillet", "août", "septembre", "octobre", "novembre", "décembre");
                        $jours = array("Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi", "Dimanche");
                        echo "<td>".$jours[$jour-1]. " $day ".$months[$month-1]." $year </td>";
                    //echo "<td>".$villeArrivee[0]["ville_nom"]; echo "</td>";
                   
                    echo "<td>"."<a href=profile?id=".$idConducteur.">".$conducteur[0]["nom"]." ".$conducteur[0]["prenom"]. "</td>";
                    echo "<td>  <div class='btn-group' role='group'>  
                                    <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                        Passagers
                                        <span class='caret'></span>
                                    </button>
                                    <ul class='dropdown-menu'>";
                                        

                                        //Récupération des passagers
                                        $pass = $conn->execute("SELECT * FROM `historiquetrajet` WHERE idOffre='".$idOf."'")->fetchAll('assoc');
                                        foreach($pass as $passager){
                                            $idPers = $passager["idMembre"];
                                            if($idPers != $idConducteur){
                                                echo "<li><a href='#'>";
                                                
                                                $personne = $conn->execute("SELECT * FROM `users` WHERE idMembre='".$idPers."'")->fetchAll('assoc');

                                                echo "<a href=profile?id=".$idPers.">".$personne[0]["nom"]." ".$personne[0]["prenom"]."</a></li>";
                                            }
                                        }
                                        
                                    echo "</ul>
                                </div> 
                            </td>";
                echo "<td> <form method='post' action='ajouterNote?idOffre=".$idOf."''><input type='number' name='note' min='0' max='5' placeholder='Indiquez une note inférieure ou égale à 5'></form>";
                echo "</td>";      
                echo "</tr>";
            }

        }
        ?>
        </table>
    </div>
</div>