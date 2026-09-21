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

    header("Location: postuler_documents.php");
    exit();

}



/*=========================================================
= Vérifier les documents reçus
=========================================================*/

if (

    !isset($_POST['nom_document']) ||
    !isset($_FILES['fichier'])

){

    die("Aucun document reçu.");

}



$nom_documents = $_POST['nom_document'];

$fichiers = $_FILES['fichier'];



/*=========================================================
= Création du dossier documents
=========================================================*/

$dossier = "../documents/";


if(!is_dir($dossier)){

    mkdir($dossier,0777,true);

}



/*=========================================================
= Enregistrement des documents
=========================================================*/

for($i=0; $i<count($nom_documents); $i++){



    $nom_document = trim($nom_documents[$i]);



    if($nom_document==""){

        continue;

    }



    /* Vérifier fichier */

    if($fichiers['error'][$i] != 0){

        continue;

    }



    $nomFichier = $fichiers['name'][$i];

    $tmpFichier = $fichiers['tmp_name'][$i];



    /* Vérifier extension PDF */

    $extension = strtolower(

        pathinfo($nomFichier, PATHINFO_EXTENSION)

    );



    if($extension != "pdf"){

        die("Seuls les fichiers PDF sont autorisés.");

    }



    /* Nouveau nom unique */

    $nouveauNom = time()."_".$nomFichier;



    $chemin = $dossier.$nouveauNom;



    /* Déplacement du fichier */

    if(move_uploaded_file($tmpFichier,$chemin)){



        $cheminBDD = "documents/".$nouveauNom;



        /* Insertion PostgreSQL */


        $sql = "

        INSERT INTO document_candidature

        (

            candidature_id,

            nom_document,

            fichier

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

                $nom_document,

                $cheminBDD

            )

        );



        if(!$result){


            die(

            "Erreur insertion document : "

            .pg_last_error($conn)

            );


        }



    }


}




/*=========================================================
= Passage à la validation finale
=========================================================*/

header("Location: validation_candidature.php");

exit();


?>