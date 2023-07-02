<?php

require "./Database.php";

$database = new BDD("randoUHA");
$database->open();
$typesExcursions = $database->typesExcursionsC($_POST["depart"], $_POST["region"], $_POST["tarif"], $_POST["participants"]);

if ($typesExcursions == NULL) {
	echo '
            <div class="list margin-bottom align_row">
                <article class="color_bk_green justify_evenly">
                    <div class="align_row">
                        <div class="align_column align_space padding_1">
                            <div>
                                <h2 class="h_list margin margin_left">Aucune excursion trouvée</h2>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
            ';
}

foreach ($typesExcursions as $typeExcursion) {
	$dureeDB = $typeExcursion["duree"];
	$duree = DateTime::createFromFormat('H:i:s', $dureeDB);
	$dureeFormatee = $duree->format('G\Hi');
	echo '
			<div class="list margin-bottom align_row">
				<img src=" ' . $typeExcursion['cheminPhoto'] . '" class="margin-right max_width" alt="">
				<article class="color_bk_green justify_evenly">
					<div class="align_row">
						<div class="align_column align_space padding_1">
							<div>
								<h2 class="h_list">' . $typeExcursion['nomExcursion'] . '</h2>
							</div>
							<div class="align_row">
								<div class="icon_hiking">
									<img class="icon_2" src="img/hiking_green.svg">
									<p class="p_icon">' . $typeExcursion['nbMaxParticipants'] . ' </p>
								</div>
								<div class="icon_hiking">
									<img class="icon_2" src="img/time_green.svg">
									<p class="p_icon">' . $dureeFormatee . ' </p>
								</div>
							</div>
						</div>
						<div class="align_column price_list">
							<p class="price_font_2">' . $typeExcursion['tarif'] . ' €</p>
							<a href="hiking.php?randonnee=' . $typeExcursion['nomExcursion'] . ' "><button class="button_2">En savoir plus</button></a>
						</div>
					</div>
				</article>
			</div>
            ';
}
