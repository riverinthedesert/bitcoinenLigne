<style>
body {
	background: WhiteSmoke  no-repeat center top;
}

</style>
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.7.3/themes/base/jquery-ui.css">

<?php 
    use Cake\Datasource\ConnectionManager;
    $conn = ConnectionManager::get('default');
    
    $session_active = $this->request->getAttribute('identity');

    //Faire la vérification des offres
    $donnees = $conn->execute("SELECT * FROM `offre` ")->fetchAll('assoc');
         
    
    

    function offreT($of, $cond, $date)
    {
        //of = idOffre
        //cond = idConducteur
        //date = dateTrajet

        $conn = ConnectionManager::get('default');
        
        //Fonction vérifier si offre est déjà ajouté sinon ajouter
        $trajet = $conn->execute("SELECT * FROM `historiquetrajet` ")->fetchAll('assoc');
        $oui = 0;
        foreach($trajet as $t){
            if($t['idOffre'] == $of)
                $oui = 1;
        }
        if($oui == 0){
            $idM = $cond;
            $idO = $of;
            $d = $date;
            $conn->insert('historiquetrajet', [
                'idMembre' => $idM ,
                'idOffre' =>  $idO ,
                'dateTrajet' => $d
                ]);

            //Ajouter les passagers
            $membre = $conn->execute("SELECT * FROM `copassager` WHERE idOffre='".$idO."' ")->fetchAll('assoc');
            foreach($membre as $m){
                $me = $m['idMembre'];
                $conn->insert('historiquetrajet', [
                    'idMembre' => $me ,
                    'idOffre' =>  $idO ,
                    'dateTrajet' => $d
                    ]);
            }
        }
    }


    foreach($donnees as $don){
        setlocale(LC_TIME, 'fra_fra');

        $jour = date("d");
        $mois = date("m");
        $anne = date("Y");
        $heure = date("H");

        $dateA = date_create($don['horaireArrivee']);

        $don['horaireArrivee'] = date_format($dateA, 'Y-m-d');

        $jourA = date_format($dateA, 'd');
        $moisA = date_format($dateA, 'm');
        $anneeA = date_format($dateA, 'Y');
        $heureA = date_format($dateA, 'H');


        if($anne == $anneeA){
            if($mois == $moisA){
                if($jour == $jourA){
                    if($heure == $heureA){
                        offreT($don['idOffre'], $don['idConducteur'], $don['horaireArrivee']);
                    }
                    elseif($heure > $heureA){
                        offreT($don['idOffre'], $don['idConducteur'], $don['horaireArrivee']);
                    }
                }
                elseif($jour > $jourA){
                    offreT($don['idOffre'], $don['idConducteur'], $don['horaireArrivee']);
                }
            }
            elseif($mois > $moisA){
                offreT($don['idOffre'], $don['idConducteur'], $don['horaireArrivee']);
            }
        }
        elseif($anne > $anneeA){
            offreT($don['idOffre'], $don['idConducteur'], $don['horaireArrivee']);
        }
        echo "\n";
    }

?>

</div>

	<div class="text-center">
        <font size="10"><p><span style="color:Navy">Qu'est-ce que souhaitez-vous faire<?php 
        
        // on vérifie si l'utilisateur est connecté
        $session_active = $this->request->getAttribute('identity');
    
        // ajout du prénom
        if (!is_null($session_active))
            echo ', ' . $session_active->prenom;
        ?>
        
        ?</span></p></font>
        

<script> $("#villeDepart").autocomplete({
	source: function (request, response) {
		$.ajax({
			url: "https://api-adresse.data.gouv.fr/search/?city="+$("input[name='villeDepart']").val(),
			data: { q: request.term },
			dataType: "json",
			success: function (data) {
				var cities = [];
				response($.map(data.features, function (item) {
					// Ici on est obligé d'ajouter les villes dans un array pour ne pas avoir plusieurs fois la même
					if ($.inArray(item.properties.postcode, cities) == -1) {
						cities.push(item.properties.postcode);
						return { label: item.properties.city + "-" + item.properties.postcode , 
								 value: item.properties.city
						};
					}
				}));
			}
		});
	}
});

$("#villeDarrivee").autocomplete({
	source: function (request, response) {
		$.ajax({
			url: "https://api-adresse.data.gouv.fr/search/?city="+$("input[name='villeDarrivee']").val(),
			data: { q: request.term },
			dataType: "json",
			success: function (data) {
				var cities = [];
				response($.map(data.features, function (item) {
					// Ici on est obligé d'ajouter les villes dans un array pour ne pas avoir plusieurs fois la même
					if ($.inArray(item.properties.postcode, cities) == -1) {
						cities.push(item.properties.postcode);
						return { label: item.properties.city + "-" + item.properties.postcode ,  
								 value: item.properties.city
						};
					}
				}));
			}
		});
	}
});
</script>
	</div>


<body>
<br>

<div class="text-center">
<?php echo $this->Html->image('accueil2.png', ['alt' => 'Accueil']); ?>
	<br>
	<div class="panel panel-primary">
		<div class="panel-body">
			<h2><span class="glyphicon glyphicon-road"></span> Utiliser bitconLigne en réduisant le coût du versement. C'est désormais possible avec le BitconLigne.<h2>
		</div>
	</div>
	
</div>

<div class="container">
	<div class="text-center">
		<h3>Vous souhaitez utiliser votre compte ? Pas de problème ! </h3>
		<a href="AjouterUneOffre" class="btn btn-default btn-lg " role="button" aria-disabled="true"><?php echo $this->Html->image('AjoutPublique.png', ['alt' => 'AjoutPublic']); ?><font size="5">Proposer un nouveau solde</font></a> <br><br>
        <?php
            if (!is_null($session_active)){
                $idMembre=$session_active->idMembre;

                //Recherche des groupes
                $donnees = $conn->execute($requete="SELECT * FROM `groupemembre` WHERE idUtilisateur=".$idMembre)->fetchAll('assoc');
                
                $admOuMembr = 0;
		
                foreach($donnees as $don){
                    $admOuMembr++;
                    $tabl[] = $don['idGroupe'];
                }

                
            }
        ?>
    </div>
	<br>
	<div class="container">
        <div class="row">
            <div class="col-sm-4">
                <h3>Ajouter un solde</h3>        
                <p>Vous souhaitez faire un sold avec votre compte mais vous ne <b>connaissez la méthoed ?</b></p>
                <p><br><a href="AjouterUneOffre"><span class="glyphicon glyphicon-plus"></span> Ajoutez un solde</a> avec toutes vos informations de versement. Ensuite attendez.</p>
                <p><br><span class="glyphicon glyphicon-eye-open"></span> Vérifiez vos mails et vos <a href="notification">notifications</a> sur le site, une fois qu'il est effectué. c'est fait</p>
                <p><br><span class="glyphicon glyphicon-ok"></span> Et voilà ! Le solde est joué.</p>
            </div>
            <div class="col-sm-4">
                <h3>Rechercher un taux de échange</h3>
                <p>Vous souhaitez bénéficier avec un taux de échange <b> meilleur ?</b></p>
                <p><br><a href="offre"><span class="glyphicon glyphicon-search"></span> Recherchez un taux de échange</a>. Filtrez les taux de échange d'un semaine. Ensuite attendez la notifiation par mail.</p>
                <p><br><span class="glyphicon glyphicon-ok"></span>  Et voilà le taux de échange meilleur. Vous pouvez faire un solde !</p>
            </div>
        </div>
    </div>
	<br>
    <font size="10"><p>Retrouvez des <b><span style="color:SteelBlue">centaines de utilisateur bitcoin près de chez</span></b> 
                        vous et profitez des <b><span style="color:SteelBlue">meilleurs prix</span></b> de versement.</p></font>
</div>

<?php if (is_null($session_active)){
    ?>
<div  style="background-color:#AFEEEE;">
    <div class="text-center">
        <p> <font size="5">Vous n'avez pas encore de compte ? <a href="inscription">Inscrivez-vous</a> ! </font></p>
    </div>
</div>
<?php }
?>



	

</body>
