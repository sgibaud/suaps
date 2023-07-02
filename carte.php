<?php 

require "./Database.php";
$database = new BDD("randoUHA");
$database->open();
$nom = $database->type_get($_GET["randonnee"])
?>

<!-- Carte Visorando -->
<iframe height="500" width="500" frameborder="0" scrolling="no"src="https://www.visorando.com/index.php?component=externe&task=showCarte&idRandonnee=<?php echo $nom["carte"] ?>&satellite=1&carte=1&navigation=1&panZoom=1&mousePosition=1&scaleLine=1"></iframe>

<!-- Fin carte Visorando -->