<?php

session_start();

require_once("../database.php");


// =====================================================
// Vérifier que l'utilisateur est connecté
// =====================================================

if (!isset($_SESSION['id'])) {

    header("Location: ../login_public.php");
    exit();

}



// =====================================================
// Vérifier que l'utilisateur est Admin
// =====================================================

if ($_SESSION['role'] != "Admin") {

    header("Location: ../login_public.php");
    exit();

}



// =====================================================
// Vérifier que l'administrateur est principal
// =====================================================

$id_admin = $_SESSION['id'];


$sqlAdmin = "SELECT fonction
             FROM utilisateur
             WHERE id=$1
             AND role='Admin'";


$resultAdmin = pg_query_params(

    $conn,

    $sqlAdmin,

    array($id_admin)

);



if(!$resultAdmin || pg_num_rows($resultAdmin)==0){


    header("Location: codes_accreditation.php");
    exit();

}



$admin = pg_fetch_assoc($resultAdmin);



if($admin['fonction'] != "Administrateur principal"){


    echo "<script>

            alert('Accès refusé. Seul l\\'administrateur principal peut supprimer un code.');

            window.location='codes_accreditation.php';

          </script>";

    exit();

}



// =====================================================
// Vérifier qu'un identifiant est fourni
// =====================================================

if (!isset($_GET['id'])) {

    header("Location: codes_accreditation.php");
    exit();

}


$id = (int) $_GET['id'];



// =====================================================
// Vérifier que le code existe
// =====================================================

$sql = "SELECT id, code, utilise
        FROM code_accreditation
        WHERE id=$1";


$resultat = pg_query_params(

    $conn,

    $sql,

    array($id)

);



if(!$resultat || pg_num_rows($resultat)==0){


    echo "<script>

            alert('Code d\\'accréditation introuvable.');

            window.location='codes_accreditation.php';

          </script>";

    exit();

}


$code = pg_fetch_assoc($resultat);



// =====================================================
// Empêcher suppression d'un code déjà utilisé
// =====================================================

if($code['utilise']=='t'){


    echo "<script>

            alert('Ce code a déjà été utilisé par un recruteur. Suppression impossible.');

            window.location='codes_accreditation.php';

          </script>";

    exit();

}



// =====================================================
// Suppression du code
// =====================================================

$sqlSuppression = "

DELETE FROM code_accreditation

WHERE id=$1

";



$suppression = pg_query_params(

    $conn,

    $sqlSuppression,

    array($id)

);



// =====================================================
// Résultat
// =====================================================

if($suppression){


    echo "<script>

            alert('Le code d\\'accréditation a été supprimé avec succès.');

            window.location='codes_accreditation.php';

          </script>";



}else{


    echo "<script>

            alert('Erreur lors de la suppression du code.');

            window.location='codes_accreditation.php';

          </script>";

}



exit();


?>