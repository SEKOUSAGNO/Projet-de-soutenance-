<?php
session_start();

if (!isset($_SESSION['id']) || $_SESSION['role'] != 'Recruteur') {
    header("Location: ../login_public.php");
    exit();
}

require_once("../database.php");

/*=========================================
= Utilisateur connecté
=========================================*/

$utilisateur_id = $_SESSION['id'];

/*=========================================
= Récupération des données du formulaire
=========================================*/

$nom_entreprise   = trim($_POST['nom_entreprise']);
$adresse          = trim($_POST['adresse']);
$telephone        = trim($_POST['telephone']);
$secteur_activite = trim($_POST['secteur_activite']);

/*=========================================
= Vérification
=========================================*/

if (
    empty($nom_entreprise) ||
    empty($adresse) ||
    empty($telephone) ||
    empty($secteur_activite)
) {
    die("Tous les champs sont obligatoires.");
}

/*=========================================
= Vérifier si le profil existe
=========================================*/

$sql = "SELECT id
        FROM recruteur_entreprise
        WHERE utilisateur_id = $1";

$result = pg_query_params($conn, $sql, array($utilisateur_id));

if (!$result) {
    die("Erreur PostgreSQL : " . pg_last_error($conn));
}

/*=========================================
= Mise à jour
=========================================*/

if (pg_num_rows($result) > 0) {

    $sql = "UPDATE recruteur_entreprise
            SET
                nom_entreprise = $1,
                adresse = $2,
                telephone = $3,
                secteur_activite = $4
            WHERE utilisateur_id = $5";

    $result = pg_query_params(
        $conn,
        $sql,
        array(
            $nom_entreprise,
            $adresse,
            $telephone,
            $secteur_activite,
            $utilisateur_id
        )
    );

}

/*=========================================
= Insertion
=========================================*/

else {

    $sql = "INSERT INTO recruteur_entreprise
    (
        utilisateur_id,
        nom_entreprise,
        adresse,
        telephone,
        secteur_activite
    )
    VALUES
    (
        $1,$2,$3,$4,$5
    )";

    $result = pg_query_params(
        $conn,
        $sql,
        array(
            $utilisateur_id,
            $nom_entreprise,
            $adresse,
            $telephone,
            $secteur_activite
        )
    );

}

/*=========================================
= Vérification
=========================================*/

if (!$result) {

    die("Erreur PostgreSQL : " . pg_last_error($conn));

}

/*=========================================
= Redirection
=========================================*/

echo "<script>

alert('Profil de l\\'entreprise enregistré avec succès.');

window.location='voir_profil.php';

</script>";

?>