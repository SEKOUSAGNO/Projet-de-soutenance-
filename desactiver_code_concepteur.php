<?php

session_start();


/*=========================================
= Vérification connexion utilisateur
=========================================*/

if(!isset($_SESSION['id'])){

    header("Location: ../login_public.php");
    exit();

}



/*=========================================
= Vérification rôle administrateur
=========================================*/

if($_SESSION['role'] != "Admin"){

    header("Location: ../login_public.php");
    exit();

}



/*=========================================
= Vérification de l'identifiant du code
=========================================*/

if(!isset($_GET['id'])){

    header("Location: codes_concepteur.php");
    exit();

}


$id = $_GET['id'];



/*=========================================
= Connexion PostgreSQL
=========================================*/

require("../database.php");



/*=========================================
= Désactivation du code concepteur
=========================================*/


$sql = "UPDATE code_concepteur

        SET actif = FALSE

        WHERE id = ?";



$stmt = $pdo->prepare($sql);


$stmt->execute([$id]);



/*=========================================
= Retour vers la liste
=========================================*/


header("Location: codes_concepteur.php");

exit();


?>