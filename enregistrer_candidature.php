<?php
session_start();

if (!isset($_SESSION['id']) || $_SESSION['role'] != 'Etudiant') {
    header("Location: ../login_public.php");
    exit();
}

require_once("../database.php");

/*=====================================
= Récupérer l'étudiant connecté
=====================================*/

$sqlEtudiant = "
SELECT id
FROM etudiant
WHERE utilisateur_id = $1
";

$resultEtudiant = pg_query_params(
    $conn,
    $sqlEtudiant,
    array($_SESSION['id'])
);

if (!$resultEtudiant || pg_num_rows($resultEtudiant) == 0) {
    die("Votre profil étudiant est introuvable.");
}

$etudiant = pg_fetch_assoc($resultEtudiant);

$etudiant_id = $etudiant['id'];


/*=====================================
= Récupérer les données du formulaire
=====================================*/

$offre_id = intval($_POST['offre_id']);

$date_candidature = date("Y-m-d H:i:s");

$statut = "En attente";

$cv_personnalise = "";


/*=====================================
= Vérifier si l'offre existe
=====================================*/

$sqlOffre = "
SELECT id
FROM offre
WHERE id = $1
";

$resultOffre = pg_query_params(
    $conn,
    $sqlOffre,
    array($offre_id)
);

if (!$resultOffre || pg_num_rows($resultOffre) == 0) {
    die("Cette offre n'existe pas.");
}


/*=====================================
= Vérifier si déjà candidat
=====================================*/

$sqlVerif = "
SELECT id
FROM candidature
WHERE etudiant_id=$1
AND offre_id=$2
";

$resultVerif = pg_query_params(
    $conn,
    $sqlVerif,
    array(
        $etudiant_id,
        $offre_id
    )
);

if (pg_num_rows($resultVerif) > 0) {
    die("Vous avez déjà postulé à cette offre.");
}


/*=====================================
= Upload du CV personnalisé
=====================================*/

if (isset($_FILES['cv_personnalise']) && $_FILES['cv_personnalise']['error'] == 0) {

    $extension = strtolower(pathinfo($_FILES['cv_personnalise']['name'], PATHINFO_EXTENSION));

    if ($extension != "pdf") {
        die("Le CV doit être au format PDF.");
    }

    $nomCV = time() . "_" . basename($_FILES['cv_personnalise']['name']);

    $destination = "../cv/" . $nomCV;

    if (move_uploaded_file($_FILES['cv_personnalise']['tmp_name'], $destination)) {

        $cv_personnalise = "cv/" . $nomCV;

    } else {

        die("Erreur lors de l'enregistrement du CV.");

    }

} else {

    die("Veuillez sélectionner votre CV.");

}


/*=====================================
= Enregistrer la candidature
=====================================*/

$sql = "
INSERT INTO candidature
(
    etudiant_id,
    offre_id,
    date_candidature,
    statut,
    cv_personnalise
)
VALUES
(
    $1,
    $2,
    $3,
    $4,
    $5
)
";

$result = pg_query_params(
    $conn,
    $sql,
    array(
        $etudiant_id,
        $offre_id,
        $date_candidature,
        $statut,
        $cv_personnalise
    )
);

if (!$result) {

    die("Erreur PostgreSQL : " . pg_last_error($conn));

}


/*=====================================
= Redirection
=====================================*/

echo "
<script>

alert('Votre candidature a été envoyée avec succès.');

window.location='candidatures.php';

</script>
";

?>