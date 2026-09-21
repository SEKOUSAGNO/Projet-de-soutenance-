<?php
session_start();
?>

<!DOCTYPE html>

<html lang="fr">

<head>

<meta charset="UTF-8">

<title>Mot de passe oublié</title>

<style>

body{

    background:#ecf0f1;

    font-family:Arial, Helvetica, sans-serif;

}

.conteneur{

    width:400px;

    margin:80px auto;

    background:white;

    padding:25px;

    border-radius:10px;

    box-shadow:0px 0px 10px gray;

}

h2{

    text-align:center;

    color:#007bff;

}

p{

    text-align:center;

    color:#555;

    font-size:15px;

}

input{

    width:100%;

    padding:10px;

    margin-top:15px;

    border:1px solid #ccc;

    border-radius:5px;

    box-sizing:border-box;

}

button{

    width:100%;

    padding:12px;

    margin-top:20px;

    background:#007bff;

    color:white;

    border:none;

    border-radius:5px;

    font-size:16px;

    cursor:pointer;

}

button:hover{

    background:#0056b3;

}

a{

    text-decoration:none;

    color:#007bff;

}

.retour{

    margin-top:20px;

    text-align:center;

}

.message{

    margin-top:15px;

    padding:10px;

    border-radius:5px;

    background:#d4edda;

    color:#155724;

    text-align:center;

}

.erreur{

    margin-top:15px;

    padding:10px;

    border-radius:5px;

    background:#f8d7da;

    color:#721c24;

    text-align:center;

}

</style>

</head>

<body>

<div class="conteneur">

<h2>🔑 Mot de passe oublié</h2>

<p>

Entrez votre adresse e-mail.<br>

Un lien de réinitialisation vous sera envoyé.

</p>

<?php

if(isset($_GET['success'])){

    echo "<div class='message'>
    Si cette adresse existe, un lien de réinitialisation a été envoyé.
    </div>";

}

if(isset($_GET['error'])){

    echo "<div class='erreur'>
    Adresse e-mail introuvable.
    </div>";

}

?>

<form action="envoyer_lien.php" method="POST">

<input
type="email"
name="email"
placeholder="Adresse e-mail"
required>

<button type="submit">

Envoyer le lien de réinitialisation

</button>

</form>

<div class="retour">

<a href="login_public.php">

⬅ Retour à la connexion

</a>

</div>

</div>

</body>

</html>