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
= Vérifier qu'une candidature existe
=========================================================*/

if (!isset($_SESSION['candidature_id'])) {

    die("Aucune candidature en cours.");

}

$candidature_id = $_SESSION['candidature_id'];


/*=========================================================
= Vérifier la méthode POST
=========================================================*/

if ($_SERVER["REQUEST_METHOD"] != "POST") {

    header("Location: postuler_competences.php");
    exit();

}


/*=========================================================
= Vérifier les données reçues
=========================================================*/

if (
    !isset($_POST['competence']) ||
    !isset($_POST['niveau'])
) {

    die("Les compétences sont incomplètes.");

}

$competences = $_POST['competence'];
$niveaux     = $_POST['niveau'];


/*=========================================================
= Supprimer les anciennes compétences
=========================================================*/

$sqlDelete = "

DELETE FROM competence

WHERE candidature_id = $1

";

pg_query_params(
    $conn,
    $sqlDelete,
    array($candidature_id)
);


/*=========================================================
= Enregistrer les compétences
=========================================================*/

for($i=0; $i<count($competences); $i++){

    $competence = trim($competences[$i]);
    $niveau     = trim($niveaux[$i]);

    if($competence==""){
        continue;
    }

    $sql = "

    INSERT INTO competence
    (
        candidature_id,
        competence,
        niveau
    )
    VALUES
    (
        $1,
        $2,
        $3
    )

    ";

    $result = pg_query_params(

        $conn,

        $sql,

        array(

            $candidature_id,

            $competence,

            $niveau

        )

    );

    if(!$result){

        die("Erreur PostgreSQL : ".pg_last_error($conn));

    }

}


/*=========================================================
= Passage à l'étape suivante
=========================================================*/

header("Location: postuler_references.php");
exit();

?>