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

    lostBar.style.width = lostWidth+'rem';
    wonBar.style.width = wonWidth+'rem';
}
