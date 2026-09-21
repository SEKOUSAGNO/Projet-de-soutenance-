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
// Vérifier que l'utilisateur connecté
// est Administrateur principal
// =====================================


$sql_droit = "

SELECT fonction

FROM utilisateur

WHERE id=$1

AND role='Admin'

";


$resultat_droit = pg_query_params(

    $conn,

    $sql_droit,

    array($_SESSION['id'])

);



$admin_connecte = pg_fetch_assoc($resultat_droit);



if(!$admin_connecte || 
   $admin_connecte['fonction']!="Administrateur principal"){


    echo "

    <script>

    alert('Accès refusé. Seul l’administrateur principal peut activer un compte.');

    window.location='administrateurs.php';

    </script>

    ";


    exit();

}






// =====================================
// Vérifier ID envoyé
// =====================================


if(!isset($_GET['id'])){


    header("Location: administrateurs.php");

    exit();

}



$id=(int)$_GET['id'];







// =====================================
// Vérifier l'administrateur ciblé
// =====================================


$sql_verification="

SELECT fonction,
       statut

FROM utilisateur

WHERE id=$1

AND role='Admin'

";



$resultat = pg_query_params(

    $conn,

    $sql_verification,

    array($id)

);




if(!$resultat || pg_num_rows($resultat)==0){


    echo "

    <script>

    alert('Administrateur introuvable.');

    window.location='administrateurs.php';

    </script>

    ";


    exit();

}



$admin=pg_fetch_assoc($resultat);






// =====================================
// Empêcher activation administrateur principal
// =====================================


if($admin['fonction']=="Administrateur principal"){


    echo "

    <script>

    alert('L’administrateur principal ne peut pas être activé par cette fonction.');

    window.location='administrateurs.php';

    </script>

    ";


    exit();


}






// =====================================
// Vérifier statut actuel
// =====================================


if($admin['statut']=="Actif"){


    echo "

    <script>

    alert('Cet administrateur est déjà actif.');

    window.location='administrateurs.php';

    </script>

    ";


    exit();


}







// =====================================
// Activation compte secondaire
// =====================================


$sql_activation="

UPDATE utilisateur

SET statut='Actif'

WHERE id=$1

AND role='Admin'

AND fonction='Administrateur secondaire'

";




$activation=pg_query_params(

    $conn,

    $sql_activation,

    array($id)

);







// =====================================
// Résultat
// =====================================


if($activation){


    echo "

    <script>

    alert('Administrateur secondaire activé avec succès.');

    window.location='administrateurs.php';

    </script>

    ";



}else{


    echo "

    <script>

    alert('Erreur lors de l’activation.');

    window.location='administrateurs.php';

    </script>

    ";


}



exit();


?>