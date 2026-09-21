<?php
session_start();

/*=========================================================
= Vérifier que l'administrateur est connecté
=========================================================*/

if (!isset($_SESSION['id']) || $_SESSION['role'] != "Admin") {

    header("Location: ../login_public.php");
    exit();

}


require_once("../database.php");



/*=========================================================
= Récupérer toutes les offres
=========================================================*/

$sql = "

SELECT 
    offre.*,
    utilisateur.nom,
    utilisateur.prenom

FROM offre

INNER JOIN recruteur_entreprise
ON offre.recruteur_id = recruteur_entreprise.id

INNER JOIN utilisateur
ON recruteur_entreprise.utilisateur_id = utilisateur.id

ORDER BY offre.date_publication DESC

";


$result = pg_query($conn, $sql);



if(!$result){

    die("Erreur lors de la récupération des offres : ".pg_last_error($conn));

}

?>


<!DOCTYPE html>

<html lang="fr">

<head>

<meta charset="UTF-8">

<title>Gestion des offres</title>


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">


<style>

body{

background:#eef5ff;

}


.card{

margin-top:30px;

}


.badge{

font-size:14px;

}


</style>


</head>


<body>


<div class="container">


<div class="card shadow">


<div class="card-body">


<h2 class="text-center text-primary">

📋 Gestion des offres

</h2>


<hr>



<?php


if(pg_num_rows($result)==0){


echo "

<div class='alert alert-info text-center'>

Aucune offre disponible.

</div>

";


}

else{


while($offre = pg_fetch_assoc($result)){


?>


<div class="card mb-4">


<div class="card-body">


<h4 class="text-primary">

<?php echo htmlspecialchars($offre['titre']); ?>

</h4>


<hr>



<p>

<strong>Recruteur :</strong>

<?php

echo htmlspecialchars(
$offre['prenom']." ".$offre['nom']
);

?>

</p>



<p>

<strong>Entreprise :</strong>

<?php echo htmlspecialchars($offre['entreprise']); ?>

</p>



<p>

<strong>Type :</strong>

<?php echo htmlspecialchars($offre['type_offre']); ?>

</p>



<p>

<strong>Description :</strong><br>

<?php echo nl2br(htmlspecialchars($offre['description'])); ?>

</p>



<p>

<strong>Lieu :</strong>

<?php echo htmlspecialchars($offre['lieu']); ?>

</p>



<p>

<strong>Date limite :</strong>

<?php echo htmlspecialchars($offre['date_limite']); ?>

</p>



<p>

<strong>Mode candidature :</strong>

<?php echo htmlspecialchars($offre['mode_candidature']); ?>

</p>



<p>

<strong>Statut :</strong>


<?php


if($offre['statut']=="Active"){


echo "

<span class='badge bg-success'>

Active

</span>

";


}else{


echo "

<span class='badge bg-danger'>

".$offre['statut']."

</span>

";


}


?>


</p>


<hr>


<a href="valider_offre.php?id=<?php echo $offre['id']; ?>"

class="btn btn-success">

✅ Valider

</a>



<a href="supprimer_offre.php?id=<?php echo $offre['id']; ?>"

class="btn btn-danger"

onclick="return confirm('Voulez-vous supprimer cette offre ?');">

🗑 Supprimer

</a>



</div>


</div>


<?php


}


}


?>


<div class="text-center mt-4">


<a href="dashboard.php" class="btn btn-secondary">

Retour tableau de bord

</a>


</div>


</div>


</div>


</div>


</body>

</html>