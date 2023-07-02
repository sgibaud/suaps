<?php
require "./Database.php";

$database = new BDD("randoUHA");
$database->open();
$prochainesRandonnees = $database->prochaines_randonnes_5();


?>


<!DOCTYPE html>
<html lang="en">

<head>
	<?php include 'component/head.php'; ?>
</head>

<body>
	<header class="wave mask1">
		<?php include 'component/nav.php'; ?>
		<div id="align_right">
			<!-- <h1>Tous les chemins</h1>
			<p class="intro">C’est bénéficier d’un encadrement de qualité afin d’optimiser la découverte d’une région, d’un pays. C’est aussi vivre votre randonnée en groupe : partager des émotions, des sensations et des découvertes. La Pèlerine en accompagné, le plaisir partagé.
			</p> -->
			<h1>L'Alsace à pied</h1>
			<p class="intro">Partez à la découverte de l'Alsace. Nos guides expérimentés vous accompagneront tout au long du parcours, vous faisant découvrir la beauté naturelle et l'histoire fascinante de la région. Réservez votre aventure dès aujourd'hui et vivez des moments inoubliables en explorant les trésors de l'Alsace !
			</p>
		</div>
	</header>

	<main class="main_2">
		<section>
			<article class="article_green align_article margin_right_left box-shadow">
				<img class="img_article" src="img/rando_1.webp">
				<div class="pad_article align_column">
					<h3 class="margin-bottom-1">Idées de voyages</h3>

				</div>
			</article>
			<article class="article_green align_article margin_right_left box-shadow">
				<img class="img_article" src="img/rando_2.webp">
				<div class="pad_article align_column">
					<h3 class="margin-bottom-1">Conseils</h3>

				</div>
			</article>
			<article class="article_green align_article margin_right_left box-shadow">
				<img class="img_article" src="img/bag.webp">
				<div class="pad_article align_column">
					<h3 class="margin-bottom-1">Nos actualités</h3>

				</div>
			</article>
		</section>
		<img id="barre" src="img/barre.svg">
		<section class="align_column">
			<h2 class="h2_margin">Nos randonnées du moment</h2>
			<div class="info justify">

				<?php foreach ($prochainesRandonnees as $prochaineRandonnee) {
					$dureeDB = $prochaineRandonnee["duree"];
					$duree = DateTime::createFromFormat('H:i:s', $dureeDB);
					$dureeFormatee = $duree->format('G\Hi'); ?>
					<a href="hiking.php?randonnee=<?php echo $prochaineRandonnee['nomExcursion'] ?>">
						<article class="article_green col_2 margin_right_left article_height">
							<img class="img_article img_index" src=" <?php echo $prochaineRandonnee['cheminPhoto'] ?>">
							<div class="pad_justify">
								<div class="pad_hiking">
									<h5> <?php echo $prochaineRandonnee['nomExcursion'] ?> </h5>
								</div>
								<div class="pad_hiking">
									<div class="info">
										<img class="icon" src="img/hiking.svg">
										<p><?php echo $prochaineRandonnee['nbMaxParticipants'] ?></p>
									</div>
									<div class="info">
										<img class="icon" src="img/time.svg">
										<p> <?php echo $dureeFormatee ?></p>
									</div>
								</div>
							</div>
						</article>
					</a>
				<?php } ?>
			</div>
		</section>
	</main>
	<?php require 'component/footer.php'; ?>
</body>

</html>