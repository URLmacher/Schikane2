var chosenCard; // Die Karte, die verschoben werden soll
var chosenSrc; // Wo die Karte herkommt

/**
 * Speichert
 * erste
 * Auswahl
 *
 * Mit zweiter Auswahl wird
 * Spiel aufgerufen
 */
function playersChoice(e) {
	if (!chosenCard) {
		var domSrc = e.target.parentElement;
		e.target.classList.add('selected');

		if (domSrc.hasAttribute('data-hand')) {
			chosenCard = e.target.dataset.id;
			chosenSrc = domSrc.id;
		} else if (domSrc.hasAttribute('data-ablageid')) {
			var abId = domSrc.dataset.ablageid;
			chosenCard = e.target.dataset.id;
			chosenSrc = domSrc.id;
		} else if (domSrc.hasAttribute('data-jokerablage')) {
			chosenCard = e.target.dataset.id;
			chosenSrc = domSrc.id;
		} else if (domSrc.hasAttribute('data-drawstack')) {
			chosenCard = e.target.dataset.id;
			chosenSrc = domSrc.id;
		} else if (domSrc.hasAttribute('data-mainstack')) {
			abheben();
		}
	} else {
		var domTarget;
		var selected = document.querySelectorAll('.selected');
		e.target.classList.add('selected');

		if (e.target.classList.contains('card')) {
			domTarget = e.target.parentElement;
		} else {
			domTarget = e.target;
		}

		if (domTarget.hasAttribute('data-ablageid')) {
			var trgt = domTarget.id;

			ablegen(chosenSrc, trgt, chosenCard);

			chosenSrc = false;
			chosenCard = false;
			removeClasses(selected);
		} else if (domTarget.hasAttribute('data-jokerablage')) {
			var trgt = domTarget.id;

			ablegen(chosenSrc, trgt, chosenCard);

			chosenSrc = false;
			chosenCard = false;
			removeClasses(selected);
		} else if (domTarget.hasAttribute('data-playareaid')) {
			var abId = domTarget.dataset.playareaid;
			var trgt = domTarget.id;

			ablegen(chosenSrc, trgt, chosenCard);

			chosenSrc = false;
			chosenCard = false;
			removeClasses(selected);
		} else {
			chosenSrc = false;
			chosenCard = false;
			removeClasses(selected);
		}
	}
}

/**
 * Wartet, bis alles fertig geladen ist
 */
document.addEventListener(
	'DOMContentLoaded',
	function() {
		spielerVergabe();
		setTimeout(function() {
			austeilen();
		}, 2000);

		document.addEventListener('click', playersChoice);
	},
	false
);

/**
 * Schreibt eine Nachricht in die Nachrichtenbox
 * @param {string} msg
 */
function writeMessage(msg) {
	var msgBox = document.getElementById('message');
	var tempString = `<p>${msg}</p>`;
	msgBox.innerHTML = tempString;
}

function renderCards(card, area) {
	if (document.getElementById(area)) {
		var domTarget = document.getElementById(area);

		var node = document.createElement('div');
		node.classList.add('card');
		node.dataset.value = card.value;
		node.dataset.id = card.id;
		node.style.backgroundImage = 'url(/assets/utility/cards/png/1x/' + card.name + '.png)';

		domTarget.appendChild(node);
	}
}

/**
 * Entfernt eine Karte oder alle Karten in einem Bereich
 *
 * @param {obj} card
 * @param {*string} area
 */
function removeCards(card, area = false) {
	if (area) {
		var domArea = document.getElementById(area);
		var cards = domArea.querySelectorAll('.card');
		cards.forEach(el => {
			el.remove();
		});
	} else {
		var cards = document.querySelectorAll('.card');
		cards.forEach(el => {
			if (el.dataset.id == card.id) {
				el.remove();
			}
		});
	}
}

/**
 * Ruft den Server an, um den Zug auf Gültigkeit zu prüfen
 * @param {string} src
 * @param {*string} trgt
 * @param {*int} cardId
 */
function ablegen(src, trgt, cardId) {
	var msg = {
		art: 'move',
		src: src,
		trgt: trgt,
		id: cardId,
	};
	console.log(msg);
	websocket.send(JSON.stringify(msg));
}

/**
 * Ruft den Server an, um um neue Karten zu bitten
 * @param {string} trgt
 */
function abheben() {
	var msg = {
		art: 'abheben',
		trgt: document.querySelector('.hand').id,
	};
	websocket.send(JSON.stringify(msg));
}

/**
 * Bestellt alle zum Spielstart benötigten Karten vom Server
 */
function austeilen() {
	var msg = {
		art: 'austeilen',
	};
	websocket.send(JSON.stringify(msg));
}

/**
 * Der Server soll festlegen, werd der erste Spieler ist
 */
function spielerVergabe() {
	var msg = {
		art: 'spielervergabe',
	};
	serverCall(msg);
}

/**
 * Der Websockerl-Server wird zum ersten Mal angerufen
 * Das Ansinnen, die Spieler auszuwählen wird mit versandt
 *
 * @param {array} data
 */
function serverCall(data) {
	websocket = new WebSocket('ws://localhost:5001');
	websocket.onopen = function() {
		websocket.send(JSON.stringify(data));
		console.log('Verbindung zum Server hergestellt.');
	};
	websocket.onclose = function() {
		console.log('Verbindung zum Server getrennt.');
	};
	websocket.onerror = function() {
		console.log('Verbindungsfehler.');
	};
	websocket.onmessage = function(e) {
		if (isJson(e.data)) {
			let msg = JSON.parse(e.data);

			if (msg.art == 'spielervergabe') {
				console.log('Spieler werden vergeben');

				changeId();
			} else if (msg.art == 'austeilen') {
				console.log('Austeilen:');
				console.log(msg);
				msg.hand.forEach(el => {
					renderCards(el, msg.trgt);
				});
				renderCards(msg.p1Drawstack, 'p1Drawstack|0');
				renderCards(msg.p2Drawstack, 'p2Drawstack|0');
			} else if (msg.art == 'move') {
				console.log(msg);
				removeCards(msg.card);
				renderCards(msg.card, msg.trgt);
			} else if (msg.art == 'abheben') {
				console.log(msg);
				renderCards(msg.card, msg.trgt);
			} else if (msg.art == 'draw') {
				removeCards(msg.card);
				renderCards(msg.card, msg.trgt);
				renderCards(msg.newcard, msg.src);
				console.log(msg);
			} else if (msg.art == 'gameover') {
				console.log(msg);
				removeCards(msg.card);
				writeMessage(msg.art);
			} else if (msg.art == 'stackfull') {
				console.log(msg);
				writeMessage(msg.art);
				removeCards(msg.card);
				if (msg.src == 'p1Drawstack|0' || msg.src == 'p2Drawstack|0') {
					renderCards(msg.newcard, msg.src);
				}
				removeCards(msg.card, msg.trgt);
			} else if (msg.art == 'debug') {
				console.log(msg);
			}
		}
	};
}

/**
 * Überprüft, ob ein String JSON ist
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

/**
 * Entfernt Klassen von einigen Elementen
 *
 * @param {Nodelist} els
 */
function removeClasses(els) {
	for (var i = 0; i < els.length; i++) {
		els[i].classList.remove('selected');
	}
}

/**
 * Ändert die Ids von Elementen, die die Spielerzahl tragen um
 */
function changeId() {
	var elements = document.getElementsByTagName('DIV');

	for (var i = 0; i < elements.length; i++) {
		var string = elements[i].id;
		if (string.match('p1')) {
			newstring = string.replace('p1', 'p2');
			elements[i].id = newstring;
		} else if (string.match('p2')) {
			newstring = string.replace('p2', 'p1');
			elements[i].id = newstring;
		}
	}
}
