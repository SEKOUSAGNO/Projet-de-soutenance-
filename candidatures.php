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
= Récupérer toutes les candidatures
=========================================================*/

$sql = "

SELECT

candidature.id,

candidature.date_candidature,

candidature.statut,


utilisateur.nom,

utilisateur.prenom,


offre.titre,

offre.entreprise


FROM candidature


INNER JOIN etudiant

ON candidature.etudiant_id = etudiant.id


INNER JOIN utilisateur

ON etudiant.utilisateur_id = utilisateur.id


INNER JOIN offre

ON candidature.offre_id = offre.id


ORDER BY candidature.date_candidature DESC


";


$result = pg_query($conn,$sql);



if(!$result){

    die(
        "Erreur récupération candidatures : "
        .pg_last_error($conn)
    );

}


?>


<!DOCTYPE html>

<html lang="fr">

<head>

<meta charset="UTF-8">

<title>Gestion des candidatures</title>


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">


<style>

body{

background:#eef5ff;

}


.card{

margin-top:30px;

}

</style>


</head>


<body>


<div class="container">


<div class="card shadow">


<div class="card-body">


<h2 class="text-center text-primary">

 Gestion globale des candidatures

</h2>


<hr>



<?php


if(pg_num_rows($result)==0){


echo "

<div class='alert alert-info'>

Aucune candidature enregistrée.

</div>

";


}

else{


?>


<div class="table-responsive">


<table class="table table-bordered table-striped">


<thead class="table-primary">


<tr>

<th>ID</th>

<th>Candidat</th>

<th>Offre</th>

<th>Entreprise</th>

<th>Date candidature</th>

<th>Statut</th>

<th>Action</th>

</tr>


</thead>



<tbody>


<?php


while($candidature = pg_fetch_assoc($result)){


?>


<tr>


<td>

<?php echo $candidature['id']; ?>

</td>



<td>

<?php

echo htmlspecialchars(

$candidature['prenom']
." ".
$candidature['nom']

);

?>

</td>



<td>

<?php

echo htmlspecialchars(

$candidature['titre']

);

?>

</td>



<td>

<?php

echo htmlspecialchars(

$candidature['entreprise']

);

?>

</td>



<td>

<?php

echo htmlspecialchars(

$candidature['date_candidature']

);

?>

</td>



<td>


<?php


if($candidature['statut']=="Acceptée"){


echo "

<span class='badge bg-success'>

Acceptée

</span>

";


}

elseif($candidature['statut']=="Refusée"){


echo "

<span class='badge bg-danger'>

Refusée

</span>

";


}

else{


echo "

<span class='badge bg-warning text-dark'>

En attente

</span>

";


}


?>


</td>



<td>


<a href="voir_candidature.php?id=<?php echo $candidature['id']; ?>"

class="btn btn-info btn-sm">

👁 Voir

</a>
<a href="modifier_statut_candidature.php?id=<?php echo $candidature['id']; ?>"

class="btn btn-warning btn-sm">

⚙ Statut

</a>


</td>


</tr>



<?php


}


?>


</tbody>


</table>


</div>


<?php


}


?>


<div class="text-center mt-3">


<a href="dashboard.php"

class="btn btn-secondary">

Retour tableau de bord

</a>


</div>


</div>


</div>


</div>



</body>

</html>