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
		saveBtn.addEventListener('click', saveProfile);
		document.addEventListener('click', differentActions);
	},
	false
);

/**
 * Nach Betätigung eines Buttons wird das Dashboard angezeigt
 * Nachrichten und Freunde werden vom Server geholt
 * Nach erneuter Betätigung schliesst das Dashboard wieder
 */
function showDashboard() {
	getMessages();
	getFriends();
	dashboard.classList.toggle('hide');
}

/**
 * Verschieden Aktionen werden durch Interaktion mit
 * dynamisch generierten Elementen ausgeführt
 * @param {event} e
 */
function differentActions(e) {
	if (e.target.classList.contains('view-msg')) {
		getSingleMessage(e.target.dataset.msgid);
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
	} else if (e.target.classList.contains('view-profile')) {
		const username = e.target.innerText;
		getOtherProfile(username);
	} else if (e.target.classList.contains('other-profile-msg')) {
		const domMsgForm = document.getElementById('send-msg-form-wrapper');
		const recipientName = e.target.dataset.othername;
		domRecipientInput.value = recipientName;
		otherProfileDom.classList.add('hide');
		domMsgForm.classList.remove('hide');
	} else if (e.target.classList.contains('other-profile-friend')) {
		const domMsgForm = document.getElementById('send-msg-form-wrapper');
		const recipientName = e.target.dataset.othername;
		domRecipientInput.value = recipientName;
		otherProfileDom.classList.add('hide');
		domMsgForm.classList.remove('hide');
		domTitleInput.value = 'Freundschaftseinladung';
	} 
}
