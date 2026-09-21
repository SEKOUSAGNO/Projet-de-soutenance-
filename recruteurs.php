<?php

session_start();

require_once("../database.php");

// =====================================
// Vérifier que l'utilisateur est Admin
// =====================================

if (!isset($_SESSION['id']) || $_SESSION['role'] != "Admin") {

    header("Location: ../login_public.php");
    exit();

}

// =====================================
// Vérifier si l'admin connecté est principal
// =====================================

$sql = "SELECT fonction
        FROM utilisateur
        WHERE id = $1
        AND role='Admin'";

$resultat = pg_query_params(
    $conn,
    $sql,
    array($_SESSION['id'])
);

$admin = pg_fetch_assoc($resultat);

$estPrincipal = ($admin['fonction'] == "Administrateur principal");

// =====================================
// Liste des recruteurs
// =====================================

$sql = "SELECT id,
               nom,
               prenom,
               email,
               statut
        FROM utilisateur
        WHERE role='Recruteur'
        ORDER BY id DESC";

$resultat = pg_query($conn,$sql);

?>

<!DOCTYPE html>
<html lang="fr">

<head>

<meta charset="UTF-8">

<title>Gestion des recruteurs</title>

<style>

body{
    font-family:Arial;
    background:#f2f2f2;
}

.container{
    width:95%;
    margin:40px auto;
    background:white;
    padding:20px;
    border-radius:10px;
    box-shadow:0 0 10px gray;
}

h1{
    text-align:center;
    color:#007bff;
}

table{
    width:100%;
    border-collapse:collapse;
}

table th{
    background:#007bff;
    color:white;
    padding:12px;
}

table td{
    padding:10px;
    border:1px solid #ddd;
    text-align:center;
}

.btn{
    padding:8px 12px;
    color:white;
    text-decoration:none;
    border-radius:5px;
    display:inline-block;
    margin:2px;
}

.valider{
    background:green;
}

.refuser{
    background:#d32f2f;
}

.activer{
    background:#2e7d32;
}

.desactiver{
    background:#f57c00;
}

.supprimer{
    background:#c62828;
}

.attente{
    color:orange;
    font-weight:bold;
}

.actif{
    color:green;
    font-weight:bold;
}

.refuse{
    color:red;
    font-weight:bold;
}

.interdit{
    color:#777;
    font-style:italic;
    font-weight:bold;
}

.retour{
    display:inline-block;
    margin-top:20px;
    background:#333;
    color:white;
    padding:10px;
    text-decoration:none;
    border-radius:5px;
}

</style>

</head>

<body>

<div class="container">

<h1>Gestion des recruteurs</h1>

<table>

<tr>

<th>Nom</th>

<th>Prénom</th>

<th>Email</th>

<th>Statut</th>

<th>Actions</th>

</tr>

<?php

if($resultat && pg_num_rows($resultat)>0){

while($recruteur = pg_fetch_assoc($resultat)){

?>

<tr>

<td><?= htmlspecialchars($recruteur['nom']); ?></td>

<td><?= htmlspecialchars($recruteur['prenom']); ?></td>

<td><?= htmlspecialchars($recruteur['email']); ?></td>

<td>

<?php

if($recruteur['statut']=="En attente"){

    echo "<span class='attente'>En attente</span>";

}elseif($recruteur['statut']=="Actif"){

    echo "<span class='actif'>Actif</span>";

}else{

    echo "<span class='refuse'>".$recruteur['statut']."</span>";

}

?>

</td>

<td>

<?php

if(!$estPrincipal){

    echo "<span class='interdit'>Consultation uniquement</span>";

}else{

    if($recruteur['statut']=="En attente"){

?>

<a class="btn valider"
href="valider_recruteur.php?id=<?= $recruteur['id']; ?>">

✅ Valider

</a>

<a class="btn refuser"
href="refuser_recruteur.php?id=<?= $recruteur['id']; ?>"
onclick="return confirm('Refuser ce recruteur ?');">

❌ Refuser

</a>

<?php

    }

    elseif($recruteur['statut']=="Actif"){

?>

<a class="btn desactiver"
href="desactiver_recruteur.php?id=<?= $recruteur['id']; ?>"
onclick="return confirm('Désactiver ce recruteur ?');">

🔒 Désactiver

</a>

<a class="btn supprimer"
href="supprimer_recruteur.php?id=<?= $recruteur['id']; ?>"
onclick="return confirm('Supprimer ce recruteur ?');">

🗑️ Supprimer

</a>

<?php

    }

    else{

?>

<a class="btn activer"
href="activer_recruteur.php?id=<?= $recruteur['id']; ?>">

🔓 Activer

</a>

<a class="btn supprimer"
href="supprimer_recruteur.php?id=<?= $recruteur['id']; ?>"
onclick="return confirm('Supprimer ce recruteur ?');">

🗑️ Supprimer

</a>

<?php

    }

}

?>

</td>

</tr>

<?php

}

}else{

?>

<tr>

<td colspan="5">

Aucun recruteur trouvé.

</td>

</tr>

<?php

}

?>

</table>

<a class="retour" href="dashboard.php">

⬅ Retour au tableau de bord

</a>

</div>

</body>

</html>