<?php

session_start();

require_once("../database.php");


// =====================================
// Vérifier que l'utilisateur est Admin
// =====================================

if (!isset($_SESSION['id']) || $_SESSION['role'] != "Admin") {

    header("Location: ../login_public.php");
    exit();

}


// =====================================
// Vérifier qu'un ID est envoyé
// =====================================

if (!isset($_GET['id'])) {

    header("Location: recruteurs.php");
    exit();

}


$id = $_GET['id'];



// =====================================
// Vérifier que l'utilisateur est recruteur
// =====================================

$verification = "SELECT id
                 FROM utilisateur
                 WHERE id = $1
                 AND role = 'Recruteur'";


$resultat = pg_query_params(

    $conn,

    $verification,

    array($id)

);



if (!$resultat || pg_num_rows($resultat) == 0) {


    echo "<script>

            alert('Recruteur introuvable.');

            window.location='recruteurs.php';

          </script>";

    exit();

}



// =====================================
// Modifier le statut du recruteur
// =====================================

$sql = "UPDATE utilisateur

        SET statut = 'Refusé'

        WHERE id = $1

        AND role = 'Recruteur'";


$mise_a_jour = pg_query_params(

    $conn,

    $sql,

    array($id)

);



// =====================================
// Message de confirmation
// =====================================

if ($mise_a_jour) {


    echo "<script>

            alert('Le compte recruteur a été refusé.');

            window.location='recruteurs.php';

          </script>";


} else {


    echo "<script>

            alert('Erreur lors du refus du recruteur.');

            window.location='recruteurs.php';

          </script>";

}


exit();


?>