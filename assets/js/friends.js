/**
 * Stellt die Freunde eines Users dar
 * @param {object} friends 
 */
function renderFriends(friends) {
	const domTableBody = document.getElementById('friend-table-body');
	if (friends.length > 0) {
		domTableBody.innerHTML = '';
		friends.forEach(friend => {
			let friendTable;
			let tableRow = document.createElement('TR');
			tableRow.className = 'table__row';
			tableRow.dataset.friendid = friend.friend_id;

			friendTable = `
				<td class="table__data ">
				<span class="view-profile">${friend.user_name}</span>
				</td>
				<td class="table__data">
				<div class="online-${friend.online} table__data--small"></div>
				</td>
                <td class="table__data table__data--small"><div data-friendname="${
					friend.user_name
				}" class="msg-to-friend table__btn"></div></td>
                <td class="table__data table__data--small"><div data-friendname="${
					friend.friendship_id
				}" class="delete-friend table__btn"></div></td>
            `;
			tableRow.innerHTML = friendTable;
			domTableBody.appendChild(tableRow);
		});
	} else {
		domTableBody.innerHTML = '<p>Keine Freunde</p>';
	}
}

/**
 * Holt Freunde vom Server
 * rendert nach Bedarf
 */
function getFriends() {
	let xhr = new XMLHttpRequest();
	xhr.open('GET', base_url + '/friends');
	xhr.send();
	xhr.onload = function() {
		if (isJson(xhr.response)) {
			const data = JSON.parse(xhr.response);
			if (data.friends) {
				renderFriends(data.friends);
			} else {
				console.log('no Messages');
			}
		}
	};
}

/**
 * Löst Freundschaft auf
 * rendert neu
 * @param {number} friendship_id 
 */
function deleteFriend(friendship_id) {
	let xhr = new XMLHttpRequest();
	xhr.open('GET', base_url + '/friends/' + friendship_id);
	xhr.send();
	xhr.onload = function() {
		console.log(xhr.response);
		if (isJson(xhr.response)) {
			const data = JSON.parse(xhr.response);
			if (data.success) {
				getFriends();
			} else {
				console.log('no Message');
			}
		}
	};
}

/**
 * Speichert Freundschaft nach Bestätigung einer Freundschaftsanfrage
 * rendert Freundesliste neu
 * zeigt Erfolgsmeldung
 *  @param {string} friendName 
 */
function confirmFriendship(friendName) {

	let data = new FormData();
	data.append('friend_name', friendName);
	let xhr = new XMLHttpRequest();
	xhr.open('POST', base_url + '/friends/add');
	xhr.send(data);
	xhr.onload = function() {
		if (isJson(xhr.response)) {
			const data = JSON.parse(xhr.response);
			if (data.success) {
                getFriends();
				const feedback = document.getElementById('async-feedback');
				feedback.innerHTML = `<p class='alert alert-success'>Freundschaft wurde bestätigt</p>`;
			} else {
				console.log(data.errors);
			}
		}
	};
}
