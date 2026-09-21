<?php
session_start();

if (!isset($_SESSION['id']) || $_SESSION['role'] != 'Recruteur') {
    header("Location: ../login_public.php");
    exit();
}

require_once("../database.php");
?>

<!DOCTYPE html>
<html lang="fr">

<head>

<meta charset="UTF-8">

<title>Publication d'une offre - Plateforme</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
    background:#eef5ff;
}

.card{
    margin-top:30px;
    border-radius:15px;
}

h2{
    color:#0d6efd;
}

</style>

</head>

<body>

<div class="container">

<div class="card shadow">

<div class="card-body">

<h2 class="text-center">

Publier une offre d'emploi ou de stage

</h2>

<p class="text-center text-muted">

Mode de candidature : <strong>Via la plateforme</strong>

</p>

<hr>

<form action="enregistrer_offre_plateforme.php" method="POST">

<div class="mb-3">

<label>Titre de l'offre</label>

<input
type="text"
name="titre"
class="form-control"
required>

</div>

<div class="row">

<div class="col-md-6">

<label>Entreprise</label>

<input
type="text"
name="entreprise"
class="form-control"
required>

</div>

<div class="col-md-6">

<label>Lieu</label>

<input
type="text"
name="lieu"
class="form-control"
required>

</div>

</div>

<br>

<div class="mb-3">

<label>Description</label>

<textarea
name="description"
rows="4"
class="form-control"
required></textarea>

</div>

<div class="row">

<div class="col-md-4">

<label>Type d'offre</label>

<select
name="type_offre"
class="form-select"
required>

<option value="">Choisir</option>

<option value="Emploi">Emploi</option>

<option value="Stage">Stage</option>

</select>

</div>

<div class="col-md-4">

<label>Date de publication</label>

<input
type="date"
name="date_publication"
class="form-control"
value="<?php echo date('Y-m-d'); ?>"
readonly>

</div>

<div class="col-md-4">

<label>Date limite</label>

<input
type="date"
name="date_limite"
class="form-control"
required>

</div>

</div>

<input
type="hidden"
name="mode_candidature"
value="Plateforme">

<input
type="hidden"
name="statut"
value="Active">

<hr>

<h5 class="text-primary">

Documents demandés

</h5>

<div class="row">

<div class="col-md-6">

<div class="form-check">

<input class="form-check-input"
type="checkbox"
name="cv"
checked>

<label class="form-check-label">

CV

</label>

</div>

<div class="form-check">

<input class="form-check-input"
type="checkbox"
name="lettre">

<label class="form-check-label">

Lettre de motivation

</label>

</div>

<div class="form-check">

<input class="form-check-input"
type="checkbox"
name="diplome">

<label class="form-check-label">

Diplôme

</label>

</div>

</div>

<div class="col-md-6">

<div class="form-check">

<input class="form-check-input"
type="checkbox"
name="releve">

<label class="form-check-label">

Relevé de notes

</label>

</div>

<div class="form-check">

<input class="form-check-input"
type="checkbox"
name="piece">

<label class="form-check-label">

Pièce d'identité

</label>

</div>

<div class="form-check">

<input class="form-check-input"
type="checkbox"
name="autres">

<label class="form-check-label">

Documents complémentaires

</label>

</div>

</div>

</div>

<br>

<div class="alert alert-info">

<b>Important :</b><br>

Les étudiants cliqueront sur <b>Postuler</b> puis rempliront automatiquement :

<ul>
<li>Leur cursus universitaire</li>
<li>Leurs formations professionnelles</li>
<li>Leurs expériences professionnelles</li>
<li>Leurs personnes de référence</li>
<li>Les documents demandés</li>
</ul>

</div>

<div class="text-center">

<button
type="submit"
class="btn btn-primary btn-lg">

Publier l'offre

</button>

<a
href="publier_offre.php"
class="btn btn-secondary btn-lg">

Retour

</a>

</div>

</form>

</div>

</div>

</div>

</body>

</html>