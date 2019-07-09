const sendButton = document.getElementById('send-msg-send');
const domRecipientInput = document.getElementById('send-msg-recipient');
const domTitleInput = document.getElementById('send-msg-title');
const domBodyTextarea = document.getElementById('send-msg-body');
let recipientUserName = false;

domRecipientInput.addEventListener('focus', function() {
	clearForm('errorsonly');
});
domTitleInput.addEventListener('focus', function() {
	clearForm('errorsonly');
});
domBodyTextarea.addEventListener('focus', function() {
	clearForm('errorsonly');
});

function differentActions(e) {
	if (e.target.parentElement.classList.contains('msg-tr')) {
		const tableRow = e.target.parentElement;
		getSingleMessage(tableRow.dataset.msgid);
	} else if (e.target.classList.contains('back-to-table')) {
		getMessages();
		const domTable = document.getElementById('msg-table');
		const domMsgBox = document.getElementById('single-msg');
		domTable.classList.remove('hide');
		domMsgBox.classList.add('hide');
	} else if (e.target.classList.contains('antworten')) {
		e.preventDefault();
		const domTable = document.getElementById('msg-table');
		const domMsgBox = document.getElementById('single-msg');
		const domMsgForm = document.getElementById('send-msg-form-wrapper');

		domMsgBox.classList.add('hide');
		domMsgForm.classList.remove('hide');
		domTable.classList.remove('hide');
		domRecipientInput.value = recipientUserName;
	} else if (e.target.classList.contains('send-msg-close')) {
		clearForm();
	} else if (e.target.classList.contains('new-msg-btn')) {
		const domMsgForm = document.getElementById('send-msg-form-wrapper');

		domMsgForm.classList.remove('hide');
	} else if (e.target.classList.contains('msg-to-friend')) {
		const domMsgForm = document.getElementById('send-msg-form-wrapper');
		const recipientName = e.target.dataset.friendname;
		domRecipientInput.value = recipientName;
		domMsgForm.classList.remove('hide');
	} else if (e.target.classList.contains('delete-friend')) {
		const friendName = e.target.dataset.friendname;
		deleteFriend(friendName);
	} else if (e.target.classList.contains('new-friend-btn')) {
		const domMsgForm = document.getElementById('send-msg-form-wrapper');

		domMsgForm.classList.remove('hide');
		domTitleInput.value = 'Freundschaftseinladung';
	} else if (e.target.classList.contains('be-friend')) {
		clearForm();
		const friendName = e.target.dataset.username;
		confirmFriendship(friendName);
	}
}

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
				<td>${msg.user_name}</td>
				<td>${date}</td>
				<td>${msg.msg_title}</td>
				<td>${msgBody}</td>
				<td>${seen}</td>
			`;
			tableRow.innerHTML = msgTable;
			domTableBody.appendChild(tableRow);
		});
	} else {
		domTableBody.innerHTML = 'Keine Nachrichten';
	}
}

function renderErrors(errors) {
	console.log(errors);
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
	recipientUserName = msg.user_name;
	domTableBody.classList.add('hide');
	domMsgBox.classList.remove('hide');
	domMsgTitle.innerHTML = msg.msg_title;
	domMsgSender.innerHTML = msg.user_name;
	domMsgBody.innerHTML = msg.msg_body;
}

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
				console.log(data.errors);
				renderErrors(data.errors);
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

function convertDate(dateString) {
	var date = new Date(dateString);
	return date.getDate() + '/' + date.getMonth() + '/' + date.getFullYear();
}

function shortenString(string) {
	if (string.length > 25) {
		return string.substring(0, 24) + '...';
	} else {
		return string;
	}
}

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
