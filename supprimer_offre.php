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
= Vérifier qu'une offre est sélectionnée
=========================================================*/

if (!isset($_GET['id'])) {

    die("Aucune offre sélectionnée.");

}


$offre_id = $_GET['id'];



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

    die("Vous n'avez pas l'autorisation de supprimer cette offre.");

}



/*=========================================================
= Suppression de l'offre
=========================================================*/

$sqlDelete = "
DELETE FROM offre
WHERE id = $1
AND recruteur_id = $2
";


$resultDelete = pg_query_params(
    $conn,
    $sqlDelete,
    array($offre_id, $recruteur_id)
);



if (!$resultDelete) {

    die("Erreur lors de la suppression : " . pg_last_error($conn));

}



/*=========================================================
= Redirection vers la liste des offres
=========================================================*/

header("Location: mes_offres.php?success=suppression");

exit();

?>