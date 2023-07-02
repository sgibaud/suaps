<?php
// // gestion mail
// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;
// use PHPMailer\SMTP;

// require 'PHPMailer/src/Exception.php';
// require 'PHPMailer/src/PHPMailer.php';
// require 'PHPMailer/src/SMTP.php';





class BDD
{

	//attributs -----------------------------------------------------------------------------------------------------------
	private $name;
	private $db;

	//constructeur ---------------------------------------------------------------------------------------------------------
	public function __construct($a)
	{
		$this->name = $a;
	}

	//getter et setter -----------------------------------------------------------------------------------------------------
	public function getName()
	{
		return $this->name;
	}

	// tostring -------------------------------------------------------------------------------------------------------------
	public function __toString()
	{
		return "La base de données : " . $this->name;
	}

	//Ouverture/fermeture ----------------------------------------------------------------------------------------------------

	// Ouverture de la BDD

	public function open()
	{
		try {
			$d = new PDO('mysql:host=localhost;port=3306;dbname=' . $this->name . ';charset=utf8', 'mariadb', 'mariadb*1');
		} catch (Exception $e) {
			die('Erreur : ' . $e->getMessage());
		}

		$this->db = $d;
	}

	// Fermeture de la BDD

	public function close()
	{
		$this->db = NULL;
	}

	//Fonctions recupération de données ----------------------------------------------------------------------------------------------------

	//    Tous les types d'excursions avec une photo
	public function typesExcursions()
	{
		$query = 'SELECT * FROM TypesExcursions
	INNER JOIN Illustrations
		ON Illustrations.idType = TypesExcursions.idType
	INNER JOIN Photos
		ON Illustrations.idPhoto = Photos.idPhoto
	GROUP BY TypesExcursions.idType';
		$Statement = $this->db->prepare($query);
		$Statement->execute([]);
		return $Statement->fetchAll();
	}


    //    Certains types d'excursions avec une photo
    public function typesExcursionsC($d,$r,$t,$p)
    {
        $search = '';
        if ((($d != 'all')||($r != 'all'))||(($t != 'all')||($p != 'all'))){
            $search = 'WHERE ';
            if($d != 'all'){
                $search = $search."dateDepart >= '".$d."' ";
            }
            if($r != 'all'){
                if($search != 'WHERE '){
                    $search = $search.'AND ';
                }
                $search = $search."idLieuDepart >= ".$r." ";
            }
            if($t != 'all'){
                if($search != 'WHERE '){
                    $search = $search.'AND ';
                }
                $search = $search."tarif <= ".$t." ";
            }
            if($p != 'all'){
                if($search != 'WHERE '){
                    $search = $search.'AND ';
                }
				$search = $search."nbMaxParticipants >= ".$p." ";
            }

        }

        $query = 'SELECT * FROM TypesExcursions
        INNER JOIN Excursions
        ON Excursions.idType = TypesExcursions.idType
        INNER JOIN Illustrations
        ON Illustrations.idType = TypesExcursions.idType
        INNER JOIN Photos
		ON Illustrations.idPhoto = Photos.idPhoto
        '.
        $search
        .'
        GROUP BY TypesExcursions.idType';
        $Statement = $this->db->prepare($query);
        $Statement->execute([]);
        return $Statement->fetchAll();
    }



	//Donne les cinq plus recentes excursions

	public function excursions5()
	{
		$query = $this->db->query('SELECT * FROM Excursions ORDER BY dateDepart ASC ;');

		$i = 0;
		$res = [0];

		if ($query) {
			foreach ($query as $row) {
				$res[$i] = $row;
				$i++;
				if ($i == 5) {
					break;
				}
			}
		}

		for ($i = 0; $i < 5; $i++) {
			$res[$i]['nom'] = $this->excursion_type($res[$i]['idType']);
		}

		for ($i = 0; $i < 5; $i++) {
			$res[$i]['places'] = $this->excursion_places($res[$i]['idExcursion']);
		}

		return $res;
	}


	// Donne les dates de toutes les excursions d'un type
	public function prochaines_randonnes_5()
	{
		$query = 'SELECT Excursions.*, TypesExcursions.*, Photos.*
		FROM Excursions
			INNER JOIN TypesExcursions ON TypesExcursions.idType = Excursions.idType
			INNER JOIN (
	    		SELECT Illustrations.idType, MIN(Photos.idPhoto) AS firstPhotoID
	    			FROM Illustrations
	    			INNER JOIN Photos ON Photos.idPhoto = Illustrations.idPhoto
	    		GROUP BY Illustrations.idType
			) 
		AS firstPhotos ON firstPhotos.idType = TypesExcursions.idType
			INNER JOIN Photos ON Photos.idPhoto = firstPhotos.firstPhotoID
			WHERE Excursions.dateDepart >= CURDATE()
	  			AND Excursions.dateDepart IN (
	    		SELECT MIN(dateDepart)
	    			FROM Excursions
	    			GROUP BY idType
				)
		ORDER BY Excursions.dateDepart ASC
			Limit 5;';

		$Statement = $this->db->prepare($query);
		$Statement->execute();
		return $Statement->fetchAll();
	}

	public function excursions_dates($type)
    {

        if (is_int($type)) {
            $query = $this->db->query('SELECT nomExcursion,dateDepart,dateRetour FROM TypesExcursions INNER JOIN Excursions ON TypesExcursions.idType = Excursions.idType WHERE idType = "' . $type . '" ORDER BY dateDepart ASC;');
        } else if ($type == 'all') {
            $query = $this->db->query('SELECT nomExcursion,dateDepart,dateRetour FROM TypesExcursions INNER JOIN Excursions ON TypesExcursions.idType = Excursions.idType ORDER BY dateDepart ASC;');
        } else {
            $query = $this->db->query('SELECT nomExcursion,dateDepart,dateRetour FROM TypesExcursions INNER JOIN Excursions ON TypesExcursions.idType = Excursions.idType WHERE nomExcursion = "' . $type . '" ORDER BY dateDepart ASC;');
        }

        $i = 0;
        $res = [0];

        if ($query) {
            foreach ($query as $row) {
                $res[$i] = $row;
                $i++;
            }
        }
        return $res;
    }

	

	public function excursions_dates_antoine($type)
	{
		$query = $this->db->query('SELECT DATE_FORMAT(dateDepart, "%d/%m/%Y") AS dateDepart, DATE_FORMAT(dateRetour, "%d/%m/%Y") AS dateRetour, idExcursion FROM Excursions WHERE Excursions.idType = ' . $type . ';');

		$res = [];

		if ($query) {
			foreach ($query as $row) {
				$res[] = $row;
			}
		}
		return $res;
	}

	//    METHODE ANTOINE: donne un type d'excursion par son nom.
	public function type_get($nom)
	{
		$query = 'SELECT * FROM TypesExcursions WHERE nomExcursion = :nomExcursion;';
		$Statement = $this->db->prepare($query);
		$Statement->execute([
			'nomExcursion' => $nom,
		]);

		$result = $Statement->fetch();

		if ($result === false) {
			// Aucun résultat trouvé, rediriger vers une autre page
			header("Location: erreur.php");
			exit; // Assurez-vous de terminer l'exécution du script après la redirection
		}

		return $result;
	}

	//    Donnes les photos d'un Type D'excursions
	public function get_photos($type)
	{
		$query = 'SELECT * FROM `Photos` INNER JOIN Illustrations ON Illustrations.idPhoto = Photos.idPhoto WHERE idType = :idType;';
		$Statement = $this->db->prepare($query);
		$Statement->execute([
			'idType' => $type,
		]);

		return $Statement->fetchAll();
	}

	// Donne un doublet [nombre de places prises,nombre de place total] pour une excursion

	public function excursion_places($id)
    {

        $num = 0;
        $max = 0;

        if(is_int($id)){
            $query = $this->db->query('SELECT nbMaxParticipants FROM TypesExcursions INNER JOIN Excursions ON TypesExcursions.idType = Excursions.idType WHERE idExcursion = "' . $id . '" ;');
            if ($query) {
                foreach ($query as $row) {
                    $max = $row[0];
                }
            }

            $query = $this->db->query('SELECT COUNT(*) FROM Inscriptions INNER JOIN Excursions ON Inscriptions.idExcursion = Excursions.idExcursion WHERE Inscriptions.idExcursion = "' . $id . '" ;');
            if ($query) {
                foreach ($query as $row) {
                    $num = $row[0];
                }
            }
        } else {
            $query = $this->db->query('SELECT nbMaxParticipants, Excursions.idType FROM TypesExcursions INNER JOIN Excursions ON TypesExcursions.idType = Excursions.idType WHERE nomExcursion = "' . $id . '" ;');
            if ($query) {
                foreach ($query as $row) {
                    $max = $row[0];
                    $id = $row[1];
                }
            }

            $query = $this->db->query('SELECT COUNT(*) FROM Inscriptions INNER JOIN Excursions ON Inscriptions.idExcursion = Excursions.idExcursion WHERE Inscriptions.idExcursion = "' . $id . '" ;');
            if ($query) {
                foreach ($query as $row) {
                    $num = $row[0];
                }
            }

        }

        return [$num, $max];
    }

	public function excursion_places_date($id,$d){

        $num = 0;
        $max = 0;

        if(is_int($id)){
            $query = $this->db->query('SELECT nbMaxParticipants FROM TypesExcursions INNER JOIN Excursions ON TypesExcursions.idType = Excursions.idType WHERE idExcursion = "' . $id . '" AND dateDepart = "'.$d.'";');
            if ($query) {
                foreach ($query as $row) {
                    $max = $row[0];
                }
            }

            $query = $this->db->query('SELECT COUNT(*) FROM Inscriptions INNER JOIN Excursions ON Inscriptions.idExcursion = Excursions.idExcursion WHERE Inscriptions.idExcursion = "' . $id . '" AND dateDepart = "'.$d.'" ;');
            if ($query) {
                foreach ($query as $row) {
                    $num = $row[0];
                }
            }
        } else {
            $query = $this->db->query('SELECT nbMaxParticipants, idExcursion FROM TypesExcursions INNER JOIN Excursions ON TypesExcursions.idType = Excursions.idType WHERE nomExcursion = "' . $id . '" AND dateDepart = "'.$d.'";');
            if ($query) {
                foreach ($query as $row) {
                    $max = $row[0];
                    $id = $row[1];
                }
            }
            
            $query = $this->db->query('SELECT COUNT(*) FROM Inscriptions INNER JOIN Excursions ON Inscriptions.idExcursion = Excursions.idExcursion WHERE Inscriptions.idExcursion = "' . $id . '" AND dateDepart = "'.$d.'" ;');
            if ($query) {
                foreach ($query as $row) {
                    $num = $row[0];
                }
            }

        }

        return [$num, $max];

    } 

	//Fonctions postage de données ----------------------------------------------------------------------------------------------------

	//Inscrit a l'esxcursion a la date la personne crée par le contenu du formulaire en deuxième position format: [nom,prenom,num,mail]

	public function inscrire($excursion, $form, $date)
    {
        $pdoStat = $this->db->prepare('INSERT INTO Participants(nomParticipant,prenomParticipant,numTelParticipant,mailParticipant) values ( :a, :b, :c, :d)');

        $pdoStat->execute([
            'a' => $form[0],      //nom
            'b' => $form[1],      //prenom
            'c' => $form[2],      //num de telephone
            'd' => $form[3],      //mail
        ]) or die(print_r($this->db->errorInfo()));

        $query = $this->db->query('SELECT idParticipant FROM Participants WHERE nomParticipant = "' . $form[0] . '" ;');
        if ($query) {
            foreach ($query as $row) {
                $id_part = $row[0];
            }
        }

        $query = $this->db->query('SELECT idExcursion FROM Excursions INNER JOIN TypesExcursions ON TypesExcursions.idType = Excursions.idType WHERE nomExcursion = "' . $excursion . '" AND Excursions.dateDepart = "'.$date.'" ;');
        if ($query) {
            foreach ($query as $row) {
                $id_exc = $row[0];
            }
        }
        if (($id_part) && ($id_exc)) {
            $pdoStat = $this->db->prepare('INSERT INTO Inscriptions(idExcursion,idParticipant,dateInscription) values ( :a, :b, :c)');

            $pdoStat->execute([
                'a' => $id_exc,
                'b' => $id_part,
                'c' => $date,
            ]) or die(print_r($this->db->errorInfo()));
        }
    }


	// méthode inscription
	// public function inscription($excursion) {
	// 	$query = "INSERT INTO Participants (nomParticipant, prenomParticipant, numTelParticipant, mailParticipant) 
	// 		VALUES (:nomParticipant, :prenomParticipant, :numTelParticipant, :mailParticipant); 
	// 	INSERT INTO Inscriptions(idExcursion, idParticipant, dateInscription) 
	// 	VALUES (:idExcursion, LAST_INSERT_ID(), :dateInscription)";
	
	// 	$pdoStat = $this -> db -> prepare($query);
	
	// 	$executeIsOk = $pdoStat -> execute([
	// 		'nomParticipant' => $_POST["nom"],
	// 		'prenomParticipant' => $_POST["prenom"],
	// 		'numTelParticipant' => $_POST["tel"],
	// 		'mailParticipant' => $_POST["email"],
	// 		'idExcursion' => $_POST["excursion"],
	// 		'dateInscription' => date("Y-m-d")
	// 	]) or die(print_r($this -> db -> errorInfo()));
	
	// 	$mail = new PHPMailer(true);
	
	// 	try {
	// 		//Server settings
	// 		$mail -> CharSet = 'UTF-8';
	// 		// $mail->SMTPDebug = SMTP::DEBUG_SERVER;//Enable verbose debug output
	// 		$mail -> isSMTP();									//Send using SMTP
	// 		$mail -> Host       = 'smtp.gmail.com';				//Set the SMTP server to send through
	// 		$mail -> SMTPAuth   = true;							//Enable SMTP authentication
	// 		$mail -> Username   = 'antoine.reib3l@gmail.com';	//SMTP username
	// 		$mail -> Password   = 'sghutxstnoxheucn';			//SMTP password
	// 		$mail -> SMTPSecure = PHPMailer:: ENCRYPTION_SMTPS;	//Enable implicit TLS encryption
	// 		$mail -> Port       = 465;							//TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
	
	// 		//Recipients
	// 		$mail -> setFrom('antoine.reib3l@gmail.com', 'SUAPS');
	// 		$mail -> addAddress($_POST["email"], 'Joe User');	//Add a recipient
	
	// 		//Attachments
	
	// 		//Content
	// 		$mail -> isHTML(true);								//Set email format to HTML
	// 		$mail -> Subject = 'confirmation d\'inscription';
	// 		$mail -> Body = '<h1>' . $excursion . '</h1><p>Je test complètement</p>';
	
	// 		$mail -> send();
	// 			echo 'Message has been sent';
	// 	} catch (Exception $e) {
	// 			echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
	// 	}
	// }
	
}
