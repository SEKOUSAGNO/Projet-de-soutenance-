<?php
session_start();

require_once("../database.php");

/*=========================================================
= Vérifier que l'étudiant est connecté
=========================================================*/

if (!isset($_SESSION['id']) || $_SESSION['role'] != "Etudiant") {

    header("Location: ../login_public.php");
    exit();

}

/*=========================================================
= Vérifier qu'une candidature existe
=========================================================*/

if (!isset($_SESSION['candidature_id'])) {

    die("Aucune candidature en cours.");

}

$candidature_id = $_SESSION['candidature_id'];

/*=========================================================
= Récupérer les informations de la candidature
=========================================================*/

$sql = "

SELECT

c.id,
c.date_candidature,
c.statut,

o.id AS offre_id,
o.titre,
o.entreprise,
o.lieu,
o.type_offre,

e.nom,
e.prenom,
e.telephone,
e.filiere,
e.niveau,
e.photo,

u.email

FROM candidature c

INNER JOIN offre o
ON o.id = c.offre_id

INNER JOIN etudiant e
ON e.id = c.etudiant_id

INNER JOIN utilisateur u
ON u.id = e.utilisateur_id

WHERE c.id = $1

";

$result = pg_query_params(

$conn,

$sql,

array($candidature_id)

);

if(!$result){

    die("Erreur PostgreSQL : ".pg_last_error($conn));

}

if(pg_num_rows($result)==0){

    die("Cette candidature est introuvable.");

}

$candidature = pg_fetch_assoc($result);

/*=========================================================
= Récupérer les cursus universitaires
=========================================================*/

$sqlCursus = "

SELECT *

FROM cursus

WHERE candidature_id = $1

ORDER BY id

";

$resultCursus = pg_query_params(

$conn,

$sqlCursus,

array($candidature_id)

);

/*=========================================================
= Récupérer les formations professionnelles
=========================================================*/

$sqlFormations = "

SELECT *

FROM formation_professionnelle

WHERE candidature_id = $1

ORDER BY id

";

$resultFormations = pg_query_params(

$conn,

$sqlFormations,

array($candidature_id)

);

/*=========================================================
= Récupérer les expériences professionnelles
=========================================================*/

$sqlExperiences = "

SELECT *

FROM experience_professionnelle

WHERE candidature_id = $1

ORDER BY id

";

$resultExperiences = pg_query_params(

$conn,

$sqlExperiences,

array($candidature_id)

);

/*=========================================================
= Récupérer les compétences
=========================================================*/

$sqlCompetences = "

SELECT *

FROM competence

WHERE candidature_id = $1

ORDER BY id

";

$resultCompetences = pg_query_params(

$conn,

$sqlCompetences,

array($candidature_id)

);

/*=========================================================
= Récupérer les références professionnelles
=========================================================*/

$sqlReferences = "

SELECT *

FROM reference_professionnelle

WHERE candidature_id = $1

ORDER BY id

";

$resultReferences = pg_query_params(

$conn,

$sqlReferences,

array($candidature_id)

);

/*=========================================================
= Récupérer les documents joints
=========================================================*/

$sqlDocuments = "

SELECT *

FROM document_candidature

WHERE candidature_id = $1

ORDER BY id

";

$resultDocuments = pg_query_params(

$conn,

$sqlDocuments,

array($candidature_id)

);

?>

<!DOCTYPE html>

<html lang="fr">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>

Validation de la candidature

</title>

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

.titre{

color:#0d6efd;

font-weight:bold;

}

.section{

background:#f8f9fa;

border-left:5px solid #0d6efd;

padding:15px;

margin-bottom:20px;

border-radius:8px;

}

</style>

</head>

<body>

<div class="container py-4">

<div class="card shadow">

<div class="card-body">

<h2 class="titre text-center">

<i class="fa-solid fa-circle-check"></i>

Validation de votre candidature

</h2>

<hr>

<div class="alert alert-primary">

<h5>Informations de l'offre</h5>

<p>

<strong>Titre :</strong>

<?php echo htmlspecialchars($candidature['titre']); ?>

</p>

<p>

<strong>Entreprise :</strong>

<?php echo htmlspecialchars($candidature['entreprise']); ?>

</p>

<p>

<strong>Lieu :</strong>

<?php echo htmlspecialchars($candidature['lieu']); ?>

</p>

<p class="mb-0">

<strong>Type :</strong>

<?php echo htmlspecialchars($candidature['type_offre']); ?>

</p>

</div>

<div class="alert alert-secondary">

<h5>Informations de l'étudiant</h5>

<p>

<strong>Nom :</strong>

<?php echo htmlspecialchars($candidature['prenom']." ".$candidature['nom']); ?>

</p>

<p>

<strong>Email :</strong>

<?php echo htmlspecialchars($candidature['email']); ?>

</p>

<p>

<strong>Téléphone :</strong>

<?php echo htmlspecialchars($candidature['telephone']); ?>

</p>

<p>

<strong>Filière :</strong>

<?php echo htmlspecialchars($candidature['filiere']); ?>

</p>

<p class="mb-0">

<strong>Niveau :</strong>

<?php echo htmlspecialchars($candidature['niveau']); ?>

</p>

</div>
<!--=========================================================
= CURSUS UNIVERSITAIRES
==========================================================-->

<div class="section">

<h4 class="text-primary">

<i class="fa-solid fa-graduation-cap"></i>

Cursus universitaires

</h4>

<?php

if(pg_num_rows($resultCursus)>0){

?>

<table class="table table-bordered table-hover">

<thead class="table-primary">

<tr>

<th>Diplôme</th>

<th>Établissement</th>

<th>Année</th>

<th>Mention</th>

</tr>

</thead>

<tbody>

<?php

while($cursus = pg_fetch_assoc($resultCursus)){

?>

<tr>

<td>

<?php echo htmlspecialchars($cursus['diplome']); ?>

</td>

<td>

<?php echo htmlspecialchars($cursus['etablissement']); ?>

</td>

<td>

<?php echo htmlspecialchars($cursus['annee']); ?>

</td>

<td>

<?php echo htmlspecialchars($cursus['mention']); ?>

</td>

</tr>

<?php

}

?>

</tbody>

</table>

<?php

}else{

?>

<div class="alert alert-warning">

Aucun cursus universitaire.

</div>

<?php

}

?>

</div>



<!--=========================================================
= FORMATIONS PROFESSIONNELLES
==========================================================-->

<div class="section">

<h4 class="text-success">

<i class="fa-solid fa-certificate"></i>

Formations professionnelles

</h4>

<?php

if(pg_num_rows($resultFormations)>0){

?>

<table class="table table-bordered table-striped">

<thead class="table-success">

<tr>

<th>Formation</th>

<th>Organisme</th>

<th>Date début</th>

<th>Date fin</th>

<th>Certificat</th>

</tr>

</thead>

<tbody>

<?php

while($formation = pg_fetch_assoc($resultFormations)){

?>

<tr>

<td>

<?php echo htmlspecialchars($formation['formation']); ?>

</td>

<td>

<?php echo htmlspecialchars($formation['organisme']); ?>

</td>

<td>

<?php echo htmlspecialchars($formation['date_debut']); ?>

</td>

<td>

<?php echo htmlspecialchars($formation['date_fin']); ?>

</td>

<td>

<?php

if(!empty($formation['certificat'])){

?>

<a

href="../<?php echo htmlspecialchars($formation['certificat']); ?>"

target="_blank"

class="btn btn-sm btn-primary">

<i class="fa-solid fa-file-pdf"></i>

Voir

</a>

<?php

}else{

echo "-";

}

?>

</td>

</tr>

<?php

}

?>

</tbody>

</table>

<?php

}else{

?>

<div class="alert alert-warning">

Aucune formation professionnelle.

</div>

<?php

}

?>

</div>
<!--=========================================================
= EXPERIENCES PROFESSIONNELLES
==========================================================-->

<div class="section">

<h4 class="text-primary">

<i class="fa-solid fa-briefcase"></i>

Expériences professionnelles

</h4>

<?php

if(pg_num_rows($resultExperiences) > 0){

?>

<table class="table table-bordered table-hover">

<thead class="table-primary">

<tr>

<th>Entreprise</th>

<th>Poste</th>

<th>Date début</th>

<th>Date fin</th>

<th>Description</th>

</tr>

</thead>

<tbody>

<?php

while($experience = pg_fetch_assoc($resultExperiences)){

?>

<tr>

<td>

<?php echo htmlspecialchars($experience['entreprise']); ?>

</td>

<td>

<?php echo htmlspecialchars($experience['poste']); ?>

</td>

<td>

<?php echo htmlspecialchars($experience['date_debut']); ?>

</td>

<td>

<?php echo htmlspecialchars($experience['date_fin']); ?>

</td>

<td>

<?php echo nl2br(htmlspecialchars($experience['description'])); ?>

</td>

</tr>

<?php

}

?>

</tbody>

</table>

<?php

}else{

?>

<div class="alert alert-warning">

Aucune expérience professionnelle.

</div>

<?php

}

?>

</div>



<!--=========================================================
= COMPETENCES
==========================================================-->

<div class="section">

<h4 class="text-success">

<i class="fa-solid fa-star"></i>

Compétences professionnelles

</h4>

<?php

if(pg_num_rows($resultCompetences) > 0){

?>

<table class="table table-bordered table-striped">

<thead class="table-success">

<tr>

<th>Compétence</th>

<th>Niveau</th>

</tr>

</thead>

<tbody>

<?php

while($competence = pg_fetch_assoc($resultCompetences)){

?>

<tr>

<td>

<?php echo htmlspecialchars($competence['competence']); ?>

</td>

<td>

<?php echo htmlspecialchars($competence['niveau']); ?>

</td>

</tr>

<?php

}

?>

</tbody>

</table>

<?php

}else{

?>

<div class="alert alert-warning">

Aucune compétence professionnelle.

</div>

<?php

}

?>

</div>
<!--=========================================================
= REFERENCES PROFESSIONNELLES
==========================================================-->

<div class="section">

<h4 class="text-primary">

<i class="fa-solid fa-user-tie"></i>

Références professionnelles

</h4>

<?php if(pg_num_rows($resultReferences)>0){ ?>

<table class="table table-bordered table-hover">

<thead class="table-primary">

<tr>

<th>Nom</th>

<th>Fonction</th>

<th>Entreprise</th>

<th>Téléphone</th>

<th>Email</th>

<th>Relation</th>

</tr>

</thead>

<tbody>

<?php while($reference = pg_fetch_assoc($resultReferences)){ ?>

<tr>

<td>

<?php echo htmlspecialchars($reference['nom']); ?>

</td>

<td>

<?php echo htmlspecialchars($reference['fonction']); ?>

</td>

<td>

<?php echo htmlspecialchars($reference['entreprise']); ?>

</td>

<td>

<?php echo htmlspecialchars($reference['telephone']); ?>

</td>

<td>

<?php echo htmlspecialchars($reference['email']); ?>

</td>

<td>

<?php echo htmlspecialchars($reference['relation']); ?>

</td>

</tr>

<?php } ?>

</tbody>

</table>

<?php } else { ?>

<div class="alert alert-warning">

Aucune référence professionnelle.

</div>

<?php } ?>

</div>



<!--=========================================================
= DOCUMENTS JOINTS
==========================================================-->

<div class="section">

<h4 class="text-success">

<i class="fa-solid fa-folder-open"></i>

Documents joints

</h4>

<?php if(pg_num_rows($resultDocuments)>0){ ?>

<table class="table table-bordered table-striped">

<thead class="table-success">

<tr>

<th>Nom du document</th>

<th>Fichier</th>

</tr>

</thead>

<tbody>

<?php while($document = pg_fetch_assoc($resultDocuments)){ ?>

<tr>

<td>

<?php echo htmlspecialchars($document['nom_document']); ?>

</td>

<td>

<?php if(!empty($document['fichier'])){ ?>

<a

href="../<?php echo htmlspecialchars($document['fichier']); ?>"

target="_blank"

class="btn btn-primary btn-sm">

<i class="fa-solid fa-eye"></i>

Voir

</a>

&nbsp;

<a

href="../<?php echo htmlspecialchars($document['fichier']); ?>"

download

class="btn btn-success btn-sm">

<i class="fa-solid fa-download"></i>

Télécharger

</a>

<?php } else { ?>

<span class="text-danger">

Aucun fichier

</span>

<?php } ?>

</td>

</tr>

<?php } ?>

</tbody>

</table>

<?php } else { ?>

<div class="alert alert-warning">

Aucun document joint.

</div>

<?php } ?>

</div>
<!--=========================================================
= VALIDATION FINALE
==========================================================-->

<hr>

<div class="alert alert-success text-center">

<h4>

<i class="fa-solid fa-circle-check"></i>

Votre candidature est complète.

</h4>

<p class="mb-0">

Toutes les informations ont été enregistrées avec succès.
Vous pouvez maintenant retourner à la liste des offres.

</p>

</div>



<!--=========================================================
= BOUTONS
==========================================================-->

<div class="d-flex justify-content-between mt-4">

<a

href="postuler_documents.php"

class="btn btn-secondary btn-lg">

<i class="fa-solid fa-arrow-left"></i>

Précédent

</a>


<a

href="offres.php"

class="btn btn-primary btn-lg">

<i class="fa-solid fa-list"></i>

Retour aux offres

</a>

</div>


</div>

</div>

</div>

</body>

</html>

<?php

/*=========================================================
= Nettoyage des variables de session
=========================================================*/

unset($_SESSION['cursus']);
unset($_SESSION['formations']);
unset($_SESSION['experiences']);
unset($_SESSION['competences']);
unset($_SESSION['references']);

/*
On conserve candidature_id tant que l'étudiant
peut consulter cette candidature.
Si tu préfères le supprimer immédiatement,
décommente la ligne suivante :

unset($_SESSION['candidature_id']);
*/

unset($_SESSION['offre_id']);

?>