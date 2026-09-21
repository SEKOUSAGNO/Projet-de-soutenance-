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
// Vérifier que c'est l'administrateur principal
// =====================================


$sql_admin = "

SELECT fonction

FROM utilisateur

WHERE id=$1

AND role='Admin'

";


$result_admin = pg_query_params(

    $conn,

    $sql_admin,

    array($_SESSION['id'])

);



$admin = pg_fetch_assoc($result_admin);



if(!$admin || $admin['fonction']!="Administrateur principal"){


    echo "

    <script>

    alert('Seul l’administrateur principal peut générer un code concepteur.');

    window.location='codes_concepteur.php';

    </script>

    ";

    exit();

}





// =====================================
// Génération du code concepteur
// =====================================


$code = "SIMAN-ADM-" 
        . strtoupper(substr(md5(uniqid()),0,8))
        . "-" 
        . rand(1000,9999);






// =====================================
// Insertion dans la base
// =====================================


$sql = "

INSERT INTO code_concepteur

(

code,

actif,

utilise

)

VALUES

(

$1,

FALSE,

FALSE

)

";




$resultat = pg_query_params(

    $conn,

    $sql,

    array($code)

);





if($resultat){


    echo "

    <script>

    alert('Code concepteur généré avec succès : $code');

    window.location='codes_concepteur.php';

    </script>

    ";


}

else{


    echo "

    <script>

    alert('Erreur lors de la génération du code.');

    window.location='codes_concepteur.php';

    </script>

    ";


}



exit();


?>