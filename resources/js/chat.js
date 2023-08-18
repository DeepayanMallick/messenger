const messageOutput = document.getElementById("messageOutput");
const messageElement = document.getElementById("messageElement");
const formElement = document.getElementById("chatForm");

let url = window.location;
let urlNew = new URL(url);
let username = urlNew.searchParams.get('username');

if (formElement != undefined && formElement != null) {
	formElement.addEventListener('submit', function (e) {
		e.preventDefault();

		if (messageElement.value != "") {
			axios({
				method: 'post',
				url: '/send-message',
				data: {
					message: messageElement.value,
					fromUserID: fromUserID,
					toUserID: toUserID,
				}
			});
		}

		window.Echo.channel("laravelChat").listen(".chatting", (res) => {
			let userName = "annonymous";
			if (res.userName != null && res.userName != "") {
				userName = res.userName;
			}

			messageOutput.innerHTML += '<div><strong class="username">' + userName + ' : </strong><span>' + window.emoji.replace_colons(res.message) + '</span></div>';
		});

		messageElement.value = "";
	});
}

const fileInput = document.getElementById('fileInput');
const uploadButton = document.getElementById('uploadButton');
const message = document.getElementById('message');
if (uploadButton != undefined && uploadButton != null) {
	uploadButton.addEventListener('click', () => {
		const file = fileInput.files[0];
		if (file) {
			const formData = new FormData();
			formData.append('file', file);

			const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
			axios({
				method: 'post',
				url: '/upload',
				data: formData,
				headers: {
					'X-CSRF-TOKEN': csrfToken
				}
			})
				.then(response => {
					console.log(response);
					axios({
						method: 'post',
						url: '/send-message',
						data: {
							message: '<a target="_blank" href="' + response.data.url + '"><img width="100" src="/storage/uploads/free-thumb.png"></a><br><span>' + response.data.message + '</span>',
							fromUserID: fromUserID,
							toUserID: toUserID,
						}
					});
					window.Echo.channel("laravelChat").listen(".chatting", (res) => {
						let userName = "annonymous";
						if (res.userName != null && res.userName != "") {
							userName = res.userName;
						}
						messageOutput.innerHTML += '<div><strong class="username">' + userName + ' : </strong><span>' + window.emoji.replace_colons(res.message) + '</span></div>';
					});
					messageElement.value = "";
				})
				.catch(error => {
					message.textContent = 'Error uploading file.';
					console.error(error);
				});
		} else {
			message.textContent = 'Please select a file.';
		}
	});
}

