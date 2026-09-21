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

    alert('Accès refusé. Seul l’administrateur principal peut supprimer un compte.');

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
// Empêcher suppression de son propre compte
// =====================================


if($id==$_SESSION['id']){


    echo "

    <script>

    alert('Vous ne pouvez pas supprimer votre propre compte.');

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
// Protection dernier administrateur principal
// =====================================


if(
    $admin['fonction']=="Administrateur principal"
){



    $sql_principal="

    SELECT COUNT(*) AS total

    FROM utilisateur

    WHERE role='Admin'

    AND fonction='Administrateur principal'

    AND statut='Actif'

    ";



    $result_principal=pg_query($conn,$sql_principal);


    $principal=pg_fetch_assoc($result_principal);



    if($principal['total']<=1){


        echo "

        <script>

        alert('Impossible de supprimer le dernier administrateur principal actif.');

        window.location='administrateurs.php';

        </script>";

        exit();

    }


}







// =====================================
// Protection dernier administrateur système
// =====================================


$sql_total="

SELECT COUNT(*) AS total

FROM utilisateur

WHERE role='Admin'

";


$result_total=pg_query($conn,$sql_total);


$data_total=pg_fetch_assoc($result_total);



if($data_total['total']<=1){


    echo "

    <script>

    alert('Impossible de supprimer le dernier administrateur du système.');

    window.location='administrateurs.php';

    </script>";

    exit();

}







// =====================================
// Suppression
// =====================================


$sql_delete="

DELETE FROM utilisateur

WHERE id=$1

AND role='Admin'

";



$suppression=pg_query_params(

    $conn,

    $sql_delete,

    array($id)

);








// =====================================
// Résultat
// =====================================


if($suppression && pg_affected_rows($suppression)>0){


    echo "

    <script>

    alert('Administrateur supprimé avec succès.');

    window.location='administrateurs.php';

    </script>";



}else{


    echo "

    <script>

    alert('Erreur lors de la suppression.');

    window.location='administrateurs.php';

    </script>";

}



exit();


?>