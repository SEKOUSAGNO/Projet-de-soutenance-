<?php
session_start();

if (!isset($_SESSION['id']) || $_SESSION['role'] != 'Recruteur') {
    header("Location: ../login_public.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>

<meta charset="UTF-8">

<title>Choisir le mode de recrutement</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
    background:#eef5ff;
}

.card{
    margin-top:80px;
    border-radius:15px;
}

</style>

</head>

<body>

<div class="container">

<div class="card shadow">

<div class="card-body text-center">

<h2 class="text-primary">

Choisissez le mode de recrutement

</h2>

<p>

Comment souhaitez-vous recevoir les candidatures ?

</p>

<form action="choix_recrutement.php" method="POST">

<div class="form-check text-start">

<input class="form-check-input"
type="radio"
name="mode"
value="Email"
required>

<label class="form-check-label">

Par e-mail

</label>

</div>

<div class="form-check text-start">

<input class="form-check-input"
type="radio"
name="mode"
value="Plateforme">

<label class="form-check-label">

Via la plateforme

</label>

</div>

<br>

<button class="btn btn-primary">

Continuer

</button>

</form>

</div>

</div>

</div>

</body>

</html>