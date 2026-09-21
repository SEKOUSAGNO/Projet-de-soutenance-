<?php
session_start();

/*=========================================================
= Vérifier que l'étudiant est connecté
=========================================================*/

if (!isset($_SESSION['id']) || $_SESSION['role'] != "Etudiant") {

    header("Location: ../login_public.php");
    exit();

}

require_once("../database.php");

/*=========================================================
= Vérifier que l'offre existe
=========================================================*/

if (!isset($_GET['id'])) {

    die("Aucune offre sélectionnée.");

}

$offre_id = intval($_GET['id']);

/*=========================================================
= Récupérer les informations de l'offre
=========================================================*/

$sqlOffre = "

SELECT

id,
titre,
description,
entreprise,
lieu,
type_offre,
date_publication,
date_limite,
mode_candidature

FROM offre

WHERE id = $1

";

$resultOffre = pg_query_params(

$conn,

$sqlOffre,

array($offre_id)

);

if (!$resultOffre) {

    die("Erreur PostgreSQL : ".pg_last_error($conn));

}

if (pg_num_rows($resultOffre) == 0) {

    die("Cette offre n'existe pas.");

}

$offre = pg_fetch_assoc($resultOffre);

/*=========================================================
= Vérifier que l'offre accepte les candidatures
sur la plateforme
=========================================================*/

if ($offre['mode_candidature'] != "Plateforme") {

    die("Cette offre ne permet pas de candidater sur la plateforme.");

}

/*=========================================================
= Récupérer le profil étudiant
=========================================================*/

$sqlEtudiant = "

SELECT

e.*,

u.email

FROM etudiant e

INNER JOIN utilisateur u

ON e.utilisateur_id = u.id

WHERE e.utilisateur_id = $1

";

$resultEtudiant = pg_query_params(

$conn,

$sqlEtudiant,

array($_SESSION['id'])

);

if (!$resultEtudiant) {

    die("Erreur PostgreSQL : ".pg_last_error($conn));

}

if (pg_num_rows($resultEtudiant)==0){

    die("Veuillez compléter votre profil étudiant avant de postuler.");

}

$etudiant = pg_fetch_assoc($resultEtudiant);

/*=========================================================
= Vérifier que l'étudiant n'a pas déjà postulé
=========================================================*/

$sqlVerif = "

SELECT id

FROM candidature

WHERE

etudiant_id = $1

AND offre_id = $2

";

$resultVerif = pg_query_params(

$conn,

$sqlVerif,

array(

$etudiant['id'],

$offre_id

)

);

if(pg_num_rows($resultVerif)>0){

die("Vous avez déjà postulé à cette offre.");

}

/*=========================================================
= Sauvegarder l'offre dans la session
=========================================================*/

$_SESSION['offre_id'] = $offre_id;

?>
<!DOCTYPE html>
<html lang="fr">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Candidature - Étape 1</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

<style>

body{

    background:#eef4ff;

}

.card{

    border:none;
    border-radius:15px;

}

.photo{

    width:140px;
    height:140px;
    border-radius:50%;
    object-fit:cover;
    border:4px solid #0d6efd;

}

.progress{

    height:12px;
    border-radius:10px;

}

.progress-bar{

    background:#0d6efd;

}

.titre{

    color:#0d6efd;
    font-weight:bold;

}

.info{

    font-size:15px;

}

label{

    font-weight:bold;

}

</style>

</head>

<body>

<div class="container py-4">

<!--==========================
BARRE DE PROGRESSION
===========================-->

<div class="card shadow mb-4">

<div class="card-body">

<div class="d-flex justify-content-between">

<h4 class="text-primary">

<i class="fa-solid fa-user-graduate"></i>

Candidature sur la plateforme

</h4>

<h5>

Étape 1 / 6

</h5>

</div>

<div class="progress mt-3">

<div
class="progress-bar"
style="width:16.66%">

</div>

</div>

<p class="mt-2 text-muted">

Informations personnelles

</p>

</div>

</div>

<!--==========================
OFFRE
===========================-->

<div class="card shadow mb-4">

<div class="card-header bg-primary text-white">

<h5 class="mb-0">

<i class="fa-solid fa-briefcase"></i>

Informations de l'offre

</h5>

</div>

<div class="card-body">

<div class="row">

<div class="col-md-6">

<p class="info">

<strong>Titre :</strong>

<?php echo htmlspecialchars($offre['titre']); ?>

</p>

<p class="info">

<strong>Entreprise :</strong>

<?php echo htmlspecialchars($offre['entreprise']); ?>

</p>

<p class="info">

<strong>Lieu :</strong>

<?php echo htmlspecialchars($offre['lieu']); ?>

</p>

</div>

<div class="col-md-6">

<p class="info">

<strong>Type :</strong>

<?php echo htmlspecialchars($offre['type_offre']); ?>

</p>

<p class="info">

<strong>Publication :</strong>

<?php

echo date(
"d/m/Y",
strtotime($offre['date_publication'])
);

?>

</p>

<p class="info">

<strong>Date limite :</strong>

<?php

echo date(
"d/m/Y",
strtotime($offre['date_limite'])
);

?>

</p>

</div>

</div>

<hr>

<p>

<?php

echo nl2br(htmlspecialchars($offre['description']));

?>

</p>

</div>

</div>

<!--==========================
FORMULAIRE
===========================-->

<form
action="postuler_cursus.php"
method="POST"
enctype="multipart/form-data">

<input
type="hidden"
name="offre_id"
value="<?php echo $offre_id; ?>">

<div class="card shadow">

<div class="card-header bg-primary text-white">

<h5>

<i class="fa-solid fa-id-card"></i>

Informations personnelles

</h5>

</div>

<div class="card-body">

<div class="text-center mb-4">

<?php

if(!empty($etudiant['photo'])){

?>

<img
src="../<?php echo htmlspecialchars($etudiant['photo']); ?>"
class="photo">

<?php

}else{

?>

<img
src="../images/photos/default.png"
class="photo">

<?php

}

?>

</div>

<hr>

<div class="row">

    <div class="col-md-6 mb-3">
        <label>Nom</label>
        <input type="text" class="form-control"
               value="<?php echo htmlspecialchars($etudiant['nom']); ?>" readonly>
    </div>

    <div class="col-md-6 mb-3">
        <label>Prénom</label>
        <input type="text" class="form-control"
               value="<?php echo htmlspecialchars($etudiant['prenom']); ?>" readonly>
    </div>

    <div class="col-md-6 mb-3">
        <label>Email</label>
        <input type="email" class="form-control"
               value="<?php echo htmlspecialchars($etudiant['email']); ?>" readonly>
    </div>

    <div class="col-md-6 mb-3">
        <label>Sexe</label>
        <input type="text" class="form-control"
               value="<?php echo htmlspecialchars($etudiant['sexe']); ?>" readonly>
    </div>

    <div class="col-md-6 mb-3">
        <label>Date de naissance</label>
        <input type="text" class="form-control"
               value="<?php echo htmlspecialchars($etudiant['date_naissance']); ?>" readonly>
    </div>

    <div class="col-md-6 mb-3">
        <label>Adresse</label>
        <input type="text" class="form-control"
               value="<?php echo htmlspecialchars($etudiant['adresse']); ?>" readonly>
    </div>

    <div class="col-md-6 mb-3">
        <label>Faculté</label>
        <input type="text" class="form-control"
               value="<?php echo htmlspecialchars($etudiant['faculte']); ?>" readonly>
    </div>

    <div class="col-md-6 mb-3">
        <label>Filière</label>
        <input type="text" class="form-control"
               value="<?php echo htmlspecialchars($etudiant['filiere']); ?>" readonly>
    </div>

    <div class="col-md-6 mb-3">
        <label>Niveau</label>
        <input type="text" class="form-control"
               value="<?php echo htmlspecialchars($etudiant['niveau']); ?>" readonly>
    </div>

    <div class="col-md-6 mb-3">
        <label>Année universitaire</label>
        <input type="text" class="form-control"
               value="<?php echo htmlspecialchars($etudiant['annee_universitaire']); ?>" readonly>
    </div>

</div>
<hr>

<div class="row">

<div class="col-md-6">

<a
href="offres.php"
class="btn btn-secondary btn-lg">

<i class="fa-solid fa-arrow-left"></i>

Retour aux offres

</a>

</div>

<div class="col-md-6 text-end">

<button
type="submit"
class="btn btn-primary btn-lg">

Suivant

<i class="fa-solid fa-arrow-right"></i>

</button>

</div>

</div>

</div>

</div>

</form>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
