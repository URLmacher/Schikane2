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
