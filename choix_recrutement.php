<?php

session_start();

if(!isset($_POST['mode'])){
    header("Location: publier_offre.php");
    exit();
}

$mode = $_POST['mode'];

if($mode=="Email"){

    header("Location: publier_offre_email.php");

}else{

    header("Location: publier_offre_plateforme.php");

}

exit();

?>