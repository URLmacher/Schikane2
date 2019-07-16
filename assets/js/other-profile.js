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
		otherProfileOnline.innerHTML = 'online';
	} else {
		otherProfileOnline.innerHTML = 'offline';
	}

	if (data.friend) {
		otherProfileFriend.innerHTML = '<h5>Freund</h5>';
	}else{
        otherProfileFriend.innerHTML =
			`<a href="#" data-othername="${data.user_name}" class="other-profile-friend card-link">Freundschaft</a>`;
    }

    otherProfileMsg.innerHTML = `<a href="#" data-othername="${data.user_name}" class="card-link other-profile-msg">Nachricht</a>`;
}
