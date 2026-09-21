<?php

session_start();

require "database.php";


/*=========================================================
    Vérifier la présence du token
=========================================================*/

if (!isset($_GET['token']) || empty($_GET['token'])) {

    die("Lien de réinitialisation invalide.");

}


$token = $_GET['token'];



/*=========================================================
    Vérifier le token dans la base
=========================================================*/

$sql = "

SELECT id, nom, prenom, reset_expiration

FROM utilisateur

WHERE reset_token = $1

";


$resultat = pg_query_params(

    $conn,

    $sql,

    [$token]

);



if (!$resultat || pg_num_rows($resultat) == 0) {

    die("Ce lien de réinitialisation n'existe pas.");

}



$utilisateur = pg_fetch_assoc($resultat);



/*=========================================================
    Vérifier l'expiration du lien
=========================================================*/


$date_actuelle = date("Y-m-d H:i:s");


if ($utilisateur['reset_expiration'] < $date_actuelle) {


    die("Ce lien a expiré. Veuillez refaire une demande.");

}


?>


<!DOCTYPE html>

<html lang="fr">


<head>

<meta charset="UTF-8">

<title>Nouveau mot de passe</title>


<style>


body{

    background:#ecf0f1;
    font-family:Arial;

}


.formulaire{

    width:400px;
    margin:80px auto;
    background:white;
    padding:30px;
    border-radius:10px;
    box-shadow:0 0 10px #ccc;

}


h2{

    text-align:center;
    color:#2c3e50;

}


input{

    width:100%;
    padding:12px;
    margin:10px 0 20px 0;
    border:1px solid #ccc;
    border-radius:5px;

}


button{

    width:100%;
    padding:12px;
    background:#27ae60;
    color:white;
    border:none;
    border-radius:5px;
    cursor:pointer;

}


button:hover{

    background:#219150;

}


</style>


</head>


<body>



<div class="formulaire">


<h2>
Créer un nouveau mot de passe
</h2>


<p>

Bonjour 
<strong>
<?php echo htmlspecialchars($utilisateur['prenom']." ".$utilisateur['nom']); ?>
</strong>

</p>



<form action="traitement_nouveau_mot_de_passe.php" method="POST">


<input 
type="hidden"
name="token"
value="<?php echo htmlspecialchars($token); ?>"
>



<label>
Nouveau mot de passe
</label>


<input

type="password"

name="nouveau_mot_de_passe"

required

>



<label>
Confirmer le nouveau mot de passe
</label>


<input

type="password"

name="confirmation"

required

>



<button type="submit">

Modifier le mot de passe

</button>



</form>



</div>


</body>


</html>