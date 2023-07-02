<form action="" method="post">
    <input type="text" id="nom" name="nom" placeholder="nom" required><br>
    <input type="text" id="prenom" name="prenom" placeholder="prénom" required><br>
    <input type="email" id="email" name="email" placeholder="email" required><br>
    <input type="tel" id="tel" name="tel" placeholder="numéro de téléphone" required><br>
    <select name="excursion">
        <?php foreach ($dates as $date) {
            $placeDispo = $database->excursion_places($date["idExcursion"]);
            $participants = $placeDispo[0];
            $maximum = $placeDispo[1];

            if ($participants >= $maximum) {
                echo '<option disabled value="' . $date['idExcursion'] . '"> Depart: ' . $date['dateDepart'] . " Retour: " . $date['dateRetour'] . ' COMPLET' . '</option>';
            } else {
                echo '<option value="' . $date['idExcursion'] . '"> Depart: ' . $date['dateDepart'] . " Retour: " . $date['dateRetour'] . ' (' . $participants . '/' . $maximum . ')</option>';
            }

        }
        ?>
    </select><br>
    <button>INSCRIPTION</button>
</form>


<form action="" method="post">
        <input type="text" id="nom" name="nom" placeholder="nom" required><br>
        <input type="text" id="prenom" name="prenom" placeholder="prénom" required><br>
        <input type="email" id="email" name="email" placeholder="email" required><br>
        <input type="tel" id="tel" name="tel" placeholder="numéro de téléphone" required><br>
        <select name="excursion">
            <?php foreach ($dates as $date) {
                $placeDispo = $database->excursion_places($date["idExcursion"]);
                $participants = $placeDispo[0];
                $maximum = $placeDispo[1];

                if ($participants >= $maximum) {
                    echo '<option disabled value="' . $date['idExcursion'] . '"> Depart: ' . $date['dateDepart'] . " Retour: " . $date['dateRetour'] . ' COMPLET' . '</option>';
                } else {
                    echo '<option value="' . $date['idExcursion'] . '"> Depart: ' . $date['dateDepart'] . " Retour: " . $date['dateRetour'] . ' (' . $participants . '/' . $maximum . ')</option>';
                }

            }
            ?>
        </select><br>
        <button>INSCRIPTION</button>
    </form>
if (isset($_POST['nom'])) {
    $database->inscription($typeExcursion["nomExcursion"]);
}