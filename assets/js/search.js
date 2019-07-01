const searchBtn = document.getElementById('search');
const player1 = document.getElementById('playerone');
let player2 = false;
let player2Id = false;
let ready = false;
let interval;

document.addEventListener(
	'DOMContentLoaded',
	function() {
		searchBtn.addEventListener('click', searchGo);
	},
	false
);
document.addEventListener('click', function(e) {
	if (e.target && e.target.id == 'readybtn') {
		player2Id = document.getElementById('playertwo').dataset.userid;
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

function searchGo(e) {
	e.preventDefault();
	if (!player2) {
		interval = setInterval(function() {
			searchPlayer2();
		}, 3000);
	}
}

function startCountdown() {
	console.log('geht');
}

function renderReady(player2) {
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

function searchPlayer2() {
	var xhr = new XMLHttpRequest();
	xhr.open('GET', 'http://schikanezwei.loc/search');
	xhr.send();
	xhr.onload = function() {
		if (isJson(xhr.response)) {
			const data = JSON.parse(xhr.response);
			player2 = data.player2;
			renderReady(data.player2);
			clearInterval(interval);
		}
	};
}

function checkIfReady() {
	var xhr = new XMLHttpRequest();
	xhr.open('GET', 'http://schikanezwei.loc/ready');
	xhr.send(JSON.stringify({"player2id": player2Id }));
	xhr.onload = function() {
		console.log(xhr.reaponse);
		if (isJson(xhr.response)) {
			const data = JSON.parse(xhr.response);
			if (data.ready) {
				ready = true;
				clearInterval(interval);
				startCountdown();
			}else{
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
