const dashboard = document.getElementById('dashboard');
const dashboardBtn = document.getElementById('dashboard-btn');
const base_url = window.location.origin;

document.addEventListener(
	'DOMContentLoaded',
	function() {
		dashboardBtn.addEventListener('click', showDashboard);
		sendButton.addEventListener('click', sendMessage);
		document.addEventListener('click', differentActions);
	},
	false
);

function showDashboard(e) {
	getMessages();
	dashboard.classList.toggle('hide');
}
