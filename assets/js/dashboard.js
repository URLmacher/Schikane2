const dashboard = document.getElementById('dashboard');
const dashboardBtn = document.getElementById('dashboard-btn');
const base_url = window.location.origin;

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

function showDashboard(e) {
	getMessages();
	getFriends();
	dashboard.classList.toggle('hide');
}

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
