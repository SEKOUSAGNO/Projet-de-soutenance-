<?php
session_start();

/*=========================================================
= Vérifier que le recruteur est connecté
=========================================================*/

if (!isset($_SESSION['id']) || $_SESSION['role'] != "Recruteur") {

    header("Location: ../login_public.php");
    exit();

}

require_once("../database.php");


/*=========================================================
= Vérifier l'identifiant de la candidature
=========================================================*/

if (!isset($_GET['id'])) {

    die("Candidature introuvable.");

}

$candidature_id = intval($_GET['id']);


/*=========================================================
= Récupérer le recruteur connecté
=========================================================*/

$sqlRecruteur = "

SELECT
id,
nom_entreprise

FROM recruteur_entreprise

WHERE utilisateur_id = $1

";

$resultRecruteur = pg_query_params(

    $conn,
    $sqlRecruteur,
    array($_SESSION['id'])

);

if (!$resultRecruteur || pg_num_rows($resultRecruteur) == 0) {

    die("Profil recruteur introuvable.");

}

$recruteur = pg_fetch_assoc($resultRecruteur);

$recruteur_id = $recruteur['id'];


/*=========================================================
= Récupérer les informations de la candidature
=========================================================*/

$sql = "

SELECT

c.id AS candidature_id,
c.date_candidature,
c.statut,

o.id AS offre_id,
o.titre,
o.entreprise,
o.type_offre,
o.lieu,

e.id AS etudiant_id,
e.nom,
e.prenom,
e.photo,
e.sexe,
e.date_naissance,
e.telephone,
e.adresse,
e.filiere,
e.niveau,
e.faculte,
e.annee_universitaire,
e.cv,

u.email

FROM candidature c

INNER JOIN offre o
ON c.offre_id = o.id

INNER JOIN etudiant e
ON c.etudiant_id = e.id

INNER JOIN utilisateur u
ON e.utilisateur_id = u.id

WHERE

c.id = $1

AND

o.recruteur_id = $2

";

$result = pg_query_params(

$conn,

$sql,

array(

$candidature_id,

$recruteur_id

)

);

if(!$result){

    die("Erreur PostgreSQL : ".pg_last_error($conn));

}

if(pg_num_rows($result)==0){

    die("Cette candidature n'existe pas ou ne vous appartient pas.");

}

$candidat = pg_fetch_assoc($result);

?>
<!DOCTYPE html>

<html lang="fr">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>

Dossier de candidature

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

.photo{

    width:170px;
    height:170px;
    border-radius:50%;
    object-fit:cover;
    border:5px solid #0d6efd;

}

.titre{

    color:#0d6efd;
    font-weight:bold;

}

.info{

    font-size:16px;
    margin-bottom:10px;

}

</style>

</head>

<body>

<div class="container py-4"></div></div>
<!--=========================================================
INFORMATIONS DU CANDIDAT
==========================================================-->

<div class="card shadow mb-4">

    <div class="card-header bg-primary text-white">

        <h3 class="mb-0">

            <i class="fa-solid fa-user-graduate"></i>

            Dossier du candidat

        </h3>

    </div>

    <div class="card-body">

        <div class="row">

            <!--==================== PHOTO ====================-->

            <div class="col-md-3 text-center">

                <?php if(!empty($candidat['photo'])){ ?>

                    <img
                    src="../<?php echo htmlspecialchars($candidat['photo']); ?>"
                    class="photo">

                <?php } else { ?>

                    <img
                    src="../images/photos/default.png"
                    class="photo">

                <?php } ?>

                <br><br>

                <?php if(!empty($candidat['cv'])){ ?>

                    <a
                    href="../<?php echo htmlspecialchars($candidat['cv']); ?>"
                    target="_blank"
                    class="btn btn-success">

                        <i class="fa-solid fa-file-pdf"></i>

                        Voir le CV

                    </a>

                <?php } else { ?>

                    <span class="badge bg-danger">

                        Aucun CV disponible

                    </span>

                <?php } ?>

            </div>

            <!--==================== INFORMATIONS ====================-->

            <div class="col-md-9">

                <div class="row">

                    <div class="col-md-6">

                        <p class="info">

                            <strong>Nom :</strong>

                            <?php echo htmlspecialchars($candidat['nom']); ?>

                        </p>

                    </div>

                    <div class="col-md-6">

                        <p class="info">

                            <strong>Prénom :</strong>

                            <?php echo htmlspecialchars($candidat['prenom']); ?>

                        </p>

                    </div>

                    <div class="col-md-6">

                        <p class="info">

                            <strong>Email :</strong>

                            <?php echo htmlspecialchars($candidat['email']); ?>

                        </p>

                    </div>

                    <div class="col-md-6">

                        <p class="info">

                            <strong>Téléphone :</strong>

                            <?php echo htmlspecialchars($candidat['telephone']); ?>

                        </p>

                    </div>

                    <div class="col-md-6">

                        <p class="info">

                            <strong>Sexe :</strong>

                            <?php echo htmlspecialchars($candidat['sexe']); ?>

                        </p>

                    </div>

                    <div class="col-md-6">

                        <p class="info">

                            <strong>Date de naissance :</strong>

                            <?php

                            if(!empty($candidat['date_naissance'])){

                                echo date("d/m/Y",strtotime($candidat['date_naissance']));

                            }

                            ?>

                        </p>

                    </div>

                    <div class="col-md-12">

                        <p class="info">

                            <strong>Adresse :</strong>

                            <?php echo htmlspecialchars($candidat['adresse']); ?>

                        </p>

                    </div>

                    <div class="col-md-6">

                        <p class="info">

                            <strong>Faculté :</strong>

                            <?php echo htmlspecialchars($candidat['faculte']); ?>

                        </p>

                    </div>

                    <div class="col-md-6">

                        <p class="info">

                            <strong>Filière :</strong>

                            <?php echo htmlspecialchars($candidat['filiere']); ?>

                        </p>

                    </div>

                    <div class="col-md-6">

                        <p class="info">

                            <strong>Niveau :</strong>

                            <?php echo htmlspecialchars($candidat['niveau']); ?>

                        </p>

                    </div>

                    <div class="col-md-6">

                        <p class="info">

                            <strong>Année universitaire :</strong>

                            <?php echo htmlspecialchars($candidat['annee_universitaire']); ?>

                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<!--=========================================================
INFORMATIONS DE L'OFFRE
==========================================================-->

<div class="card shadow mb-4">

    <div class="card-header bg-success text-white">

        <h4 class="mb-0">

            <i class="fa-solid fa-briefcase"></i>

            Offre concernée

        </h4>

    </div>

    <div class="card-body">

        <div class="row">

            <div class="col-md-6">

                <p>

                    <strong>Titre :</strong>

                    <?php echo htmlspecialchars($candidat['titre']); ?>

                </p>

            </div>

            <div class="col-md-6">

                <p>

                    <strong>Entreprise :</strong>

                    <?php echo htmlspecialchars($candidat['entreprise']); ?>

                </p>

            </div>

            <div class="col-md-6">

                <p>

                    <strong>Type :</strong>

                    <?php echo htmlspecialchars($candidat['type_offre']); ?>

                </p>

            </div>

            <div class="col-md-6">

                <p>

                    <strong>Lieu :</strong>

                    <?php echo htmlspecialchars($candidat['lieu']); ?>

                </p>

            </div>

            <div class="col-md-6">

                <p>

                    <strong>Date de candidature :</strong>

                    <?php

                    echo date(

                    "d/m/Y H:i",

                    strtotime($candidat['date_candidature'])

                    );

                    ?>

                </p>

            </div>

            <div class="col-md-6">

                <p>

                    <strong>Statut :</strong>

                    <?php echo htmlspecialchars($candidat['statut']); ?>

                </p>

            </div>

        </div>

    </div>

</div>
<!--=========================================================
RÉCUPÉRATION DES CURSUS UNIVERSITAIRES
==========================================================-->

<?php

$sqlCursus = "

SELECT

diplome,
etablissement,
annee,
mention

FROM cursus

WHERE candidature_id = $1

ORDER BY annee DESC

";

$resultCursus = pg_query_params(

$conn,

$sqlCursus,

array($candidature_id)

);

?>

<!--=========================================================
AFFICHAGE DES CURSUS
==========================================================-->

<div class="card shadow mb-4">

<div class="card-header bg-primary text-white">

<h4 class="mb-0">

<i class="fa-solid fa-graduation-cap"></i>

Cursus universitaires

</h4>

</div>

<div class="card-body">

<?php if(pg_num_rows($resultCursus) > 0){ ?>

<div class="table-responsive">

<table class="table table-bordered table-striped table-hover">

<thead class="table-primary">

<tr>

<th width="35%">Diplôme</th>

<th width="35%">Établissement</th>

<th width="15%">Année</th>

<th width="15%">Mention</th>

</tr>

</thead>

<tbody>

<?php while($cursus = pg_fetch_assoc($resultCursus)){ ?>

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

<?php

if(empty($cursus['mention'])){

    echo "-";

}else{

    echo htmlspecialchars($cursus['mention']);

}

?>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

<?php } else { ?>

<div class="alert alert-warning mb-0">

<i class="fa-solid fa-circle-exclamation"></i>

Aucun cursus universitaire enregistré.

</div>

<?php } ?>

</div>

</div>
<!--=========================================================
RÉCUPÉRATION DES FORMATIONS PROFESSIONNELLES
==========================================================-->

<?php

$sqlFormations = "

SELECT

formation,
organisme,
date_debut,
date_fin,
certificat

FROM formation_professionnelle

WHERE candidature_id = $1

ORDER BY date_debut DESC

";

$resultFormations = pg_query_params(

    $conn,

    $sqlFormations,

    array($candidature_id)

);

?>

<!--=========================================================
AFFICHAGE DES FORMATIONS PROFESSIONNELLES
==========================================================-->

<div class="card shadow mb-4">

    <div class="card-header bg-success text-white">

        <h4 class="mb-0">

            <i class="fa-solid fa-certificate"></i>

            Formations professionnelles

        </h4>

    </div>

    <div class="card-body">

        <?php if(pg_num_rows($resultFormations) > 0){ ?>

        <div class="table-responsive">

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

                <?php while($formation = pg_fetch_assoc($resultFormations)){ ?>

                <tr>

                    <td><?= htmlspecialchars($formation['formation']) ?></td>

                    <td><?= htmlspecialchars($formation['organisme']) ?></td>

                    <td>

                        <?= !empty($formation['date_debut']) ? date("d/m/Y",strtotime($formation['date_debut'])) : "-" ?>

                    </td>

                    <td>

                        <?= !empty($formation['date_fin']) ? date("d/m/Y",strtotime($formation['date_fin'])) : "-" ?>

                    </td>

                    <td>

                        <?php

                        if(!empty($formation['certificat'])){

                            ?>

                            <a

                            href="../<?= htmlspecialchars($formation['certificat']) ?>"

                            target="_blank"

                            class="btn btn-primary btn-sm">

                                Voir

                            </a>

                            <?php

                        }else{

                            echo "-";

                        }

                        ?>

                    </td>

                </tr>

                <?php } ?>

                </tbody>

            </table>

        </div>

        <?php }else{ ?>

            <div class="alert alert-warning mb-0">

                Aucune formation professionnelle enregistrée.

            </div>

        <?php } ?>

    </div>

</div>



<!--=========================================================
RÉCUPÉRATION DES EXPÉRIENCES PROFESSIONNELLES
==========================================================-->

<?php

$sqlExperiences = "

SELECT

entreprise,
poste,
date_debut,
date_fin,
description

FROM experience_professionnelle

WHERE candidature_id = $1

ORDER BY date_debut DESC

";

$resultExperiences = pg_query_params(

    $conn,

    $sqlExperiences,

    array($candidature_id)

);

?>



<!--=========================================================
AFFICHAGE DES EXPÉRIENCES PROFESSIONNELLES
==========================================================-->

<div class="card shadow mb-4">

    <div class="card-header bg-info text-white">

        <h4 class="mb-0">

            <i class="fa-solid fa-briefcase"></i>

            Expériences professionnelles

        </h4>

    </div>

    <div class="card-body">

        <?php if(pg_num_rows($resultExperiences)>0){ ?>

        <?php while($experience = pg_fetch_assoc($resultExperiences)){ ?>

        <div class="card border-primary mb-3">

            <div class="card-body">

                <h5 class="text-primary">

                    <?= htmlspecialchars($experience['poste']) ?>

                </h5>

                <p>

                    <strong>Entreprise :</strong>

                    <?= htmlspecialchars($experience['entreprise']) ?>

                </p>

                <p>

                    <strong>Période :</strong>

                    <?=

                    (!empty($experience['date_debut'])

                    ? date("d/m/Y",strtotime($experience['date_debut']))

                    : "-")

                    ?>

                    →

                    <?=

                    (!empty($experience['date_fin'])

                    ? date("d/m/Y",strtotime($experience['date_fin']))

                    : "Aujourd'hui")

                    ?>

                </p>

                <p>

                    <strong>Description :</strong>

                    <br>

                    <?= nl2br(htmlspecialchars($experience['description'])) ?>

                </p>

            </div>

        </div>

        <?php } ?>

        <?php }else{ ?>

        <div class="alert alert-warning mb-0">

            Aucune expérience professionnelle enregistrée.

        </div>

        <?php } ?>

    </div>

</div>
<!--=========================================================
RÉFÉRENCES PROFESSIONNELLES
==========================================================-->

<?php

$sqlReferences = "

SELECT

nom,
fonction,
entreprise,
telephone,
email,
relation

FROM reference_professionnelle

WHERE candidature_id = $1

ORDER BY id

";

$resultReferences = pg_query_params(

$conn,

$sqlReferences,

array($candidature_id)

);

?>

<div class="card shadow mb-4">

<div class="card-header bg-warning">

<h4 class="mb-0">

<i class="fa-solid fa-users"></i>

Références professionnelles

</h4>

</div>

<div class="card-body">

<?php if(pg_num_rows($resultReferences)>0){ ?>

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead>

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

<?php while($ref = pg_fetch_assoc($resultReferences)){ ?>

<tr>

<td><?= htmlspecialchars($ref['nom']) ?></td>

<td><?= htmlspecialchars($ref['fonction']) ?></td>

<td><?= htmlspecialchars($ref['entreprise']) ?></td>

<td><?= htmlspecialchars($ref['telephone']) ?></td>

<td><?= htmlspecialchars($ref['email']) ?></td>

<td><?= htmlspecialchars($ref['relation']) ?></td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

<?php }else{ ?>

<div class="alert alert-warning mb-0">

Aucune référence professionnelle.

</div>

<?php } ?>

</div>

</div>



<!--=========================================================
DOCUMENTS DE CANDIDATURE
==========================================================-->

<?php

$sqlDocuments = "

SELECT

nom_document,
fichier

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

<div class="card shadow mb-4">

<div class="card-header bg-secondary text-white">

<h4 class="mb-0">

<i class="fa-solid fa-folder-open"></i>

Documents joints

</h4>

</div>

<div class="card-body">

<?php if(pg_num_rows($resultDocuments)>0){ ?>

<table class="table table-bordered table-hover">

<thead>

<tr>

<th>Document</th>

<th>Télécharger</th>

</tr>

</thead>

<tbody>

<?php while($doc = pg_fetch_assoc($resultDocuments)){ ?>

<tr>

<td>

<?= htmlspecialchars($doc['nom_document']) ?>

</td>

<td>

<a

href="../<?= htmlspecialchars($doc['fichier']) ?>"

target="_blank"

class="btn btn-primary btn-sm">

<i class="fa-solid fa-download"></i>

Ouvrir

</a>

</td>

</tr>

<?php } ?>

</tbody>

</table>

<?php }else{ ?>

<div class="alert alert-warning mb-0">

Aucun document joint.

</div>

<?php } ?>

</div>

</div>



<!--=========================================================
BOUTONS
==========================================================-->

<div class="text-center mb-5">

<a

href="candidatures.php"

class="btn btn-secondary btn-lg">

<i class="fa-solid fa-arrow-left"></i>

Retour

</a>

<a

href="accepter_candidature.php?id=<?= $candidature_id ?>"

class="btn btn-success btn-lg">

<i class="fa-solid fa-check"></i>

Accepter

</a>

<a

href="refuser_candidature.php?id=<?= $candidature_id ?>"

class="btn btn-danger btn-lg">

<i class="fa-solid fa-xmark"></i>

Refuser

</a>

</div>

</div>

</body>

</html>