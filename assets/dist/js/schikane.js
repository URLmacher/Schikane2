const dashboard = document.getElementById('dashboard');
const dashboardBtn = document.getElementById('dashboard-btn');
const base_url = window.location.origin;

/**
 * Verschiedene Eventlistener zum Dashboard
 */
document.addEventListener(
	'DOMContentLoaded',
	function() {
		dashboardBtn.addEventListener('click', showDashboard);
		sendButton.addEventListener('click', sendMessage);
		editBtn.addEventListener('click', showEdit);
		profileEditBackBtn.addEventListener('click', backToProfileViewFromEdit);
		profileDeleteBackBtn.addEventListener('click', backToProfileViewFromSett);
		profileSettBackBtn.addEventListener('click', backToProfileViewFromSett);
		profileDeleteConfirmBtn.addEventListener('click', deleteProfile);
		settingsBtn.addEventListener('click', showSettings);
		if (document.getElementById('game-info')) {
			infoBtn.addEventListener('click', showGameInfo);
			leaveBtn.addEventListener('click', showGameLeave);
		}
		if (document.getElementById('username-to-dashboard')) {
			document.getElementById('username-to-dashboard').addEventListener('click', showDashboard);
		}
		if (document.getElementById('dashboard-close-btn-nav')) {
			document.getElementById('dashboard-close-btn-nav').addEventListener('click', hideDashboard);
		}
		saveBtn.addEventListener('click', saveProfile);
		document.addEventListener('click', differentActions);
		if (document.getElementById('dashboard-close-btn')) {
			const dashboardCloseBtn = document.getElementById('dashboard-close-btn');
			dashboardCloseBtn.addEventListener('click', () => {
				hideDashboard();
				hideGameInfo();
				hideGameLeave();
			});
		}
	},
	false
);

/**
 * Nach Betätigung eines Buttons wird das Dashboard angezeigt
 * Nachrichten und Freunde werden vom Server geholt
 * Nach erneuter Betätigung schliesst das Dashboard wieder
 * Wenn wir uns im Spielbildschirm befinden, werden Buttons versteckt bzw. angezeigt.
 */
function showDashboard() {
	getMessages();
	getFriends();
	renderWonAndLost();
	if (document.getElementById('dashboard-close-btn')) {
		const gameMenuCloseBtn = document.getElementById('dashboard-close-btn');
		const otherGameMenuBtns = document.querySelectorAll('.game-menu__btn');

		gameMenuCloseBtn.classList.remove('hide');
		otherGameMenuBtns.forEach(btn => {
			btn.classList.add('hide');
		});
	}
	if (document.getElementById('navbar')) {
		const closeBtn = document.getElementById('dashboard-close-btn-nav');
		const navBar = document.getElementById('navbar');
		navBar.classList.add('hide');
		closeBtn.classList.remove('hide');
	}
	dashboard.classList.remove('hide');
	dashboard.classList.add('show');
}

/**
 * Nach Buttonbedrückung wird das Dashboard wieder versteckt
 * Andere Buttons werden wieder hergestellt
 * Schliess-Button wird versteckt
 */
function hideDashboard() {
	if (document.getElementById('navbar')) {
		const closeBtn = document.getElementById('dashboard-close-btn-nav');
		const navBar = document.getElementById('navbar');
		navBar.classList.remove('hide');
		closeBtn.classList.add('hide');

		dashboard.classList.add('hider');

		setTimeout(() => {
			dashboard.classList.add('hide');
			dashboard.classList.remove('hider');
		}, 900);
		dashboard.classList.remove('show');
	} else {
		const gameMenuCloseBtn = document.getElementById('dashboard-close-btn');
		const otherGameMenuBtns = document.querySelectorAll('.game-menu__btn');
		otherGameMenuBtns.forEach(btn => {
			btn.classList.remove('hide');
		});
		dashboard.classList.add('hider');
		gameMenuCloseBtn.classList.add('hide');
		setTimeout(() => {
			dashboard.classList.add('hide');
			dashboard.classList.remove('hider');
		}, 900);
		dashboard.classList.remove('show');
	}
}

/**
 * Verschieden Aktionen werden durch Interaktion mit
 * dynamisch generierten Elementen ausgeführt
 * @param {event} e
 */
function differentActions(e) {
	// Einzelne Nachricht aufrufen
	if (e.target.classList.contains('view-msg')) {
		getSingleMessage(e.target.dataset.msgid);
	}
	// Zurück zu den Nachrichten von der Einzelansicht
	else if (e.target.classList.contains('back-to-table')) {
		getMessages();
		const domTable = document.getElementById('msg-table');
		const domMsgBox = document.getElementById('single-msg');
		const nemMsgBtn = document.getElementById('profile-msgs__new-msg');
		nemMsgBtn.classList.remove('hide');
		domTable.classList.remove('hide');
		domMsgBox.classList.add('hide');
	}
	// Auf Nachricht antworten
	else if (e.target.classList.contains('antworten')) {
		e.preventDefault();
		const domTable = document.getElementById('msg-table');
		const domMsgBox = document.getElementById('single-msg');
		const domMsgForm = document.getElementById('send-msg-form-wrapper');

		domMsgBox.classList.add('hide');
		domMsgForm.classList.remove('hide');
		domTable.classList.remove('hide');
		domRecipientInput.value = recipientUserName;
	}
	// Nachrichtenformular schließen
	else if (e.target.classList.contains('send-msg-close')) {
		clearForm();
	}
	// Neue Nachricht schreiben
	else if (e.target.classList.contains('profile-msgs__new-msg')) {
		const domMsgForm = document.getElementById('send-msg-form-wrapper');
		domMsgForm.classList.remove('hide');
	}
	// Nachricht an User aus der Friendlist
	else if (e.target.classList.contains('msg-to-friend')) {
		const domMsgForm = document.getElementById('send-msg-form-wrapper');
		const recipientName = e.target.dataset.friendname;
		domRecipientInput.value = recipientName;
		domMsgForm.classList.remove('hide');
	}
	// User aus der Friendlist löschen
	else if (e.target.classList.contains('delete-friend')) {
		const friendName = e.target.dataset.friendname;
		deleteFriend(friendName);
	}
	// Freundschaftsanfrage verschicken
	else if (e.target.classList.contains('profile-friends__add-friend')) {
		const domMsgForm = document.getElementById('send-msg-form-wrapper');

		domMsgForm.classList.remove('hide');
		domTitleInput.value = 'Freundschaftseinladung';
	}
	// Freundschaftsanfrage bestätigen
	else if (e.target.classList.contains('be-friend')) {
		clearForm();
		const friendName = e.target.dataset.username;
		confirmFriendship(friendName);
	}
	// Profil von anderen ansehen
	else if (e.target.classList.contains('view-profile')) {
		const username = e.target.innerText;
		getOtherProfile(username);
	}
	// Nachricht an Profilinhaber verschicken
	else if (e.target.classList.contains('other-profile-msg')) {
		const domMsgForm = document.getElementById('send-msg-form-wrapper');
		const recipientName = e.target.dataset.othername;
		domRecipientInput.value = recipientName;
		otherProfileDom.classList.add('hide');
		domMsgForm.classList.remove('hide');
	}
	// FReundschaftsanfrage an Profilinhaber senden
	else if (e.target.classList.contains('other-profile-friend')) {
		const domMsgForm = document.getElementById('send-msg-form-wrapper');
		const recipientName = e.target.dataset.othername;
		domRecipientInput.value = recipientName;
		otherProfileDom.classList.add('hide');
		domMsgForm.classList.remove('hide');
		domTitleInput.value = 'Freundschaftseinladung';
	}
	// Spiel verlassen
	else if (
		e.target.classList.contains('game-leave__leave-game') ||
		e.target.classList.contains('game-over__leave-game')
	) {
		location.replace(base_url);
	}
	// Im Spiel bleiben
	else if (e.target.classList.contains('game-leave__stay-game')) {
		hideGameLeave();
	}
}

const sendButton = document.getElementById('send-msg-send');
const domRecipientInput = document.getElementById('send-msg-recipient');
const domTitleInput = document.getElementById('send-msg-title');
const domBodyTextarea = document.getElementById('send-msg-body');
let recipientUserName = false;

// Fehler werden entfernt bei Fokusierung der Input-Felder
domRecipientInput.addEventListener('focus', function() {
	clearForm('errorsonly');
});
domTitleInput.addEventListener('focus', function() {
	clearForm('errorsonly');
});
domBodyTextarea.addEventListener('focus', function() {
	clearForm('errorsonly');
});

/**
 * Stellt alle Nachrichten dar
 * passt Formatierung an
 * @param {object} msgs
 */
function renderMessages(msgs) {
	const domTableBody = document.getElementById('msg-table-body');
	if (msgs.length > 0) {
		domTableBody.innerHTML = '';
		msgs.forEach(msg => {
			let msgTable;
			let tableRow = document.createElement('TR');
			tableRow.className = 'msg-tr';
			tableRow.dataset.msgid = msg.msg_id;
			let msgBody = shortenString(msg.msg_body);
			let date = convertDate(msg.created_at);
			let seen = seenOrNot(msg.msg_seen);
			let profile = deletedorNot(msg.user_name);

			msgTable = `
				<td ><span class="${profile}">${msg.user_name}</span></td> 
				<td data-msgid="${msg.msg_id}" class="view-msg">${date}</td>
				<td data-msgid="${msg.msg_id}" class="view-msg">${msg.msg_title}</td>
				<td data-msgid="${msg.msg_id}" class="view-msg">${msgBody}</td>
				<td data-msgid="${msg.msg_id}" class="view-msg">${seen}</td>
			`;
			tableRow.innerHTML = msgTable;
			domTableBody.appendChild(tableRow);
		});
	} else {
		domTableBody.innerHTML = 'Keine Nachrichten';
	}
}

/**
 * Stellt Fehler dar
 * @param {object} errors
 */
function renderMsgErrors(errors) {
	if (errors.hasOwnProperty('recipient')) {
		document.getElementById('recipient-error').innerHTML = errors.recipient;
	}
	if (errors.hasOwnProperty('title')) {
		document.getElementById('title-error').innerHTML = errors.title;
	}
	if (errors.hasOwnProperty('body')) {
		document.getElementById('body-error').innerHTML = errors.body;
	}
}

/**
 * Rendert Einzelansicht einer Nachricht
 * Versteckt Ansicht aller Nachrichten
 * Bei speziellen Nachrichtenarten werden Buttons dargestellt
 * @param {object} msg
 */
function renderSingleMessage(msg) {
	msg = msg[0];
	const domTableBody = document.getElementById('msg-table');
	const domMsgBox = document.getElementById('single-msg');
	const domMsgTitle = document.getElementById('msg-title');
	const domMsgSender = document.getElementById('msg-sender');
	const domMsgBody = document.getElementById('msg-body');
	const nemMsgBtn = document.getElementById('profile-msgs__new-msg');

	if (msg.msg_title == 'Freundschaftseinladung') {
		const buttonArea = document.getElementById('specialpurpose');
		buttonArea.innerHTML = `<button data-username="${
			msg.user_name
		}" class="be-friend btn">Anfrage annehmen</button>`;
	}
	if (msg.msg_title == 'Spieleinladung') {
		const buttonArea = document.getElementById('specialpurpose');
		const url = base_url + '/join';
		buttonArea.innerHTML = `<a href="${url}/${
			msg.user_name
		}" class="join-game btn ">Spiel beitreten</a>`;
	}
	recipientUserName = msg.user_name;
	domTableBody.classList.add('hide');
	nemMsgBtn.classList.add('hide');
	domMsgBox.classList.remove('hide');
	domMsgTitle.innerHTML = msg.msg_title;
	domMsgSender.innerHTML = 'von: '+msg.user_name;
	domMsgBody.innerHTML = msg.msg_body;
}

/**
 * Bestellt frische Nachrichten vom Server
 * ruft Renderung auf, wenns welche gibt
 */
function getMessages() {
	let xhr = new XMLHttpRequest();
	xhr.open('GET', base_url + '/messages');
	xhr.send();
	xhr.onload = function() {
		if (isJson(xhr.response)) {
			const data = JSON.parse(xhr.response);
			if (data.msgs) {
				renderMessages(data.msgs);
			} else {
				console.log('no Messages');
			}
		}
	};
}

/**
 * Leert die Input-Felder
 * Oder entfernt Fehleranzeigen
 * @param {string} errorsonly
 */
function clearForm(errorsonly = false) {
	if (errorsonly) {
		const errors = document.querySelectorAll('.error-box');
		errors.forEach(el => {
			el.innerHTML = '';
		});
	} else {
		const domMsgForm = document.getElementById('send-msg-form-wrapper');
		const buttonArea = document.getElementById('specialpurpose');
		buttonArea.innerHTML = '';
		domMsgForm.classList.add('hide');
		domRecipientInput.value = '';
		domTitleInput.value = '';
		domBodyTextarea.value = '';
	}
}

/**
 * Holt einzelne Nachricht vom Server anhand der Nachrichten-ID
 * Ruft Darstellung der Nachricht auf, wenn vorhanden
 * @param {number} msg_id
 */
function getSingleMessage(msg_id) {
	let xhr = new XMLHttpRequest();
	xhr.open('GET', base_url + '/messages/' + msg_id);
	xhr.send();
	xhr.onload = function() {
		if (isJson(xhr.response)) {
			const data = JSON.parse(xhr.response);
			if (data.msg) {
				renderSingleMessage(data.msg);
			} else {
				console.log('no Message');
			}
		}
	};
}

/**
 * Verschickt Nachricht an Server
 * Empfänger, Betreff und Nachricht werden übergeben
 * Erfolg leert und enfernt das Formular
 * Erfolgsnachricht wird dargestellt
 * Fehler werden dem Nutzer angezeigt
 * @param {event} e
 */
function sendMessage(e) {
	e.preventDefault();
	const recipient = domRecipientInput.value;
	const title = domTitleInput.value;
	const body = domBodyTextarea.value;

	let data = new FormData();
	data.append('recipient', recipient);
	data.append('title', title);
	data.append('body', body);
	let xhr = new XMLHttpRequest();
	xhr.open('POST', base_url + '/messages/create');
	xhr.send(data);
	xhr.onload = function() {
		if (isJson(xhr.response)) {
			const data = JSON.parse(xhr.response);
			if (data.success) {
				const feedback = document.getElementById('async-feedback');
				feedback.classList.add('async-feedback--flash')
				feedback.classList.add('async-feedback');
				feedback.innerHTML = `Nachricht wurde verschickt`;
				clearForm();
			} else {
				renderMsgErrors(data.errors);
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

/**
 * Wandelt SQL datetime in ordentliches Datum um
 * @param {string} dateString
 */
function convertDate(dateString) {
	var date = new Date(dateString);
	return date.getDate() + '.' + date.getMonth() + '.' + date.getFullYear();
}

/**
 * Kürzt Nachrichten in der Mehrfach-Ansicht
 * @param {string} string
 */
function shortenString(string) {
	if (string.length > 25) {
		return string.substring(0, 24) + '...';
	} else {
		return string;
	}
}

/**
 * Stellt dar, ob Nachrichten gelesen wurden, oder nicht
 * @param {string} string
 */
function seenOrNot(string) {
	let eyeCon = `
        <div class="eyeCon--seen"></div>
    `;
	if (string == '0') {
		eyeCon = `
        <div class="eyeCon--unseen"></div>
    `;
		return eyeCon;
	}
	return eyeCon;
}

/**
 * Überprüft, ob User gelöscht wurde
 * erlaubt/verbietet das ansehen von Profilen
 * 
 * @param {string} user_name 
 */
function deletedorNot(user_name){
	let className = 'view-profile';
	if(user_name == 'deleted') {
		className = '';
	}
	return className;
}

const infoBtn = document.getElementById('game-info');

/**
 * Öffnet ein Overlay mit Hilfstext
 */
function showGameInfo() {
	const info = document.getElementById('game-info__wrapper');
	const gameMenuCloseBtn = document.getElementById('dashboard-close-btn');
	const otherGameMenuBtns = document.querySelectorAll('.game-menu__btn');

	gameMenuCloseBtn.classList.remove('hide');
	otherGameMenuBtns.forEach(btn => {
		btn.classList.add('hide');
	});

	info.classList.remove('hide');
	info.classList.add('show');
}

/**
 * Schliesst das Overlay mit Hilfstext
 */
function hideGameInfo() {
	const info = document.getElementById('game-info__wrapper');
	const gameMenuCloseBtn = document.getElementById('dashboard-close-btn');
	const otherGameMenuBtns = document.querySelectorAll('.game-menu__btn');

	otherGameMenuBtns.forEach(btn => {
		btn.classList.remove('hide');
	});
	info.classList.add('hider');
	gameMenuCloseBtn.classList.add('hide');
	setTimeout(() => {
		info.classList.add('hide');
		info.classList.remove('hider');
		
	}, 900);
	info.classList.remove('show');
}

const leaveBtn = document.getElementById('game-leave');

/**
 * Öffnet ein Overlay mit Auswahl zur Verlassung des Spiels
 */
function showGameLeave() {
	const leave = document.getElementById('game-leave__wrapper');
	const gameMenuCloseBtn = document.getElementById('dashboard-close-btn');
	const otherGameMenuBtns = document.querySelectorAll('.game-menu__btn');

	gameMenuCloseBtn.classList.remove('hide');
	otherGameMenuBtns.forEach(btn => {
		btn.classList.add('hide');
	});

	leave.classList.remove('hide');
	leave.classList.add('show');
}

/**
 * Schliesst das Overlay mit Auswahl zur Verlassung des Spiels
 */
function hideGameLeave() {
	const leave = document.getElementById('game-leave__wrapper');
	const gameMenuCloseBtn = document.getElementById('dashboard-close-btn');
	const otherGameMenuBtns = document.querySelectorAll('.game-menu__btn');

	otherGameMenuBtns.forEach(btn => {
		btn.classList.remove('hide');
	});
	leave.classList.add('hider');
	gameMenuCloseBtn.classList.add('hide');
	setTimeout(() => {
		leave.classList.add('hide');
		leave.classList.remove('hider');
	}, 900);
	leave.classList.remove('show');
}

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

/**
 * Stellt die Freunde eines Users dar
 * @param {object} friends 
 */
function renderFriends(friends) {
	const domTableBody = document.getElementById('friend-table-body');
	if (friends.length > 0) {
		domTableBody.innerHTML = '';
		friends.forEach(friend => {
			let friendTable;
			let tableRow = document.createElement('TR');
			tableRow.className = 'table__row';
			tableRow.dataset.friendid = friend.friend_id;

			friendTable = `
				<td class="table__data ">
				<span class="view-profile">${friend.user_name}</span>
				</td>
				<td class="table__data">
				<div class="online-${friend.online} table__data--small"></div>
				</td>
                <td class="table__data table__data--small"><div data-friendname="${
					friend.user_name
				}" class="msg-to-friend table__btn"></div></td>
                <td class="table__data table__data--small"><div data-friendname="${
					friend.friendship_id
				}" class="delete-friend table__btn"></div></td>
            `;
			tableRow.innerHTML = friendTable;
			domTableBody.appendChild(tableRow);
		});
	} else {
		domTableBody.innerHTML = '<p>Keine Freunde</p>';
	}
}

/**
 * Holt Freunde vom Server
 * rendert nach Bedarf
 */
function getFriends() {
	let xhr = new XMLHttpRequest();
	xhr.open('GET', base_url + '/friends');
	xhr.send();
	xhr.onload = function() {
		if (isJson(xhr.response)) {
			const data = JSON.parse(xhr.response);
			if (data.friends) {
				renderFriends(data.friends);
			} else {
				console.log('no Messages');
			}
		}
	};
}

/**
 * Löst Freundschaft auf
 * rendert neu
 * @param {number} friendship_id 
 */
function deleteFriend(friendship_id) {
	let xhr = new XMLHttpRequest();
	xhr.open('GET', base_url + '/friends/' + friendship_id);
	xhr.send();
	xhr.onload = function() {
		console.log(xhr.response);
		if (isJson(xhr.response)) {
			const data = JSON.parse(xhr.response);
			if (data.success) {
				getFriends();
			} else {
				console.log('no Message');
			}
		}
	};
}

/**
 * Speichert Freundschaft nach Bestätigung einer Freundschaftsanfrage
 * rendert Freundesliste neu
 * zeigt Erfolgsmeldung
 *  @param {string} friendName 
 */
function confirmFriendship(friendName) {

	let data = new FormData();
	data.append('friend_name', friendName);
	let xhr = new XMLHttpRequest();
	xhr.open('POST', base_url + '/friends/add');
	xhr.send(data);
	xhr.onload = function() {
		if (isJson(xhr.response)) {
			const data = JSON.parse(xhr.response);
			if (data.success) {
                getFriends();
				const feedback = document.getElementById('async-feedback');
				feedback.classList.add('async-feedback--flash');
				feedback.classList.add('async-feedback');
				feedback.innerHTML = `Freundschaft wurde bestätigt`;
			} else {
				console.log(data.errors);
			}
		}
	};
}

/**
 * Erzeugt ein simples Barchart für die Anzeige der gewonnenen und verlorenen Spiele
 */
function renderWonAndLost() {
	const lostBar = document.getElementById('games-lost-bar');
	const lost = document.getElementById('games-lost').textContent;
	const wonBar = document.getElementById('games-won-bar');
    const won = document.getElementById('games-won').textContent;

    const lostWidth = parseInt(lost) * 1 + 1.4;
    const wonWidth = parseInt(won) * 1 + 1.4;

    lostBar.style.width = lostWidth+'rem';
    wonBar.style.width = wonWidth+'rem';
}

const editBtn = document.getElementById('profile-edit-btn');
const saveBtn = document.getElementById('profile-edit-save-btn');
const profileEditBackBtn = document.getElementById('profile-edit-back-btn');
const profileDeleteBackBtn = document.getElementById('profile-delete-back-btn');
const profileSettBackBtn = document.getElementById('profile-settings-back-btn');
const settingsBtn = document.getElementById('profile-settings-btn');
const ageInput = document.getElementById('profile-age-input');
const cityInput = document.getElementById('profile-city-input');
const profileDeleteBtn = document.getElementById('profile-delete-btn');
const profileDeleteConfirmBtn = document.getElementById('profile-delete-confirm-btn');

// Eröffnet eine zusätzliche Konfirmation zur Verlöschung der Kontung
profileDeleteBtn.addEventListener('click', function() {
	const profileDeleteConfirmBox = document.getElementById('profile-delete-confirm');
	profileDeleteConfirmBox.classList.remove('hide');
	profileDeleteBtn.classList.add('hide');
});
// Putzt die Inputs aus, wenn man wieder zu schreiben beginnt
ageInput.addEventListener('focus', function() {
	clearEditForm('errorsonly');
});
cityInput.addEventListener('focus', function() {
	clearEditForm('errorsonly');
});

/**
 * Zeigt die Editierungs-Inputs und Buttons an
 * Versteckt die Profile-View Buttons
 */
function showEdit() {
	const domProfilForm = document.getElementById('own-profile__edit');
	const profileViewBtnBox = document.getElementById('profile-view-btn-box');
	const formViewBtnBox = document.getElementById('profile-edit-view-btn-box');
	const infoText = document.getElementById('profile-info-text');

	infoText.classList.remove('hide');
	profileViewBtnBox.classList.add('hide');
	formViewBtnBox.classList.remove('hide');
	domProfilForm.classList.remove('hide');
}

/**
 * Versteckt die Editierungsoptionen und Buttons
 * Stellt den Profile-View wieder her
 */
function backToProfileViewFromEdit() {
	const domProfilForm = document.getElementById('own-profile__edit');
	const profileViewBtnBox = document.getElementById('profile-view-btn-box');
	const formViewBtnBox = document.getElementById('profile-edit-view-btn-box');
	const infoText = document.getElementById('profile-info-text');

	infoText.classList.add('hide');
	profileViewBtnBox.classList.remove('hide');
	formViewBtnBox.classList.add('hide');
	domProfilForm.classList.add('hide');

	clearEditForm('errorsonly');
	clearEditForm();
}

/**
 * Versteckt die Einstellungs-Ansicht
 * Stellt den Profile-View wieder her
 */
function backToProfileViewFromSett() {
	const settingContainer = document.getElementById('profile-settings');
	const profileContent = document.getElementById('profile-content');
	const profileViewBtnBox = document.getElementById('profile-view-btn-box');
	const profileSettBtnBox = document.getElementById('profile-settings-view-btn-box');
	const profileDeleteConfirmBox = document.getElementById('profile-delete-confirm');

	profileDeleteConfirmBox.classList.add('hide');
	profileDeleteBtn.classList.remove('hide');
	profileSettBtnBox.classList.add('hide');
	profileViewBtnBox.classList.remove('hide');
	settingContainer.classList.add('hide');
	profileContent.classList.remove('hide');
}

/**
 * Zeigt das Einstellungs-Menü
 * Versteckt den Profile-View
 */
function showSettings() {
	const settingContainer = document.getElementById('profile-settings');
	const profileContent = document.getElementById('profile-content');
	const profileViewBtnBox = document.getElementById('profile-view-btn-box');
	const profileSettBtnBox = document.getElementById('profile-settings-view-btn-box');

	profileSettBtnBox.classList.remove('hide');
	profileViewBtnBox.classList.add('hide');
	settingContainer.classList.remove('hide');
	profileContent.classList.add('hide');
}

/**
 * Speichert Alter, Geschlecht oder Stadt nach Eingabe
 * Übernimmt die alten Werte, falls keine übergeben werden
 * Erfolg stellt die Profil-Ansicht mit den neuen Werten her
 * Fehler werden dem User dargestellt
 */
function saveProfile() {
	const sexDom = document.getElementById('own-profile-sex');
	const ageDom = document.getElementById('own-profile-age');
	const cityDom = document.getElementById('own-profile-city');
	let ageInput = document.getElementById('profile-age-input').value;
	let sexInput = document.querySelector('input[name="profile-sex-radio"]:checked');
	let cityInput = document.getElementById('profile-city-input').value;

	if (!sexInput) {
		sexInput = sexDom.textContent;
		sexInput = sexInput.replace(/(\r\n|\n|\r|\s)/gm, '');
	} else {
		sexInput = sexInput.value;
	}
	if (ageInput == '') {
		ageInput = ageDom.textContent;
		ageInput = parseInt(ageInput);
	}
	if (cityInput == '') {
		cityInput = cityDom.textContent;
		cityInput = cityInput.replace(/(\r\n|\n|\r|\s)/gm, '');
	}

	let data = new FormData();
	data.append('age', ageInput);
	data.append('sex', sexInput);
	data.append('city', cityInput);
	let xhr = new XMLHttpRequest();
	xhr.open('POST', base_url + '/profile/edit');
	xhr.send(data);
	xhr.onload = function() {
		if (isJson(xhr.response)) {
			const data = JSON.parse(xhr.response);
			if (data.success) {
				const feedback = document.getElementById('async-feedback');
				feedback.classList.add('async-feedback--flash');
				feedback.classList.add('async-feedback');
				feedback.innerHTML = `Profil wurde gespeichert`;
				sexDom.innerHTML = sexInput;
				ageDom.innerHTML = ageInput;
				cityDom.innerHTML = cityInput;
				clearEditForm();
				backToProfileViewFromEdit();
			} else {
				renderEditErrors(data.errors);
			}
		}
	};
}

/**
 * Löscht das Benutzerprofil nach Passwort-Eingabe
 * Bei Erfolg wird ein Redirect zur Homepage ausgeführt
 * Fehler werden dem User dargestellt
 */
function deleteProfile() {
	const password = document.getElementById('profil-delete-password');
	const passwordError = document.getElementById('profile-delete-error');

	if (password.value == '') {
		passwordError.innerHTML = 'Passwort fehlt';
	} else {
		let data = new FormData();
		data.append('password', password.value);
		let xhr = new XMLHttpRequest();
		xhr.open('POST', base_url + '/profile/delete');
		xhr.send(data);
		xhr.onload = function() {
			if (isJson(xhr.response)) {
				const data = JSON.parse(xhr.response);
				if (data.success) {
					window.location.replace(base_url);
				} else {
					renderEditErrors(data.errors);
				}
			}
		};
	}
}

/**
 * Stellt Fehler nach Art in den passenden Feldern dar
 * @param {object} errors
 */
function renderEditErrors(errors) {
	if (errors.hasOwnProperty('age')) {
		document.getElementById('profile-age-error').innerHTML = errors.age;
	}
	if (errors.hasOwnProperty('sex')) {
		document.getElementById('profile-sex-error').innerHTML = errors.sex;
	}
	if (errors.hasOwnProperty('city')) {
		document.getElementById('profile-city-error').innerHTML = errors.city;
	}
	if (errors.hasOwnProperty('password')) {
		document.getElementById('profile-delete-error').innerHTML = errors.password;
	}
}

/**
 * Leert die Input-Felder
 * Oder entfernt Fehleranzeigen
 * @param {string} errorsonly
 */
function clearEditForm(errorsonly = false) {
	if (errorsonly) {
		const errors = document.querySelectorAll('.error-profile');
		errors.forEach(el => {
			el.innerHTML = '';
		});
	} else {
		const ageInput = document.getElementById('profile-age-input');
		const cityInput = document.getElementById('profile-city-input');
		ageInput.value = '';
		cityInput.value = '';
	}
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

const otherUsers = document.querySelectorAll('.view-profile');
const otherProfileDom = document.getElementById('other-profile-wrapper');
const otherProfileClose = document.getElementById('other-profile-close');

otherProfileClose.addEventListener('click', () => {
	otherProfileDom.classList.add('hide');
});

/**
 * Holt die Profildaten eines Users
 * 
 * @param {string} username 
 */
function getOtherProfile(username) {

	let data = new FormData();
	data.append('username', username);
	let xhr = new XMLHttpRequest();
	xhr.open('POST', base_url + '/users/show');
	xhr.send(data);
	xhr.onload = function() {
		if (isJson(xhr.response)) {
			const data = JSON.parse(xhr.response);
			if (data.success) {
				renderOtherProfile(data.profile);
				otherProfileDom.classList.remove('hide');
			} else {
				console.log(data);
			}
		}
	};
}

/**
 * Erzeugt ein simples Barchart für die Anzeige der gewonnenen und verlorenen Spiele
 */
function renderWonAndLostOther() {
	const lostBar = document.getElementById('other-games-lost-bar');
	const lost = document.getElementById('other-profile-lost').textContent;
	const wonBar = document.getElementById('other-games-won-bar');
	const won = document.getElementById('other-profile-won').textContent;

	const lostWidth = parseInt(lost) * 1 + 1.4;
	const wonWidth = parseInt(won) * 1 + 1.4;

	lostBar.style.width = lostWidth + 'rem';
	wonBar.style.width = wonWidth + 'rem';
}

/**
 * Stellt das Profil eines anderen Nutzers dar
 * 
 * @param {object} data 
 */
function renderOtherProfile(data) {
	const otherProfileName = document.getElementById('other-profile-name');
	const otherProfileSex = document.getElementById('other-profile-sex');
	const otherProfileAge = document.getElementById('other-profile-age');
	const otherProfileCity = document.getElementById('other-profile-city');
	const otherProfileOnline = document.getElementById('other-profile-online');
	const otherProfileWon = document.getElementById('other-profile-won');
	const otherProfileLost = document.getElementById('other-profile-lost');
	const otherProfileFriend = document.getElementById('other-profile-friend');
	const otherProfileMsg = document.getElementById('other-profile-msg');

	otherProfileName.innerHTML = data.user_name;
	otherProfileSex.innerHTML = data.user_sex ? data.user_sex : 'Keine Angabe';
	otherProfileAge.innerHTML = data.user_age ? data.user_age : 'Keine Angabe';
	otherProfileCity.innerHTML = data.user_city ? data.user_city : 'Keine Angabe';
	otherProfileWon.innerHTML = data.games_won;
	otherProfileLost.innerHTML = data.games_lost;

	if (data.online > 0) {
		otherProfileOnline.innerHTML = '<div class="online-1"></div>online';
	} else {
		otherProfileOnline.innerHTML = '<div class="online-0"></div>offline';
	}

	if (data.friend) {
		otherProfileFriend.innerHTML = '<h5 class="other-profile__btn-box__item__text">Freund</h5>';
	}else{
		otherProfileFriend.innerHTML = `
		<div  data-othername="${data.user_name}" class="other-profile-friend other-profile__friend-btn"></div>`;
    }

	otherProfileMsg.innerHTML = `
	<div data-othername="${data.user_name}" class="other-profile-msg other-profile__msg-btn"></div>`;

	renderWonAndLostOther();
}
