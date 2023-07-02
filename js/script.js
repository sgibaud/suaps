document.querySelector('.carte').addEventListener('click', async function (event) {
	event.stopPropagation();
	document.body.style.overflow = 'hidden';
	let popUp = document.querySelector('.popUpCarte');

	try {
		const response = await fetch('carte.php?randonnee=' + randonneeId, {
			method: 'GET'
		});

		if (!response.ok) {
			throw new Error('Erreur lors du chargement de la carte.');
		}
		const responseText = await response.text();
		popUp.innerHTML = responseText;
		popUp.style.display = 'block';

	} catch (error) {
		popUp.innerHTML = error;
	}
});


// apparition de la modal
function popUpDate() {
	let popUp = document.querySelector('.popUpDates')
	popUp.style.display = 'block'
	document.body.style.overflow = 'hidden';
}

// pour fermer les modals
document.addEventListener('click', function (event) {
	let popupCarte = document.querySelector('.popUpCarte');
	let popupDates = document.querySelector('.popUpDates');
	let popupForm = document.querySelector('.popUpForm');
	let carte = document.querySelector('.carte')
	let dates = document.querySelector('.dates')
	let form = document.querySelector('.button')
	if (!popupCarte.contains(event.target) && !popupDates.contains(event.target) && !popupForm.contains(event.target) && !carte.contains(event.target) && !dates.contains(event.target) && !form.contains(event.target)) {
		popupCarte.style.display = 'none';
		popupDates.style.display = 'none';
		popupForm.style.display = 'none';

		document.body.style.overflow = 'auto';
	}
});

// fonction reservation
function reserver() {
    nom = document.getElementById('nomform').value;
    prenom = document.getElementById('prenomform').value;
    mail = document.getElementById('mailform').value;
    tel =     document.getElementById('telform').value;
    date =  document.getElementById('datesform').value;
    excursion = document.getElementById('nomexcursion').innerHTML;
	console.log(date);
    $.ajax({
        dataType: 'html',
        cache: false,
        type: 'POST',
        url: 'form.php',
        data: { nom: nom ,prenom: prenom, mail: mail,tel: tel,date: date,excursion: excursion },
        success: function (data) {
            document.getElementById("msgform").innerHTML = data;
        }
    });
}

function popUpForm() {
    let popUp = document.querySelector('.popUpForm')
    popUp.style.display = 'block'
    document.body.style.overflow = 'hidden';
}