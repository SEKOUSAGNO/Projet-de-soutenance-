<!DOCTYPE html>
<html lang="fr">

<head>

<meta charset="UTF-8">

<title>Connexion - SIMAN-JOB</title>


<style>

/* STYLE GENERAL */

body{

    margin:0;
    background:#007bff; /* Bleu */
    font-family:Arial, Helvetica, sans-serif;

}


/* TITRE */

.titre{

    text-align:center;

    font-size:35px;

    font-weight:bold;

    color:#007bff;

    margin-top:20px;

    text-transform:uppercase;

}



/* BANNIERE */

.banniere{

    width:75%;

    margin:15px auto;

    text-align:center;

}


.banniere img{

    width:100%;

    height:150px;

    object-fit:cover;

    border-radius:12px;

    box-shadow:0px 3px 10px gray;

}




/* FORMULAIRE */

.login{


    width:320px;

    margin:15px auto;

    background:white;

    padding:20px;

    border-radius:12px;

    box-shadow:0px 0px 10px #aaa;


}



.login h2{

    margin:5px;

    color:#007bff;

}




input{


    width:100%;

    padding:9px;

    margin-top:10px;

    border:1px solid #ccc;

    border-radius:6px;

    font-size:14px;


}




button{


    width:100%;

    padding:10px;

    margin-top:15px;

    background:#007bff;

    border:none;

    border-radius:6px;

    color:white;

    font-size:16px;

    cursor:pointer;


}


button:hover{

background:#0056b3;

}



/* LIENS */

a{

    text-decoration:none;

    color:#007bff;

    font-weight:bold;

    font-size:14px;

}



/* DESCRIPTION */


.description{


    width:70%;

    margin:15px auto;

    background:white;

    padding:15px;

    border-radius:10px;

    text-align:center;

    font-size:15px;

    line-height:1.4;

    box-shadow:0px 0px 8px #aaa;


}


.description h3{


    color:#007bff;

    margin:5px;

    font-size:22px;


}



/* FOOTER */


footer{

    text-align:center;

    background:#007bff;

    color:white;

    padding:10px;

    font-size:13px;

}



</style>


</head>


<body>



<div class="titre">

BIENVENUE SUR LA TOILE SIMAN-JOB

</div>



<div class="cv">


<img src="image/c.jpeg"

alt="accuel SIMAN-JOB">


</div>





<div class="login">


<h2 align="center">

Connexion

</h2>



<form action="traitement_login.php" method="POST">


<input

type="email"

name="email"

placeholder="Adresse Email"

required>



<input

type="password"

name="password"

placeholder="Mot de passe"

required>



<button type="submit">

Se connecter

</button>



</form>



<br>


<center>


<a href="mot_de_passe_oublie.php">

Mot de passe oublié ?

</a>


<br><br>


<a href="inscription.php">

Créer un compte

</a>


</center>



</div>






<div class="description">


<h3>

À propos de SIMAN-JOB

</h3>

<p>

SIMAN-JOB est une plateforme web qui a pour objectif de faciliter l'insertion 
professionnelle des étudiants et jeunes diplômés en mettant en relation les 
candidats et les entreprises à travers un espace numérique centralisé.
Avec SIMAN-JOB, la recherche d'emploi et de stage devient plus simple, 
rapide et accessible 
grâce à une solution moderne adaptée aux besoins du monde professionnel.

</p>


</div>




<footer>

© 2026 SIMAN-JOB - Plateforme de recrutement

</footer>



</body>

</html>