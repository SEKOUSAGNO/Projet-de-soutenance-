<?php

session_start();

require_once("../database.php");


// ======================================
// Vérifier connexion administrateur
// ======================================

if(!isset($_SESSION['id']) || $_SESSION['role']!="Admin"){

    header("Location: ../login_public.php");
    exit();

}




// ======================================
// Vérifier administrateur principal
// ======================================


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


    echo "<script>

            alert('Seul l\\'administrateur principal peut générer des codes administrateurs.');

            window.location='dashboard.php';

          </script>";

    exit();

}




// ======================================
// Génération du code unique
// ======================================


$code = "ADMIN-".strtoupper(substr(md5(uniqid()),0,10));




// ======================================
// Vérifier que le code n'existe pas
// ======================================


$verification = "

SELECT id

FROM code_admin

WHERE code=$1

";



$result = pg_query_params(

    $conn,

    $verification,

    array($code)

);



if(pg_num_rows($result)>0){


    header("Location: generer_code_admin.php");

    exit();

}





// ======================================
// Enregistrer le code
// ======================================


$sql = "

INSERT INTO code_admin

(

code,

genere_par

)

VALUES

(

$1,

$2

)

";




$insert = pg_query_params(

    $conn,

    $sql,

    array(

        $code,

        $_SESSION['id']

    )

);






// ======================================
// Résultat
// ======================================


if($insert){



echo "<script>


alert('Code administrateur généré avec succès :\\n\\n".$code."');


window.location='codes_admin.php';


</script>";




}else{



echo "<script>


alert('Erreur lors de la génération du code.');


window.location='codes_admin.php';


</script>";



}




exit();


?>