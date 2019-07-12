const editBtn = document.getElementById('profile-edit-btn');
const saveBtn = document.getElementById('profile-edit-save-btn');
const profileEditBackBtn = document.getElementById('profile-edit-back-btn');
const profileDeleteBackBtn = document.getElementById('profile-delete-back-btn');
const profileSettBackBtn = document.getElementById('profile-settings-back-btn');
const settingsBtn = document.getElementById('profile-settings-btn');
const ageInput = document.getElementById('profile-age-input');
const cityInput = document.getElementById('profile-city-input');
const profileDeleteBtn = document.getElementById('profile-delete-btn');
const profileDeleteConfirmBtn = document.getElementById('profile-delete-confirm-btn');

profileDeleteBtn.addEventListener('click', function() {
	const profileDeleteConfirmBox = document.getElementById('profile-delete-confirm');
	profileDeleteConfirmBox.classList.remove('hide');
	profileDeleteBtn.classList.add('hide');
});
ageInput.addEventListener('focus', function() {
	clearEditForm('errorsonly');
});
cityInput.addEventListener('focus', function() {
	clearEditForm('errorsonly');
});

function showEdit() {
	const domProfilForm = document.querySelectorAll('.profile-edit-form');
	const profileViewBtnBox = document.getElementById('profile-view-btn-box');
	const formViewBtnBox = document.getElementById('profile-edit-view-btn-box');

	profileViewBtnBox.classList.add('hide');
	formViewBtnBox.classList.remove('hide');
	domProfilForm.forEach(el => {
		el.classList.remove('hide');
	});
}

function backToProfileViewFromEdit() {
	const domProfilForm = document.querySelectorAll('.profile-edit-form');
	const profileViewBtnBox = document.getElementById('profile-view-btn-box');
	const formViewBtnBox = document.getElementById('profile-edit-view-btn-box');

	profileViewBtnBox.classList.remove('hide');
	formViewBtnBox.classList.add('hide');
	domProfilForm.forEach(el => {
		el.classList.add('hide');
	});
	clearEditForm('errorsonly');
	clearEditForm();
}

function backToProfileViewFromSett() {
	const settingContainer = document.getElementById('profile-settings');
	const profileContent = document.getElementById('profile-content');
	const profileViewBtnBox = document.getElementById('profile-view-btn-box');
	const profileSettBtnBox = document.getElementById('profile-settings-view-btn-box');
	const infoText = document.getElementById('profile-info-text');
	const profileDeleteConfirmBox = document.getElementById('profile-delete-confirm');

	profileDeleteConfirmBox.classList.add('hide');
	profileDeleteBtn.classList.remove('hide');
	profileSettBtnBox.classList.add('hide');
	profileViewBtnBox.classList.remove('hide');
	infoText.classList.remove('hide');
	settingContainer.classList.add('hide');
	profileContent.classList.remove('hide');
}

function showSettings() {
	const settingContainer = document.getElementById('profile-settings');
	const profileContent = document.getElementById('profile-content');
	const profileViewBtnBox = document.getElementById('profile-view-btn-box');
	const profileSettBtnBox = document.getElementById('profile-settings-view-btn-box');
	const infoText = document.getElementById('profile-info-text');

	profileSettBtnBox.classList.remove('hide');
	profileViewBtnBox.classList.add('hide');
	infoText.classList.add('hide');
	settingContainer.classList.remove('hide');
	profileContent.classList.add('hide');
}

function saveProfile() {
	const sexSpan = document.getElementById('own-profile-sex');
	const ageSpan = document.getElementById('own-profile-age');
	const citySpan = document.getElementById('own-profile-city');
	let ageInput = document.getElementById('profile-age-input').value;
	let sexInput = document.querySelector('input[name="profile-sex-radio"]:checked');
	let cityInput = document.getElementById('profile-city-input').value;

	if (!sexInput) {
		sexInput = sexSpan.textContent;
		sexInput = sexInput.replace(/(\r\n|\n|\r|\s)/gm, '');
	} else {
		sexInput = sexInput.value;
	}
	if (ageInput == '') {
		ageInput = ageSpan.textContent;
		ageInput = parseInt(ageInput);
	}
	if (cityInput == '') {
		cityInput = citySpan.textContent;
	}

	let data = new FormData();
	data.append('age', ageInput);
	data.append('sex', sexInput);
	data.append('city', cityInput);
	let xhr = new XMLHttpRequest();
	xhr.open('POST', base_url + '/profile/edit');
	xhr.send(data);
	xhr.onload = function() {
		if (isJson(xhr.response)) {
			const data = JSON.parse(xhr.response);
			if (data.success) {
				const feedback = document.getElementById('async-feedback');

				feedback.innerHTML = `<p class='alert alert-success'>Profil wurde gespeichert</p>`;
				sexSpan.innerHTML = sexInput;
				ageSpan.innerHTML = ageInput;
				citySpan.innerHTML = cityInput;
				clearEditForm();
				backToProfileViewFromEdit();
			} else {
				renderEditErrors(data.errors);
			}
		}
	};
}

function deleteProfile() {
    const password = document.getElementById('profil-delete-password');
    const passwordError = document.getElementById('profile-delete-error');

    if(password.value == '') {
        passwordError.innerHTML='Passwort fehlt';
    }else{
        console.log('Profil wird gelöscht...');
    }
}

function renderEditErrors(errors) {
	if (errors.hasOwnProperty('age')) {
		document.getElementById('profile-age-error').innerHTML = errors.age;
	}
	if (errors.hasOwnProperty('sex')) {
		document.getElementById('profile-sex-error').innerHTML = errors.sex;
	}
	if (errors.hasOwnProperty('city')) {
		document.getElementById('profile-city-error').innerHTML = errors.city;
	}
}

function clearEditForm(errorsonly = false) {
	if (errorsonly) {
		const errors = document.querySelectorAll('.error-profile');
		errors.forEach(el => {
			el.innerHTML = '';
		});
	} else {
		const ageInput = document.getElementById('profile-age-input');
		const cityInput = document.getElementById('profile-city-input');
		ageInput.value = '';
		cityInput.value = '';
	}
}

/**Überprüft, ob ein String JSON ist
 * zum Zwecke der Fehlerunterdrückung
 *
 * @param {string} item
 * @return {bool}
 */
function isJson(item) {
	item = typeof item !== 'string' ? JSON.stringify(item) : item;
	try {
		item = JSON.parse(item);
	} catch (e) {
		return false;
	}
	if (typeof item === 'object' && item !== null) {
		return true;
	}
	return false;
}
