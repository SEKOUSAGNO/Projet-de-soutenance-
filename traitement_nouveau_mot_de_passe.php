<?php

session_start();

require "database.php";


/*=========================================================
    Vérification des données reçues
=========================================================*/


if (

    !isset($_POST['token']) ||
    !isset($_POST['nouveau_mot_de_passe']) ||
    !isset($_POST['confirmation'])

) {

    die("Données manquantes.");

}



$token = $_POST['token'];

$nouveau_mot_de_passe = $_POST['nouveau_mot_de_passe'];

$confirmation = $_POST['confirmation'];



/*=========================================================
    Vérifier que les mots de passe correspondent
=========================================================*/


if ($nouveau_mot_de_passe !== $confirmation) {


    die("Les deux mots de passe ne correspondent pas.");

}



/*=========================================================
    Vérifier la longueur du mot de passe
=========================================================*/


if (strlen($nouveau_mot_de_passe) < 6) {


    die("Le mot de passe doit contenir au moins 6 caractères.");

}



/*=========================================================
    Vérifier le token
=========================================================*/


$sql = "

SELECT id

FROM utilisateur

WHERE reset_token = $1

";


$resultat = pg_query_params(

    $conn,

    $sql,

    [$token]

);



if (!$resultat || pg_num_rows($resultat) == 0) {


    die("Token invalide.");

}



$utilisateur = pg_fetch_assoc($resultat);



/*=========================================================
    Chiffrer le nouveau mot de passe
=========================================================*/


$mot_de_passe_hash = password_hash(

    $nouveau_mot_de_passe,

    PASSWORD_DEFAULT

);



/*=========================================================
    Mise à jour du mot de passe
    Suppression du token
=========================================================*/


$sql_update = "

UPDATE utilisateur

SET mot_de_passe = $1,

    reset_token = NULL,

    reset_expiration = NULL

WHERE id = $2

";



$update = pg_query_params(

    $conn,

    $sql_update,

    [

        $mot_de_passe_hash,

        $utilisateur['id']

    ]

);



if (!$update) {


    die("Erreur lors de la modification du mot de passe.");

}



/*=========================================================
    Message succès
=========================================================*/


echo "

<!DOCTYPE html>

<html lang='fr'>

<head>

<meta charset='UTF-8'>

<title>Mot de passe modifié</title>


<style>

body{

background:#ecf0f1;

font-family:Arial;

}


.message{

width:400px;

margin:100px auto;

background:white;

padding:30px;

text-align:center;

border-radius:10px;

box-shadow:0 0 10px #ccc;

}


a{

display:inline-block;

margin-top:20px;

padding:10px 20px;

background:#3498db;

color:white;

text-decoration:none;

border-radius:5px;

}


</style>


</head>


<body>


<div class='message'>

<h2 style='color:green;'>
Succès
</h2>


<p>
Votre mot de passe a été modifié avec succès.
</p>


<a href='login_public.php'>
Se connecter
</a>


</div>


</body>


</html>

";

?>