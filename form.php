<?php
include('Database.php');
$dbform = new BDD('randoUHA');
$dbform->open();

// gestion mail
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\SMTP;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';



$name = $_POST['nom'];
$surname = $_POST['prenom'];
$mail = $_POST['mail'];
$num = $_POST['tel'];
$excursion = trim($_POST['excursion']);
$date = $_POST['date'];

$places = $dbform->excursion_places_date($excursion, $date);

if ((($name != '') && ($surname != '')) && (($mail != '') && ($num != ''))) {
	if (!(strpos($mail, '@') && strpos($mail, '.'))) {
		echo "<p class='error'>E-mail invalide!</p>";
	} else if ($places[0] >= $places[1]) {
		echo "<p class='error'>Il n'y a plus de place libre!</p>";
	} else {
		$dbform->inscrire($excursion, [$name, $surname, $num, $mail], $date);
		echo "<h5 class='margin-top'>Merci de vous êtes inscrit !</h5>
			<pre class='color_white'>Un mail de confirmation vous a été transmit, 
si vous le souhaitez, vous pouvez effectuer
une autre inscription ou 
fermer cette fenêtre.</pre>";


		$mail = new PHPMailer(true);

		try {
			//Server settings
			$mail->CharSet = 'UTF-8';
			// $mail->SMTPDebug = SMTP::DEBUG_SERVER;//Enable verbose debug output
			$mail->isSMTP();									//Send using SMTP
			$mail->Host       = 'smtp.gmail.com';				//Set the SMTP server to send through
			$mail->SMTPAuth   = true;							//Enable SMTP authentication
			$mail->Username   = 'antoine.reib3l@gmail.com';	//SMTP username
			$mail->Password   = 'sghutxstnoxheucn';			//SMTP password
			$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;	//Enable implicit TLS encryption
			$mail->Port       = 465;							//TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

			//Recipients
			$mail->setFrom('antoine.reib3l@gmail.com', 'SUAPS');
			$mail->addAddress($_POST["mail"], 'SUAPS');	//Add a recipient

			//Attachments

			//Content
			$mail->isHTML(true);								//Set email format to HTML
			$mail->Subject = 'confirmation d\'inscription';
			$mail->Body = 'Bonjour ' . $_POST['prenom'] . ',<br><br>' . 'Nous vous confirmons votre inscription à l\'excursion : ' . $excursion . ' le ' . $date . '<br>' . 'Merci de votre participation.<br><br>' . 'Cordialement,<br>' . 'Le SUAPS';

			$mail->send();
		} catch (Exception $e) {
			echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
		}
	}
} else {
	echo "<p class='error'>Veuillez remplir tout les champs pour vous incrire</p>";
}

$dbform->close();


?>

<?php
