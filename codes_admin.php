<?php

session_start();

require_once("../database.php");


// ======================================
// Vérification connexion
// ======================================

if(!isset($_SESSION['id']) || $_SESSION['role']!="Admin"){

    header("Location: ../login_public.php");
    exit();

}



// ======================================
// Vérification administrateur principal
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

            alert('Accès réservé à l\\'administrateur principal.');

            window.location='dashboard.php';

          </script>";

    exit();

}




// ======================================
// Récupération des codes administrateurs
// ======================================


$sql = "

SELECT 

code_admin.id,

code_admin.code,

code_admin.utilise,

code_admin.actif,

code_admin.date_creation,

code_admin.date_utilisation,

utilisateur.nom,

utilisateur.prenom


FROM code_admin


INNER JOIN utilisateur

ON code_admin.genere_par = utilisateur.id


ORDER BY code_admin.id DESC


";



$resultat = pg_query($conn,$sql);



?>



<!DOCTYPE html>

<html lang="fr">

<head>

<meta charset="UTF-8">

<title>Gestion des codes administrateurs</title>



<style>


body{

font-family:Arial;

background:#f4f6f9;

margin:0;

}



header{

background:#1565C0;

color:white;

padding:20px;

text-align:center;

}



.container{

width:95%;

margin:30px auto;

background:white;

padding:20px;

border-radius:10px;

box-shadow:0 0 10px #ccc;

}



.btn{

padding:8px 12px;

color:white;

text-decoration:none;

border-radius:5px;

}



.generer{

background:green;

}


.activer{

background:#008000;

}


.desactiver{

background:orange;

}


.supprimer{

background:red;

}


.retour{

background:#555;

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



.oui{

color:green;

font-weight:bold;

}


.non{

color:red;

font-weight:bold;

}



</style>


</head>




<body>


<header>

<h1>

🔐 Gestion des codes administrateurs

</h1>

</header>




<div class="container">



<a href="generer_code_admin.php" class="btn generer">

➕ Générer un code administrateur

</a>



<a href="dashboard.php" class="btn retour">

⬅ Retour tableau de bord

</a>



<br><br>





<table>


<tr>


<th>ID</th>

<th>Code</th>

<th>Généré par</th>

<th>Actif</th>

<th>Utilisé</th>

<th>Date création</th>

<th>Date utilisation</th>

<th>Actions</th>


</tr>




<?php



if($resultat && pg_num_rows($resultat)>0){



while($code=pg_fetch_assoc($resultat)){



?>



<tr>


<td>

<?= $code['id']; ?>

</td>




<td>

<?= htmlspecialchars($code['code']); ?>

</td>





<td>

<?= htmlspecialchars($code['prenom']." ".$code['nom']); ?>

</td>





<td>


<?php


if($code['actif']=="t"){


echo "<span class='oui'>Oui</span>";


}else{


echo "<span class='non'>Non</span>";


}


?>


</td>







<td>


<?php


if($code['utilise']=="t"){


echo "<span class='oui'>Oui</span>";


}else{


echo "<span class='non'>Non</span>";


}


?>


</td>






<td>

<?= $code['date_creation']; ?>

</td>






<td>


<?php


if($code['date_utilisation']==""){


echo "-";


}else{


echo $code['date_utilisation'];


}



?>


</td>








<td>



<?php



if($code['actif']=="t"){


?>

<a class="btn desactiver"

href="desactiver_code_admin.php?id=<?= $code['id']; ?>">

Désactiver

</a>



<?php


}else{


?>

<a class="btn activer"

href="activer_code_admin.php?id=<?= $code['id']; ?>">

Activer

</a>



<?php


}



?>





<a class="btn supprimer"

href="supprimer_code_admin.php?id=<?= $code['id']; ?>"

onclick="return confirm('Supprimer ce code administrateur ?');">


Supprimer


</a>



</td>



</tr>



<?php


}



}else{


?>


<tr>

<td colspan="8">

Aucun code administrateur disponible.

</td>

</tr>


<?php


}



?>


</table>




</div>


</body>

</html>