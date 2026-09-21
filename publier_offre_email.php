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

<title>Publication d'une offre - Candidature par e-mail</title>

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

.obligatoire{
    color:red;
}

</style>

</head>

<body>

<div class="container">

<div class="card shadow">

<div class="card-body">

<h2 class="text-center">

Publication d'une offre d'emploi ou de stage

</h2>

<p class="text-center text-muted">

Mode de candidature : <strong>Par e-mail</strong>

</p>

<hr>

<form action="enregistrer_offre_email.php" method="POST">

<div class="mb-3">

<label class="form-label">

Titre de l'offre
<span class="obligatoire">*</span>

</label>

<input
type="text"
name="titre"
class="form-control"
required>

</div>

<div class="mb-3">

<label class="form-label">

Entreprise
<span class="obligatoire">*</span>

</label>

<input
type="text"
name="entreprise"
class="form-control"
required>

</div>

<div class="mb-3">

<label class="form-label">

Lieu
<span class="obligatoire">*</span>

</label>

<input
type="text"
name="lieu"
class="form-control"
required>

</div>

<div class="mb-3">

<label class="form-label">

Description de l'offre
<span class="obligatoire">*</span>

</label>

<textarea
name="description"
rows="6"
class="form-control"
required></textarea>

</div>

<div class="row">

<div class="col-md-6">

<label class="form-label">

Type d'offre

</label>

<select
name="type_offre"
class="form-select"
required>

<option value="">Choisir</option>

<option value="Emploi">Emploi</option>

<option value="Stage">Stage</option>

</select>

</div>

<div class="col-md-6">

<label class="form-label">

Date de publication

</label>

<input
type="date"
name="date_publication"
class="form-control"
value="<?php echo date('Y-m-d'); ?>"
readonly>

</div>

</div>

<br>

<div class="mb-3">

<label class="form-label">

Date limite de candidature

</label>

<input
type="date"
name="date_limite"
class="form-control"
required>

</div>

<div class="mb-3">

<label class="form-label">

Adresse e-mail de réception

</label>

<input
type="email"
name="email_reception"
class="form-control"
placeholder="recrutement@entreprise.com"
required>

</div>

<input
type="hidden"
name="mode_candidature"
value="Email">

<div class="mb-3">

<label class="form-label">

Statut

</label>

<input
type="text"
name="statut"
class="form-control"
value="Active"
readonly>

</div>

<div class="alert alert-info">

<b>Information :</b>

Les étudiants intéressés verront cette offre sur la plateforme.

En cliquant sur <b>Envoyer ma candidature</b>, ils enverront leur dossier directement à l'adresse e-mail renseignée ci-dessus.

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