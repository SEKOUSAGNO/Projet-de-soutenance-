<?php

session_start();

require_once("database.php");


// =======================================
// Vérifier les données envoyées
// =======================================

if (!isset($_POST['email']) || !isset($_POST['password'])) {

    die("Accès non autorisé.");

}


$email = trim($_POST['email']);

$password = $_POST['password'];




// =======================================
// Recherche utilisateur
// =======================================


$sql = "

SELECT *

FROM utilisateur

WHERE email=$1

";


$resultat = pg_query_params(

    $conn,

    $sql,

    array($email)

);



if (!$resultat || pg_num_rows($resultat) != 1) {


    echo "

    <script>

    alert('Adresse e-mail introuvable.');

    window.location='login_public.php';

    </script>";

    exit();

}



$user = pg_fetch_assoc($resultat);




// =======================================
// Vérification mot de passe
// =======================================


if (!password_verify($password,$user['mot_de_passe'])) {


    echo "

    <script>

    alert('Mot de passe incorrect.');

    window.location='login_public.php';

    </script>";

    exit();

}




// =======================================
// CONTRÔLE DU STATUT DU COMPTE
// =======================================



if($user['statut'] != "Actif"){



    $message="";



    // ===============================
    // RECRUTEUR
    // ===============================

    if($user['role']=="Recruteur"){



        if($user['statut']=="En attente"){


            $message="
            Votre compte recruteur est en attente de validation 
            par l'administrateur principal.
            ";


        }


        elseif($user['statut']=="Refusé"){


            $message="
            Votre demande de création de compte recruteur 
            a été refusée par l'administrateur principal.
            ";


        }


        elseif($user['statut']=="Désactivé"){


            $message="
            Votre compte recruteur a été désactivé 
            par l'administrateur principal.

            Veuillez contacter l'administrateur 
            pour demander sa réactivation.
            ";


        }


        else{


            $message="
            Votre compte recruteur n'est pas actif.
            ";

        }



    }



    // ===============================
    // ETUDIANT
    // ===============================


    elseif($user['role']=="Etudiant"){


        $message="
        Votre compte étudiant est désactivé.
        Veuillez contacter l'administration.
        ";


    }




    // ===============================
    // ADMIN
    // ===============================


    elseif($user['role']=="Admin"){


        $message="
        Votre compte administrateur est désactivé.
        Veuillez contacter l'administrateur principal.
        ";


    }



    else{


        $message="
        Votre compte est désactivé.
        ";

    }





    ?>



    <!DOCTYPE html>

    <html lang="fr">

    <head>

    <meta charset="UTF-8">

    <title>Compte désactivé</title>


    <style>


    body{

        margin:0;
        font-family:Arial;
        background:#f2f2f2;

    }



    .box{

        width:450px;
        margin:100px auto;
        background:white;
        padding:30px;
        text-align:center;
        border-radius:10px;
        box-shadow:0 0 15px gray;

    }



    h1{

        color:red;

    }



    p{

        font-size:18px;
        line-height:1.5;

    }



    a{

        display:inline-block;
        margin-top:20px;
        padding:12px 25px;
        background:#1565C0;
        color:white;
        text-decoration:none;
        border-radius:5px;

    }



    a:hover{

        background:#0D47A1;

    }


    </style>


    </head>



    <body>


    <div class="box">


    <h1>
    Compte non actif
    </h1>


    <p>
    <?= $message; ?>
    </p>



    <a href="login_public.php">

    ⬅ Retour à la connexion

    </a>



    </div>


    </body>


    </html>


    <?php


    exit();


}







// =======================================
// Création session
// =======================================


$_SESSION['id']=$user['id'];

$_SESSION['nom']=$user['nom'];

$_SESSION['prenom']=$user['prenom'];

$_SESSION['email']=$user['email'];

$_SESSION['role']=$user['role'];

$_SESSION['statut']=$user['statut'];






// =======================================
// Redirection selon rôle
// =======================================


switch($user['role']) {



case "Admin":


header("Location: admin/dashboard.php");

break;




case "Recruteur":


header("Location: recruteur/dashboard.php");

break;




case "Etudiant":


header("Location: etudiant/dashboard.php");

break;




default:


session_destroy();


echo "

<script>

alert('Rôle utilisateur non reconnu.');

window.location='login_public.php';

</script>";

break;


}



exit();



?>