const dashboard = document.getElementById('dashboard');
const dashboardBtn = document.getElementById('dashboard-btn');


document.addEventListener(
	'DOMContentLoaded',
	function() {
        dashboardBtn.addEventListener('click', showDashboard);
        sendButton.addEventListener('click', sendMessage);
		dashboard.addEventListener('click', differentActions);
	},
	false
);

function showDashboard(e) {
	getMessages();
	dashboard.classList.toggle('hide');
}