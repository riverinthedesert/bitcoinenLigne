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

<form action="Offre" method="GET">
  <label for="villeDepart">Ville de départ : </label>
  <input type="text" name="villeDepart"/>
  <label for="villeDarivee">Ville d'arrivée : </label>
  <input type="text" name="villeDarrivee"/>
  <label for="date">Date de départ : </label>
  <input type="date" name="date"/>
  <label for="horaireDepart">Horaire de départ : </label>
  <input type="number" name="horaireDepart"/>
  <label for="nombrePassagersMax">Nombre de passagers : </label>
  <input type="number" name="nombrePassagersMax"/>
  <label for="prix">Prix : </label>
  <input type="number" name="prix"/>

  <div>
    <button class="btn btn-info" type="submit">Rechercher</button>
    <a href="Offre" class="btn btn-info" role="button">Annuler</a>
  </div>

</form>

</html>
