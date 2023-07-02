<?php

require "./Database.php";

$database = new BDD("randoUHA");
$database->open();

$typeExcursion = $database->type_get($_GET['randonnee']);
$placeDispo = $database->excursion_places($typeExcursion["idType"]);
//echo $placeDispo['0'] . '/' . $placeDispo['1'];
$dates = $database->excursions_dates_antoine($typeExcursion["idType"]);
// $photos = $database->get_photos($typeExcursion["idType"]);

// if (isset($_POST['nom'])) {
// 	$database->inscription($typeExcursion["nomExcursion"]);
// }

//Conversion durée au bon format (3H30)
$dureeDB = $typeExcursion["duree"];
$duree = DateTime::createFromFormat('H:i:s', $dureeDB);
$dureeFormatee = $duree->format('G\Hi');

// popup Form
$excursion = $typeExcursion["nomExcursion"];

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<?php include 'component/head.php'; ?>
</head>

<body>
	<header class="wave mask1">
		<?php include 'component/nav_hiking.php'; ?>
		<div id="align_right">
			<h1 id="nomexcursion">
				<?php echo $typeExcursion["nomExcursion"]; ?>
			</h1>
		</div>
	</header>
	<section class="main front">
		<article class="info">
			<div class="col_info article_front">
				<div class="icon_hiking">
					<img class="icon_2" src="img/hiking_green.svg">
					<p class="p_icon color_green">
						<?php echo $typeExcursion['nbMaxParticipants'] ?>
					</p>
				</div>
				<div class="icon_hiking">
					<img class="icon_2" src="img/time_green.svg">
					<p class="p_icon color_green">
						<?php echo $dureeFormatee ?>
					</p>
				</div>
				<div class="icon_hiking">
					<img class="icon_3" src="img/hiking_guide.svg">
					<p class="p_icon color_green"></p>
				</div>
			</div>
		</article>
		<div class="price">
			<div class="align_article">
				<p class="price_font">
					<?php echo $typeExcursion["tarif"]; ?> €
				</p>
				<button id="btn_reserve" class="button" onclick="popUpForm()" value="1">RÉSERVER</button>
			</div>
		</div>
	</section>

	<main class="main margin-bottom">
		<section class="align_column col_5 margin_left">
			<article class="margin-bottom col_5">
				<h2>La randonnée</h2>
				<p>
					<?php echo $typeExcursion["description"]; ?>
				</p>
			</article>
			<article class="margin-top-1">
				<h2>Informations</h2>
				<p> <b>Depart à
						<?php if ($typeExcursion["idLieuDepart"] == 1) {
							echo " Colmar";
						} else {
							echo " Mulhouse";
						}  ?>, retour à
						<?php if ($typeExcursion["idLieuArrivee"] == 1) {
							echo " Colmar";
						} else {
							echo " Mulhouse";
						}  ?>.</b></p>
				<p class="margin-top_input">
					Prévoir un pique-nique et des bouteilles d'eau. Les randonnées avec retour le lendemain incluent une nuit dans un gîte et un petit-déjeuner pour un supplément de <b>20€</b> par personnes.
				</p>
			</article>
		</section>
		<aside>
			<img class="img_icon carte" onclick="mod()" src="img/map.svg" alt="">
			<img class="img_icon dates" onclick="popUpDate()" src="img/calendar.svg" alt="">
		</aside>
	</main>
	<section class="main">
		<!-- <?php foreach ($photos as $photo) { ?>
			<a href="<?php echo $photo['cheminPhoto'] ?>"><img class="img_hiking margin_right_left" src=" <?php echo $photo['cheminPhoto'] ?> " alt=" <?php echo $photo['cheminPhoto'] ?> " </a>
			<?php } ?> -->
		<a href="photo/<?php echo $typeExcursion["idType"] ?>_01.jpg"><img class="img_hiking margin_right_left" src="photo/<?php echo $typeExcursion["idType"] ?>_01.jpg" alt=""></a>
		<a href="photo/<?php echo $typeExcursion["idType"] ?>_02.jpg"><img class="img_hiking margin_right_left" src="photo/<?php echo $typeExcursion["idType"] ?>_02.jpg" alt=""></a>
		<a href="photo/<?php echo $typeExcursion["idType"] ?>_03.jpg"><img class="img_hiking margin_right_left" src="photo/<?php echo $typeExcursion["idType"] ?>_03.jpg" alt=""></a>
	</section>
	<?php include 'component/footer.php'; ?>

	<!-- modal carte -->
	<div class="popUpCarte">
		<!-- Carte Visorando -->
	</div>

	<!-- modal dates -->
	<div class="popUpDates">
		<div class="align_column">
			<h2 class="margin-bottom color_white">Dates disponibles</h2>
			<div class="align_column">
				<?php foreach ($dates as $date) {
					$placeDispo = $database->excursion_places($date["idExcursion"]);
					$participants = $placeDispo[0];
					$maximum = $placeDispo[1];
					if ($date[0] == $date[1]) {
						echo '<div>
						<p>' . '<b>Départ :</b></p>' . '<p>' . $date[0] . '</p>' .
							'<p><b>Nombre de participants : </b>' . $participants  . '/' . $maximum . '</p></div>
					<img id="barre_w" src="img/barre_white.svg">';
					} else {
						echo 	'
					<div>
								<div>
									<p>' . '<b>Départ : </b></p>' .
							'<p>' . $date[0] . '</p>
								</div>' .
							'<div class="align_column margin-top-carte">
									<p>' . '<b>Retour : </b></p>' . '<p>' . $date[1] .  '</p>' .
							'<p><b>Nombre de participants : </b>' . $participants  . '/' . $maximum . '</p></div>
							</div>
							<img id="barre_w" src="img/barre_white.svg">';
					}
				};
				?>
			</div>
		</div>
	</div>

	<!-- modal inscription -->
	<div class="popUpForm" style="display: none;">
		<div class="form">
			<input class="margin-bottom-1" type="text" id="nomform" name="user_name" placeholder="Nom">
			<br>
			<input class="margin-bottom-1" type="text" id="prenomform" name="user_surname" placeholder="Prénom">
			<br>
			<input class="margin-bottom-1" type="text" id="mailform" name="user_mail" placeholder="mail">
			<br>
			<input class="margin-bottom-1" type="number" id="telform" name="user_num" placeholder="Numéro de telephone">
			<br>
			<select name="dates" id="datesform">
				<?php
				$dattt = $database->excursions_dates($excursion);
				for ($i = 0; $i < sizeof($dattt); $i++) {
					echo '
        <option value="' . $dattt[$i][1] . '"class="options_date">
        Du ' . $dattt[$i][1] . ' au ' . $dattt[$i][2] . '
        </option>
        ';
				}
				?>
			</select>
			<button name='Creation' class="btn_data" onclick="reserver()">S'inscrire</button>
			<div class='message' id="msgform">
			</div>
		</div>
	</div>
</body>

</html>