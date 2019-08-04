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
