<?php
session_start();

require_once("../database.php");

/*=========================================================
  Vérifier que l'étudiant est connecté
=========================================================*/

if (!isset($_SESSION['id'])) {

    header("Location: ../login_public.php");
    exit;

}


/*=========================================================
  Vérifier que l'offre existe dans l'URL
=========================================================*/

if (!isset($_GET['offre_id'])) {

    echo "Aucune offre sélectionnée.";
    exit;

}


$offre_id = $_GET['offre_id'];


// Récupérer l'identifiant utilisateur connecté
$utilisateur_id = $_SESSION['id'];



/*=========================================================
  Récupérer l'id étudiant correspondant
=========================================================*/

$sql_etudiant = "
SELECT id 
FROM etudiant
WHERE utilisateur_id = $1
";


$resultat = pg_query_params(
    $conn,
    $sql_etudiant,
    array($utilisateur_id)
);



if (!$resultat || pg_num_rows($resultat)==0) {

    echo "Profil étudiant introuvable.";
    exit;

}


$ligne = pg_fetch_assoc($resultat);

$etudiant_id = $ligne['id'];



/*=========================================================
  Vérifier si l'étudiant a déjà postulé
=========================================================*/

$verification = "
SELECT id 
FROM candidature
WHERE etudiant_id = $1
AND offre_id = $2
";


$check = pg_query_params(
    $conn,
    $verification,
    array($etudiant_id,$offre_id)
);



if(pg_num_rows($check)>0){

    echo "
    <script>
    alert('Vous avez déjà postulé à cette offre.');
    window.location='offres.php';
    </script>
    ";

    exit;

}



/*=========================================================
  Insérer la candidature
=========================================================*/


$sql = "
INSERT INTO candidature
(
etudiant_id,
offre_id,
date_candidature,
statut
)

VALUES
(
$1,
$2,
CURRENT_TIMESTAMP,
$3
)

";


$insert = pg_query_params(

    $conn,

    $sql,

    array(
        $etudiant_id,
        $offre_id,
        'En attente'
    )

);



if($insert){


echo "

<script>

alert('Votre candidature a été enregistrée avec succès.');

window.location='candidatures.php';

</script>

";


}

else{


echo "

Erreur lors de l'enregistrement de la candidature : 

".pg_last_error($conn);


}


?>