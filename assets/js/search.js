const searchBtn = document.getElementById('search');
const player1 = document.getElementById('playerone');
let player2 = false;
let ready = false;
let interval;

/**
 * Neuer Eventlistener auf dem Such-Button, nachdem das Document fertig geladen hat
 */
document.addEventListener(
	'DOMContentLoaded',
	function() {
		searchBtn.addEventListener('click', searchGo);
	},
	false
);

/**
 * Der 'Bereit'-Button wird abgehört
 * Bei Click werden die indikatoren verändert
 * Funktion checkIfReady wird alle paar Sekunden ausgeführt
 */
document.addEventListener('click', function(e) {
	if (e.target && e.target.id == 'readybtn') {
		document.getElementById('readybtn').remove();
		let readyDom = document.getElementById('playeronereadyindicator');
		readyDom.className = 'ready';
		readyDom.innerHTML = 'Bereit';
		if (!ready) {
			interval = setInterval(function() {
				checkIfReady();
			}, 3000);
		}
	}
});

/**
 * Such-Funktion wird alle paar Sekunden ausgeführt
 * Button wird entfernt
 * Lade-Gif wird angezeigt
 * @param {Event} e 
 */
function searchGo(e) {
	searchBtn.remove();
	document.getElementById('searching').classList.remove('hide');
	e.preventDefault();
	if (!player2) {
		interval = setInterval(function() {
			searchPlayer2();
		}, 3000);
	}
}

/**
 * Countdown wird angezeigt
 * Indikatoren des zweiten Spielers zeigen Bereitschaft an
 * Nach Ablauf der Zeit werden die Spieler zum eigentlichen Spiel weitergeleitet
 */
function startCountdown() {
	const player2Dom = document.getElementById('playertworeadyindicator');
	player2Dom.className = 'ready';
	player2Dom.innerHTML = 'Bereit';
	let timeleft = 5;
	let downloadTimer = setInterval(function() {
		document.getElementById('countdown').innerHTML =
			'Spiel startet in: '+timeleft;
		timeleft -= 1;
		if (timeleft <= 0) {
			clearInterval(downloadTimer);
			window.location.replace(base_url + '/start');
		}
	}, 1000);
}

/**
 * Nach erfolgreicher Suche werden beide Spieler anezeigt
 * Bereitsschafts-Indikatoren sind auf 'Nicht Bereit'
 * Bereitschaftsbutton wird eingeblendet
 * @param {string} player2 
 */
function renderReady(player2) {
	document.getElementById('searching').remove();
	const btnBox = document.getElementById('button-box');
	const player1Dom = document.getElementById('playeroneready');
	const player2Dom = document.getElementById('playertwoready');
	const player2Name = document.getElementById('playertwoname');

	let span1 = `
        <span id="playeronereadyindicator" class="not-ready">Nicht Bereit</span>
    `;
	let span2 = `
        <span id="playertworeadyindicator" class="not-ready">Nicht Bereit</span>
    `;

	player1Dom.innerHTML = span1;
	player2Dom.innerHTML = span2;

	player2Name.innerHTML = player2;

	let btn = `
        <button id="readybtn" class="btn btn-primary">Bereit</button>
    `;

	btnBox.innerHTML = btn;
}

/**
 * AJAX-Request
 * Liefert Namen des zweiten Spielers
 * startet Renderung der nächsten Stufe
 * Beendet die Suche
 */
function searchPlayer2() {
	let xhr = new XMLHttpRequest();
	xhr.open('GET', base_url + '/search');
	xhr.send();
	xhr.onload = function() {
	
		if (isJson(xhr.response)) {
			const data = JSON.parse(xhr.response);
			if(data.player2){
				player2 = data.player2;
				renderReady(data.player2);
				clearInterval(interval);
			}
		}
	};
}

/**
 * AJAX-Request
 * Sendet Namen des zweiten Spielers
 * Bringt in Erfahrung, ob der zweite Spieler bereit ist
 * Beendet die Überprüfung der Bereitschaft
 * Startet den Countdown zum Spiel
 */
function checkIfReady() {
	let data = new FormData();
	data.append('player2', player2);
	let xhr = new XMLHttpRequest();
	xhr.open('POST', base_url + '/ready');
	xhr.send(data);
	xhr.onload = function() {
	
		if (isJson(xhr.response)) {
			const data = JSON.parse(xhr.response);
			if (data.ready) {
			
				ready = true;
				clearInterval(interval);
				startCountdown();
			} else {
				console.log('notready');
			}
		}
	};
}

/**Überprüft, ob ein String JSON ist
 * zum Zwecke der Fehlerunterdrückung
 *
 * @param {string} item
 * @return {bool}
 */
function isJson(item) {
	item = typeof item !== 'string' ? JSON.stringify(item) : item;
	try {
		item = JSON.parse(item);
	} catch (e) {
		return false;
	}
	if (typeof item === 'object' && item !== null) {
		return true;
	}
	return false;
}
