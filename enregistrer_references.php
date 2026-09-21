<?php
session_start();

/*=========================================================
= Vérifier que l'étudiant est connecté
=========================================================*/

if (!isset($_SESSION['id']) || $_SESSION['role'] != "Etudiant") {

    header("Location: ../login_public.php");
    exit();

}

require_once("../database.php");

/*=========================================================
= Vérifier qu'une candidature est en cours
=========================================================*/

if (!isset($_SESSION['candidature_id'])) {

    die("Aucune candidature en cours.");

}

$candidature_id = $_SESSION['candidature_id'];

/*=========================================================
= Vérifier que les données ont été envoyées
=========================================================*/

if (
    !isset($_POST['nom']) ||
    !isset($_POST['fonction']) ||
    !isset($_POST['entreprise']) ||
    !isset($_POST['telephone']) ||
    !isset($_POST['email']) ||
    !isset($_POST['relation'])
) {

    die("Aucune référence professionnelle reçue.");

}

/*=========================================================
= Récupération des tableaux
=========================================================*/

$nom = $_POST['nom'];
$fonction = $_POST['fonction'];
$entreprise = $_POST['entreprise'];
$telephone = $_POST['telephone'];
$email = $_POST['email'];
$relation = $_POST['relation'];

/*=========================================================
= Vérifier que la candidature existe
=========================================================*/

$sqlVerif = "

SELECT id

FROM candidature

WHERE id = $1

";

$resultVerif = pg_query_params(

    $conn,

    $sqlVerif,

    array($candidature_id)

);

if(!$resultVerif){

    die("Erreur PostgreSQL : ".pg_last_error($conn));

}

if(pg_num_rows($resultVerif)==0){

    die("Candidature introuvable.");

}

/*=========================================================
= Supprimer les anciennes références
=========================================================*/

$sqlSuppression = "

DELETE FROM reference_professionnelle

WHERE candidature_id = $1

";

$resultSuppression = pg_query_params(

    $conn,

    $sqlSuppression,

    array($candidature_id)

);

if(!$resultSuppression){

    die("Erreur lors de la suppression des anciennes références : ".pg_last_error($conn));

}

/*=========================================================
= La Partie 2 contiendra l'insertion des nouvelles
= références puis la redirection.
=========================================================*/
/*=========================================================
= ENREGISTREMENT DES RÉFÉRENCES PROFESSIONNELLES
=========================================================*/

for($i = 0; $i < count($nom); $i++){

    /* Ignorer les lignes totalement vides */

    if(
        trim($nom[$i]) == "" &&
        trim($fonction[$i]) == "" &&
        trim($entreprise[$i]) == ""
    ){
        continue;
    }

    $sqlInsertion = "

    INSERT INTO reference_professionnelle
    (
        candidature_id,
        nom,
        fonction,
        entreprise,
        telephone,
        email,
        relation
    )

    VALUES
    (
        $1,
        $2,
        $3,
        $4,
        $5,
        $6,
        $7
    )

    ";

    $resultInsertion = pg_query_params(

        $conn,

        $sqlInsertion,

        array(

            $candidature_id,
            trim($nom[$i]),
            trim($fonction[$i]),
            trim($entreprise[$i]),
            trim($telephone[$i]),
            trim($email[$i]),
            trim($relation[$i])

        )

    );

    if(!$resultInsertion){

        die(

            "Erreur lors de l'enregistrement d'une référence : "

            .pg_last_error($conn)

        );

    }

}

/*=========================================================
= REDIRECTION VERS L'ÉTAPE SUIVANTE
=========================================================*/

header("Location: postuler_documents.php");

exit();

?>