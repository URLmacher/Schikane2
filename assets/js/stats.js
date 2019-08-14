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

	lostBar.style.width = lostWidth + 'rem';
	wonBar.style.width = wonWidth + 'rem';
}

/**
 * Updates stats
 */
function getFreshStats() {
	const lost = document.getElementById('games-lost');
    const won = document.getElementById('games-won');
    const lostBar = document.getElementById('games-lost-bar');
    const wonBar = document.getElementById('games-won-bar');

	let xhr = new XMLHttpRequest();
	xhr.open('GET', base_url + '/profile/stats');
	xhr.send();
	xhr.onload = function() {
		if (isJson(xhr.response)) {
			const data = JSON.parse(xhr.response);
			if (data.stats) {
				lost.innerHTML = data.stats[0].games_lost;
                won.innerHTML = data.stats[0].games_won;
                const lostWidth = parseInt(data.stats[0].games_lost) * 1 + 1.4;
				const wonWidth = parseInt(data.stats[0].games_won) * 1 + 1.4;

				lostBar.style.width = lostWidth + 'rem';
				wonBar.style.width = wonWidth + 'rem';
			} else {
				console.log('no Stats');
			}
		}
	};
}
