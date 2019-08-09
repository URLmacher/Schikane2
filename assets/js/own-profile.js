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

// Eröffnet eine zusätzliche Konfirmation zur Verlöschung der Kontung
profileDeleteBtn.addEventListener('click', function() {
	const profileDeleteConfirmBox = document.getElementById('profile-delete-confirm');
	profileDeleteConfirmBox.classList.remove('hide');
	profileDeleteBtn.classList.add('hide');
});
// Putzt die Inputs aus, wenn man wieder zu schreiben beginnt
ageInput.addEventListener('focus', function() {
	clearEditForm('errorsonly');
});
cityInput.addEventListener('focus', function() {
	clearEditForm('errorsonly');
});

/**
 * Zeigt die Editierungs-Inputs und Buttons an
 * Versteckt die Profile-View Buttons
 */
function showEdit() {
	const domProfilForm = document.getElementById('own-profile__edit');
	const profileViewBtnBox = document.getElementById('profile-view-btn-box');
	const formViewBtnBox = document.getElementById('profile-edit-view-btn-box');
	const infoText = document.getElementById('profile-info-text');

	infoText.classList.remove('hide');
	profileViewBtnBox.classList.add('hide');
	formViewBtnBox.classList.remove('hide');
	domProfilForm.classList.remove('hide');
}

/**
 * Versteckt die Editierungsoptionen und Buttons
 * Stellt den Profile-View wieder her
 */
function backToProfileViewFromEdit() {
	const domProfilForm = document.getElementById('own-profile__edit');
	const profileViewBtnBox = document.getElementById('profile-view-btn-box');
	const formViewBtnBox = document.getElementById('profile-edit-view-btn-box');
	const infoText = document.getElementById('profile-info-text');

	infoText.classList.add('hide');
	profileViewBtnBox.classList.remove('hide');
	formViewBtnBox.classList.add('hide');
	domProfilForm.classList.add('hide');

	clearEditForm('errorsonly');
	clearEditForm();
}

/**
 * Versteckt die Einstellungs-Ansicht
 * Stellt den Profile-View wieder her
 */
function backToProfileViewFromSett() {
	const settingContainer = document.getElementById('profile-settings');
	const profileContent = document.getElementById('profile-content');
	const profileViewBtnBox = document.getElementById('profile-view-btn-box');
	const profileSettBtnBox = document.getElementById('profile-settings-view-btn-box');
	const profileDeleteConfirmBox = document.getElementById('profile-delete-confirm');

	profileDeleteConfirmBox.classList.add('hide');
	profileDeleteBtn.classList.remove('hide');
	profileSettBtnBox.classList.add('hide');
	profileViewBtnBox.classList.remove('hide');
	settingContainer.classList.add('hide');
	profileContent.classList.remove('hide');
}

/**
 * Zeigt das Einstellungs-Menü
 * Versteckt den Profile-View
 */
function showSettings() {
	const settingContainer = document.getElementById('profile-settings');
	const profileContent = document.getElementById('profile-content');
	const profileViewBtnBox = document.getElementById('profile-view-btn-box');
	const profileSettBtnBox = document.getElementById('profile-settings-view-btn-box');

	profileSettBtnBox.classList.remove('hide');
	profileViewBtnBox.classList.add('hide');
	settingContainer.classList.remove('hide');
	profileContent.classList.add('hide');
}

/**
 * Speichert Alter, Geschlecht oder Stadt nach Eingabe
 * Übernimmt die alten Werte, falls keine übergeben werden
 * Erfolg stellt die Profil-Ansicht mit den neuen Werten her
 * Fehler werden dem User dargestellt
 */
function saveProfile() {
	const sexDom = document.getElementById('own-profile-sex');
	const ageDom = document.getElementById('own-profile-age');
	const cityDom = document.getElementById('own-profile-city');
	let ageInput = document.getElementById('profile-age-input').value;
	let sexInput = document.querySelector('input[name="profile-sex-radio"]:checked');
	let cityInput = document.getElementById('profile-city-input').value;

	if (!sexInput) {
		sexInput = sexDom.textContent;
		sexInput = sexInput.replace(/(\r\n|\n|\r|\s)/gm, '');
	} else {
		sexInput = sexInput.value;
	}
	if (ageInput == '') {
		ageInput = ageDom.textContent;
		ageInput = parseInt(ageInput);
	}
	if (cityInput == '') {
		cityInput = cityDom.textContent;
		cityInput = cityInput.replace(/(\r\n|\n|\r|\s)/gm, '');
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
				feedback.innerHTML = `<p class=' async-feedback--flash async-feedback__text'>Profil wurde gespeichert</p>`;
				sexDom.innerHTML = sexInput;
				ageDom.innerHTML = ageInput;
				cityDom.innerHTML = cityInput;
				clearEditForm();
				backToProfileViewFromEdit();
			} else {
				renderEditErrors(data.errors);
			}
		}
	};
}

/**
 * Löscht das Benutzerprofil nach Passwort-Eingabe
 * Bei Erfolg wird ein Redirect zur Homepage ausgeführt
 * Fehler werden dem User dargestellt
 */
function deleteProfile() {
	const password = document.getElementById('profil-delete-password');
	const passwordError = document.getElementById('profile-delete-error');

	if (password.value == '') {
		passwordError.innerHTML = 'Passwort fehlt';
	} else {
		let data = new FormData();
		data.append('password', password.value);
		let xhr = new XMLHttpRequest();
		xhr.open('POST', base_url + '/profile/delete');
		xhr.send(data);
		xhr.onload = function() {
			if (isJson(xhr.response)) {
				const data = JSON.parse(xhr.response);
				if (data.success) {
					window.location.replace(base_url);
				} else {
					renderEditErrors(data.errors);
				}
			}
		};
	}
}

/**
 * Stellt Fehler nach Art in den passenden Feldern dar
 * @param {object} errors
 */
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
	if (errors.hasOwnProperty('password')) {
		document.getElementById('profile-delete-error').innerHTML = errors.password;
	}
}

/**
 * Leert die Input-Felder
 * Oder entfernt Fehleranzeigen
 * @param {string} errorsonly
 */
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
