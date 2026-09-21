<?php
session_start();

if (!isset($_SESSION['id']) || $_SESSION['role'] != "Recruteur") {
    header("Location: ../login_public.php");
    exit();
}

require_once("../database.php");

/*=========================================
= Vérifier l'identifiant de la candidature
==========================================*/

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Candidature invalide.");
}

$candidature_id = intval($_GET['id']);


/*=========================================
= Récupérer l'identifiant du recruteur
==========================================*/

$sql = "
SELECT id
FROM recruteur_entreprise
WHERE utilisateur_id = $1
";

$result = pg_query_params(
    $conn,
    $sql,
    array($_SESSION['id'])
);

if (!$result || pg_num_rows($result) == 0) {
    die("Profil recruteur introuvable.");
}

$recruteur = pg_fetch_assoc($result);

$recruteur_id = $recruteur['id'];


/*=========================================
= Vérifier que cette candidature appartient
= à une offre de ce recruteur
==========================================*/

$sql = "
SELECT c.id
FROM candidature c
INNER JOIN offre o
ON c.offre_id = o.id
WHERE c.id = $1
AND o.recruteur_id = $2
";

$result = pg_query_params(
    $conn,
    $sql,
    array(
        $candidature_id,
        $recruteur_id
    )
);

if (!$result || pg_num_rows($result) == 0) {
    die("Vous n'êtes pas autorisé à modifier cette candidature.");
}


/*=========================================
= Accepter la candidature
==========================================*/

$sql = "
UPDATE candidature
SET statut = 'Acceptée'
WHERE id = $1
";

$result = pg_query_params(
    $conn,
    $sql,
    array($candidature_id)
);

if (!$result) {
    die("Erreur PostgreSQL : " . pg_last_error($conn));
}


/*=========================================
= Retour à la liste
==========================================*/

header("Location: candidatures.php");
exit();
?>