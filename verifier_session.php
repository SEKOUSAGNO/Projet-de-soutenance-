<?php

// =======================================
// Vérification globale de session
// SIMAN-JOB
// =======================================


if(session_status() == PHP_SESSION_NONE){

    session_start();

}


require_once("database.php");



// Vérifier connexion

if(!isset($_SESSION['id'])){


    header("Location: login_public.php");

    exit();

}




$id = $_SESSION['id'];



// Récupérer le statut actuel

$sql = "

SELECT role, statut

FROM utilisateur

WHERE id=$1

";



$resultat = pg_query_params(

    $conn,

    $sql,

    array($id)

);





if(!$resultat || pg_num_rows($resultat)==0){


    session_destroy();


    header("Location: login_public.php");

    exit();


}




$user = pg_fetch_assoc($resultat);





// Mise à jour session

$_SESSION['statut']=$user['statut'];






// =======================================
// BLOQUER LES COMPTES NON ACTIFS
// =======================================


if($user['statut']!="Actif"){



    session_destroy();



    echo "<script>

    alert('Votre compte a été désactivé ou n\\'est plus actif.');

    window.location='login_public.php';

    </script>";



    exit();


}




?>