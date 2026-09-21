<?php

session_start();

require_once("../database.php");


// =====================================
// Vérifier connexion Admin
// =====================================

if(!isset($_SESSION['id']) || $_SESSION['role']!="Admin"){

    header("Location: ../login_public.php");
    exit();

}



// =====================================
// Vérifier ID du code
// =====================================

if(!isset($_GET['id'])){

    header("Location: codes_concepteur.php");
    exit();

}


$id = $_GET['id'];




// =====================================
// Activer le code concepteur
// =====================================


$sql = "

UPDATE code_concepteur

SET actif = TRUE

WHERE id=$1

";



$resultat = pg_query_params(

    $conn,

    $sql,

    array($id)

);





if($resultat){


    echo "

    <script>

    alert('Code concepteur activé avec succès.');

    window.location='codes_concepteur.php';

    </script>

    ";



}

else{


    echo "

    <script>

    alert('Erreur lors de l activation du code.');

    window.location='codes_concepteur.php';

    </script>

    ";


}



exit();


?>