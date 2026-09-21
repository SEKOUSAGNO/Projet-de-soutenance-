<?php
session_start();


/*=========================================================
= Vérifier que l'étudiant est connecté
=========================================================*/

if (!isset($_SESSION['id']) || $_SESSION['role'] != "Etudiant") {

    header("Location: ../login_public.php");
    exit();

}



/*=========================================================
= Vérifier qu'une offre existe
=========================================================*/

if (!isset($_SESSION['offre_id'])) {

    die("Aucune offre sélectionnée.");

}



/*=========================================================
= Vérifier la méthode POST
=========================================================*/

if ($_SERVER["REQUEST_METHOD"] != "POST") {

    header("Location: postuler_experiences.php");
    exit();

}



/*=========================================================
= Vérifier les champs reçus
=========================================================*/

if (

    !isset($_POST['entreprise']) ||
    !isset($_POST['poste']) ||
    !isset($_POST['date_debut']) ||
    !isset($_POST['date_fin']) ||
    !isset($_POST['description'])

) {

    die("Les informations des expériences sont incomplètes.");

}



/*=========================================================
= Récupération des données
=========================================================*/

$entreprises = $_POST['entreprise'];

$postes = $_POST['poste'];

$date_debut = $_POST['date_debut'];

$date_fin = $_POST['date_fin'];

$descriptions = $_POST['description'];



/*=========================================================
= Initialisation de la session
=========================================================*/

$_SESSION['experiences'] = array();



/*=========================================================
= Enregistrement temporaire des expériences
=========================================================*/


for($i = 0; $i < count($entreprises); $i++){



    // Ignorer une ligne vide

    if(

        trim($entreprises[$i]) == "" &&

        trim($postes[$i]) == ""

    ){

        continue;

    }



    $_SESSION['experiences'][] = array(


        "entreprise" => trim($entreprises[$i]),


        "poste" => trim($postes[$i]),


        "date_debut" => $date_debut[$i],


        "date_fin" => $date_fin[$i],


        "description" => trim($descriptions[$i])


    );


}

/*=========================================================
= Vérifier qu'une expérience existe
=========================================================*/

if(count($_SESSION['experiences']) == 0){


    die("Veuillez ajouter au moins une expérience professionnelle.");

}

/*=========================================================
= Enregistrer les expériences dans PostgreSQL
=========================================================*/

require_once("../database.php");

if (!isset($_SESSION['candidature_id'])) {
    die("Candidature introuvable.");
}

$candidature_id = $_SESSION['candidature_id'];

/* Supprimer les anciennes expériences si modification */
pg_query_params(
    $conn,
    "DELETE FROM experience_professionnelle WHERE candidature_id = $1",
    array($candidature_id)
);

foreach ($_SESSION['experiences'] as $experience) {

    $sql = "
        INSERT INTO experience_professionnelle
        (
            candidature_id,
            entreprise,
            poste,
            date_debut,
            date_fin,
            description
        )
        VALUES
        (
            $1,
            $2,
            $3,
            $4,
            $5,
            $6
        )
    ";

    $result = pg_query_params(
        $conn,
        $sql,
        array(
            $candidature_id,
            $experience['entreprise'],
            $experience['poste'],
            $experience['date_debut'],
            $experience['date_fin'],
            $experience['description']
        )
    );

    if (!$result) {
        die("Erreur insertion expérience : " . pg_last_error($conn));
    }
}

/*=========================================================
= Étape suivante
=========================================================*/

header("Location: postuler_competences.php");
exit();


?>