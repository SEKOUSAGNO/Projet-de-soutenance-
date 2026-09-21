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

    alert('Seul l’administrateur principal peut supprimer un code concepteur.');

    window.location='codes_concepteur.php';

    </script>

    ";

    exit();

}




// =====================================
// Vérifier présence de l'identifiant
// =====================================


if(!isset($_GET['id'])){


    header("Location: codes_concepteur.php");

    exit();

}


$id = $_GET['id'];




// =====================================
// Vérifier si le code existe
// =====================================


$sql_check = "

SELECT utilise

FROM code_concepteur

WHERE id=$1

";


$result_check = pg_query_params(

    $conn,

    $sql_check,

    array($id)

);



$code = pg_fetch_assoc($result_check);



if(!$code){


    echo "

    <script>

    alert('Code concepteur introuvable.');

    window.location='codes_concepteur.php';

    </script>

    ";

    exit();

}



// =====================================
// Empêcher suppression d'un code utilisé
// =====================================


if($code['utilise']=="t"){


    echo "

    <script>

    alert('Impossible de supprimer un code déjà utilisé.');

    window.location='codes_concepteur.php';

    </script>

    ";

    exit();

}



// =====================================
// Suppression
// =====================================


$sql_delete = "

DELETE FROM code_concepteur

WHERE id=$1

";



$result_delete = pg_query_params(

    $conn,

    $sql_delete,

    array($id)

);






if($result_delete){


    echo "

    <script>

    alert('Code concepteur supprimé avec succès.');

    window.location='codes_concepteur.php';

    </script>

    ";


}

else{


    echo "

    <script>

    alert('Erreur lors de la suppression du code.');

    window.location='codes_concepteur.php';

    </script>

    ";


}



exit();


?>