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
