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
// Vérifier droit Administrateur principal
// =====================================

$sql_droit="

SELECT fonction

FROM utilisateur

WHERE id=$1

AND role='Admin'

";


$resultat_droit=pg_query_params(

    $conn,

    $sql_droit,

    array($_SESSION['id'])

);


$admin_connecte=pg_fetch_assoc($resultat_droit);



if(!$admin_connecte || 
   $admin_connecte['fonction']!="Administrateur principal"){


    echo "

    <script>

    alert('Accès refusé. Seul l’administrateur principal peut désactiver un compte.');

    window.location='administrateurs.php';

    </script>";

    exit();

}




// =====================================
// Vérifier ID
// =====================================

if(!isset($_GET['id'])){

    header("Location: administrateurs.php");

    exit();

}


$id=(int)$_GET['id'];




// =====================================
// Empêcher son propre compte
// =====================================

if($id==$_SESSION['id']){


    echo "

    <script>

    alert('Vous ne pouvez pas désactiver votre propre compte.');

    window.location='administrateurs.php';

    </script>";

    exit();

}





// =====================================
// Récupérer administrateur ciblé
// =====================================


$sql="

SELECT 

fonction,
statut

FROM utilisateur

WHERE id=$1

AND role='Admin'

";


$resultat=pg_query_params(

    $conn,

    $sql,

    array($id)

);



if(!$resultat || pg_num_rows($resultat)==0){


    echo "

    <script>

    alert('Administrateur introuvable.');

    window.location='administrateurs.php';

    </script>";

    exit();

}



$admin=pg_fetch_assoc($resultat);





// =====================================
// Vérifier si déjà désactivé
// =====================================


if($admin['statut']=="Désactivé"){


    echo "

    <script>

    alert('Cet administrateur est déjà désactivé.');

    window.location='administrateurs.php';

    </script>";

    exit();

}






// =====================================
// Protection du dernier administrateur principal
// =====================================


if($admin['fonction']=="Administrateur principal"){



    $sql_count="

    SELECT COUNT(*) AS total

    FROM utilisateur

    WHERE role='Admin'

    AND fonction='Administrateur principal'

    AND statut='Actif'

    ";


    $result_count=pg_query($conn,$sql_count);


    $data=pg_fetch_assoc($result_count);



    if($data['total']<=1){


        echo "

        <script>

        alert('Impossible de désactiver le dernier administrateur principal actif.');

        window.location='administrateurs.php';

        </script>";

        exit();

    }


}







// =====================================
// Désactivation
// =====================================


$sql_update="

UPDATE utilisateur

SET statut='Désactivé'

WHERE id=$1

AND role='Admin'

";



$result_update=pg_query_params(

    $conn,

    $sql_update,

    array($id)

);







// =====================================
// Résultat
// =====================================


if($result_update){


    echo "

    <script>

    alert('Administrateur désactivé avec succès.');

    window.location='administrateurs.php';

    </script>";



}else{


    echo "

    <script>

    alert('Erreur lors de la désactivation.');

    window.location='administrateurs.php';

    </script>";

}



exit();


?>