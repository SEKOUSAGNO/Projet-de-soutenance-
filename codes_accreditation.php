<?php
session_start();

require_once("../database.php");

/*=========================================================
= Vérifier que l'utilisateur est connecté
=========================================================*/

if (!isset($_SESSION['id'])) {

    header("Location: ../login_public.php");
    exit();

}

/*=========================================================
= Vérifier que l'utilisateur est administrateur
=========================================================*/

if ($_SESSION['role'] != "Admin") {

    header("Location: ../login_public.php");
    exit();

}

/*=========================================================
= Vérifier si c'est l'administrateur principal
=========================================================*/

$id_admin = $_SESSION['id'];

$sqlAdmin = "SELECT fonction
             FROM utilisateur
             WHERE id = $1
             AND role='Admin'";

$resultAdmin = pg_query_params(
    $conn,
    $sqlAdmin,
    array($id_admin)
);

$estPrincipal = false;

if ($resultAdmin && pg_num_rows($resultAdmin) > 0) {

    $admin = pg_fetch_assoc($resultAdmin);

    if ($admin['fonction'] == "Administrateur principal") {

        $estPrincipal = true;

    }

}

/*=========================================================
= Récupération des codes d'accréditation
=========================================================*/

$sql = "SELECT *
        FROM code_accreditation
        ORDER BY id DESC";

$resultat = pg_query($conn, $sql);

?>

<!DOCTYPE html>

<html lang="fr">

<head>

<meta charset="UTF-8">

<title>Gestion des Codes d'Accréditation</title>

<style>

body{

    margin:0;
    font-family:Arial, Helvetica, sans-serif;
    background:#f4f6f9;

}

header{

    background:#1565C0;
    color:white;
    text-align:center;
    padding:20px;

}

.container{

    width:95%;
    margin:30px auto;
    background:white;
    padding:20px;
    border-radius:10px;
    box-shadow:0 0 10px rgba(0,0,0,.2);

}

.actions{

    margin-bottom:20px;

}

.btn{

    display:inline-block;
    padding:10px 18px;
    text-decoration:none;
    color:white;
    border-radius:5px;
    margin-right:10px;
    margin-bottom:5px;

}

.ajouter{

    background:#28a745;

}

.generer{

    background:#1565C0;

}

.retour{

    background:#555;

}

.activer{

    background:#28a745;

}

.desactiver{

    background:#ff9800;

}

.supprimer{

    background:#dc3545;

}

.interdit{

    color:red;
    font-weight:bold;

}

table{

    width:100%;
    border-collapse:collapse;

}

table th{

    background:#1565C0;
    color:white;
    padding:12px;

}

table td{

    border:1px solid #ddd;
    padding:10px;
    text-align:center;

}

table tr:nth-child(even){

    background:#f8f8f8;

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

Gestion des Codes d'Accréditation

</h1>

</header>

<div class="container">

<div class="actions">

<a href="ajouter_code.php" class="btn ajouter">

➕ Ajouter un code

</a>

<a href="generer_code.php" class="btn generer">

⚡ Générer automatiquement

</a>

<a href="dashboard.php" class="btn retour">

⬅ Retour au tableau de bord

</a>

</div>

<table>

<tr>

<th>ID</th>

<th>Code</th>

<th>Actif</th>

<th>Utilisé</th>

<th>Date création</th>

<th>Date utilisation</th>

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

if($code['actif']=='t'){

    echo "<span class='oui'>Oui</span>";

}else{

    echo "<span class='non'>Non</span>";

}

?>

</td>

<td>

<?php

if($code['utilise']=='t'){

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

if(empty($code['date_utilisation'])){

    echo "-";

}else{

    echo $code['date_utilisation'];

}

?>

</td>

<td>

<?php

/* ===================================================
   La Partie 2 commence ici (gestion des droits)
   =================================================== */
   /*=========================================================
= Gestion des droits administrateur
=========================================================*/


if($estPrincipal == true){



    // ================================
    // Administrateur principal
    // ================================


    if($code['actif']=='t'){


?>

<a class="btn desactiver"
href="desactiver_code.php?id=<?= $code['id']; ?>"
onclick="return confirm('Voulez-vous désactiver ce code ?');">

🔒 Désactiver

</a>


<?php


    }else{


?>


<a class="btn activer"
href="activer_code.php?id=<?= $code['id']; ?>"
onclick="return confirm('Voulez-vous activer ce code ?');">

🔓 Activer

</a>


<?php

    }



?>


<a class="btn supprimer"
href="supprimer_code.php?id=<?= $code['id']; ?>"
onclick="return confirm('Voulez-vous vraiment supprimer ce code ?');">

🗑️ Supprimer

</a>


<?php


}else{


    // ================================
    // Administrateur secondaire
    // ================================


?>


<span class="interdit">

🔒 Réservé à l'administrateur principal

</span>


<?php


}


?>


</td>


</tr>


<?php


    }


}else{


?>


<tr>

<td colspan="7">

Aucun code d'accréditation disponible.

</td>

</tr>


<?php


}


?>


</table>


</div>


</body>

</html>