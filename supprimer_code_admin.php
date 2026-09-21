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
// Vérifier Administrateur principal
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

            alert('Action réservée à l\\'administrateur principal.');

            window.location='codes_admin.php';

          </script>";

    exit();

}




// ======================================
// Vérifier présence de l'identifiant
// ======================================


if(!isset($_GET['id'])){


    header("Location: codes_admin.php");

    exit();

}



$id = (int) $_GET['id'];





// ======================================
// Vérifier existence du code
// ======================================


$sql = "

SELECT id, utilise

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




$code = pg_fetch_assoc($resultat);





// ======================================
// Empêcher suppression code utilisé
// ======================================


if($code['utilise']=="t"){


    echo "<script>

            alert('Ce code administrateur a déjà été utilisé. Suppression impossible.');

            window.location='codes_admin.php';

          </script>";

    exit();

}






// ======================================
// Suppression du code
// ======================================


$sql = "

DELETE FROM code_admin

WHERE id=$1

";



$suppression = pg_query_params(

    $conn,

    $sql,

    array($id)

);





// ======================================
// Résultat
// ======================================


if($suppression){


    echo "<script>

            alert('Code administrateur supprimé avec succès.');

            window.location='codes_admin.php';

          </script>";



}else{


    echo "<script>

            alert('Erreur lors de la suppression du code.');

            window.location='codes_admin.php';

          </script>";



}



exit();


?>