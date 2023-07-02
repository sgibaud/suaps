function datelisible(d){   //convertit les date renvoyées par info.event.start et info.event.end au format yyyy-mm-dd

    let date = d.toString();

    date = date.split(' ');

    switch (date[1]){
        case 'Jan': return date[3]+'-01-'+date[2];
        case 'Feb': return date[3]+'-02-'+date[2];
        case 'Mar': return date[3]+'-03-'+date[2];
        case 'Apr': return date[3]+'-04-'+date[2];
        case 'May': return date[3]+'-05-'+date[2];
        case 'Jun': return date[3]+'-06-'+date[2];
        case 'Jul': return date[3]+'-07-'+date[2];
        case 'Aug': return date[3]+'-08-'+date[2];
        case 'Sep': return date[3]+'-09-'+date[2];
        case 'Oct': return date[3]+'-10-'+date[2];
        case 'Nov': return date[3]+'-11-'+date[2];
        case 'Dec': return date[3]+'-12-'+date[2];
        default: return 'Error: not a valid date';

    }
}


function calendrie(liste) {   //Affiche un calendrier contenant les evenements de la liste dans la div avec l'id calendar

    document.addEventListener('DOMContentLoaded', function () {  // création du calendrier
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {

            headerToolbar: {
                right: "prev,next today"
            },

            buttonText: {
                today: "Aujourd'hui"
            },

            events:
            liste
            ,
            //eventClick: function (info) {                                            // Fonction activée quand on clique sur un evenement
                //alert('an event has been clicked!'+info.event.start+datelisible(info.event.end)+'  b');
            //},
            initialView: 'dayGridMonth',                                        //Système de vue.
            schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives'   //Clé de license (usage d'une clé test)
        });
        calendar.render();                                      // Affichage du calendrier
    });
}


function calendrier(nomExcursion){

    let dataform = [];

    $.ajax({
        dataType : 'html',
        cache : false,
        type : 'POST',
        url : 'caldata.php',
        async: false,
        data:{nomEx:nomExcursion},
        success: function(data){
            let dat = data.split("/");
            for(i=0;i<dat.length-1;i++){
                d = dat[i].split("$")
                if(nomExcursion == 'all'){
                    dataform[i]={
                        id: ''+parseInt(i),
                        url: 'hiking.php?randonnee='+d[0],
                        title: d[0],
                        start: d[1],
                        end: d[2],
                        backgroundColor: "#bf9a78",
                        borderColor: "#bf9a78",
                        textColor: "white",
						fontSize: "0.5em"
                    };
                }
                else{
                    dataform[i]={
                        id: ''+parseInt(i),
                        title: d[0],
                        start: d[1],
                        end: d[2],
                        backgroundColor: "#bf9a78",
                        borderColor: "#bf9a78",
                        textColor: "white",
						fontSize: "1em"
                    };
                }

            }

        }
    });

    calendrie(dataform);

}

a = document.getElementById('calendar').innerHTML;

document.getElementById('calendar').innerHTML = '';

calendrier(a);
//console.log("calendrier fonctionne");