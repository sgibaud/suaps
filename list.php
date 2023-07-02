<?php

require "./Database.php";

$database = new BDD("randoUHA");
$database->open();
$typesExcursions = $database->typesExcursions();

// var_dump($typesExcursions);

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<?php include 'component/head.php'; ?>
</head>

<body>
	<header class="wave mask1">
		<?php include 'component/nav_list.php'; ?>
		<div id="align_right">
			<h1>Nos randonnées</h1>
			<p class="intro">Envie de partir en randonnée pour explorer de superbes coins ? Dans cette liste, nous avons sélectionné les meilleurs chemins pour vos balades. Découvrez les photos et les descriptifs et trouvez toutes les promenades qui répondent à vos besoins.</p>
		</div>
	</header>
	<main class="main_list main_2">
		<section class="align_column form" id="formulaire">
			<article class="align_column article_brown padding">
				<h4 class="form_title">
					Lieux / Dates
				</h4>
				<div class="align_column">
					<label>Rechercher une excursion</label>
					<div class="align_row">
						<input id="recherche" list="excursions" name="excursion" class="data input_data">
						<datalist id="excursions">
							<?php
							for ($i = 0; $i < sizeof($typesExcursions); $i++) {

								echo '<option value="' . $typesExcursions[$i]['nomExcursion'] . '"></option>';
							}

							?>
						</datalist>
						<button id="boutonrech" class="btn_data">OK</button>
					</div>
					<label>A partir du</label>
					<input type="date" class="data" id="date" default="01/01/0001">
					<label>Lieu de départ</label>
					<div class="align_row margin-top_input">
						<input type="radio" class="data" name="region" value="1">
						<p class="margin_left">Colmar</p></input>
					</div>
					<div class="align_row margin-top_input">
						<input type="radio" class="data" name="region" value="2">
						<p class="margin_left">Mulhouse</p></input>
					</div>
				</div>
				<div>
					<h4 class="form_title">Budget</h4>
					<label>Tarif maximum</label>
					<p class="align_row">
						<input type="range" class="data data_list" min="0" max="100" value="0" id="tarif" oninput="this.nextElementSibling.value = this.value">
						<output class="margin_left">0</output>
					</p>
				</div>
				<div class="align_column">
					<h4 class="form_title">Participants</h4>
					<label>Nombre de participants minimum</label>
					<p class="align_row">
						<input type="range" class="data data_list" min="1" max="20" value="1" id="participants" oninput="this.nextElementSibling.value = this.value">
						<output class="margin_left">0</output>
					</p>
				</div>
			</article>
		</section>
		<section id="resultats" class="section_column">
			<?php foreach ($typesExcursions as $typeExcursion) {
				$dureeDB = $typeExcursion['duree'];
				$duree = DateTime::createFromFormat('H:i:s', $dureeDB);
				$dureeFormatee = $duree->format('G\Hi'); ?>
				<div class="list margin-bottom align_row">
					<img src="<?php echo $typeExcursion['cheminPhoto'] ?>" class="margin-right max_width" alt="">
					<article class="color_bk_green justify_evenly">
						<div class="align_row">
							<div class="align_column align_space padding_1">
								<div>
									<h2 class="h_list"><?php echo $typeExcursion['nomExcursion'] ?></h2>
								</div>
								<div class="align_row">
									<div class="icon_hiking">
										<img class="icon_2" src="img/hiking_green.svg">
										<p class="p_icon"><?php echo $typeExcursion['nbMaxParticipants'] ?></p>
									</div>
									<div class="icon_hiking">
										<img class="icon_2" src="img/time_green.svg">
										<p class="p_icon"><?php echo $dureeFormatee ?></p>
									</div>
								</div>
							</div>
							<div class="align_column price_list">
								<p class="price_font_2"><?php echo $typeExcursion['tarif'] ?> €</p>
								<a href="hiking.php?randonnee=<?php echo $typeExcursion['nomExcursion'] ?>"><button class="button_2">En savoir plus</button></a>
							</div>
						</div>
					</article>
				</div>

			<?php } ?>
		</section>
	</main>
	<?php require 'component/footer.php'; ?>

	<script>
		document.getElementById("boutonrech").onclick = function() {
			v = document.getElementById("recherche").value;
			window.location = "./hiking.php?randonnee=" + v;
		}

		document.getElementById("formulaire").onchange = function() {

			dep = document.getElementById("date").value;
			if (dep == "") {
				dep = "all";
			}

			reg = document.getElementsByName("region");
			switch (true) {
				case (reg[0].checked):
					reg = "1";
					break;
				case (reg[1].checked):
					reg = "2";
					break;
				default:
					reg = "all";
					break;

			}


			tar = document.getElementById("tarif").value;
			if (tar == 0) {
				tar = "all";
			}

			part = document.getElementById("participants").value;
			if (part == 1) {
				part = "all";
			}


			$.ajax({
				dataType: 'html',
				cache: false,
				type: 'POST',
				url: 'listdata.php',
				data: {
					depart: dep,
					region: reg,
					tarif: tar,
					participants: part
				},
				success: function(data) {
					document.getElementById("resultats").innerHTML = data;
				}
			});
		}
	</script>

</body>

</html>