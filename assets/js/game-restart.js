const demandRestartBtn = document.getElementById('game-over__restart-game');
let restart = false;
let otherplayerReady = false;
let restartInterval;

demandRestartBtn.addEventListener('click', demandRestart);

/**
 * Startet alle paar Sekunden die Überprüfung, ob ein Spieler bereit ist
 */
function checkIfRestart() {
	const user = document.getElementById('playerUsername').value;
	const player1 = document.getElementById('p1Name').innerText;
	const player2 = document.getElementById('p2Name').innerText;

	if (user == player1 && !restart) {
		restartInterval = setInterval(function() {
			checkIfAnyoneIsReady(player2);
		}, 1000);
	} else if (user == player2 && !restart) {
		restartInterval = setInterval(function() {
			checkIfAnyoneIsReady(player1);
		}, 1000);
	}
}

/**
 * Überprüft, ob einer der beidern Spieler bereit ist
 * Oder ob schon beide Spieler bereit sind
 *
 * @param {string} player2
 */
function checkIfAnyoneIsReady(player2) {
	let data = new FormData();
	data.append('player2', player2);
	let xhr = new XMLHttpRequest();
	xhr.open('POST', base_url + '/checkready');
	xhr.send(data);
	xhr.onload = function() {
		console.log(xhr.response);
		if (isJson(xhr.response)) {
			const data = JSON.parse(xhr.response);
			if (data.readyP1 && !data.readyP2) {
				renderReadyForRestart(data.readyP1);
			} else if (data.readyP2 && !data.readyP1) {
				renderReadyForRestart(data.readyP2);
			} else if (data.readyP2 && data.readyP1) {
				restart = true;
				clearInterval(restartInterval);
				startRestartCountdown();
			} else {
				// console.log('Niemand ist bereit');
			}
		}
	};
}

/**
 * Zeigt an, dass einer der beiden Spieler eine weitere Runde möchte
 * Zeigt Konfirmations-Button
 *
 * @param {string} playername
 */
function renderReadyForRestart(playername) {
	const user = document.getElementById('playerUsername').value;
	const restartText = document.getElementById('game-restart-text');
	const restartName = document.getElementById('game-restart-player');

	if (user !== playername) {
		restartText.classList.remove('hide');
		restartName.innerHTML = playername;
		otherplayerReady = true;
	}
}

/**
 * Setzt den Spieler, der den Restart-Knopf drückt auf bereit
 */
function demandRestart() {
	const restartText = document.getElementById('game-restart-text');
	const demandRestartText = document.getElementById('game-demand-restart-text');

	if (otherplayerReady) {
		restartText.innerHTML = 'Neustart bestätigt';
	}
	demandRestartText.classList.remove('hide');

	demandRestartBtn.classList.add('hide');

	let xhr = new XMLHttpRequest();
	xhr.open('GET', base_url + '/setready');
	xhr.send();
	xhr.onload = function() {
		if (isJson(xhr.response)) {
			const data = JSON.parse(xhr.response);
			if (data.success) {
				console.log('Du bist bereit');
			}
		}
	};
}

/**
 * Startet Countdown
 * bei 0 startet das Spiel neu
 */
function startRestartCountdown() {
	const restartCountdown = document.getElementById('restartCountdown');
	const restartContent = document.getElementById('restartContent');
	restartCountdown.classList.remove('hide');
	restartContent.classList.add('hide');
	let timeleft = 5;
	let gameStartTimer = setInterval(function() {
		restartCountdown.innerHTML = 'Spiel startet in: ' + timeleft;
		timeleft -= 1;
		if (timeleft <= 0) {
			clearInterval(gameStartTimer);
			window.location.replace(base_url + '/restart');
		}
	}, 1000);
}
