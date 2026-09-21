<?php
session_start();

/*=========================================================
= Vérifier que le recruteur est connecté
=========================================================*/
if (!isset($_SESSION['id']) || $_SESSION['role'] != "Recruteur") {
    header("Location: ../login_public.php");
    exit();
}

require_once("../database.php");

/*=========================================================
= Vérifier qu'une offre est sélectionnée
=========================================================*/
if (!isset($_GET['id'])) {
    die("Aucune offre sélectionnée.");
}

$offre_id = $_GET['id'];

/*=========================================================
= Récupérer l'ID du recruteur connecté
=========================================================*/
$sqlRecruteur = "
SELECT id
FROM recruteur_entreprise
WHERE utilisateur_id = $1
";

$resultRecruteur = pg_query_params(
    $conn,
    $sqlRecruteur,
    array($_SESSION['id'])
);

if (!$resultRecruteur || pg_num_rows($resultRecruteur) == 0) {
    die("Profil recruteur introuvable.");
}

$recruteur = pg_fetch_assoc($resultRecruteur);
$recruteur_id = $recruteur['id'];

/*=========================================================
= Vérifier que l'offre appartient au recruteur
=========================================================*/
$sql = "
SELECT *
FROM offre
WHERE id = $1
AND recruteur_id = $2
";

$result = pg_query_params(
    $conn,
    $sql,
    array($offre_id, $recruteur_id)
);

if (!$result || pg_num_rows($result) == 0) {
    die("Cette offre n'existe pas ou ne vous appartient pas.");
}

$offre = pg_fetch_assoc($result);

?>

<!DOCTYPE html>
<html lang="fr">

<head>

<meta charset="UTF-8">

<title>Modifier une offre</title>

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

<h2 class="text-center text-warning">

✏ Modifier une offre

</h2>

<hr>

<form action="enregistrer_modification.php" method="POST">

<input
type="hidden"
name="offre_id"
value="<?php echo $offre['id']; ?>">

<div class="mb-3">

<label class="form-label">

Titre

</label>

<input
type="text"
name="titre"
class="form-control"
required
value="<?php echo htmlspecialchars($offre['titre']); ?>">

</div>

<div class="mb-3">

<label class="form-label">

Description

</label>

<textarea
name="description"
class="form-control"
rows="5"
required><?php echo htmlspecialchars($offre['description']); ?></textarea>

</div>

<div class="mb-3">

<label class="form-label">

Type d'offre

</label>

<select
name="type_offre"
class="form-select"
required>

<option value="Emploi"
<?php if($offre['type_offre']=="Emploi") echo "selected"; ?>>
Emploi
</option>

<option value="Stage"
<?php if($offre['type_offre']=="Stage") echo "selected"; ?>>
Stage
</option>

</select>

</div>

<div class="mb-3">

<label class="form-label">

Entreprise

</label>

<input
type="text"
name="entreprise"
class="form-control"
required
value="<?php echo htmlspecialchars($offre['entreprise']); ?>">

</div>

<div class="mb-3">

<label class="form-label">

Lieu

</label>

<input
type="text"
name="lieu"
class="form-control"
required
value="<?php echo htmlspecialchars($offre['lieu']); ?>">

</div>

<div class="mb-3">

<label class="form-label">

Date limite

</label>

<input
type="date"
name="date_limite"
class="form-control"
required
value="<?php echo $offre['date_limite']; ?>">

</div>

<div class="mb-3">

<label class="form-label">

Mode de candidature

</label>

<select
name="mode_candidature"
class="form-select"
required>

<option value="Plateforme"
<?php if($offre['mode_candidature']=="Plateforme") echo "selected"; ?>>
Plateforme
</option>

<option value="Email"
<?php if($offre['mode_candidature']=="Email") echo "selected"; ?>>
Email
</option>

</select>

</div>

<div class="mb-3">

<label class="form-label">

Email de réception

</label>

<input
type="email"
name="email_reception"
class="form-control"
value="<?php echo htmlspecialchars($offre['email_reception']); ?>">

</div>

<div class="text-center">

<button
type="submit"
class="btn btn-success">

💾 Enregistrer les modifications

</button>

<a
href="mes_offres.php"
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