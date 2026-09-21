<?php
session_start();

if (!isset($_SESSION['id']) || $_SESSION['role'] != 'Recruteur') {
    header("Location: ../login_public.php");
    exit();
}

require_once("../database.php");

/*==================================================
= Récupérer l'id du recruteur dans recruteur_entreprise
===================================================*/

$sqlRecruteur = "SELECT id
                 FROM recruteur_entreprise
                 WHERE utilisateur_id = $1";

$resultRecruteur = pg_query_params($conn, $sqlRecruteur, array($_SESSION['id']));

if (!$resultRecruteur) {
    die("Erreur PostgreSQL : " . pg_last_error($conn));
}

if (pg_num_rows($resultRecruteur) == 0) {
    die("Votre profil recruteur n'existe pas. Veuillez d'abord compléter votre profil entreprise.");
}

$recruteur = pg_fetch_assoc($resultRecruteur);

$recruteur_id = $recruteur['id'];

/*==================================================
= Récupération des données du formulaire
===================================================*/

$titre = trim($_POST['titre']);
$description = trim($_POST['description']);
$type_offre = trim($_POST['type_offre']);
$date_publication = $_POST['date_publication'];
$date_limite = $_POST['date_limite'];
$entreprise = trim($_POST['entreprise']);
$lieu = trim($_POST['lieu']);
$mode_candidature = "Email";
$email_reception = trim($_POST['email_reception']);
$statut = "Active";

/*==================================================
= Vérification des champs
===================================================*/

if (
    empty($titre) ||
    empty($description) ||
    empty($entreprise) ||
    empty($lieu) ||
    empty($type_offre) ||
    empty($date_limite) ||
    empty($email_reception)
) {
    die("Tous les champs sont obligatoires.");
}

/*==================================================
= Vérification de la date
===================================================*/

if (strtotime($date_limite) < strtotime(date("Y-m-d"))) {
    die("La date limite doit être supérieure ou égale à aujourd'hui.");
}

/*==================================================
= Insertion dans la table offre
===================================================*/

$sql = "INSERT INTO offre
(
    recruteur_id,
    titre,
    description,
    type_offre,
    date_publication,
    date_limite,
    entreprise,
    lieu,
    mode_candidature,
    email_reception,
    statut
)
VALUES
(
    $1,$2,$3,$4,$5,$6,$7,$8,$9,$10,$11
)";

$result = pg_query_params(
    $conn,
    $sql,
    array(
        $recruteur_id,
        $titre,
        $description,
        $type_offre,
        $date_publication,
        $date_limite,
        $entreprise,
        $lieu,
        $mode_candidature,
        $email_reception,
        $statut
    )
);

if (!$result) {
    die("Erreur PostgreSQL : " . pg_last_error($conn));
}

/*==================================================
= Redirection
===================================================*/

echo "<script>
alert('Offre publiée avec succès.');
window.location='mes_offres.php';
</script>";

?>