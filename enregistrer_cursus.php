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
= Vérifier qu'une offre est sélectionnée
=========================================================*/

if (!isset($_SESSION['offre_id'])) {

    die("Aucune offre sélectionnée.");

}

$offre_id = $_SESSION['offre_id'];



/*=========================================================
= Vérifier que le formulaire est envoyé
=========================================================*/

if ($_SERVER["REQUEST_METHOD"] != "POST") {

    header("Location: postuler_cursus.php");

    exit();

}



/*=========================================================
= Vérifier les données reçues
=========================================================*/

if (

    !isset($_POST['diplome']) ||
    !isset($_POST['etablissement']) ||
    !isset($_POST['annee']) ||
    !isset($_POST['mention'])

) {

    die("Les informations du cursus sont incomplètes.");

}



/*=========================================================
= Récupération des cursus
=========================================================*/

$diplomes       = $_POST['diplome'];

$etablissements = $_POST['etablissement'];

$annees         = $_POST['annee'];

$mentions       = $_POST['mention'];



/*=========================================================
= Stockage temporaire des cursus
=========================================================*/

$_SESSION['cursus'] = array();



for($i=0; $i<count($diplomes); $i++){


    $diplome = trim($diplomes[$i]);

    $etablissement = trim($etablissements[$i]);

    $annee = trim($annees[$i]);

    $mention = trim($mentions[$i]);



    if(
        $diplome == "" &&
        $etablissement == "" &&
        $annee == ""
    ){

        continue;

    }



    $_SESSION['cursus'][] = array(

        "diplome" => $diplome,

        "etablissement" => $etablissement,

        "annee" => $annee,

        "mention" => $mention

    );


}




/*=========================================================
= Vérifier au moins un cursus
=========================================================*/

if(count($_SESSION['cursus']) == 0){

    die("Veuillez ajouter au moins un cursus.");

}



/*=========================================================
= Récupérer l'identifiant de l'étudiant
=========================================================*/

$sqlEtudiant = "

SELECT id

FROM etudiant

WHERE utilisateur_id = $1

";


$resultEtudiant = pg_query_params(

    $conn,

    $sqlEtudiant,

    array($_SESSION['id'])

);



if(!$resultEtudiant){

    die("Erreur étudiant : ".pg_last_error($conn));

}



if(pg_num_rows($resultEtudiant)==0){

    die("Profil étudiant introuvable.");

}



$etudiant = pg_fetch_assoc($resultEtudiant);


$etudiant_id = $etudiant['id'];




/*=========================================================
= Vérifier si une candidature existe déjà
=========================================================*/

$sqlExiste = "

SELECT id

FROM candidature

WHERE etudiant_id=$1

AND offre_id=$2

";



$resultExiste = pg_query_params(

    $conn,

    $sqlExiste,

    array(

        $etudiant_id,

        $offre_id

    )

);



if(!$resultExiste){

    die("Erreur vérification candidature : ".pg_last_error($conn));

}





if(pg_num_rows($resultExiste)>0){


    $candidature = pg_fetch_assoc($resultExiste);


    $_SESSION['candidature_id'] = $candidature['id'];


}

else{


/*=========================================================
= Création de la candidature
=========================================================*/


$sqlCandidature = "

INSERT INTO candidature

(

etudiant_id,

offre_id,

statut

)

VALUES

(

$1,

$2,

'En attente'

)

RETURNING id

";



$resultCandidature = pg_query_params(

    $conn,

    $sqlCandidature,

    array(

        $etudiant_id,

        $offre_id

    )

);



if(!$resultCandidature){

    die("Erreur création candidature : ".pg_last_error($conn));

}



$candidature = pg_fetch_assoc($resultCandidature);



$_SESSION['candidature_id'] = $candidature['id'];



}


/*=========================================================
= Enregistrer les cursus dans la table cursus
=========================================================*/

$candidature_id = $_SESSION['candidature_id'];

/* Supprimer les anciens cursus si l'étudiant revient modifier */
pg_query_params(
    $conn,
    "DELETE FROM cursus WHERE candidature_id = $1",
    array($candidature_id)
);

foreach ($_SESSION['cursus'] as $cursus) {

    $sql = "
        INSERT INTO cursus
        (
            candidature_id,
            diplome,
            etablissement,
            annee,
            mention
        )
        VALUES
        (
            $1,
            $2,
            $3,
            $4,
            $5
        )
    ";

    $result = pg_query_params(
        $conn,
        $sql,
        array(
            $candidature_id,
            $cursus['diplome'],
            $cursus['etablissement'],
            $cursus['annee'],
            $cursus['mention']
        )
    );

    if (!$result) {

        die("Erreur insertion cursus : " . pg_last_error($conn));

    }

}

header("Location: postuler_formations.php");

exit();


?>