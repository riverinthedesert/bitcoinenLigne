<html>

<style>
  button {
    height: auto;
    font-style: normal;
    text-transform: unset !important;

  }

  label {
    font-size: medium;
    font-weight: normal;
    display: inline;
  }
</style>



<h1> Filtres recherche de offres </h1>

<form action="../offre" method="GET">
  <h2>Trier par</h2>
  <div class="form-group">
    <input type="radio" id="depart" name="tri" value="2">
    <label class="label_filtre" for="depart">Départ le plus tôt</label>

    </br>

    <input type="radio" id="prix" name="tri" value="1">
    <label for="prix">Prix le plus bas</label>

    </br>

    <input type="radio" id="note" name="tri" value="3">
    <label for="note">Note utilisateur</label>

  </div>

  <h2>Offre privée</h2>
  <div class="form-group">
    <input type="radio" id="nonpr" name="privee" value="0">
    <label class="label_filtre" for="nonpr">Afficher les offres publics</label>

    </br>

    <input type="radio" id="pr" name="privee" value="1">
    <label for="pr">Afficher les offres privées</label>

  </div>

  <div class="form-group">
    <h2>Heure de départ</h2>

    <input type="radio" id="6heures" name="depart" value="6">
    <label for="heure6">06:00-12:00</label>

    </br>

    <input type="radio" id="12heures" name="depart" value="12">
    <label for="heure12">12:01-18:00</label>

    </br>

    <input type="radio" id="18heures" name="depart" value="18">
    <label for="heure18">Après 18:00</label>

  </div>

  <div>
    <button class="btn btn-info" type="submit">Rechercher</button>
    <a href="../offre" class="btn btn-info" role="button">Annuler</a>
  </div>

</form>

</html>
