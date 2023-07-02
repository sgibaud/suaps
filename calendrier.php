<?php

require "./Database.php";

?>

<!DOCTYPE html>
<html lang="en">


<head>
	<?php include 'component/head_calendar.php'; ?>
</head>

<body>
	<header class="wave mask1">
		<?php include 'component/nav_calendar.php'; ?>
		<div id="align_right">
			<h1>
				Calendrier
			</h1>
			<p class="intro">A partir du calendrier, choissisez la date qui vous convient et explorer les différentes balades que nous vous proposons !
			</p>
		</div>
	</header>

	<main class="margin-bottom">
		<div id='calendar'>all</div>
	</main>

	<?php include 'component/footer.php'; ?>

</body>

<script src="js/calendrier.js"></script>

</html>