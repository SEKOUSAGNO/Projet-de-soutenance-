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
= Vérifier qu'une offre est sélectionnée
=========================================================*/

if (!isset($_SESSION['offre_id'])) {

    die("Aucune offre sélectionnée.");

}

$offre_id = $_SESSION['offre_id'];

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
date_limite

FROM offre

WHERE id = $1

";

$resultOffre = pg_query_params(

$conn,

$sqlOffre,

array($offre_id)

);

if(!$resultOffre){

    die("Erreur PostgreSQL : ".pg_last_error($conn));

}

if(pg_num_rows($resultOffre)==0){

    die("Cette offre est introuvable.");

}

$offre = pg_fetch_assoc($resultOffre);

/*=========================================================
= Récupérer les informations de l'étudiant
=========================================================*/

$sqlEtudiant = "

SELECT

e.id,
e.nom,
e.prenom,
u.email

FROM etudiant e

INNER JOIN utilisateur u

ON u.id = e.utilisateur_id

WHERE e.utilisateur_id = $1

";

$resultEtudiant = pg_query_params(

$conn,

$sqlEtudiant,

array($_SESSION['id'])

);

if(!$resultEtudiant){

    die("Erreur PostgreSQL : ".pg_last_error($conn));

}

if(pg_num_rows($resultEtudiant)==0){

    die("Veuillez compléter votre profil avant de poursuivre.");

}

$etudiant = pg_fetch_assoc($resultEtudiant);

?>

<!DOCTYPE html>

<html lang="fr">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>

Étape 2 - Cursus universitaire

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

.progress{

    height:12px;

}

.progress-bar{

    background:#0d6efd;

}

.titre{

    color:#0d6efd;
    font-weight:bold;

}

label{

    font-weight:bold;

}

</style>

</head>

<body>

<div class="container py-4">

<div class="card shadow">

<div class="card-body">

<div class="d-flex justify-content-between align-items-center">

<h3 class="titre">

<i class="fa-solid fa-graduation-cap"></i>

Candidature sur la plateforme

</h3>

<h5>

Étape 2 / 7

</h5>

</div>

<div class="progress mt-3">

<div
class="progress-bar"
style="width:28.57%">

</div>

</div>

<p class="mt-3 text-muted">

Veuillez renseigner votre parcours universitaire.

</p>

<hr>

<div class="alert alert-primary">

<h5>

<i class="fa-solid fa-briefcase"></i>

Informations de l'offre

</h5>

<p>

<strong>Titre :</strong>

<?php echo htmlspecialchars($offre['titre']); ?>

</p>

<p>

<strong>Entreprise :</strong>

<?php echo htmlspecialchars($offre['entreprise']); ?>

</p>

<p>

<strong>Lieu :</strong>

<?php echo htmlspecialchars($offre['lieu']); ?>

</p>

<p class="mb-0">

<strong>Type :</strong>

<?php echo htmlspecialchars($offre['type_offre']); ?>

</p>

</div>

<form

action="enregistrer_cursus.php"

method="POST"

id="formCursus">

<input

type="hidden"

name="offre_id"

value="<?php echo $offre_id; ?>">

<div id="conteneurCursus">
    <!--=========================================================
PREMIER CURSUS
==========================================================-->

<div class="card border-primary mb-4 cursus-item">

    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

        <h5 class="mb-0">

            <i class="fa-solid fa-graduation-cap"></i>

            Cursus universitaire 1

        </h5>

    </div>

    <div class="card-body">

        <div class="row">

            <div class="col-md-6 mb-3">

                <label class="form-label">

                    Diplôme obtenu <span class="text-danger">*</span>

                </label>

                <input
                    type="text"
                    name="diplome[]"
                    class="form-control"
                    placeholder="Ex : Licence Informatique"
                    required>

            </div>

            <div class="col-md-6 mb-3">

                <label class="form-label">

                    Établissement <span class="text-danger">*</span>

                </label>

                <input
                    type="text"
                    name="etablissement[]"
                    class="form-control"
                    placeholder="Ex : Université Kofi Annan de Guinée"
                    required>

            </div>

            <div class="col-md-6 mb-3">

                <label class="form-label">

                    Année d'obtention <span class="text-danger">*</span>

                </label>

                <input
                    type="text"
                    name="annee[]"
                    class="form-control"
                    placeholder="Ex : 2025"
                    required>

            </div>

            <div class="col-md-6 mb-3">

                <label class="form-label">

                    Mention

                </label>

                <select
                    name="mention[]"
                    class="form-select">

                    <option value="">Sélectionner</option>

                    <option value="Passable">Passable</option>

                    <option value="Assez Bien">Assez Bien</option>

                    <option value="Bien">Bien</option>

                    <option value="Très Bien">Très Bien</option>

                    <option value="Excellent">Excellent</option>

                    <option value="Autres">Autres</option>
                                        

                </select>

            </div>

        </div>

    </div>

</div>

</div>

<hr>

<div class="d-flex justify-content-between">

    <a
        href="postuler.php?id=<?php echo $offre_id; ?>"
        class="btn btn-secondary btn-lg">

        <i class="fa-solid fa-arrow-left"></i>

        Précédent

    </a>

    <button
        type="button"
        id="ajouterCursus"
        class="btn btn-success btn-lg">

        <i class="fa-solid fa-plus"></i>

        Ajouter un cursus

    </button>

    <button
        type="submit"
        class="btn btn-primary btn-lg">

        Suivant

        <i class="fa-solid fa-arrow-right"></i>

    </button>

</div>
<!--=========================================================
JAVASCRIPT
==========================================================-->

<script>

let numeroCursus = 1;

/*=========================================================
AJOUTER UN CURSUS
=========================================================*/

document.getElementById("ajouterCursus").addEventListener("click", function(){

    numeroCursus++;

    let html = `

    <div class="card border-primary mb-4 cursus-item">

        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

            <h5 class="mb-0">

                <i class="fa-solid fa-graduation-cap"></i>

                Cursus universitaire ${numeroCursus}

            </h5>

            <button
                type="button"
                class="btn btn-danger btn-sm supprimerCursus">

                <i class="fa-solid fa-trash"></i>

            </button>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-6 mb-3">

                    <label class="form-label">

                        Diplôme obtenu

                    </label>

                    <input
                        type="text"
                        name="diplome[]"
                        class="form-control"
                        required>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">

                        Établissement

                    </label>

                    <input
                        type="text"
                        name="etablissement[]"
                        class="form-control"
                        required>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">

                        Année d'obtention

                    </label>

                    <input
                        type="text"
                        name="annee[]"
                        class="form-control"
                        placeholder="2025"
                        required>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">

                        Mention

                    </label>

                    <select
                        name="mention[]"
                        class="form-select">

                        <option value="">Sélectionner</option>

                        <option value="Passable">Passable</option>

                        <option value="Assez Bien">Assez Bien</option>

                        <option value="Bien">Bien</option>

                        <option value="Très Bien">Très Bien</option>

                        <option value="Excellent">Excellent</option>

                    </select>

                </div>

            </div>

        </div>

    </div>

    `;

    document.getElementById("conteneurCursus")
            .insertAdjacentHTML("beforeend", html);

});


/*=========================================================
SUPPRIMER UN CURSUS
=========================================================*/

document.addEventListener("click", function(e){

    if(e.target.closest(".supprimerCursus")){

        e.target.closest(".cursus-item").remove();

    }

});

</script>

</form>

</div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>