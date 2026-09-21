<?php
session_start();

if (!isset($_SESSION['id']) || $_SESSION['role'] != 'Recruteur') {
    header("Location: ../login_public.php");
    exit();
}

require_once("../database.php");

// Vérifier si le profil existe déjà
$sql = "SELECT * FROM recruteur_entreprise WHERE utilisateur_id = $1";
$result = pg_query_params($conn, $sql, array($_SESSION['id']));

$profil = null;

if ($result && pg_num_rows($result) > 0) {
    $profil = pg_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>

<meta charset="UTF-8">

<title>Profil de l'entreprise</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
    background:#eef5ff;
}

.card{
    max-width:700px;
    margin:40px auto;
    border-radius:15px;
}

h2{
    color:#0d6efd;
}

</style>

</head>

<body>

<div class="container">

<div class="card shadow">

<div class="card-body">

<h2 class="text-center">

Profil de l'entreprise

</h2>

<hr>

<form action="enregistrer_profil.php" method="POST">

<div class="mb-3">

<label class="form-label">

Nom de l'entreprise

</label>

<input
type="text"
name="nom_entreprise"
class="form-control"
value="<?php echo isset($profil['nom_entreprise']) ? htmlspecialchars($profil['nom_entreprise']) : ''; ?>"
required>

</div>

<div class="mb-3">

<label class="form-label">

Adresse

</label>

<textarea
name="adresse"
class="form-control"
rows="3"
required><?php echo isset($profil['adresse']) ? htmlspecialchars($profil['adresse']) : ''; ?></textarea>

</div>

<div class="mb-3">

<label class="form-label">

Téléphone

</label>

<input
type="text"
name="telephone"
class="form-control"
value="<?php echo isset($profil['telephone']) ? htmlspecialchars($profil['telephone']) : ''; ?>"
required>

</div>

<div class="mb-3">

<label class="form-label">

Secteur d'activité

</label>

<select
name="secteur_activite"
class="form-select"
required>

<option value="">Choisir...</option>

<?php

$secteurs = array(
"Administration publique",
"Agriculture",
"Banque et Finance",
"BTP",
"Commerce",
"Communication",
"Éducation",
"Énergie",
"Hôtellerie",
"Industrie",
"Informatique",
"Logistique",
"Mines",
"Santé",
"Télécommunications",
"Transport",
"Autres"
);

foreach($secteurs as $secteur){

    $selected = "";

    if(isset($profil['secteur_activite']) && $profil['secteur_activite']==$secteur){

        $selected="selected";

    }

    echo "<option value=\"$secteur\" $selected>$secteur</option>";

}

?>

</select>

</div>

<div class="text-center">

<button
type="submit"
class="btn btn-primary btn-lg">

Enregistrer

</button>

<a
href="dashboard.php"
class="btn btn-secondary btn-lg">

Retour

</a>

</div>

</form>

</div>

</div>

</div>

</body>

</html>