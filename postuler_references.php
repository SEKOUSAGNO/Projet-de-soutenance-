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
= Vérifier qu'une candidature existe
=========================================================*/

if (!isset($_SESSION['candidature_id'])) {

    die("Aucune candidature en cours.");

}

$candidature_id = $_SESSION['candidature_id'];

/*=========================================================
= Vérifier que la candidature existe
=========================================================*/

$sql = "

SELECT

c.id,
o.titre,
o.entreprise,
o.lieu,
o.type_offre

FROM candidature c

INNER JOIN offre o

ON c.offre_id = o.id

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

    die("Candidature introuvable.");

}

$candidature = pg_fetch_assoc($result);

?>

<!DOCTYPE html>

<html lang="fr">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1">

<title>

Étape 5 - Références professionnelles

</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

<style>

body{

background:#eef5ff;

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

<div class="d-flex justify-content-between">

<h3 class="titre">

<i class="fa-solid fa-user-tie"></i>

Références professionnelles

</h3>

<h5>

Étape 5 / 7

</h5>

</div>

<div class="progress mt-3">

<div

class="progress-bar"

style="width:71.43%">

</div>

</div>

<p class="mt-3 text-muted">

Ajoutez une ou plusieurs références professionnelles.

</p>

<hr>

<div class="alert alert-primary">

<strong>Offre :</strong>

<?php echo htmlspecialchars($candidature['titre']); ?>

<br>

<strong>Entreprise :</strong>

<?php echo htmlspecialchars($candidature['entreprise']); ?>

<br>

<strong>Lieu :</strong>

<?php echo htmlspecialchars($candidature['lieu']); ?>

</div>

<form

action="enregistrer_references.php"

method="POST"

id="formReference">

<input

type="hidden"

name="candidature_id"

value="<?php echo $candidature_id; ?>">

<div id="conteneurReferences">
    <!--=========================================================
PREMIÈRE RÉFÉRENCE PROFESSIONNELLE
==========================================================-->

<div class="card border-primary mb-4 reference-item">

    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

        <h5 class="mb-0">

            <i class="fa-solid fa-user-tie"></i>

            Référence professionnelle 1

        </h5>

    </div>

    <div class="card-body">

        <div class="row">

            <!-- Nom -->

            <div class="col-md-6 mb-3">

                <label class="form-label">

                    Nom complet <span class="text-danger">*</span>

                </label>

                <input
                type="text"
                name="nom[]"
                class="form-control"
                placeholder="Nom et prénom"
                required>

            </div>

            <!-- Fonction -->

            <div class="col-md-6 mb-3">

                <label class="form-label">

                    Fonction <span class="text-danger">*</span>

                </label>

                <input
                type="text"
                name="fonction[]"
                class="form-control"
                placeholder="Ex : Directeur Général"
                required>

            </div>

            <!-- Entreprise -->

            <div class="col-md-6 mb-3">

                <label class="form-label">

                    Entreprise <span class="text-danger">*</span>

                </label>

                <input
                type="text"
                name="entreprise[]"
                class="form-control"
                placeholder="Nom de l'entreprise"
                required>

            </div>

            <!-- Téléphone -->

            <div class="col-md-6 mb-3">

                <label class="form-label">

                    Téléphone <span class="text-danger">*</span>

                </label>

                <input
                type="text"
                name="telephone[]"
                class="form-control"
                placeholder="+224 ..."
                required>

            </div>

            <!-- Email -->

            <div class="col-md-6 mb-3">

                <label class="form-label">

                    Email

                </label>

                <input
                type="email"
                name="email[]"
                class="form-control"
                placeholder="adresse@email.com">

            </div>

            <!-- Relation -->

            <div class="col-md-6 mb-3">

                <label class="form-label">

                    Relation professionnelle <span class="text-danger">*</span>

                </label>

                <select
                name="relation[]"
                class="form-select"
                required>

                    <option value="">Sélectionner</option>

                    <option value="Supérieur hiérarchique">

                        Supérieur hiérarchique

                    </option>

                    <option value="Chef de service">

                        Chef de service

                    </option>

                    <option value="Directeur">

                        Directeur

                    </option>

                    <option value="Responsable RH">

                        Responsable RH

                    </option>

                    <option value="Collègue">

                        Collègue

                    </option>

                    <option value="Autre">

                        Autre

                    </option>

                </select>

            </div>

        </div>

    </div>

</div>

</div>

<hr>

<div class="d-flex justify-content-between">

    <a
    href="postuler_experiences.php"
    class="btn btn-secondary btn-lg">

        <i class="fa-solid fa-arrow-left"></i>

        Précédent

    </a>

    <button
    type="button"
    id="ajouterReference"
    class="btn btn-success btn-lg">

        <i class="fa-solid fa-plus"></i>

        Ajouter une référence

    </button>

    <button
    type="submit"
    class="btn btn-primary btn-lg">

        Suivant

        <i class="fa-solid fa-arrow-right"></i>

    </button>

</div>
</form>

</div>
</div>
</div>

<script>

let numeroReference = 1;

/*=========================================================
AJOUTER UNE RÉFÉRENCE
=========================================================*/

document.getElementById("ajouterReference").addEventListener("click", function(){

    numeroReference++;

    let html = `

<div class="card border-primary mb-4 reference-item">

<div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

<h5 class="mb-0">

<i class="fa-solid fa-user-tie"></i>

Référence professionnelle ${numeroReference}

</h5>

<button
type="button"
class="btn btn-danger btn-sm supprimerReference">

<i class="fa-solid fa-trash"></i>

</button>

</div>

<div class="card-body">

<div class="row">

<div class="col-md-6 mb-3">

<label class="form-label">

Nom complet

</label>

<input
type="text"
name="nom[]"
class="form-control"
required>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Fonction

</label>

<input
type="text"
name="fonction[]"
class="form-control"
required>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Entreprise

</label>

<input
type="text"
name="entreprise[]"
class="form-control"
required>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Téléphone

</label>

<input
type="text"
name="telephone[]"
class="form-control"
required>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Email

</label>

<input
type="email"
name="email[]"
class="form-control">

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Relation

</label>

<select
name="relation[]"
class="form-select"
required>

<option value="">Sélectionner</option>

<option value="Supérieur hiérarchique">Supérieur hiérarchique</option>

<option value="Chef de service">Chef de service</option>

<option value="Directeur">Directeur</option>

<option value="Responsable RH">Responsable RH</option>

<option value="Collègue">Collègue</option>

<option value="Autre">Autre</option>

</select>

</div>

</div>

</div>

</div>

`;

    document.getElementById("conteneurReferences")
            .insertAdjacentHTML("beforeend", html);

});


/*=========================================================
SUPPRIMER UNE RÉFÉRENCE
=========================================================*/

document.addEventListener("click", function(e){

    if(e.target.closest(".supprimerReference")){

        e.target.closest(".reference-item").remove();

    }

});

</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>