<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login_public.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil</title>

    <style>
        body{
            margin:0;
            padding:0;
            font-family: Arial, Helvetica, sans-serif;
            background: linear-gradient(to right, #0d47a1, #1976d2);
            color:white;
        }

        .conteneur{
            width:70%;
            margin:80px auto;
            background:white;
            color:#0d47a1;
            padding:40px;
            border-radius:12px;
            text-align:center;
            box-shadow:0 0 20px rgba(0,0,0,0.3);
        }

        h1{
            color:#1565c0;
        }

        h3{
            color:#1976d2;
        }

        .btn{
            display:inline-block;
            margin-top:20px;
            padding:12px 25px;
            background:#1565c0;
            color:white;
            text-decoration:none;
            border-radius:5px;
            font-weight:bold;
        }

        .btn:hover{
            background:#0d47a1;
        }
    </style>

</head>

<body>

<div class="conteneur">

    <h1>
        Bienvenue Monsieur / Madame
        <?php
        echo $_SESSION['prenom']." ".$_SESSION['nom'];
        ?>
    </h1>

    <h3>
        Vous êtes connecté en tant que :
        <strong><?php echo $_SESSION['role']; ?></strong>
    </h3>

    <br>

    <a href="deconnexion.php" class="btn">
        Déconnexion
    </a>

</div>

</body>
</html>