function renderFriends(friends) {
	const domTableBody = document.getElementById('friend-table-body');
	if (friends.length > 0) {
		domTableBody.innerHTML = '';
		friends.forEach(friend => {
			let friendTable;
			let tableRow = document.createElement('TR');
			tableRow.className = 'friend-tr';
			tableRow.dataset.friendid = friend.friend_id;

			friendTable = `
                <td>${friend.user_name}</td>
                <td>${friend.online}</td>
                <td><div data-friendname="${
					friend.user_name
				}" class="msg-to-friend table-btn"></div></td>
                <td><div data-friendname="${
					friend.friendship_id
				}" class="delete-friend table-btn"></div></td>
            `;
			tableRow.innerHTML = friendTable;
			domTableBody.appendChild(tableRow);
		});
	} else {
		domTableBody.innerHTML = '<p>Keine Freunde</p>';
	}
}

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
