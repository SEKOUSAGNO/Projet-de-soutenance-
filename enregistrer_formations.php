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
= Vérifier que le formulaire est envoyé
=========================================================*/

if ($_SERVER["REQUEST_METHOD"] != "POST") {

    header("Location: postuler_formations.php");
    exit();

}


/*=========================================================
= Vérifier les données reçues
=========================================================*/

if (

    !isset($_POST['formation']) ||
    !isset($_POST['organisme']) ||
    !isset($_POST['date_debut']) ||
    !isset($_POST['date_fin'])

) {

    die("Les informations des formations sont incomplètes.");

}



/*=========================================================
= Création du dossier certificat
=========================================================*/

$dossier = "../uploads/certificats/";


if (!is_dir($dossier)) {

    mkdir($dossier,0777,true);

}



/*=========================================================
= Récupération des tableaux
=========================================================*/

$formations = $_POST['formation'];

$organismes = $_POST['organisme'];

$date_debut = $_POST['date_debut'];

$date_fin = $_POST['date_fin'];



/*=========================================================
= Initialisation de la session
=========================================================*/

$_SESSION['formations'] = array();



/*=========================================================
= Traitement des formations
=========================================================*/


for($i=0; $i<count($formations); $i++){


    $certificat = "";



    /*=========================================
    Gestion du fichier certificat
    =========================================*/


    if(

        isset($_FILES['certificat']['name'][$i])

        &&

        $_FILES['certificat']['name'][$i] != ""

    ){


        $nomFichier = time()
        ."_"
        .basename($_FILES['certificat']['name'][$i]);


        $chemin = $dossier.$nomFichier;



        if(move_uploaded_file(

            $_FILES['certificat']['tmp_name'][$i],

            $chemin

        )){


            $certificat = "uploads/certificats/".$nomFichier;


        }


    }



    /*=========================================
    Ajouter la formation dans la session
    =========================================*/


    $_SESSION['formations'][] = array(


        "formation" => trim($formations[$i]),


        "organisme" => trim($organismes[$i]),


        "date_debut" => $date_debut[$i],


        "date_fin" => $date_fin[$i],


        "certificat" => $certificat


    );


}



/*=========================================================
= Vérifier qu'une formation existe
=========================================================*/

if(count($_SESSION['formations']) == 0){


    die("Veuillez ajouter au moins une formation.");

}

/*=========================================================
= Enregistrer les formations dans PostgreSQL
=========================================================*/

require_once("../database.php");

if (!isset($_SESSION['candidature_id'])) {
    die("Candidature introuvable.");
}

$candidature_id = $_SESSION['candidature_id'];

pg_query_params(
    $conn,
    "DELETE FROM formation_professionnelle WHERE candidature_id = $1",
    array($candidature_id)
);

foreach ($_SESSION['formations'] as $formation) {

    $sql = "
        INSERT INTO formation_professionnelle
        (
            candidature_id,
            formation,
            organisme,
            date_debut,
            date_fin,
            certificat
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
            $formation['formation'],
            $formation['organisme'],
            $formation['date_debut'],
            $formation['date_fin'],
            $formation['certificat']
        )
    );

    if (!$result) {
        die("Erreur insertion formation : " . pg_last_error($conn));
    }
}

/*=========================================================
= Étape suivante
=========================================================*/

header("Location: postuler_experiences.php");
exit();

?>