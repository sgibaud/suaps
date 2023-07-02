<?php
include ('Database.php');

$nomEx =  $_POST['nomEx'] ;

$db = new BDD('randoUHA');

$db->open();

$res = $db->excursions_dates($nomEx);


for($i=0;$i<sizeof($res);$i++){

    echo $res[$i][0].'$'.$res[$i][1].'$'.$res[$i][2].'/';
};

?>