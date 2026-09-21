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
= Vérifier que le formulaire a été soumis
=========================================================*/
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    die("Accès non autorisé.");
}

/*=========================================================
= Vérifier les données obligatoires
=========================================================*/
if (
    !isset($_POST['offre_id']) ||
    !isset($_POST['titre']) ||
    !isset($_POST['description']) ||
    !isset($_POST['type_offre']) ||
    !isset($_POST['entreprise']) ||
    !isset($_POST['lieu']) ||
    !isset($_POST['date_limite']) ||
    !isset($_POST['mode_candidature'])
) {
    die("Informations incomplètes.");
}

/*=========================================================
= Récupération des données
=========================================================*/
$offre_id          = $_POST['offre_id'];
$titre             = trim($_POST['titre']);
$description       = trim($_POST['description']);
$type_offre        = trim($_POST['type_offre']);
$entreprise        = trim($_POST['entreprise']);
$lieu              = trim($_POST['lieu']);
$date_limite       = $_POST['date_limite'];
$mode_candidature  = trim($_POST['mode_candidature']);
$email_reception   = trim($_POST['email_reception']);

/*=========================================================
= Récupérer l'ID du recruteur connecté
=========================================================*/
$sqlRecruteur = "
SELECT id
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
= Vérifier que l'offre appartient au recruteur
=========================================================*/
$sqlVerification = "
SELECT id
FROM offre
WHERE id = $1
AND recruteur_id = $2
";

$resultVerification = pg_query_params(
    $conn,
    $sqlVerification,
    array($offre_id, $recruteur_id)
);

if (!$resultVerification || pg_num_rows($resultVerification) == 0) {
    die("Vous n'êtes pas autorisé à modifier cette offre.");
}

/*=========================================================
= Mise à jour de l'offre
=========================================================*/
$sqlUpdate = "
UPDATE offre
SET
    titre = $1,
    description = $2,
    type_offre = $3,
    date_limite = $4,
    entreprise = $5,
    lieu = $6,
    mode_candidature = $7,
    email_reception = $8
WHERE id = $9
";

$resultUpdate = pg_query_params(
    $conn,
    $sqlUpdate,
    array(
        $titre,
        $description,
        $type_offre,
        $date_limite,
        $entreprise,
        $lieu,
        $mode_candidature,
        $email_reception,
        $offre_id
    )
);

if (!$resultUpdate) {
    die("Erreur lors de la modification de l'offre : " . pg_last_error($conn));
}

/*=========================================================
= Redirection
=========================================================*/
header("Location: mes_offres.php?success=modification");
exit();
?>