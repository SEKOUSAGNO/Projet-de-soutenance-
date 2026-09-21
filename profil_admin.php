<?php

session_start();

require_once("../database.php");


// =====================================
// Vérification connexion Admin
// =====================================

if (!isset($_SESSION['id']) || $_SESSION['role'] != "Admin") {

    header("Location: ../login_public.php");
    exit();

}


$id = $_SESSION['id'];



// =====================================
// Traitement modification du profil
// =====================================

if(isset($_POST['nom'])){


    $nom = trim($_POST['nom']);

    $prenom = trim($_POST['prenom']);

    $email = trim($_POST['email']);

    $fonction = trim($_POST['fonction']);



    // Vérifier si l'email appartient déjà à un autre utilisateur

    $verification = "SELECT id
                     FROM utilisateur
                     WHERE email=$1
                     AND id<>$2";


    $resultat_verification = pg_query_params(

        $conn,

        $verification,

        array($email,$id)

    );



    if(pg_num_rows($resultat_verification)>0){


        echo "<script>

        alert('Cet email est déjà utilisé.');

        window.location='modifier_profil_admin.php';

        </script>";

        exit();

    }



    // Mise à jour du profil

    $sql_update = "UPDATE utilisateur

                   SET nom=$1,
                       prenom=$2,
                       email=$3,
                       fonction=$4

                   WHERE id=$5

                   AND role='Admin'";



    $mise_a_jour = pg_query_params(

        $conn,

        $sql_update,

        array(

            $nom,

            $prenom,

            $email,

            $fonction,

            $id

        )

    );



    if($mise_a_jour){


        // Mise à jour des sessions

        $_SESSION['nom']=$nom;

        $_SESSION['prenom']=$prenom;



        echo "<script>

        alert('Profil modifié avec succès.');

        window.location='profil_admin.php';

        </script>";

        exit();


    }else{


        echo "<script>

        alert('Erreur lors de la modification.');

        window.location='profil_admin.php';

        </script>";

        exit();

    }


}



// =====================================
// Récupérer les informations administrateur
// =====================================


$sql = "SELECT nom, prenom, email, fonction, statut

        FROM utilisateur

        WHERE id=$1

        AND role='Admin'";



$resultat = pg_query_params(

    $conn,

    $sql,

    array($id)

);



if (!$resultat || pg_num_rows($resultat)==0){

    die("Administrateur introuvable.");

}



$admin = pg_fetch_assoc($resultat);



?>



<!DOCTYPE html>

<html lang="fr">

<head>

<meta charset="UTF-8">

<title>Mon profil administrateur</title>



<style>


body{

font-family:Arial, Helvetica, sans-serif;

background:#f2f2f2;

margin:0;

}



.container{

width:500px;

margin:50px auto;

background:white;

padding:25px;

border-radius:10px;

box-shadow:0 0 10px gray;

}



h1{

text-align:center;

color:#007bff;

}



.info{

padding:12px;

border-bottom:1px solid #ddd;

font-size:17px;

}



.statut{

font-weight:bold;

color:green;

}



.btn{

display:block;

text-align:center;

margin-top:15px;

padding:12px;

background:#007bff;

color:white;

text-decoration:none;

border-radius:5px;

}



.btn:hover{

background:#0056b3;

}



.retour{

background:#333;

}



</style>


</head>



<body>



<div class="container">



<h1>

👤 Mon profil

</h1>




<div class="info">

<strong>Nom :</strong>

<?= htmlspecialchars($admin['nom']); ?>

</div>




<div class="info">

<strong>Prénom :</strong>

<?= htmlspecialchars($admin['prenom']); ?>

</div>




<div class="info">

<strong>Email :</strong>

<?= htmlspecialchars($admin['email']); ?>

</div>




<div class="info">

<strong>Fonction :</strong>

<?= htmlspecialchars($admin['fonction']); ?>

</div>




<div class="info">

<strong>Statut :</strong>

<span class="statut">

<?= htmlspecialchars($admin['statut']); ?>

</span>

</div>





<a class="btn" href="modifier_profil_admin.php">

✏️ Modifier mon profil

</a>





<a class="btn" href="changer_mot_de_passe_admin.php">

🔑 Changer mon mot de passe

</a>





<a class="btn retour" href="dashboard.php">

⬅ Retour tableau de bord

</a>




</div>



</body>


</html>