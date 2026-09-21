<?php

session_start();

require_once("../database.php");


// =======================================
// VERIFICATION ADMIN
// =======================================

if(!isset($_SESSION['id']) || $_SESSION['role']!="Admin"){

    header("Location: ../login_public.php");
    exit();

}



// =======================================
// IDENTIFIER LE TYPE ADMINISTRATEUR
// =======================================


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



$admin_principal = false;


if($admin && $admin['fonction']=="Administrateur principal"){

    $admin_principal=true;

}



// =================================================
// STATISTIQUES UTILISATEURS
// =================================================


// Etudiants

$sql="
SELECT COUNT(*) AS total
FROM utilisateur
WHERE role='Etudiant'
";

$res=pg_query($conn,$sql);

$etudiants=pg_fetch_assoc($res)['total'];




// Recruteurs

$sql="
SELECT COUNT(*) AS total
FROM utilisateur
WHERE role='Recruteur'
";

$res=pg_query($conn,$sql);

$recruteurs=pg_fetch_assoc($res)['total'];




// Recruteurs actifs

$sql="
SELECT COUNT(*) AS total
FROM utilisateur
WHERE role='Recruteur'
AND statut='Actif'
";

$res=pg_query($conn,$sql);

$recruteurs_actifs=pg_fetch_assoc($res)['total'];




// Recruteurs en attente

$sql="
SELECT COUNT(*) AS total
FROM utilisateur
WHERE role='Recruteur'
AND statut='En attente'
";

$res=pg_query($conn,$sql);

$recruteurs_attente=pg_fetch_assoc($res)['total'];





// =================================================
// STATISTIQUES OFFRES ET CANDIDATURES
// =================================================


// Offres

$sql="
SELECT COUNT(*) AS total
FROM offre
";

$res=pg_query($conn,$sql);

$offres=pg_fetch_assoc($res)['total'];




// Candidatures

$sql="
SELECT COUNT(*) AS total
FROM candidature
";

$res=pg_query($conn,$sql);

$candidatures=pg_fetch_assoc($res)['total'];




// =================================================
// STATISTIQUES CODES ACCREDITATION RECRUTEURS
// =================================================


// Codes actifs

$sql="
SELECT COUNT(*) AS total
FROM code_accreditation
WHERE actif=true
";

$res=pg_query($conn,$sql);

$codes_actifs=pg_fetch_assoc($res)['total'];




// Codes utilisés

$sql="
SELECT COUNT(*) AS total
FROM code_accreditation
WHERE utilise=true
";

$res=pg_query($conn,$sql);

$codes_utilises=pg_fetch_assoc($res)['total'];




// Codes disponibles

$sql="
SELECT COUNT(*) AS total
FROM code_accreditation
WHERE actif=true
AND utilise=false
";

$res=pg_query($conn,$sql);

$codes_disponibles=pg_fetch_assoc($res)['total'];





// =================================================
// STATISTIQUES CODE ADMIN
// =================================================



$codes_admin_actifs=0;
$codes_admin_utilises=0;
$codes_admin_disponibles=0;



// Vérification existence table code_admin

$table_test = pg_query($conn,"
SELECT to_regclass('code_admin')
");



if($table_test && pg_fetch_result($table_test,0,0)!=null){



    // Codes admin actifs

    $sql="
    SELECT COUNT(*) AS total
    FROM code_admin
    WHERE actif=true
    ";

    $res=pg_query($conn,$sql);

    $codes_admin_actifs=pg_fetch_assoc($res)['total'];





    // Codes admin utilisés

    $sql="
    SELECT COUNT(*) AS total
    FROM code_admin
    WHERE utilise=true
    ";

    $res=pg_query($conn,$sql);

    $codes_admin_utilises=pg_fetch_assoc($res)['total'];





    // Codes admin disponibles

    $sql="
    SELECT COUNT(*) AS total
    FROM code_admin
    WHERE actif=true
    AND utilise=false
    ";

    $res=pg_query($conn,$sql);

    $codes_admin_disponibles=pg_fetch_assoc($res)['total'];

}



?>



<!DOCTYPE html>

<html lang="fr">

<head>

<meta charset="UTF-8">

<title>
Dashboard Administrateur SIMAN-JOB
</title>


<style>


body{

margin:0;

font-family:Arial,Helvetica,sans-serif;

background:#f4f6f9;

}




header{

background:#1565C0;

color:white;

padding:20px;

text-align:center;

}





.menu{

width:250px;

height:100vh;

background:#0D47A1;

float:left;

}





.menu h2{

color:white;

text-align:center;

padding:20px;

}





.menu ul{

list-style:none;

padding:0;

}




.menu li{

border-bottom:1px solid #ffffff55;

}




.menu a{

display:block;

padding:15px;

color:white;

text-decoration:none;

}




.menu a:hover{

background:#1976D2;

}





.contenu{

margin-left:250px;

padding:30px;

}





.bienvenue{

background:white;

padding:20px;

border-radius:10px;

box-shadow:0 0 10px #ccc;

margin-bottom:25px;

}





.notifications{

background:white;

padding:20px;

border-radius:10px;

box-shadow:0 0 10px #ccc;

margin-bottom:25px;

}





.alerte{

padding:15px;

border-radius:8px;

margin:10px;

}




.orange{

background:#fff3cd;

}




.bleu{

background:#cfe2ff;

}




.vert{

background:#d1e7dd;

}




.alerte a{

float:right;

}




.cartes{

display:grid;

grid-template-columns:repeat(4,1fr);

gap:20px;

}




.carte_stat{

background:white;

padding:20px;

border-radius:10px;

box-shadow:0 0 10px #ccc;

text-align:center;

}



.carte_stat h2{

font-size:35px;

color:#1565C0;

}



</style>


</head>


<body>



<header>

<h1>
SIMAN-JOB - Tableau de bord Administrateur
</h1>

</header>




<div class="menu">


<h2>
Administrateur
</h2>



<ul>


<li>
<a href="dashboard.php">
   Tableau de bord
</a>
</li>


<li>
<a href="profil_admin.php">
Gestion_profil
</a>
</li>


<?php if($admin_principal){ ?>


<li>
<a href="utilisateurs.php">
Utilisateurs
</a>
</li>


<li>
<a href="recruteurs.php">
Gestion_Recruteurs
</a>
</li>


<?php } ?>


<li>
<a href="administrateurs.php">
Gestion_Admins
</a>
</li>


<li>
<a href="offres.php">
Gestion_Offres
</a>
</li>


<li>
<a href="candidatures.php">
Liste_Candidatures
</a>
</li>


<li>
<a href="codes_accreditation.php">
Codes_d'accréditation
</a>
</li>


<?php if($admin_principal){ ?>


<li>
<a href="codes_admin.php">
Codes administrateurs
</a>
</li>


<?php } ?>


<li>
<a href="statistiques.php">
 Statistiques
</a>
</li>


<li>
<a href="../logout.php">
 Déconnexion
</a>
</li>


</ul>


</div>
<div class="contenu">



<!-- =================================
BLOC BIENVENUE
================================= -->


<div class="bienvenue">


<h2>

Bienvenue 
<?= htmlspecialchars($_SESSION['prenom']." ".$_SESSION['nom']); ?>

</h2>



<p>

Vous êtes connecté en tant que :

<strong>

<?php


if($admin_principal){

    echo "Administrateur principal";

}else{

    echo "Administrateur secondaire";

}


?>

</strong>


</p>


</div>





<!-- =================================
NOTIFICATIONS
================================= -->


<div class="notifications">


<h2>

🔔 Notifications

</h2>





<!-- Recruteurs en attente -->

<?php if($admin_principal && $recruteurs_attente>0){ ?>


<div class="alerte orange">


🔔

<?= $recruteurs_attente; ?>

recruteur(s) en attente de validation.



<a href="recruteurs.php">

Voir

</a>


</div>


<?php } ?>






<!-- Candidatures -->

<?php if($candidatures>0){ ?>


<div class="alerte bleu">


📄

<?= $candidatures; ?>

candidature(s) enregistrée(s).



<a href="candidatures.php">

Consulter

</a>


</div>


<?php } ?>






<!-- Codes recruteurs disponibles -->

<?php if($codes_disponibles>0){ ?>


<div class="alerte vert">


🔑

<?= $codes_disponibles; ?>

code(s) d'accréditation recruteur disponible(s).



<a href="codes_accreditation.php">

Gérer

</a>


</div>


<?php } ?>






<!-- Codes administrateurs disponibles -->

<?php if($admin_principal && $codes_admin_disponibles>0){ ?>


<div class="alerte vert">


🔐

<?= $codes_admin_disponibles; ?>

code(s) administrateur(s) disponible(s).



<a href="codes_admin.php">

Gérer

</a>


</div>


<?php } ?>





</div>









<!-- =================================
CARTES STATISTIQUES
================================= -->


<div class="cartes">






<!-- Etudiants -->

<div class="carte_stat">


<h2>

<?= $etudiants ?>

</h2>


<p>

👨‍🎓 Étudiants

</p>


</div>






<!-- Recruteurs -->

<div class="carte_stat">


<h2>

<?= $recruteurs ?>

</h2>


<p>

🏢 Recruteurs

</p>


</div>







<!-- Recruteurs actifs -->

<div class="carte_stat">


<h2>

<?= $recruteurs_actifs ?>

</h2>


<p>

✅ Recruteurs actifs

</p>


</div>






<!-- Recruteurs attente -->

<div class="carte_stat">


<h2>

<?= $recruteurs_attente ?>

</h2>


<p>

⏳ Validation recruteurs

</p>


</div>







<!-- Offres -->

<div class="carte_stat">


<h2>

<?= $offres ?>

</h2>


<p>

📢 Offres

</p>


</div>







<!-- Candidatures -->

<div class="carte_stat">


<h2>

<?= $candidatures ?>

</h2>


<p>

📄 Candidatures

</p>


</div>







<!-- Codes recruteurs actifs -->

<div class="carte_stat">


<h2>

<?= $codes_actifs ?>

</h2>


<p>

🔑 Codes recruteurs actifs

</p>


</div>







<!-- Codes recruteurs utilisés -->

<div class="carte_stat">


<h2>

<?= $codes_utilises ?>

</h2>


<p>

✔ Codes recruteurs utilisés

</p>


</div>







<?php if($admin_principal){ ?>





<!-- Codes administrateurs actifs -->

<div class="carte_stat">


<h2>

<?= $codes_admin_actifs ?>

</h2>


<p>

🔐 Codes Admin actifs

</p>


</div>







<!-- Codes administrateurs utilisés -->

<div class="carte_stat">


<h2>

<?= $codes_admin_utilises ?>

</h2>


<p>

✔ Codes Admin utilisés

</p>


</div>





<?php } ?>





</div>





</div>







</body>

</html>