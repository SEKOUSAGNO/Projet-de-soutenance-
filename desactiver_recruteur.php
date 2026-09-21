<?php

session_start();

require_once("../database.php");

// =====================================
// Vérifier que l'utilisateur est connecté
// =====================================

if (!isset($_SESSION['id']) || $_SESSION['role'] != "Admin") {
    header("Location: ../login_public.php");
    exit();
}

// =====================================
// Vérifier que l'administrateur est principal
// =====================================

$sql = "SELECT fonction
        FROM utilisateur
        WHERE id = $1
        AND role = 'Admin'";

$resultat = pg_query_params(
    $conn,
    $sql,
    array($_SESSION['id'])
);

if (!$resultat || pg_num_rows($resultat) == 0) {
    session_destroy();
    header("Location: ../login_public.php");
    exit();
}

$admin = pg_fetch_assoc($resultat);

if ($admin['fonction'] != "Administrateur principal") {

    echo "<script>

            alert('Accès refusé. Seul l\\'administrateur principal peut désactiver un recruteur.');

            window.location='recruteurs.php';

          </script>";

    exit();
}

// =====================================
// Vérifier qu'un ID valide est fourni
// =====================================

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {

    header("Location: recruteurs.php");
    exit();

}

$id = (int) $_GET['id'];

// =====================================
// Vérifier que le recruteur existe
// =====================================

$sql = "SELECT id, statut
        FROM utilisateur
        WHERE id = $1
        AND role = 'Recruteur'";

$resultat = pg_query_params(
    $conn,
    $sql,
    array($id)
);

if (!$resultat || pg_num_rows($resultat) == 0) {

    echo "<script>

            alert('Recruteur introuvable.');

            window.location='recruteurs.php';

          </script>";

    exit();
}

$recruteur = pg_fetch_assoc($resultat);

// =====================================
// Vérifier si le compte est déjà désactivé
// =====================================

if ($recruteur['statut'] == "Désactivé") {

    echo "<script>

            alert('Ce recruteur est déjà désactivé.');

            window.location='recruteurs.php';

          </script>";

    exit();
}

// =====================================
// Désactiver le recruteur
// =====================================

$sql = "UPDATE utilisateur
        SET statut = 'Désactivé'
        WHERE id = $1
        AND role = 'Recruteur'";

$miseAJour = pg_query_params(
    $conn,
    $sql,
    array($id)
);

// =====================================
// Résultat
// =====================================

if ($miseAJour) {

    echo "<script>

            alert('Le compte recruteur a été désactivé avec succès.');

            window.location='recruteurs.php';

          </script>";

} else {

    echo "<script>

            alert('Erreur lors de la désactivation du recruteur.');

            window.location='recruteurs.php';

          </script>";

}

exit();

?>