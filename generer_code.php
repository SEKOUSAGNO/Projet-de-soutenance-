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
// Fonction de génération d'un code unique
// ======================================
function genererCode($longueur = 8)
{
    $caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

    $code = '';

    for ($i = 0; $i < $longueur; $i++) {

        $code .= $caracteres[random_int(0, strlen($caracteres) - 1)];

    }

    return "SIMAN-" . $code;
}

// ======================================
// Générer un code qui n'existe pas déjà
// ======================================
do {

    $nouveauCode = genererCode();

    $verification = "SELECT id
                     FROM code_accreditation
                     WHERE code = $1";

    $resultat = pg_query_params(
        $conn,
        $verification,
        array($nouveauCode)
    );

} while (pg_num_rows($resultat) > 0);

// ======================================
// Enregistrer le code
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

$insertion = pg_query_params(
    $conn,
    $sql,
    array($nouveauCode)
);

// ======================================
// Résultat
// ======================================
if ($insertion) {

    echo "<script>

            alert('Code généré avec succès : " . $nouveauCode . "');

            window.location='codes_accreditation.php';

          </script>";

} else {

    echo "<script>

            alert('Erreur lors de la génération du code.');

            window.location='codes_accreditation.php';

          </script>";

}

exit();

?>