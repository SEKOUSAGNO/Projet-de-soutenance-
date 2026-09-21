<?php

session_start();

require_once("../database.php");


// ===================================================
// Vérifier la connexion
// ===================================================

if(!isset($_SESSION['id']) || $_SESSION['role']!="Admin"){

    header("Location: ../login_public.php");

    exit();

}



// ===================================================
// Informations administrateur connecté
// ===================================================

$sql_admin="

SELECT

id,
fonction,
statut

FROM utilisateur

WHERE id=$1

AND role='Admin'

";


$result_admin = pg_query_params(

    $conn,

    $sql_admin,

    array($_SESSION['id'])

);


$admin_connecte = pg_fetch_assoc($result_admin);



$estPrincipal=false;


if(
    $admin_connecte &&
    $admin_connecte['fonction']=="Administrateur principal"
){

    $estPrincipal=true;

}



// ===================================================
// Liste des administrateurs
// ===================================================

$sql="

SELECT

id,
nom,
prenom,
email,
fonction,
statut

FROM utilisateur

WHERE role='Admin'

ORDER BY 

CASE

WHEN fonction='Administrateur principal'
THEN 1

ELSE 2

END,

id ASC

";


$resultat = pg_query($conn,$sql);




// ===================================================
// Nombre d'administrateurs principaux actifs
// ===================================================

$sql_count="

SELECT COUNT(*) AS total

FROM utilisateur

WHERE role='Admin'

AND fonction='Administrateur principal'

AND statut='Actif'

";


$result_count = pg_query($conn,$sql_count);


$count_data = pg_fetch_assoc($result_count);


$nombre_principaux_actifs = $count_data['total'];

?>


<!DOCTYPE html>

<html lang="fr">

<head>

<meta charset="UTF-8">

<title>
Gestion des administrateurs SIMAN-JOB
</title>


<style>


body{

margin:0;

padding:0;

font-family:Arial,Helvetica,sans-serif;

background:#eef2f7;

}



.container{

width:95%;

margin:25px auto;

background:white;

padding:25px;

border-radius:10px;

box-shadow:0 0 10px #bbb;

}



h1{

text-align:center;

color:#1565C0;

}



.actions{

margin-bottom:20px;

}



.btn{

display:inline-block;

padding:10px 15px;

border-radius:6px;

color:white;

text-decoration:none;

margin:3px;

font-size:14px;

}



.ajouter{

background:#28a745;

}



.codes{

background:#6f42c1;

}



.retour{

background:#555;

}



.modifier{

background:#1565C0;

}



.activer{

background:#008000;

}



.desactiver{

background:#ff9800;

}



.supprimer{

background:#dc3545;

}



table{

width:100%;

border-collapse:collapse;

}



th{

background:#1565C0;

color:white;

padding:12px;

}



td{

padding:10px;

border:1px solid #ddd;

text-align:center;

}



.principal{

background:#d4edda;

padding:5px 10px;

border-radius:15px;

}



.secondaire{

background:#cfe2ff;

padding:5px 10px;

border-radius:15px;

}



.actif{

color:green;

font-weight:bold;

}



.attente{

color:#ff9800;

font-weight:bold;

}



.desactive{

color:red;

font-weight:bold;

}



.interdit{

color:#777;

font-style:italic;

}


</style>

</head>


<body>


<div class="container">


<h1>

👨‍💼 Gestion des administrateurs

</h1>



<div class="actions">


<?php

if($estPrincipal){

?>


<a href="ajouter_admin.php" class="btn ajouter">

➕ Ajouter administrateur

</a>


<a href="codes_concepteur.php" class="btn codes">

🔑 Codes concepteurs

</a>


<?php

}

?>


<a href="dashboard.php" class="btn retour">

⬅ Retour

</a>


</div>



<table>


<tr>

<th>Nom</th>

<th>Prénom</th>

<th>Email</th>

<th>Fonction</th>

<th>Statut</th>

<th>Actions</th>

</tr>
<?php


if($resultat && pg_num_rows($resultat)>0){


    while($admin = pg_fetch_assoc($resultat)){


?>


<tr>


<td>

<?= htmlspecialchars($admin['nom']); ?>

</td>



<td>

<?= htmlspecialchars($admin['prenom']); ?>

</td>



<td>

<?= htmlspecialchars($admin['email']); ?>

</td>




<td>


<?php


if($admin['fonction']=="Administrateur principal"){


    echo "

    <span class='principal'>

    Administrateur principal

    </span>

    ";


}else{


    echo "

    <span class='secondaire'>

    Administrateur secondaire

    </span>

    ";


}


?>


</td>





<td>


<?php


if($admin['statut']=="Actif"){


    echo "

    <span class='actif'>

    ✅ Actif

    </span>

    ";


}

elseif($admin['statut']=="En attente"){


    echo "

    <span class='attente'>

    ⏳ En attente

    </span>

    ";


}

else{


    echo "

    <span class='desactive'>

    ❌ Désactivé

    </span>

    ";


}


?>


</td>






<td>


<?php


// ===================================================
// Gestion des actions
// ===================================================


if($estPrincipal){



    // -----------------------------------------------
    // Protection du dernier administrateur principal actif
    // -----------------------------------------------


    if(

        $admin['fonction']=="Administrateur principal"

        &&

        $admin['statut']=="Actif"

        &&

        $nombre_principaux_actifs <= 1

    ){



        echo "

        <span class='interdit'>

        🔒 Dernier administrateur principal protégé

        </span>

        ";



    }

    else{



?>



<a class="btn modifier"

href="modifier_admin.php?id=<?=$admin['id']?>">

✏️ Modifier

</a>



<?php



// -----------------------------------------------
// Bouton Activer / Désactiver
// -----------------------------------------------


if($admin['statut']=="Actif"){



?>


<a class="btn desactiver"

href="desactiver_admin.php?id=<?=$admin['id']?>"

onclick="return confirm('Voulez-vous désactiver cet administrateur ?');">

🔒 Désactiver

</a>



<?php


}

else{


?>


<a class="btn activer"

href="activer_admin.php?id=<?=$admin['id']?>"

onclick="return confirm('Voulez-vous activer cet administrateur ?');">

🔓 Activer

</a>



<?php


}



?>



<a class="btn supprimer"

href="supprimer_admin.php?id=<?=$admin['id']?>"

onclick="return confirm('Voulez-vous supprimer cet administrateur ?');">

🗑️ Supprimer

</a>



<?php


    }


}



else{


    echo "

    <span class='interdit'>

    Accès réservé à l'administrateur principal

    </span>

    ";


}


?>


</td>



</tr>



<?php


    }


}

else{


?>


<tr>


<td colspan="6">


Aucun administrateur enregistré.


</td>


</tr>



<?php


}


?>


</table>



</div>



</body>


</html>