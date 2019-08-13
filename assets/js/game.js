let chosenCard; // Die Karte, die verschoben werden soll
let chosenSrc; // Wo die Karte herkommt
let ausgeteilt = false; // Flag, damit nicht zu oft ausgeteilt wird
let clickVerbot = false; // Flag, damit nicht unerlaubt gecklickt wird

/**
 * Speichert erste Auswahl
 * Mit zweiter Auswahl wird Spiel aufgerufen
 * Karten werden als ausgewählt markiert
 */
function playersChoice(e) {
	if (!chosenCard) {
		const domSrc = e.target.parentElement;
		removeSelectedClass();

		if (domSrc.hasAttribute('data-mainstack')) {
			if (e.target.classList.contains('abheben-allowed')) {
				abheben();
			}
		} else if (clickVerbot) {
			writeSmallMessage('Abheben nicht vergessen!');
			return;
		} else if (domSrc.hasAttribute('data-hand')) {
			chosenCard = e.target.dataset.id;
			chosenSrc = domSrc.id;
			e.target.classList.add('selected');
		} else if (domSrc.hasAttribute('data-ablageid') && e.target == domSrc.lastChild) {
			chosenCard = e.target.dataset.id;
			chosenSrc = domSrc.id;
			e.target.classList.add('selected');
		} else if (domSrc.hasAttribute('data-jokerablage')) {
			chosenCard = e.target.dataset.id;
			chosenSrc = domSrc.id;
			e.target.classList.add('selected');
		} else if (domSrc.hasAttribute('data-drawstack')) {
			chosenCard = e.target.dataset.id;
			chosenSrc = domSrc.id;
			e.target.classList.add('selected');
		}
	} else {
		let domTarget;
		e.target.classList.add('selected');

		if (e.target.classList.contains('card')) {
			domTarget = e.target.parentElement;
		} else {
			domTarget = e.target;
		}

		if (domTarget.hasAttribute('data-ablageid')) {
			const trgt = domTarget.id;

			ablegen(chosenSrc, trgt, chosenCard);

			chosenSrc = false;
			chosenCard = false;
			removeSelectedClass();
		} else if (domTarget.hasAttribute('data-jokerablage')) {
			const trgt = domTarget.id;

			ablegen(chosenSrc, trgt, chosenCard);

			chosenSrc = false;
			chosenCard = false;
			removeSelectedClass();
		} else if (domTarget.hasAttribute('data-playareaid')) {
			const trgt = domTarget.id;

			ablegen(chosenSrc, trgt, chosenCard);

			chosenSrc = false;
			chosenCard = false;
			removeSelectedClass();
		} else {
			chosenSrc = false;
			chosenCard = false;
			removeSelectedClass();
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
			anmelden();
		}, 2000);

		document.addEventListener('click', playersChoice);
	},
	false
);

/**
 * Animiert das Abheben einer Karte
 *
 * @param {array} card
 * @param {string} trgt
 */
function animateAbheben(cards, trgt) {
	const mainStack = document.getElementById('mainstack-dummy');
	const hand = document.querySelector('.hand');
	let startPosTop = mainStack.getBoundingClientRect().top;
	let startPosLeft = mainStack.getBoundingClientRect().left;
	let halfEndWidth = hand.getBoundingClientRect().width / 2;
	let endPosLeft = hand.getBoundingClientRect().left + halfEndWidth;
	let endPosTop = hand.getBoundingClientRect().top;
	let animationCard;

	cards.forEach((card, index) => {
		setTimeout(() => {
			let node = document.createElement('div');
			node.classList.add('card');
			node.classList.add('animation-card');
			node.style.backgroundImage = 'url(/assets/utility/cards/png/1x/back-fuchsia.png)';
			node.style.position = 'absolute';
			node.style.top = startPosTop + 'px';
			node.style.left = startPosLeft + 'px';
			node.style.transform = 'rotate(90deg)';
			node.style.zIndex = 3333;
			node.id = 'animation-card' + index;

			document.body.appendChild(node);
			animationCard = document.getElementById('animation-card' + index);

			actualCardAnimator(
				animationCard,
				300,
				startPosTop,
				startPosLeft,
				endPosTop,
				endPosLeft,
				card,
				trgt
			);
		}, 500 * (index + 1));
	});
}

/**
 * Signalisiert, dass das abheben nun erlaubt ist
 *
 * @param {bool} abhebenP1
 * @param {bool} abhebenP2
 * @param {string} player1
 * @param {string} player2
 */
function renderAbhebenAllowed(abhebenP1, abhebenP2, player1, player2) {
	const playerUsername = document.getElementById('playerUsername').value;
	const mainStack = document.getElementById('mainstack-dummy');

	if (player1 == playerUsername) {
		if (abhebenP1) {
			clickVerbot = true;
			mainStack.classList.add('abheben-allowed');
		}
	} else if (player2 == playerUsername) {
		if (abhebenP2) {
			clickVerbot = true;
			mainStack.classList.add('abheben-allowed');
		}
	}
}

/**
 * Das ablegen der Karten wird animiert
 * Die Ursprungskarte wird entfernt
 * Für die Dauer der Animation wird eine Dummy-Karte erzeugt
 * Nach Animationsende werden die echten Karten gerendert
 * und die Dummy-Karte entfernt
 *
 * @param {object} card
 * @param {string} trgt
 * @param {boolean} draw
 */
function animateAblegen(card, trgt, src, newcard, draw = false) {
	const cards = document.querySelectorAll('.card');
	let endPosTop = document.getElementById(trgt).getBoundingClientRect().top;
	let endPosLeft = document.getElementById(trgt).getBoundingClientRect().left;

	cards.forEach(domCard => {
		if (domCard.dataset.id == card.id) {
			const startPosTop = domCard.getBoundingClientRect().top;
			const startPosLeft = domCard.getBoundingClientRect().left;
			const domBody = document.body;

			removeCards(card);
			if (draw) {
				renderCards(newcard, src);
			}
			let node = document.createElement('div');
			node.classList.add('card');

			node.style.backgroundImage = 'url(/assets/utility/cards/png/1x/' + card.name + '.png)';
			node.style.position = 'absolute';
			node.style.top = startPosTop + 'px';
			node.style.left = startPosLeft + 'px';
			node.style.zIndex = 3333;
			node.id = 'animation-card';

			domBody.appendChild(node);
			const animationCard = document.getElementById('animation-card');

			animationCard.animate(
				[
					{
						top: startPosTop + 'px',
						left: startPosLeft + 'px',
					},
					{
						top: endPosTop + 'px',
						left: endPosLeft + 'px',
					},
				],
				500
			);

			setTimeout(function() {
				animationCard.remove();
			}, 500);
		}
	});

	setTimeout(function() {
		renderCards(card, trgt);
	}, 500);
}

/**
 * Schreibt einen Hinweis, der nach kurzer Zeit verschwindet
 * 
 * @param {string} msg 
 */
function writeSmallMessage(msg) {
	const tinyMsg = document.getElementById('game-tiny-message');
	const tinyMsgText = document.getElementById('game-tiny-msg__text');
	tinyMsg.classList.add('game-tiny-msg__flash');
	tinyMsgText.innerHTML = msg;
	setTimeout(function() {
		tinyMsg.classList.remove('game-tiny-msg__flash');
	}, 1000);
}

/**
 * Schreibt eine Nachricht in die Nachrichtenbox
 * sorgt dafür, dass die richtigen Spieler die richtige Nachricht erhalten
 * @param {string} msgP1
 * @param {string} msgP2
 * @param {string} player1
 * @param {string} player2
 */
function writeMessage(msgP1, msgP2, player1, player2) {
	const playerUsername = document.getElementById('playerUsername').value;
	const msgBox = document.getElementById('message-container__message');
	if (player1 == playerUsername) {
		if (msgP1 == 'dran') {
			changeMessage();
			msgBox.innerHTML = 'Du bist dran!';
		} else if (msgP1 == 'nicht dran') {
			changeMessage('big');
			msgBox.innerHTML = `${player2} ist dran`;
		} else {
			changeMessage('big');
			msgBox.innerHTML = msgP1;
		}
	} else if (player2 == playerUsername) {
		if (msgP2 == 'dran') {
			changeMessage();
			msgBox.innerHTML = 'Du bist dran!';
		} else if (msgP2 == 'nicht dran') {
			changeMessage('big');
			msgBox.innerHTML = `${player1} ist dran`;
		} else {
			changeMessage('big');
			msgBox.innerHTML = msgP2;
		}
	}
}

/**
 * Erzeugt ein Overlay mit Infos, wenn ein Spieler nicht dran ist
 * Wenn der Spieler dran ist, wird das Overlay zu einer kleinen Box am Rande
 *
 * @param {bool} big
 */
function changeMessage(big = false) {
	const msgContainer = document.getElementById('message-container');
	const msgBox = document.getElementById('message-container__message-box');
	const msg = document.getElementById('message-container__message');

	if (big) {
		msgContainer.classList.remove('message-container--hidden');
		msgBox.classList.remove('message-container__message-box--minified');
		msg.classList.remove('message-container__message--minified');
	} else {
		msgContainer.classList.add('message-container--hidden');
		msgBox.classList.add('message-container__message-box--minified');
		msg.classList.add('message-container__message--minified');
	}
}

/**
 * Rendert die Karten in den verschiedenen Ablageplätzchen
 * Baut auch ein bisserl CSS ein, je nachdem wohin die Karten sollen
 *
 * @param {object} card
 * @param {string} area
 */
function renderCards(card, area) {
	if (document.getElementById(area)) {
		const domTarget = document.getElementById(area);
		let node = document.createElement('div');
		node.classList.add('card');
		node.dataset.value = card.value;
		node.dataset.id = card.id;
		node.style.backgroundImage = 'url(/assets/utility/cards/png/1x/' + card.name + '.png)';

		if (domTarget.classList.contains('p2Ablage') || domTarget.classList.contains('p2Joker')) {
			const childCount = domTarget.childElementCount;
			const offsetPixel = childCount * 30;
			node.style.bottom = offsetPixel + 'px';
		}
		if (domTarget.classList.contains('p1Ablage') || domTarget.classList.contains('p1Joker')) {
			const childCount = domTarget.childElementCount;
			const offsetPixel = childCount * 30;
			node.style.top = offsetPixel + 'px';
		}

		domTarget.appendChild(node);
	}
}

/**
 * Zeigt einen Spielabbruch des anderen Spielers an
 */
function renderAbbruch() {
	const gameOverScreen = document.getElementById('game-over__wrapper');
	const gameOverText = document.getElementById('game-over-text');
	const restartBtn = document.getElementById('game-over__restart-game');
	restartBtn.classList.add('hide');
	gameOverScreen.classList.remove('hide');
	gameOverScreen.classList.add('show');
	gameOverText.innerHTML = 'Ihr Mitspieler hat das Spiel verlassen.';
}

/**
 * Zeigt den Game-Over-Bildschirm an
 * informiert Verlierer und Gewinner
 *
 * @param {int} winner
 * @param {string} player1
 * @param {string} player2
 * @param {bool} unentschieden
 */
function gameOver(winner, player1, player2, unentschieden = false) {
	const gameOverScreen = document.getElementById('game-over__wrapper');
	const gameOverText = document.getElementById('game-over-text');
	const playerUsername = document.getElementById('playerUsername').value;
	gameOverScreen.classList.remove('hide');
	gameOverScreen.classList.add('show');

	checkIfRestart();

	if (unentschieden) {
		gameOverText.innerHTML = 'Unentschieden. Es gibt keine Karten mehr.';
		return;
	}

	if (player1 == playerUsername) {
		if (winner == 1) {
			gameOverText.innerHTML = 'Sie haben gewonnen!';
		} else {
			gameOverText.innerHTML = 'Sie haben verloren!';
		}
	} else if (player2 == playerUsername) {
		if (winner == 2) {
			gameOverText.innerHTML = 'Sie haben gewonnen!';
		} else {
			gameOverText.innerHTML = 'Sie haben verloren!';
		}
	}
}

/**
 * Entfernt eine Karte oder alle Karten in einem Bereich
 *
 * @param {obj} card
 * @param {string} area
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
 * @param {string} trgt
 * @param {int} cardId
 */
function ablegen(src, trgt, cardId) {
	var msg = {
		art: 'move',
		src: src,
		trgt: trgt,
		id: cardId,
	};
	removeSelectedClass();
	console.log(msg);
	websocket.send(JSON.stringify(msg));
}

/**
 * Entfernt den ladescreen, nachdem alle User und Karten verteilt sind
 */
function removeLoadingscreen() {
	const loadingscreen = document.getElementById('loading-screen');
	loadingscreen.classList.add('loading-screen--hidden');
}

/**
 * Ruft den Server an, um um neue Karten zu bitten
 */
function abheben() {
	const mainStack = document.getElementById('mainstack-dummy');
	mainStack.classList.remove('abheben-allowed');
	var msg = {
		art: 'abheben',
		trgt: document.querySelector('.hand').id,
	};
	websocket.send(JSON.stringify(msg));
}

/**
 * Schickt Username an Server
 */
function anmelden() {
	const playerUsername = document.getElementById('playerUsername').value;
	var msg = {
		art: 'anmelden',
		username: playerUsername,
	};
	websocket.send(JSON.stringify(msg));
}

/**
 * Bestellt alle zum Spielstart benötigten Karten vom Server
 * Der Username wird nochmal mitgeschickt,
 * damit der erste Anmelder auch die alle Namen kriegt
 */
function austeilen() {
	ausgeteilt = true;
	const playerUsername = document.getElementById('playerUsername').value;
	var msg = {
		art: 'austeilen',
		username: playerUsername,
	};
	websocket.send(JSON.stringify(msg));
}

/**
 * Zeigt den aktuellen Punktestand an
 *
 * @param {int} p1Points
 * @param {int} p2Points
 */
function renderPoints(p1Points, p2Points) {
	const p1PointsDom = document.getElementById('p1Points');
	const p2PointsDom = document.getElementById('p2Points');
	p1PointsDom.innerHTML = p1Points;
	p2PointsDom.innerHTML = p2Points;
}

/**
 * Zeigt die Usernamen der Spieler an
 *
 * @param {string} p1Name
 * @param {string} p2Name
 */
function renderUsernames(p1Name, p2Name) {
	const p1NameDom = document.getElementById('p1Name');
	const p2NameDom = document.getElementById('p2Name');
	p1NameDom.innerHTML = p1Name;
	p2NameDom.innerHTML = p2Name;
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
 * Die Antworten des Servers werden hier auch verwaltet
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
			console.log(msg);
			if (msg.art == 'spielervergabe') {
				changeId();
			} else if (msg.art == 'anmelden') {
				renderUsernames(msg.player1Username, msg.player2Username);
				renderPoints(msg.player1Points, msg.player2Points);
				if (msg.player1Username && msg.player2Username) {
					removeLoadingscreen();
				}
				if (!ausgeteilt) {
					austeilen();
				}
			} else if (msg.art == 'austeilen') {
				msg.hand.forEach(el => {
					renderCards(el, msg.trgt);
				});
				renderUsernames(msg.player1Username, msg.player2Username);
				renderPoints(msg.player1Points, msg.player2Points);
				renderCards(msg.p1Drawstack, 'p1Drawstack|0');
				renderCards(msg.p2Drawstack, 'p2Drawstack|0');
				if (msg.player1Username && msg.player2Username) {
					removeLoadingscreen();
				}
				writeMessage(msg.msgP1, msg.msgP2, msg.player1Username, msg.player2Username);
			} else if (msg.art == 'move') {
				if (msg.abhebenP1 || msg.abhebenP2) {
					renderAbhebenAllowed(
						msg.abhebenP1,
						msg.abhebenP2,
						msg.player1Username,
						msg.player2Username
					);
				}
				animateAblegen(msg.card, msg.trgt, 'src', 'newcard');
				if (msg.msgP1) {
					writeMessage(msg.msgP1, msg.msgP2, msg.player1Username, msg.player2Username);
				}
			} else if (msg.art == 'abheben') {
				clickVerbot = false;
				animateAbheben(msg.cards, msg.trgt);
			} else if (msg.art == 'draw') {
				renderPoints(msg.player1Points, msg.player2Points);
				animateAblegen(msg.card, msg.trgt, msg.src, msg.newcard, 'draw');
			} else if (msg.art == 'gameover') {
				removeCards(msg.card);
				renderPoints(msg.player1Points, msg.player2Points);
				gameOver(msg.winner, msg.player1Username, msg.player2Username);
			} else if (msg.art == 'stackfull') {
				animateStackFull(msg.trgt, mag.card);
				if (msg.src == 'p1Drawstack|0' || msg.src == 'p2Drawstack|0') {
					renderCards(msg.newcard, msg.src);
				}
				removeCards(msg.card, msg.trgt);
			} else if (msg.art == 'debug') {
			} else if (msg.art == 'unentschieden') {
				gameOver(3, 'niemand', 'niemand', msg.art);
			} else if (msg.art == 'noaccess') {
				unallowedAccess();
			} else if (msg.art == 'abbruch') {
				renderAbbruch();
			}
		}
	};
}

/**
 * Animiert das einsortieren eines vollen Stapels in den Hauptstapel
 *
 * @param {string} target
 * @param {object} card
 */
function animateStackFull(target, card) {
	const mainStack = document.getElementById('mainstack-dummy');
	const fullStack = document.getElementById(target);
	let startPosTop = fullStack.getBoundingClientRect().top;
	let startPosLeft = fullStack.getBoundingClientRect().left;

	let endPosLeft = mainStack.getBoundingClientRect().left;
	let endPosTop = mainStack.getBoundingClientRect().top;

	setTimeout(() => {
		let node = document.createElement('div');
		node.classList.add('card');
		node.style.backgroundImage = 'url(/assets/utility/cards/png/1x/' + card.name + '.png)';
		node.style.position = 'absolute';
		node.style.top = startPosTop + 'px';
		node.style.left = startPosLeft + 'px';
		node.style.zIndex = 3333;
		node.id = 'animation-card';

		document.body.appendChild(node);

		const dummyCard = document.getElementById('animation-card');

		dummyCard.animate(
			[
				{
					top: startPosTop + 'px',
					left: startPosLeft + 'px',
					transform: 'rotate(0deg)',
				},
				{
					top: endPosTop + 'px',
					left: endPosLeft + 'px',
					transform: 'rotate(90deg)',
				},
			],
			duration
		);
	}, 500);

	setTimeout(function() {
		dummyCard.remove();
	}, 500);
}

/**
 * Entfernt die Markierung der ausgewählten Karte
 *
 */
function removeSelectedClass() {
	const els = document.querySelectorAll('.selected');
	for (let i = 0; i < els.length; i++) {
		els[i].classList.remove('selected');
	}
}

/**
 * Ändert die Ids von Elementen, die die Spielerzahl tragen um die Anzeige umzukehren
 * Hint: der Aufrufer der Funktion ist immer Spieler 2
 */
function changeId() {
	let elements = document.getElementsByTagName('DIV');
	for (let i = 0; i < elements.length; i++) {
		let string = elements[i].id;
		if (string.match('p1')) {
			newstring = string.replace('p1', 'p2');
			elements[i].id = newstring;
		} else if (string.match('p2')) {
			newstring = string.replace('p2', 'p1');
			elements[i].id = newstring;
		}
	}
}

/**
 * Kümmert sich um die Animation der Karte
 * und enfernt Sie dann auch wieder
 *
 * @param {HTMLElement} dummyCard
 * @param {int} duration
 * @param {int} startPosTop
 * @param {int} startPosLeft
 * @param {int} endPosTop
 * @param {int} endPosLeft
 * @param {object} card
 * @param {string} trgt
 */
function actualCardAnimator(
	dummyCard,
	duration,
	startPosTop,
	startPosLeft,
	endPosTop,
	endPosLeft,
	card,
	trgt
) {
	dummyCard.animate(
		[
			{
				top: startPosTop + 'px',
				left: startPosLeft + 'px',
				transform: 'rotate(0deg)',
			},
			{
				top: endPosTop + 'px',
				left: endPosLeft + 'px',
				transform: 'rotate(90deg)',
			},
		],
		duration
	);

	setTimeout(function() {
		dummyCard.remove();
		renderCards(card, trgt);
	}, duration);
}

/**
 * Zeigt bei unerlaubten Zutritt eine Warnmeldung
 */
function unallowedAccess() {
	const loadingBar = document.querySelector('.loading-screen__loading-bar');
	const noAccessMsg = document.querySelector('.loading-screen__noaccess');
	loadingBar.classList.add('hide');
	noAccessMsg.classList.remove('hide');
}
