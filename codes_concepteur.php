<?php

session_start();


/*=========================================
= Vérifier que l'utilisateur est connecté
=========================================*/

if (!isset($_SESSION['id'])) {

    header("Location: ../login_public.php");
    exit();

}


/*=========================================
= Vérifier rôle Admin
=========================================*/

if ($_SESSION['role'] != "Admin") {

    header("Location: ../login_public.php");
    exit();

}


/*=========================================
= Connexion PostgreSQL
=========================================*/

require_once("../database.php");



/*=========================================
= Récupération des codes concepteurs
=========================================*/


$sql = "

SELECT *

FROM code_concepteur

ORDER BY id DESC

";


$resultat = pg_query($conn,$sql);



?>


<!DOCTYPE html>

<html lang="fr">

<head>

<meta charset="UTF-8">

<title>Gestion des codes concepteurs</title>


<style>


*{

margin:0;
padding:0;
box-sizing:border-box;

}


body{

font-family:Arial, Helvetica, sans-serif;

background:#ecf2f7;

}



.header{

background:#003366;

color:white;

padding:20px;

text-align:center;

font-size:30px;

font-weight:bold;

}



.container{

width:95%;

margin:30px auto;

}



.titre{

display:flex;

justify-content:space-between;

align-items:center;

margin-bottom:20px;

}



.titre h2{

color:#003366;

}



.btn{

background:#28a745;

color:white;

padding:12px 20px;

border-radius:6px;

text-decoration:none;

font-weight:bold;

}



.btn:hover{

background:#218838;

}



table{

width:100%;

border-collapse:collapse;

background:white;

box-shadow:0 0 10px #ccc;

}



th{

background:#003366;

color:white;

padding:15px;

}



td{

padding:12px;

text-align:center;

border-bottom:1px solid #ddd;

}



tr:hover{

background:#f8f9fa;

}



/* ETAT */


.actif{

background:#28a745;

color:white;

padding:6px 12px;

border-radius:20px;

}



.inactif{

background:#dc3545;

color:white;

padding:6px 12px;

border-radius:20px;

}



/* UTILISATION */


.utilise{

background:#007bff;

color:white;

padding:6px 12px;

border-radius:20px;

}



.nonutilise{

background:#ffc107;

color:black;

padding:6px 12px;

border-radius:20px;

}



/* ACTIONS */


.action{

padding:8px 12px;

border-radius:5px;

color:white;

text-decoration:none;

font-size:14px;

display:inline-block;

margin:2px;

}



.vert{

background:#28a745;

}



.orange{

background:#ff9800;

}



.rouge{

background:#dc3545;

}



.retour{

text-align:center;

margin-top:30px;

}



</style>


</head>



<body>



<div class="header">

SIMAN-JOB

<br>

Gestion des Codes Concepteurs

</div>




<div class="container">


<div class="titre">


<h2>

Liste des codes concepteurs

</h2>


<a href="generer_code_concepteur.php" class="btn">

+ Générer un code

</a>


</div>




<table>


<tr>

<th>ID</th>

<th>Code</th>

<th>Etat</th>

<th>Utilisation</th>

<th>Date création</th>

<th>Actions</th>

</tr>



<?php


if($resultat && pg_num_rows($resultat)>0){



while($code = pg_fetch_assoc($resultat)){



?>

<tr>


<td>

<?= $code['id']; ?>

</td>



<td>

<?= htmlspecialchars($code['code']); ?>

</td>




<td>


<?php


if($code['actif']=="t"){


echo "

<span class='actif'>

Actif

</span>

";


}

else{


echo "

<span class='inactif'>

Désactivé

</span>

";


}


?>


</td>




<td>


<?php


if($code['utilise']=="t"){


echo "

<span class='utilise'>

Utilisé

</span>

";


}

else{


echo "

<span class='nonutilise'>

Disponible

</span>

";


}


?>


</td>



<td>

<?= $code['date_creation']; ?>

</td>



<td>



<?php


if($code['actif']=="t"){


?>


<a class="action orange"

href="desactiver_code_concepteur.php?id=<?=$code['id']?>"

onclick="return confirm('Désactiver ce code ?');">

Désactiver

</a>



<?php


}

else{


?>


<a class="action vert"

href="activer_code_concepteur.php?id=<?=$code['id']?>">

Activer

</a>



<?php


}


?>



<a class="action rouge"

href="supprimer_code_concepteur.php?id=<?=$code['id']?>"

onclick="return confirm('Supprimer ce code ?');">

Supprimer

</a>



</td>



</tr>



<?php


}


}

else{


?>


<tr>

<td colspan="6">

Aucun code concepteur trouvé.

</td>

</tr>


<?php


}


?>


</table>


</div>





<div class="retour">


<a href="dashboard.php"

style="
background:#003366;
color:white;
padding:12px 25px;
border-radius:6px;
text-decoration:none;
font-weight:bold;
">

Retour au tableau de bord

</a>


</div>





<footer style="

margin-top:40px;

background:#003366;

color:white;

text-align:center;

padding:15px;

">


© 2026 SIMAN-JOB - Gestion des codes concepteurs


</footer>



</body>

</html>