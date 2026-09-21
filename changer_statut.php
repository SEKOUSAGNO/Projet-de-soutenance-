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
$sqlOffre = "
SELECT statut
FROM offre
WHERE id = $1
AND recruteur_id = $2
";


$resultOffre = pg_query_params(
    $conn,
    $sqlOffre,
    array($offre_id, $recruteur_id)
);



if (!$resultOffre || pg_num_rows($resultOffre) == 0) {

    die("Cette offre n'existe pas ou ne vous appartient pas.");

}



$offre = pg_fetch_assoc($resultOffre);



/*=========================================================
= Changer le statut
=========================================================*/

if ($offre['statut'] == "Active") {

    $nouveau_statut = "Désactivée";

} else {

    $nouveau_statut = "Active";

}



/*=========================================================
= Mise à jour du statut
=========================================================*/

$sqlUpdate = "
UPDATE offre
SET statut = $1
WHERE id = $2
";


$resultUpdate = pg_query_params(
    $conn,
    $sqlUpdate,
    array($nouveau_statut, $offre_id)
);



if (!$resultUpdate) {

    die("Erreur lors du changement de statut : " . pg_last_error($conn));

}



/*=========================================================
= Retour vers mes offres
=========================================================*/

header("Location: mes_offres.php?success=statut_modifie");

exit();

?>