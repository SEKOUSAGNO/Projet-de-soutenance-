<?php

session_start();

require_once("../database.php");

// ======================================
// Vérifier que l'utilisateur est connecté
// ======================================
if (!isset($_SESSION['id'])) {
    header("Location: ../login_public.php");
    exit();
}

// ======================================
// Vérifier que l'utilisateur est Admin
// ======================================
if ($_SESSION['role'] != "Admin") {
    header("Location: ../login_public.php");
    exit();
}

// ======================================
// Vérifier que le formulaire est envoyé
// ======================================
if (!isset($_POST['code'])) {

    header("Location: ajouter_code.php");
    exit();

}

// ======================================
// Récupération du code
// ======================================
$code = strtoupper(trim($_POST['code']));

// Vérifier que le champ n'est pas vide
if (empty($code)) {

    echo "<script>
            alert('Veuillez saisir un code.');
            window.location='ajouter_code.php';
          </script>";
    exit();

}

// ======================================
// Vérifier si le code existe déjà
// ======================================
$sql = "SELECT id
        FROM code_accreditation
        WHERE code = $1";

$resultat = pg_query_params(
    $conn,
    $sql,
    array($code)
);

if (pg_num_rows($resultat) > 0) {

    echo "<script>
            alert('Ce code existe déjà.');
            window.location='ajouter_code.php';
          </script>";
    exit();

}

// ======================================
// Enregistrement
// ======================================
$sql = "INSERT INTO code_accreditation
        (
            code,
            actif,
            utilise,
            date_creation
        )
        VALUES
        (
            $1,
            TRUE,
            FALSE,
            NOW()
        )";

$resultat = pg_query_params(
    $conn,
    $sql,
    array($code)
);

// ======================================
// Vérification
// ======================================
if ($resultat) {

    echo "<script>
            alert('Code d\\'accréditation enregistré avec succès.');
            window.location='codes_accreditation.php';
          </script>";

} else {

    echo "<script>
            alert('Erreur lors de l\\'enregistrement.');
            window.location='ajouter_code.php';
          </script>";

}

exit();

?>