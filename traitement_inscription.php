<?php

require_once __DIR__ . "/database.php";


// =================================================
// RECUPERATION DES DONNEES DU FORMULAIRE
// =================================================

$nom = trim($_POST['nom'] ?? '');

$prenom = trim($_POST['prenom'] ?? '');

$email = trim($_POST['email'] ?? '');

$password_original = $_POST['password'] ?? '';

$role = trim($_POST['role'] ?? '');


// Code recruteur
$code_accreditation = trim(
    $_POST['code_accreditation'] ?? ""
);


// Type administrateur
$type_admin = $_POST['type_admin'] ?? "";


// Code concepteur
$code_concepteur = trim(
    $_POST['code_concepteur'] ?? ""
);


// Code administrateur
$code_admin = trim(
    $_POST['code_admin'] ?? ""
);


// =================================================
// VERIFICATION DES CHAMPS OBLIGATOIRES
// =================================================

if (
    empty($nom) ||
    empty($prenom) ||
    empty($email) ||
    empty($password_original) ||
    empty($role)
) {

    echo "

    <script>

    alert('Veuillez remplir tous les champs obligatoires.');

    window.location='inscription.php';

    </script>

    ";

    exit();

}


// =================================================
// HASH DU MOT DE PASSE
// =================================================

$password = password_hash(
    $password_original,
    PASSWORD_DEFAULT
);


// =================================================
// VALEURS PAR DEFAUT
// =================================================

$statut = "Actif";

$fonction = "";


// =================================================
// VERIFICATION EMAIL EXISTANT
// =================================================

$sql_email = "

    SELECT id

    FROM utilisateur

    WHERE email = $1

";


$resultat_email = pg_query_params(

    $conn,

    $sql_email,

    array($email)

);


if (
    $resultat_email &&
    pg_num_rows($resultat_email) > 0
) {

    echo "

    <script>

    alert('Cette adresse email existe déjà.');

    window.location='inscription.php';

    </script>

    ";

    exit();

}


// =================================================
// GESTION DU COMPTE RECRUTEUR
// =================================================

if ($role == "Recruteur") {


    // Le recruteur attend la validation
    $statut = "En attente";


    $sql_code = "

        SELECT id

        FROM code_accreditation

        WHERE code = $1

        AND actif = true

        AND utilise = false

    ";


    $verification_code = pg_query_params(

        $conn,

        $sql_code,

        array($code_accreditation)

    );


    if (
        !$verification_code ||
        pg_num_rows($verification_code) == 0
    ) {

        echo "

        <script>

        alert('Code d’accréditation recruteur invalide ou déjà utilisé.');

        window.location='inscription.php';

        </script>

        ";

        exit();

    }

}


// =================================================
// GESTION DES ADMINISTRATEURS
// =================================================

if ($role == "Admin") {


    // =================================================
    // VERIFICATION DU TYPE ADMINISTRATEUR
    // =================================================

    if (empty($type_admin)) {

        echo "

        <script>

        alert('Veuillez choisir le type administrateur.');

        window.location='inscription.php';

        </script>

        ";

        exit();

    }


    // =================================================
    // ADMINISTRATEUR PRINCIPAL
    // =================================================

    if ($type_admin == "principal") {


        $fonction = "Administrateur principal";


        // ---------------------------------------------
        // Vérification code concepteur
        // ---------------------------------------------

        $sql_concepteur = "

            SELECT id

            FROM code_concepteur

            WHERE code = $1

            AND utilise = false

        ";


        $resultat_concepteur = pg_query_params(

            $conn,

            $sql_concepteur,

            array($code_concepteur)

        );


        if (
            !$resultat_concepteur ||
            pg_num_rows($resultat_concepteur) == 0
        ) {

            echo "

            <script>

            alert('Code concepteur invalide.');

            window.location='inscription.php';

            </script>

            ";

            exit();

        }

    }


    // =================================================
    // ADMINISTRATEUR SECONDAIRE
    // =================================================

    elseif ($type_admin == "secondaire") {


        $fonction = "Administrateur secondaire";


        // Le compte attend l'activation
        $statut = "En attente";


        // ---------------------------------------------
        // Vérification code administrateur
        // ---------------------------------------------

        $sql_admin = "

            SELECT id

            FROM code_admin

            WHERE code = $1

            AND actif = true

            AND utilise = false

        ";


        $resultat_admin = pg_query_params(

            $conn,

            $sql_admin,

            array($code_admin)

        );


        if (
            !$resultat_admin ||
            pg_num_rows($resultat_admin) == 0
        ) {

            echo "

            <script>

            alert('Code administrateur invalide ou déjà utilisé.');

            window.location='inscription.php';

            </script>

            ";

            exit();

        }

    }


    else {

        echo "

        <script>

        alert('Type administrateur incorrect.');

        window.location='inscription.php';

        </script>

        ";

        exit();

    }

}


// =================================================
// CREATION DU COMPTE UTILISATEUR
// =================================================

$sql_insert = "

    INSERT INTO utilisateur

    (

        nom,

        prenom,

        email,

        mot_de_passe,

        role,

        statut,

        fonction

    )

    VALUES

    (

        $1,

        $2,

        $3,

        $4,

        $5,

        $6,

        $7

    )

    RETURNING id

";


$resultat_insert = pg_query_params(

    $conn,

    $sql_insert,

    array(

        $nom,

        $prenom,

        $email,

        $password,

        $role,

        $statut,

        $fonction

    )

);


// =================================================
// VERIFICATION CREATION UTILISATEUR
// =================================================

if (!$resultat_insert) {


    echo "

    <script>

    alert('Erreur lors de la création du compte.');

    window.location='inscription.php';

    </script>

    ";

    exit();

}


// =================================================
// RECUPERATION ID UTILISATEUR
// =================================================

$nouvel_utilisateur = pg_fetch_assoc(
    $resultat_insert
);

$user_id = $nouvel_utilisateur['id'];


// =================================================
// IMPORTANT POUR LES ETUDIANTS
// =================================================
//
// Aucun INSERT dans la table etudiant ici.
//
// Le compte utilisateur est créé maintenant.
// L'étudiant complètera son profil après connexion.
// Le profil sera créé depuis profil.php.
//
// =================================================


// =================================================
// UTILISATION DU CODE RECRUTEUR
// =================================================

if ($role == "Recruteur") {


    $sql_update = "

        UPDATE code_accreditation

        SET

            utilise = true,

            date_utilisation = NOW()

        WHERE code = $1

    ";


    pg_query_params(

        $conn,

        $sql_update,

        array($code_accreditation)

    );

}


// =================================================
// UTILISATION DU CODE CONCEPTEUR
// =================================================

if (
    $role == "Admin" &&
    $type_admin == "principal"
) {


    $sql_update = "

        UPDATE code_concepteur

        SET

            utilise = true,

            date_utilisation = NOW()

        WHERE code = $1

    ";


    pg_query_params(

        $conn,

        $sql_update,

        array($code_concepteur)

    );

}


// =================================================
// UTILISATION DU CODE ADMIN
// =================================================

if (
    $role == "Admin" &&
    $type_admin == "secondaire"
) {


    $sql_update = "

        UPDATE code_admin

        SET

            utilise = true,

            date_utilisation = NOW()

        WHERE code = $1

    ";


    pg_query_params(

        $conn,

        $sql_update,

        array($code_admin)

    );

}


// =================================================
// MESSAGE FINAL
// =================================================

if ($role == "Etudiant") {


    $message =

        "Compte étudiant créé avec succès.

        Vous pouvez maintenant vous connecter et compléter votre profil.";


}


elseif ($role == "Recruteur") {


    $message =

        "Votre compte recruteur a été créé.

        Il est en attente de validation par l'administrateur principal.";


}


elseif (
    $role == "Admin" &&
    $type_admin == "principal"
) {


    $message =

        "Compte administrateur principal créé avec succès.";


}


elseif (
    $role == "Admin" &&
    $type_admin == "secondaire"
) {


    $message =

        "Compte administrateur secondaire créé.

        Il est en attente d'activation par l'administrateur principal.";


}


else {


    $message =

        "Compte créé avec succès.";

}


// =================================================
// MESSAGE FINAL + REDIRECTION
// =================================================

echo "

<script>

alert(" . json_encode($message) . ");

window.location='login_public.php';

</script>

";


exit();

?>
