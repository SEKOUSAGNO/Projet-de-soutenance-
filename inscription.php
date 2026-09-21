<!DOCTYPE html>
<html lang="fr">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Créer un compte - SIMAN-JOB</title>


<style>

/* =========================================================
   RESET GENERAL
========================================================= */

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}


html{
    scroll-behavior:smooth;
}


body{

    font-family:"Segoe UI", Arial, Helvetica, sans-serif;

    min-height:100vh;

    color:#1f2937;

    background:
        radial-gradient(circle at top left, #ede9fe 0%, transparent 35%),
        radial-gradient(circle at bottom right, #ddd6fe 0%, transparent 30%),
        #f8fafc;

}


/* =========================================================
   HEADER
========================================================= */

.header{

    width:100%;

    background:
        linear-gradient(
            135deg,
            #3b0764,
            #5b21b6,
            #7c3aed
        );

    color:white;

    padding:18px 35px;

    box-shadow:0 4px 20px rgba(0,0,0,0.15);

}


.header-container{

    max-width:1250px;

    margin:auto;

    display:flex;

    justify-content:space-between;

    align-items:center;

    gap:25px;

}


/* LOGO TEXTE */

.logo{

    font-size:29px;

    font-weight:800;

    letter-spacing:1px;

    white-space:nowrap;

}


.logo span{

    color:#facc15;

}


/* TEXTE HEADER */

.header-text{

    font-size:14px;

    color:#f3e8ff;

    text-align:right;

}


.header-text strong{

    font-weight:600;

}


/* =========================================================
   CONTENU PRINCIPAL
========================================================= */

.main{

    width:100%;

    max-width:1180px;

    margin:0 auto;

    padding:35px 20px 0;

}


/* =========================================================
   TITRE
========================================================= */

.titre{

    text-align:center;

    margin-bottom:28px;

}


.titre h1{

    font-size:34px;

    font-weight:800;

    color:#4c1d95;

    margin-bottom:8px;

    letter-spacing:-0.5px;

}


.titre p{

    font-size:15px;

    color:#64748b;

}


/* =========================================================
   ZONE INSCRIPTION
========================================================= */

.inscription-zone{

    display:grid;

    grid-template-columns:1fr 1.15fr;

    max-width:1050px;

    min-height:560px;

    margin:auto;

    background:white;

    border-radius:24px;

    overflow:hidden;

    box-shadow:
        0 20px 50px rgba(76,29,149,0.13);

    border:1px solid #eee7ff;

}


/* =========================================================
   PARTIE GAUCHE : LOGO / PRESENTATION
========================================================= */

.presentation{

    position:relative;

    padding:40px 35px;

    display:flex;

    flex-direction:column;

    align-items:center;

    justify-content:center;

    text-align:center;

    color:white;

    background:
        linear-gradient(
            145deg,
            #3b0764,
            #5b21b6 55%,
            #7c3aed
        );

    overflow:hidden;

}


/* Cercles décoratifs */

.presentation::before{

    content:"";

    position:absolute;

    width:220px;

    height:220px;

    border-radius:50%;

    background:rgba(255,255,255,0.07);

    top:-90px;

    left:-80px;

}


.presentation::after{

    content:"";

    position:absolute;

    width:260px;

    height:260px;

    border-radius:50%;

    background:rgba(255,255,255,0.06);

    bottom:-120px;

    right:-100px;

}


/* CONTENU PRESENTATION */

.presentation-content{

    position:relative;

    z-index:2;

}


/* LOGO IMAGE */

.logo-image{

    width:210px;

    height:210px;

    margin:0 auto 20px;

    background:white;

    border-radius:50%;

    display:flex;

    align-items:center;

    justify-content:center;

    padding:20px;

    box-shadow:

        0 10px 35px rgba(0,0,0,0.25);

}


.logo-image img{

    width:100%;

    height:100%;

    object-fit:contain;

}


/* TEXTE PRESENTATION */

.presentation h2{

    font-size:26px;

    margin-bottom:12px;

    font-weight:800;

}


.presentation h2 span{

    color:#facc15;

}


.presentation p{

    font-size:14px;

    line-height:1.7;

    color:#ede9fe;

    max-width:380px;

}


/* PETITS AVANTAGES */

.avantages{

    margin-top:24px;

    width:100%;

    max-width:350px;

}


.avantage{

    display:flex;

    align-items:center;

    gap:10px;

    text-align:left;

    padding:9px 0;

    color:#f5f3ff;

    font-size:13px;

}


.avantage-icon{

    width:28px;

    height:28px;

    border-radius:50%;

    background:rgba(255,255,255,0.15);

    display:flex;

    align-items:center;

    justify-content:center;

    color:#facc15;

    font-weight:bold;

    flex-shrink:0;

}


/* =========================================================
   PARTIE DROITE : FORMULAIRE
========================================================= */

.formulaire{

    padding:35px 42px;

    background:#ffffff;

}


/* TITRE FORMULAIRE */

.formulaire-header{

    margin-bottom:22px;

}


.formulaire-header h2{

    color:#3b0764;

    font-size:24px;

    font-weight:800;

    margin-bottom:5px;

}


.formulaire-header p{

    color:#64748b;

    font-size:13px;

    line-height:1.5;

}


/* =========================================================
   GROUPES DE FORMULAIRE
========================================================= */

.ligne{

    display:grid;

    grid-template-columns:1fr 1fr;

    gap:13px;

}


.form-group{

    margin-bottom:14px;

}


.form-group label{

    display:block;

    color:#374151;

    font-size:13px;

    font-weight:700;

    margin-bottom:6px;

}


.form-group label span{

    color:#7c3aed;

}


/* =========================================================
   INPUTS
========================================================= */

.input-container{

    position:relative;

}


.form-group input,
.form-group select{

    width:100%;

    height:43px;

    border:1px solid #d8dbe3;

    border-radius:8px;

    padding:0 13px;

    font-family:inherit;

    font-size:13px;

    color:#1f2937;

    background:#f9fafb;

    outline:none;

    transition:all .25s ease;

}


.form-group input::placeholder{

    color:#9ca3af;

}


.form-group input:hover,
.form-group select:hover{

    border-color:#a78bfa;

}


.form-group input:focus,
.form-group select:focus{

    border-color:#7c3aed;

    background:white;

    box-shadow:
        0 0 0 3px rgba(124,58,237,0.10);

}


/* =========================================================
   ICONE INPUT
========================================================= */

.input-icon{

    position:absolute;

    left:13px;

    top:50%;

    transform:translateY(-50%);

    font-size:15px;

    color:#7c3aed;

    pointer-events:none;

}


.input-with-icon{

    padding-left:40px !important;

}


/* =========================================================
   MOT DE PASSE
========================================================= */

.password-container{

    position:relative;

}


.password-container input{

    padding-right:45px;

}


.toggle-password{

    position:absolute;

    right:12px;

    top:50%;

    transform:translateY(-50%);

    border:none;

    background:transparent;

    color:#64748b;

    font-size:16px;

    cursor:pointer;

    padding:4px;

}


.toggle-password:hover{

    color:#6d28d9;

}


/* =========================================================
   BLOCS DYNAMIQUES
========================================================= */

.bloc{

    display:none;

    margin:3px 0 15px;

    padding:15px;

    border-radius:10px;

    background:#faf7ff;

    border:1px solid #e9ddff;

    animation:apparition .25s ease;

}


@keyframes apparition{

    from{

        opacity:0;

        transform:translateY(-5px);

    }

    to{

        opacity:1;

        transform:translateY(0);

    }

}


/* =========================================================
   INFORMATION
========================================================= */

.info{

    margin-top:8px;

    padding:9px 11px;

    border-radius:7px;

    background:#f3efff;

    border-left:3px solid #7c3aed;

    color:#5b21b6;

    font-size:11px;

    line-height:1.5;

}


/* =========================================================
   BOUTON
========================================================= */

button[type="submit"]{

    width:100%;

    height:46px;

    margin-top:5px;

    border:none;

    border-radius:9px;

    background:
        linear-gradient(
            135deg,
            #5b21b6,
            #7c3aed
        );

    color:white;

    font-family:inherit;

    font-size:14px;

    font-weight:700;

    letter-spacing:.2px;

    cursor:pointer;

    transition:all .25s ease;

    box-shadow:
        0 5px 14px rgba(91,33,182,0.20);

}


button[type="submit"]:hover{

    transform:translateY(-2px);

    box-shadow:
        0 8px 20px rgba(91,33,182,0.30);

}


button[type="submit"]:active{

    transform:translateY(0);

}


/* =========================================================
   LIEN CONNEXION
========================================================= */

.login-link{

    text-align:center;

    margin-top:17px;

    padding-top:15px;

    border-top:1px solid #eee;

    color:#64748b;

    font-size:13px;

}


.login-link a{

    color:#6d28d9;

    font-weight:700;

    text-decoration:none;

}


.login-link a:hover{

    color:#4c1d95;

    text-decoration:underline;

}


/* =========================================================
   SECTION CONTACT
========================================================= */

.contact-section{

    margin-top:40px;

    background:
        linear-gradient(
            145deg,
            #24103f,
            #35145c
        );

    color:white;

    padding:35px 25px 30px;

    border-radius:20px 20px 0 0;

}


.contact-container{

    max-width:1100px;

    margin:auto;

}


.contact-title{

    text-align:center;

    font-size:22px;

    margin-bottom:7px;

}


.contact-description{

    text-align:center;

    color:#d8d1e5;

    font-size:13px;

    margin-bottom:25px;

}


/* =========================================================
   CONTACTS
========================================================= */

.contacts{

    display:grid;

    grid-template-columns:
        repeat(4,1fr);

    gap:12px;

}


.contact-box{

    background:rgba(255,255,255,0.07);

    border:1px solid rgba(255,255,255,0.08);

    padding:16px 12px;

    border-radius:11px;

    text-align:center;

    transition:.25s;

}


.contact-box:hover{

    transform:translateY(-3px);

    background:rgba(255,255,255,0.11);

}


.contact-icon{

    font-size:22px;

    margin-bottom:7px;

}


.contact-box h3{

    font-size:13px;

    margin-bottom:5px;

}


.contact-box p{

    color:#d8d1e5;

    font-size:11px;

    line-height:1.5;

    word-break:break-word;

}


.contact-box a{

    color:#d8d1e5;

    text-decoration:none;

}


.contact-box a:hover{

    color:#facc15;

}


/* =========================================================
   RESEAUX SOCIAUX
========================================================= */

.socials{

    display:flex;

    justify-content:center;

    align-items:center;

    flex-wrap:wrap;

    gap:9px;

    margin-top:25px;

}


.socials a{

    padding:8px 15px;

    border:1px solid rgba(255,255,255,0.18);

    border-radius:20px;

    color:#eee;

    text-decoration:none;

    font-size:11px;

    transition:.25s;

}


.socials a:hover{

    background:white;

    color:#4c1d95;

}


/* =========================================================
   FOOTER
========================================================= */

.footer{

    text-align:center;

    padding:17px 15px;

    background:#180b29;

    color:#9f94ad;

    font-size:11px;

}


.footer strong{

    color:#eee;

}


/* =========================================================
   RESPONSIVE TABLETTE
========================================================= */

@media(max-width:900px){

    .inscription-zone{

        grid-template-columns:1fr;

        max-width:650px;

    }


    .presentation{

        padding:30px 25px;

    }


    .logo-image{

        width:150px;

        height:150px;

        margin-bottom:15px;

    }


    .presentation h2{

        font-size:22px;

    }


    .contacts{

        grid-template-columns:
            repeat(2,1fr);

    }

}


/* =========================================================
   RESPONSIVE MOBILE
========================================================= */

@media(max-width:600px){

    .header{

        padding:15px 18px;

    }


    .header-container{

        flex-direction:column;

        text-align:center;

        gap:5px;

    }


    .logo{

        font-size:25px;

    }


    .header-text{

        text-align:center;

        font-size:11px;

    }


    .main{

        padding:25px 12px 0;

    }


    .titre{

        margin-bottom:20px;

    }


    .titre h1{

        font-size:25px;

        line-height:1.2;

    }


    .titre p{

        font-size:13px;

    }


    .inscription-zone{

        border-radius:17px;

    }


    .presentation{

        padding:28px 20px;

    }


    .logo-image{

        width:130px;

        height:130px;

    }


    .presentation h2{

        font-size:21px;

    }


    .presentation p{

        font-size:12px;

    }


    .formulaire{

        padding:25px 18px;

    }


    .formulaire-header h2{

        font-size:21px;

    }


    .ligne{

        grid-template-columns:1fr;

        gap:0;

    }


    .contacts{

        grid-template-columns:1fr;

    }


    .contact-section{

        padding:30px 15px;

    }

}


/* =========================================================
   TRES PETITS ECRANS
========================================================= */

@media(max-width:380px){

    .titre h1{

        font-size:22px;

    }


    .formulaire{

        padding:22px 14px;

    }


    .presentation{

        padding:25px 15px;

    }

}

</style>

</head>


<body>


<!-- =========================================================
     HEADER
========================================================= -->

<header class="header">

    <div class="header-container">


        <div class="logo">

            SIMAN<span>-JOB</span>

        </div>


        <div class="header-text">

            <strong>
                Plateforme de recherche d'emploi et de stage
                de l'UKAG
            </strong>

        </div>


    </div>

</header>



<!-- =========================================================
     CONTENU PRINCIPAL
========================================================= -->

<main class="main">


    <!-- TITRE -->

    <div class="titre">

        <h1>
            BIENVENUE SUR LA TOILE SIMAN-JOB
        </h1>

        <p>
            Créez votre compte et rejoignez la plateforme
            de l'Université Kofi Annan de Guinée
        </p>

    </div>



    <!-- =====================================================
         ZONE PRINCIPALE
    ====================================================== -->

    <div class="inscription-zone">



        <!-- =================================================
             PRESENTATION / LOGO
        ================================================== -->

        <div class="presentation">

            <div class="presentation-content">


                <div class="logo-image">

                    <img
                        src="image/wc.png"
                        alt="Logo SIMAN-JOB"
                    >

                </div>


                <h2>

                    SIMAN<span>-JOB</span>

                </h2>


                <p>

                    La plateforme dédiée à la recherche
                    d'emploi et de stage de l'Université
                    Kofi Annan de Guinée.

                </p>



                <div class="avantages">


                    <div class="avantage">

                        <div class="avantage-icon">
                            ✓
                        </div>

                        <div>
                            Consultez les offres d'emploi et de stage
                        </div>

                    </div>



                    <div class="avantage">

                        <div class="avantage-icon">
                            ✓
                        </div>

                        <div>
                            Déposez facilement vos candidatures
                        </div>

                    </div>



                    <div class="avantage">

                        <div class="avantage-icon">
                            ✓
                        </div>

                        <div>
                            Connectez étudiants et recruteurs
                        </div>

                    </div>



                    <div class="avantage">

                        <div class="avantage-icon">
                            ✓
                        </div>

                        <div>
                            Facilitez votre recherche professionnelle
                        </div>

                    </div>


                </div>


            </div>

        </div>



        <!-- =================================================
             FORMULAIRE
        ================================================== -->

        <div class="formulaire">


            <div class="formulaire-header">

                <h2>
                    Créer un compte
                </h2>

                <p>
                    Remplissez les informations ci-dessous
                    pour rejoindre SIMAN-JOB.
                </p>

            </div>



            <form
                action="traitement_inscription.php"
                method="POST"
            >


                <!-- =================================================
                     NOM + PRENOM
                ================================================== -->

                <div class="ligne">


                    <!-- NOM -->

                    <div class="form-group">

                        <label for="nom">
                            Nom <span>*</span>
                        </label>

                        <div class="input-container">

                            <span class="input-icon">
                                👤
                            </span>

                            <input
                                type="text"
                                id="nom"
                                name="nom"
                                class="input-with-icon"
                                placeholder="Votre nom"
                                required
                            >

                        </div>

                    </div>



                    <!-- PRENOM -->

                    <div class="form-group">

                        <label for="prenom">
                            Prénom <span>*</span>
                        </label>

                        <div class="input-container">

                            <span class="input-icon">
                                👤
                            </span>

                            <input
                                type="text"
                                id="prenom"
                                name="prenom"
                                class="input-with-icon"
                                placeholder="Votre prénom"
                                required
                            >

                        </div>

                    </div>


                </div>



                <!-- =================================================
                     EMAIL
                ================================================== -->

                <div class="form-group">

                    <label for="email">
                        Adresse email <span>*</span>
                    </label>

                    <div class="input-container">

                        <span class="input-icon">
                            ✉
                        </span>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="input-with-icon"
                            placeholder="exemple@email.com"
                            required
                        >

                    </div>

                </div>



                <!-- =================================================
                     MOT DE PASSE
                ================================================== -->

                <div class="form-group">

                    <label for="password">
                        Mot de passe <span>*</span>
                    </label>


                    <div class="password-container">

                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Créez votre mot de passe"
                            required
                        >


                        <button
                            type="button"
                            class="toggle-password"
                            onclick="togglePassword()"
                            aria-label="Afficher le mot de passe"
                        >

                            👁

                        </button>


                    </div>

                </div>



                <!-- =================================================
                     ROLE
                ================================================== -->

                <div class="form-group">

                    <label for="role">
                        Type de compte <span>*</span>
                    </label>


                    <select
                        name="role"
                        id="role"
                        required
                    >

                        <option value="Etudiant">
                            Étudiant/E-diplomé/Chomage
                        </option>

                        <option value="Recruteur">
                            Recruteur
                        </option>

                        <option value="Admin">
                            Administrateur
                        </option>

                    </select>

                </div>



                <!-- =================================================
                     BLOC RECRUTEUR
                ================================================== -->

                <div
                    id="bloc_recruteur"
                    class="bloc"
                >

                    <div class="form-group">

                        <label for="code_accreditation">

                            🔐 Code d'accréditation recruteur

                        </label>


                        <input
                            type="password"
                            name="code_accreditation"
                            id="code_accreditation"
                            placeholder="Entrez votre code"
                        >


                        <div class="info">

                            🔒 Ce code est fourni par
                            l'administrateur de la plateforme.

                        </div>

                    </div>

                </div>



                <!-- =================================================
                     BLOC TYPE ADMIN
                ================================================== -->

                <div
                    id="bloc_type_admin"
                    class="bloc"
                >

                    <div class="form-group">

                        <label for="type_admin">

                            🛡️ Type d'administrateur

                        </label>


                        <select
                            name="type_admin"
                            id="type_admin"
                        >

                            <option value="">
                                -- Choisir le type --
                            </option>

                            <option value="principal">
                                Administrateur principal
                            </option>

                            <option value="secondaire">
                                Administrateur secondaire
                            </option>

                        </select>

                    </div>

                </div>



                <!-- =================================================
                     CODE CONCEPTEUR
                ================================================== -->

                <div
                    id="bloc_code_concepteur"
                    class="bloc"
                >

                    <div class="form-group">

                        <label for="code_concepteur">

                            🔑 Code concepteur

                        </label>


                        <input
                            type="password"
                            name="code_concepteur"
                            id="code_concepteur"
                            placeholder="Entrez le code concepteur"
                        >


                        <div class="info">

                            Le code concepteur est obligatoire
                            pour créer un administrateur principal.

                        </div>

                    </div>

                </div>



                <!-- =================================================
                     CODE ADMIN
                ================================================== -->

                <div
                    id="bloc_code_admin"
                    class="bloc"
                >

                    <div class="form-group">

                        <label for="code_admin">

                            🔐 Code administrateur

                        </label>


                        <input
                            type="password"
                            name="code_admin"
                            id="code_admin"
                            placeholder="Entrez le code administrateur"
                        >


                        <div class="info">

                            Ce code est généré par un administrateur
                            autorisé. Après création, votre compte
                            devra être activé par l'administrateur principal.

                        </div>

                    </div>

                </div>



                <!-- =================================================
                     BOUTON
                ================================================== -->

                <button type="submit">

                    Créer mon compte

                </button>


            </form>



            <!-- =================================================
                 CONNEXION
            ================================================== -->

            <div class="login-link">

                Vous avez déjà un compte ?

                <a href="login_public.php">
                    Se connecter
                </a>

            </div>


        </div>


    </div>



    <!-- =========================================================
         CONTACT
    ========================================================= -->

    <section class="contact-section">


        <div class="contact-container">


            <h2 class="contact-title">

                Contactez SIMAN-JOB

            </h2>


            <p class="contact-description">

                Pour toute information, assistance ou collaboration,
                notre équipe reste à votre disposition.

            </p>



            <div class="contacts">


                <!-- TELEPHONE -->

                <div class="contact-box">

                    <div class="contact-icon">
                        📞
                    </div>

                    <h3>
                        Téléphone
                    </h3>

                    <p>
                       contacts: +224 818 194
                    </p>

                    <p>
                        +224 655 095 458
                    </p>

                    <p>
                        +224 666 234 610
                    </p>

                </div>



                <!-- EMAIL -->

                <div class="contact-box">

                    <div class="contact-icon">
                        📧
                    </div>

                    <h3>
                        Email
                    </h3>

                    <p>

                        <a href="mailto:siman-job@gmail.com">

                            siman-job@gmail.com

                        </a>

                    </p>

                </div>



                <!-- SITE -->

                <div class="contact-box">

                    <div class="contact-icon">
                        🌐
                    </div>

                    <h3>
                        Site web
                    </h3>

                    <p>

                        <a
                            href="#"
                            target="_blank"
                        >

                            www.siman-job.com

                        </a>

                    </p>

                </div>



                <!-- ADRESSE -->

                <div class="contact-box">

                    <div class="contact-icon">
                        📍
                    </div>

                    <h3>
                        Adresse
                    </h3>

                    <p>
                        Nongo, Commune de Ratoma
                    </p>

                    <p>
                        République de Guinée
                    </p>

                    <p>
                        Université Kofi Annan de Guinée
                    </p>

                </div>


            </div>



            <!-- =================================================
                 RESEAUX
            ================================================== -->

            <div class="socials">


                <a href="#" target="_blank">

                    WhatsApp : 624 81 81 94

                </a>


                <a href="#" target="_blank">

                    Facebook

                </a>


                <a href="#" target="_blank">

                    GitHub

                </a>


                <a href="#" target="_blank">

                    Bibliographie

                </a>


            </div>


        </div>

    </section>


</main>



<!-- =========================================================
     FOOTER
========================================================= -->

<footer class="footer">

    <strong>

        © 2026 SIMAN-JOB

    </strong>

    — Plateforme de recherche d'emploi et de stage de l'UKAG.

</footer>



<script>

/* =========================================================
   ELEMENTS
========================================================= */

const role =
    document.getElementById("role");

const blocRecruteur =
    document.getElementById("bloc_recruteur");

const blocTypeAdmin =
    document.getElementById("bloc_type_admin");

const typeAdmin =
    document.getElementById("type_admin");

const blocConcepteur =
    document.getElementById("bloc_code_concepteur");

const blocAdmin =
    document.getElementById("bloc_code_admin");

const codeRecruteur =
    document.getElementById("code_accreditation");

const codeConcepteur =
    document.getElementById("code_concepteur");

const codeAdmin =
    document.getElementById("code_admin");


/* =========================================================
   VERIFIER ROLE
========================================================= */

function verifierRole(){


    /* Cacher les blocs */

    blocRecruteur.style.display = "none";

    blocTypeAdmin.style.display = "none";

    blocConcepteur.style.display = "none";

    blocAdmin.style.display = "none";


    /* Réinitialiser required */

    codeRecruteur.required = false;

    codeConcepteur.required = false;

    codeAdmin.required = false;

    typeAdmin.required = false;



    /* =====================================================
       RECRUTEUR
    ===================================================== */

    if(role.value === "Recruteur"){

        blocRecruteur.style.display = "block";

        codeRecruteur.required = true;

    }



    /* =====================================================
       ADMINISTRATEUR
    ===================================================== */

    else if(role.value === "Admin"){

        blocTypeAdmin.style.display = "block";

        typeAdmin.required = true;

    }

}



/* =========================================================
   VERIFIER TYPE ADMIN
========================================================= */

function verifierTypeAdmin(){


    blocConcepteur.style.display = "none";

    blocAdmin.style.display = "none";


    codeConcepteur.required = false;

    codeAdmin.required = false;



    /* ADMIN PRINCIPAL */

    if(typeAdmin.value === "principal"){

        blocConcepteur.style.display = "block";

        codeConcepteur.required = true;

    }



    /* ADMIN SECONDAIRE */

    else if(typeAdmin.value === "secondaire"){

        blocAdmin.style.display = "block";

        codeAdmin.required = true;

    }

}



/* =========================================================
   AFFICHER / CACHER MOT DE PASSE
========================================================= */

function togglePassword(){

    const password =
        document.getElementById("password");

    const icon =
        document.querySelector(".toggle-password");


    if(password.type === "password"){

        password.type = "text";

        icon.textContent = "🙈";

    }

    else{

        password.type = "password";

        icon.textContent = "👁";

    }

}



/* =========================================================
   EVENEMENTS
========================================================= */

role.addEventListener(
    "change",
    verifierRole
);


typeAdmin.addEventListener(
    "change",
    verifierTypeAdmin
);


/* =========================================================
   INITIALISATION
========================================================= */

verifierRole();

</script>


</body>

</html>
