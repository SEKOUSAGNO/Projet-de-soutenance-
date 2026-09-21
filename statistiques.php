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
= Fonction compteur
=========================================================*/

function compter($conn, $sql){

    $result = pg_query($conn,$sql);

    if(!$result){

        return 0;

    }

    $data = pg_fetch_assoc($result);

    return $data['total'];

}



/*=========================================================
= STATISTIQUES UTILISATEURS
=========================================================*/


$total_utilisateurs = compter(
$conn,
"SELECT COUNT(*) AS total FROM utilisateur"
);



$total_etudiants = compter(
$conn,
"SELECT COUNT(*) AS total 
 FROM utilisateur 
 WHERE role='Etudiant'"
);



$total_recruteurs = compter(
$conn,
"SELECT COUNT(*) AS total 
 FROM utilisateur 
 WHERE role='Recruteur'"
);



$total_admin = compter(
$conn,
"SELECT COUNT(*) AS total 
 FROM utilisateur 
 WHERE role='Admin'"
);



/*=========================================================
= STATISTIQUES OFFRES
=========================================================*/


$total_offres = compter(
$conn,
"SELECT COUNT(*) AS total FROM offre"
);



$offres_actives = compter(
$conn,
"SELECT COUNT(*) AS total 
 FROM offre 
 WHERE statut='Active'"
);



$offres_supprimees = compter(
$conn,
"SELECT COUNT(*) AS total 
 FROM offre 
 WHERE statut='Supprimée'"
);



/*=========================================================
= STATISTIQUES CANDIDATURES
=========================================================*/


$total_candidatures = compter(
$conn,
"SELECT COUNT(*) AS total FROM candidature"
);



$candidatures_attente = compter(
$conn,
"SELECT COUNT(*) AS total 
 FROM candidature 
 WHERE statut='En attente'"
);



$candidatures_acceptees = compter(
$conn,
"SELECT COUNT(*) AS total 
 FROM candidature 
 WHERE statut='Acceptée'"
);



$candidatures_refusees = compter(
$conn,
"SELECT COUNT(*) AS total 
 FROM candidature 
 WHERE statut='Refusée'"
);



?>


<!DOCTYPE html>

<html lang="fr">

<head>

<meta charset="UTF-8">

<title>Statistiques Administration</title>


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">



<style>


body{

    background:#eef5ff;

}



.container{

    max-width:950px;

}



.card{

    margin-top:15px;

}



.card-body{

    padding:15px;

}



.titre{

    font-size:22px;

    font-weight:bold;

}



.section{

    font-size:17px;

    font-weight:bold;

    margin-top:10px;

}



.stat-card{

    text-align:center;

    padding:12px;

    border-radius:10px;

}



.nombre{

    font-size:28px;

    font-weight:bold;

    margin-top:5px;

}



.small-text{

    font-size:14px;

}



</style>


</head>



<body>



<div class="container">



<div class="card shadow">



<div class="card-body">



<div class="text-center titre text-primary">

📊 Tableau de bord statistique

</div>



<hr>



<!-- UTILISATEURS -->


<div class="section text-success">

👥 Utilisateurs

</div>



<div class="row g-2">



<div class="col-md-3 col-6">

<div class="card bg-light stat-card">


<div class="small-text">

Total

</div>


<div class="nombre">

<?php echo $total_utilisateurs; ?>

</div>


</div>

</div>




<div class="col-md-3 col-6">

<div class="card bg-light stat-card">


<div class="small-text">

🎓 Etudiants

</div>


<div class="nombre">

<?php echo $total_etudiants; ?>

</div>


</div>

</div>





<div class="col-md-3 col-6">

<div class="card bg-light stat-card">


<div class="small-text">

🏢 Recruteurs

</div>


<div class="nombre">

<?php echo $total_recruteurs; ?>

</div>


</div>

</div>





<div class="col-md-3 col-6">

<div class="card bg-light stat-card">


<div class="small-text">

⚙ Admin

</div>


<div class="nombre">

<?php echo $total_admin; ?>

</div>


</div>

</div>



</div>




<hr>



<!-- OFFRES -->


<div class="section text-primary">

💼 Offres

</div>



<div class="row g-2">



<div class="col-md-4 col-6">

<div class="card bg-light stat-card">


<div class="small-text">

Total offres

</div>


<div class="nombre">

<?php echo $total_offres; ?>

</div>


</div>

</div>





<div class="col-md-4 col-6">

<div class="card bg-light stat-card">


<div class="small-text">

🟢 Actives

</div>


<div class="nombre">

<?php echo $offres_actives; ?>

</div>


</div>

</div>





<div class="col-md-4 col-6">

<div class="card bg-light stat-card">


<div class="small-text">

🔴 Supprimées

</div>


<div class="nombre">

<?php echo $offres_supprimees; ?>

</div>


</div>

</div>



</div>





<hr>



<!-- CANDIDATURES -->


<div class="section text-warning">

📄 Candidatures

</div>




<div class="row g-2">



<div class="col-md-3 col-6">

<div class="card bg-light stat-card">


<div class="small-text">

Total

</div>


<div class="nombre">

<?php echo $total_candidatures; ?>

</div>


</div>

</div>





<div class="col-md-3 col-6">

<div class="card bg-light stat-card">


<div class="small-text">

🟡 Attente

</div>


<div class="nombre">

<?php echo $candidatures_attente; ?>

</div>


</div>

</div>





<div class="col-md-3 col-6">

<div class="card bg-light stat-card">


<div class="small-text">

🟢 Acceptées

</div>


<div class="nombre">

<?php echo $candidatures_acceptees; ?>

</div>


</div>

</div>





<div class="col-md-3 col-6">

<div class="card bg-light stat-card">


<div class="small-text">

🔴 Refusées

</div>


<div class="nombre">

<?php echo $candidatures_refusees; ?>

</div>


</div>

</div>




</div>



<hr>



<div class="text-center">


<a href="dashboard.php"

class="btn btn-secondary btn-sm">

Retour tableau de bord

</a>


</div>



</div>


</div>


</div>



</body>


</html>