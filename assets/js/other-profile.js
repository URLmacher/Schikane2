const otherUsers = document.querySelectorAll('.view-profile');
const otherProfileDom = document.getElementById('other-profile-wrapper');
const otherProfileClose = document.getElementById('other-profile-close');

otherProfileClose.addEventListener('click', () => {
	otherProfileDom.classList.add('hide');
});

/**
 * Holt die Profildaten eines Users
 * 
 * @param {string} username 
 */
function getOtherProfile(username) {

	let data = new FormData();
	data.append('username', username);
	let xhr = new XMLHttpRequest();
	xhr.open('POST', base_url + '/users/show');
	xhr.send(data);
	xhr.onload = function() {
		if (isJson(xhr.response)) {
			const data = JSON.parse(xhr.response);
			if (data.success) {
				renderOtherProfile(data.profile);
				otherProfileDom.classList.remove('hide');
			} else {
				console.log(data);
			}
		}
	};
}

/**
 * Erzeugt ein simples Barchart für die Anzeige der gewonnenen und verlorenen Spiele
 */
function renderWonAndLostOther() {
	const lostBar = document.getElementById('other-games-lost-bar');
	const lost = document.getElementById('other-profile-lost').textContent;
	const wonBar = document.getElementById('other-games-won-bar');
	const won = document.getElementById('other-profile-won').textContent;

	const lostWidth = parseInt(lost) * 1 + 1.4;
	const wonWidth = parseInt(won) * 1 + 1.4;

	lostBar.style.width = lostWidth + 'rem';
	wonBar.style.width = wonWidth + 'rem';
}

/**
 * Stellt das Profil eines anderen Nutzers dar
 * 
 * @param {object} data 
 */
function renderOtherProfile(data) {
	const otherProfileName = document.getElementById('other-profile-name');
	const otherProfileSex = document.getElementById('other-profile-sex');
	const otherProfileAge = document.getElementById('other-profile-age');
	const otherProfileCity = document.getElementById('other-profile-city');
	const otherProfileOnline = document.getElementById('other-profile-online');
	const otherProfileWon = document.getElementById('other-profile-won');
	const otherProfileLost = document.getElementById('other-profile-lost');
	const otherProfileFriend = document.getElementById('other-profile-friend');
	const otherProfileMsg = document.getElementById('other-profile-msg');

	otherProfileName.innerHTML = data.user_name;
	otherProfileSex.innerHTML = data.user_sex ? data.user_sex : 'Keine Angabe';
	otherProfileAge.innerHTML = data.user_age ? data.user_age : 'Keine Angabe';
	otherProfileCity.innerHTML = data.user_city ? data.user_city : 'Keine Angabe';
	otherProfileWon.innerHTML = data.games_won;
	otherProfileLost.innerHTML = data.games_lost;

	if (data.online > 0) {
		otherProfileOnline.innerHTML = '<div class="online-1"></div>online';
	} else {
		otherProfileOnline.innerHTML = '<div class="online-0"></div>offline';
	}

	if (data.friend) {
		otherProfileFriend.innerHTML = '<h5 class="other-profile__btn-box__item__text">Freund</h5>';
	}else{
		otherProfileFriend.innerHTML = `
		<div  data-othername="${data.user_name}" class="other-profile-friend other-profile__friend-btn"></div>`;
    }

	otherProfileMsg.innerHTML = `
	<div data-othername="${data.user_name}" class="other-profile-msg other-profile__msg-btn"></div>`;

	renderWonAndLostOther();
}
