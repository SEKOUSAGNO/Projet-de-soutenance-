<?php

if(isset($_SESSION['erreur'])){

?>

<div class="alert alert-danger">

<?= $_SESSION['erreur']; ?>

</div>

<?php

unset($_SESSION['erreur']);

}

?>