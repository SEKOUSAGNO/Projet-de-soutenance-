<?php
session_start();

require_once("../database.php");

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['id'])) {
    header("Location: ../login_public.php");
    exit();
}

// Vérifier que c'est un administrateur
if ($_SESSION['role'] != "Admin") {
    header("Location: ../login_public.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>

<meta charset="UTF-8">

<title>Ajouter un Code d'Accréditation</title>

<style>

body{
    margin:0;
    font-family:Arial, Helvetica, sans-serif;
    background:#f4f6f9;
}

header{
    background:#1565C0;
    color:white;
    text-align:center;
    padding:20px;
}

.container{
    width:500px;
    margin:40px auto;
    background:white;
    padding:30px;
    border-radius:10px;
    box-shadow:0 0 10px rgba(0,0,0,.2);
}

h2{
    text-align:center;
    color:#1565C0;
}

label{
    font-weight:bold;
}

input[type=text]{
    width:100%;
    padding:12px;
    margin-top:10px;
    margin-bottom:20px;
    border:1px solid #ccc;
    border-radius:5px;
    box-sizing:border-box;
}

button{
    width:100%;
    padding:12px;
    background:#1565C0;
    color:white;
    border:none;
    border-radius:5px;
    font-size:16px;
    cursor:pointer;
}

button:hover{
    background:#0D47A1;
}

.retour{
    display:block;
    text-align:center;
    margin-top:20px;
    text-decoration:none;
    color:#1565C0;
    font-weight:bold;
}

</style>

</head>

<body>

<header>

<h1>Administration SIMAN-JOB</h1>

</header>

<div class="container">

<h2>Ajouter un code d'accréditation</h2>

<form action="enregistrer_code.php" method="POST">

<label>Code d'accréditation</label>

<input
type="text"
name="code"
placeholder="Exemple : SIMAN-UKAG-2026"
required>

<button type="submit">

Enregistrer le code

</button>

</form>

<a class="retour" href="codes_accreditation.php">

⬅ Retour à la liste des codes

</a>

</div>

</body>

</html>