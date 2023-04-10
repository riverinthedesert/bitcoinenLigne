<?php

// on vérifie si l'utilisateur est connecté
$session_active = $this->request->getAttribute('identity');

// pour le prenom
if (!is_null($session_active))
    //echo $session_active->prenom;
    //echo $session_active->idMembre;

 $query1 = $offre->find();
 $query2 = $users->find();
 $query3 = $ville->find();
 $query4 = $copassager->find();

foreach ($query4 as $row) {
    //echo  $row->idMembre ;
}


?>
<?= $this->Html->css(['visuoffre']) ?>
<div class="row">
    <div class="column-responsive column-400">
        <div class="users view content">
            <h3><?= h($session_active->prenom  ) ?></h3>
            <table>
            <?php 
            echo"<tr>
            <th>IdSolde</th>
            <th>HoraireEnvoie</th>
            <th>HoraireArrivee</th>
            <th>Montant</th>
            <th>CléExpéditeur</th>
            <th>CléDestinataire</th>
            <th>NomExpéditeur</th>
            <th>Note Expéditeur</th>
            <th>Frais</th>
            </tr>";
            foreach ($query1 as $row) {
                // si le colonne est vide
                $blank=0;

                $estCopassager=false;
                foreach ($query4 as $rowfin){
                    if (($rowfin->idMembre==$session_active->idMembre)&&($rowfin->idOffre==$row->idOffre)){
                        $estCopassager=true;
                    }
                }
                if(($row->idConducteur==$session_active->idMembre)||$estCopassager){
                    echo"<tr>
                            <td>".$row->idOffre." </td>
                        
                            <td>".$row->horaireDepart." </td>
                        
                            <td>".$row->horaireArrivee." </td>

                            <td>".$row->nbPassagersMax." </td>";


                    foreach ($query3 as $row2) {
                        if($row->idVilleDepart==$row2->ville_id){
                            echo "<td>".$row2->ville_nom." </td>";
                            $blank=1;
                            break;
                        }
                    }
                    if($blank==0){
                        echo "<td></td>";
                        $blank=0;
                    }

                    foreach ($query3 as $row3) {
                        if($row->idVilleArrivee==$row3->ville_id){
                            echo "<td>".$row3->ville_nom." </td>";
                            $blank=1;
                            break;
                        }
                    }
                    if($blank==0){
                        echo "<td></td>";
                        $blank=0;
                    }

                    foreach ($query2 as $row4) {
                        if($row->idConducteur==$row4->idMembre){
                            echo "<td>".$row4->prenom." ".$row4->nom." </td>";
                            $blank=1;
                            break;
                        }
                    }
                    if($blank==0){
                        echo "<td></td>";
                        $blank=0;
                    }

                    foreach ($query2 as $row5) {
                        if($row->idConducteur==$row5->idMembre){
                            echo "<td>".$row4->noteMoyenne." </td>";
                            $blank=1;
                            break;
                        }
                    }
                    if($blank==0){
                        echo "<td></td>";
                        $blank=0;
                    }

                    echo "<td>".$row->prix." </td>";
                    echo "<td><a role='button' class='btn btn-info' href = 'Offre/details?idOffre=$row->idOffre'>Détails</a></td>"; ?>
                    
                    <td>
                    <?= $this->Form->postLink('Annuler solde', ['action' => 'delete', $row->idOffre], ['confirm' => 'Êtes vous sûr ?']); ?>
                    </td></tr>
                    <?php
                }
            }
            ?>
            </table>
        </div>
    </div>
</div>

