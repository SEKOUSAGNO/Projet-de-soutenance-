<?php

session_start();

require_once("../database.php");


// ======================================
// Vérifier la connexion
// ======================================

if(!isset($_SESSION['id'])){

    header("Location: ../login_public.php");
    exit();

}


// ======================================
// Vérifier que l'utilisateur est Admin
// ======================================

if($_SESSION['role'] != "Admin"){

    header("Location: ../login_public.php");
    exit();

}



// ======================================
// Vérifier que l'administrateur est principal
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



if(!$admin || $admin['fonction'] != "Administrateur principal"){


    echo "<script>

            alert('Action réservée à l\\'administrateur principal.');

            window.location='codes_admin.php';

          </script>";

    exit();

}



// ======================================
// Vérifier l'identifiant du code
// ======================================

if(!isset($_GET['id'])){


    header("Location: codes_admin.php");

    exit();

}



$id = (int) $_GET['id'];




// ======================================
// Vérifier que le code existe
// ======================================


$sql = "

SELECT id

FROM code_admin

WHERE id=$1

";


$resultat = pg_query_params(

    $conn,

    $sql,

    array($id)

);



if(!$resultat || pg_num_rows($resultat)==0){


    echo "<script>

            alert('Code administrateur introuvable.');

            window.location='codes_admin.php';

          </script>";

    exit();

}




// ======================================
// Désactivation du code
// ======================================


$sql = "

UPDATE code_admin

SET actif=false

WHERE id=$1

";


$update = pg_query_params(

    $conn,

    $sql,

    array($id)

);





// ======================================
// Message
// ======================================


if($update){


    echo "<script>

            alert('Code administrateur désactivé avec succès.');

            window.location='codes_admin.php';

          </script>";



}else{


    echo "<script>

            alert('Erreur lors de la désactivation.');

            window.location='codes_admin.php';

          </script>";


}



exit();


?>