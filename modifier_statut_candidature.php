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
= Vérifier l'identifiant candidature
=========================================================*/

if (!isset($_GET['id'])) {

    die("Aucune candidature sélectionnée.");

}


$candidature_id = $_GET['id'];



/*=========================================================
= Vérifier que la candidature existe
=========================================================*/

$sqlVerification = "

SELECT id

FROM candidature

WHERE id = $1

";


$resultVerification = pg_query_params(

    $conn,

    $sqlVerification,

    array($candidature_id)

);



if (!$resultVerification || pg_num_rows($resultVerification)==0) {

    die("Candidature introuvable.");

}



/*=========================================================
= Si formulaire envoyé
=========================================================*/

if($_SERVER["REQUEST_METHOD"]=="POST"){



    if(!isset($_POST['statut'])){

        die("Statut non sélectionné.");

    }



    $statut = $_POST['statut'];



    /*=========================================
    Mise à jour du statut
    =========================================*/


    $sqlUpdate = "

    UPDATE candidature

    SET statut = $1

    WHERE id = $2

    ";



    $resultUpdate = pg_query_params(

        $conn,

        $sqlUpdate,

        array(
            $statut,
            $candidature_id
        )

    );



    if(!$resultUpdate){


        die(

            "Erreur modification statut : "

            .pg_last_error($conn)

        );


    }



    header(
        "Location: candidatures.php?success=statut_modifie"
    );

    exit();


}



?>


<!DOCTYPE html>

<html lang="fr">

<head>

<meta charset="UTF-8">

<title>Modifier statut candidature</title>


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">


<style>

body{

background:#eef5ff;

}


.card{

margin-top:40px;

}

</style>


</head>


<body>


<div class="container">


<div class="card shadow">


<div class="card-body">


<h2 class="text-center text-primary">

⚙ Modifier le statut de la candidature

</h2>


<hr>



<form method="POST">



<div class="mb-3">


<label class="form-label">

Choisir le nouveau statut

</label>


<select name="statut" class="form-select" required>


<option value="En attente">

En attente

</option>


<option value="Acceptée">

Acceptée

</option>


<option value="Refusée">

Refusée

</option>


</select>


</div>



<div class="text-center">


<button type="submit"

class="btn btn-success">

💾 Enregistrer

</button>


<a href="candidatures.php"

class="btn btn-secondary">

Annuler

</a>


</div>


</form>


</div>

</div>


</div>


</body>

</html>