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
= Vérifier que les cursus existent
=========================================================*/

if (!isset($_SESSION['cursus']) || count($_SESSION['cursus']) == 0) {

    header("Location: postuler_cursus.php");
    exit();

}

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

?>

<!DOCTYPE html>

<html lang="fr">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1">

<title>

Étape 3 - Formations professionnelles

</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

<link
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
rel="stylesheet">

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

<i class="fa-solid fa-certificate"></i>

Candidature sur la plateforme

</h3>

<h5>

Étape 3 / 7

</h5>

</div>

<div class="progress mt-3">

<div

class="progress-bar"

style="width:42.85%">

</div>

</div>

<p class="mt-3 text-muted">

Veuillez ajouter vos formations professionnelles.

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

action="enregistrer_formations.php"

method="POST"

enctype="multipart/form-data"

id="formFormation">

<input

type="hidden"

name="offre_id"

value="<?php echo $offre_id; ?>">

<div id="conteneurFormation">
    <!--=========================================================
PREMIÈRE FORMATION PROFESSIONNELLE
==========================================================-->

<div class="card border-primary mb-4 formation-item">

    <div class="card-header bg-primary text-white">

        <h5 class="mb-0">

            <i class="fa-solid fa-certificate"></i>

            Formation professionnelle 1

        </h5>

    </div>

    <div class="card-body">

        <div class="row">

            <!-- Formation -->

            <div class="col-md-6 mb-3">

                <label class="form-label">

                    Intitulé de la formation
                    <span class="text-danger">*</span>

                </label>

                <input
                    type="text"
                    name="formation[]"
                    class="form-control"
                    placeholder="Ex : Développement Web"
                    required>

            </div>

            <!-- Organisme -->

            <div class="col-md-6 mb-3">

                <label class="form-label">

                    Organisme de formation
                    <span class="text-danger">*</span>

                </label>

                <input
                    type="text"
                    name="organisme[]"
                    class="form-control"
                    placeholder="Ex : Orange Digital Center"
                    required>

            </div>

            <!-- Date début -->

            <div class="col-md-6 mb-3">

                <label class="form-label">

                    Date de début
                    <span class="text-danger">*</span>

                </label>

                <input
                    type="date"
                    name="date_debut[]"
                    class="form-control"
                    required>

            </div>

            <!-- Date fin -->

            <div class="col-md-6 mb-3">

                <label class="form-label">

                    Date de fin
                    <span class="text-danger">*</span>

                </label>

                <input
                    type="date"
                    name="date_fin[]"
                    class="form-control"
                    required>

            </div>

            <!-- Certificat -->

            <div class="col-md-12 mb-3">

                <label class="form-label">

                    Certificat (facultatif)

                </label>

                <input
                    type="file"
                    name="certificat[]"
                    class="form-control"
                    accept=".pdf,.jpg,.jpeg,.png">

                <small class="text-muted">

                    Formats autorisés : PDF, JPG, JPEG, PNG

                </small>

            </div>

        </div>

    </div>

</div>

</div>

<hr>

<div class="d-flex justify-content-between">

    <a
        href="postuler_cursus.php"
        class="btn btn-secondary btn-lg">

        <i class="fa-solid fa-arrow-left"></i>

        Précédent

    </a>

    <button
        type="button"
        id="ajouterFormation"
        class="btn btn-success btn-lg">

        <i class="fa-solid fa-plus"></i>

        Ajouter une formation

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

let numeroFormation = 1;

/*=========================================================
AJOUTER UNE FORMATION
=========================================================*/

document.getElementById("ajouterFormation").addEventListener("click", function(){

    numeroFormation++;

    let html = `

    <div class="card border-primary mb-4 formation-item">

        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

            <h5 class="mb-0">

                <i class="fa-solid fa-certificate"></i>

                Formation professionnelle ${numeroFormation}

            </h5>

            <button
                type="button"
                class="btn btn-danger btn-sm supprimerFormation">

                <i class="fa-solid fa-trash"></i>

            </button>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-6 mb-3">

                    <label class="form-label">

                        Intitulé de la formation

                    </label>

                    <input
                        type="text"
                        name="formation[]"
                        class="form-control"
                        required>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">

                        Organisme

                    </label>

                    <input
                        type="text"
                        name="organisme[]"
                        class="form-control"
                        required>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">

                        Date de début

                    </label>

                    <input
                        type="date"
                        name="date_debut[]"
                        class="form-control"
                        required>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">

                        Date de fin

                    </label>

                    <input
                        type="date"
                        name="date_fin[]"
                        class="form-control"
                        required>

                </div>

                <div class="col-md-12 mb-3">

                    <label class="form-label">

                        Certificat (facultatif)

                    </label>

                    <input
                        type="file"
                        name="certificat[]"
                        class="form-control"
                        accept=".pdf,.jpg,.jpeg,.png">

                </div>

            </div>

        </div>

    </div>

    `;

    document.getElementById("conteneurFormation")
            .insertAdjacentHTML("beforeend", html);

});


/*=========================================================
SUPPRIMER UNE FORMATION
=========================================================*/

document.addEventListener("click", function(e){

    if(e.target.closest(".supprimerFormation")){

        e.target.closest(".formation-item").remove();

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