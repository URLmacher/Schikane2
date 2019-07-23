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

			msgTable = `
				<td class="view-profile">${msg.user_name}</td> 
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

	if (msg.msg_title == 'Freundschaftseinladung') {
		const buttonArea = document.getElementById('specialpurpose');
		buttonArea.innerHTML = `<button data-username="${
			msg.user_name
		}" class="be-friend btn btn-primary">Anfrage annehmen</button>`;
	}
	if (msg.msg_title == 'Spieleinladung') {
		const buttonArea = document.getElementById('specialpurpose');
		const url = base_url + '/join';
		buttonArea.innerHTML = `<a href="${url}/${
			msg.user_name
		}" class="join-game btn btn-primary">Spiel beitreten</a>`;
	}
	recipientUserName = msg.user_name;
	domTableBody.classList.add('hide');
	domMsgBox.classList.remove('hide');
	domMsgTitle.innerHTML = msg.msg_title;
	domMsgSender.innerHTML = msg.user_name;
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

				feedback.innerHTML = `<p class='alert alert-success'>Nachricht wurde verschickt</p>`;
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
	return date.getDate() + '/' + date.getMonth() + '/' + date.getFullYear();
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
        <img class="eyeCon"  src="assets/images/eye-blocked.png"/> 
    `;
	if (string == 0) {
		eyeCon = `
        <img class="eyeCon"  src="assets/images/eye.png"/> 
    `;
		return eyeCon;
	}
	return eyeCon;
}
