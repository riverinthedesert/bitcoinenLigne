function Afficher(){ 

    /* Récuperation des données du mot de passe pour changer le bon champ */
    var mdp = document.getElementById("mdp"); 
    var img = document.getElementById("imageOeil");

    /* Affichage du mot de passe */
    if (mdp.type === "password"){ 
        mdp.type = "text"; 
        img.src = "/BitcoinLigne/BitcoinLigne/webroot/img/eye_hide.png";

    /* Masquage du mot de passe */
    }else{ 
        mdp.type = "password";
        img.src = "/BitcoinLigne/BitcoinLigne/webroot/img/eye_show.png";
    } 
} 

function conf_Afficher(){ 

    /* Récuperation des données du mot de passe pour changer le bon champ */
    var mdp = document.getElementById("cmdp"); 
    var img = document.getElementById("cimg");

     /* Affichage du mot de passe */
    if (mdp.type === "password"){ 
        mdp.type = "text"; 
        img.src = "/BitcoinLigne/BitcoinLigne/webroot/img/eye_hide.png";
    } 

    /* Masquage du mot de passe */
    else{ 
        mdp.type = "password";
        img.src = "/BitcoinLigne/BitcoinLigne/webroot/img/eye_show.png";
    } 
}


function montrerChamp(){

    /* Si l'utilisateur indique qu'il a une voiture */
    if(document.getElementById("estconducteur-oui").checked){
        /* Affichage des champs des informations de la voiture de l'utilisateur */
        document.getElementById("voiture").style.display="block";
        document.getElementById("immatriculation").style.display="block";
        document.getElementById("labelVoiture").style.display="block";
        document.getElementById("labelImmatriculation").style.display="block";
        document.getElementById("aide-immatriculation").style.display="block";
        document.getElementById("voiture").required= true;
        document.getElementById("immatriculation").required= true;

    /* Si l'utilisateur indique qu'il n'a pas de voiture */
    }else{
        /* Masquage des champs des informations de la voiture de l'utilisateur */
        document.getElementById("voiture").style.display="none";
        document.getElementById("immatriculation").style.display="none";
        document.getElementById("labelVoiture").style.display="none";
        document.getElementById("labelImmatriculation").style.display="none";
        document.getElementById("aide-immatriculation").style.display="none";
        document.getElementById("voiture").required= false;
        document.getElementById("immatriculation").required= false;
    }
}
