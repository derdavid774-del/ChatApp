document.addEventListener("DOMContentLoaded", () => {
    const currentUsername = "Tom";

    const friendsListContainer = document.getElementById("friend-list");
    const requestsListContainer = document.getElementById("request-list");
    const requestsHeader = document.getElementById("requests-header");

    const friendInputElement = document.getElementById("friend-request-name");
    const datalistElement = document.getElementById("friend-selector");
    const addButtonElement = document.getElementById("add-friend-btn");

    const requestModalElement = document.getElementById("requestModal");
    const requestModal = new bootstrap.Modal(requestModalElement);
    const modalRequesterNameSpan = document.getElementById("modal-requester-name");
    const modalAcceptBtn = document.getElementById("modal-accept-btn");
    const modalRejectBtn = document.getElementById("modal-reject-btn");

    let currentFriendUsernames = [];
    let currentRequestUser = null;

    function loadFriends() {
        const friendsRequest = new XMLHttpRequest();
        friendsRequest.onreadystatechange = function () {
            if (friendsRequest.readyState == 4 && friendsRequest.status == 200) {
                const friends = JSON.parse(friendsRequest.responseText);
                currentFriendUsernames = friends.map((friend) => friend.username);

                friendsListContainer.innerHTML = "";
                requestsListContainer.innerHTML = "";
                requestsHeader.style.display = "none";

                let hasRequests = false;
                let hasFriends = false;

                friends.forEach((friend) => {
                    if (friend.status === "accepted") {
                        hasFriends = true;
                        const a = document.createElement("a");
                        a.className = "list-group-item list-group-item-action d-flex justify-content-between align-items-center";
                        a.href = "chat.php?friend=" + encodeURIComponent(friend.username);
                        a.textContent = friend.username;

                        if (friend.unread && friend.unread > 0) {
                            const notifySpan = document.createElement("span");
                            notifySpan.className = "badge bg-primary rounded-pill";
                            notifySpan.textContent = friend.unread;
                            a.appendChild(notifySpan);
                        }
                        friendsListContainer.appendChild(a);

                    } else if (friend.status === "requested") {
                        hasRequests = true;

                        const btn = document.createElement("button");
                        btn.className = "list-group-item list-group-item-action list-group-item-light";
                        btn.innerHTML = `Request from <strong>${friend.username}</strong>`;

                        btn.onclick = function () {
                            showRequestModal(friend.username);
                        };
                        requestsListContainer.appendChild(btn);
                    }
                });

                if (!hasFriends) {
                    friendsListContainer.innerHTML = '<div class="list-group-item text-center text-muted">No friends yet.</div>';
                }

                requestsHeader.style.display = hasRequests ? "block" : "none";

            } else if (friendsRequest.readyState == 4) {
                console.error("Error updating friend list:", friendsRequest.status);
            }
        };

        friendsRequest.open("GET", "ajax_load_friends.php", true);
        friendsRequest.send();
    }

    function showRequestModal(username) {
        currentRequestUser = username;
        modalRequesterNameSpan.textContent = username;
        requestModal.show();
    }

    modalAcceptBtn.onclick = function () {
        if (currentRequestUser) {
            handleRequestAction(currentRequestUser, "accept");
            requestModal.hide();
        }
    };

    modalRejectBtn.onclick = function () {
        if (currentRequestUser) {
            handleRequestAction(currentRequestUser, "reject");
            requestModal.hide();
        }
    };


    function handleRequestAction(username, action) {
        const req = new XMLHttpRequest();
        req.onreadystatechange = function () {
            if (req.readyState == 4) {
                if (req.status == 200 || req.status == 204) {
                    loadFriends();
                } else {
                    alert("action failed.");
                }
            }
        };

        req.open("POST", "friends.php", true);
        req.setRequestHeader("Content-type", "application/json");
        req.send(JSON.stringify({
            username: username,
            action: action
        }));
    }

    function handleAddFriendClick() {
        const usernameToAdd = friendInputElement.value.trim();

        if (!usernameToAdd) {
            alert("Please enter a username.");
            friendInputElement.style.border = "2px solid red";
            return;
        }

        if (usernameToAdd === currentUsername) {
            alert("You cannot add yourself as a friend.");
            friendInputElement.style.border = "2px solid red";
            return;
        }

        if (currentFriendUsernames.includes(usernameToAdd)) {
            alert("This user is already your friend.");
            friendInputElement.style.border = "2px solid red";
            return;
        }

        const checkReq = new XMLHttpRequest();
        checkReq.onreadystatechange = function () {
            if (checkReq.readyState == 4) {
                if (checkReq.status == 204 || checkReq.status == 200) {
                    sendFriendRequest(usernameToAdd);
                } else {
                    alert("Benutzer existiert nicht.");
                    friendInputElement.style.border = "2px solid red";
                }
            }
        };

        checkReq.open("GET", "ajax_check_user.php?user=" + encodeURIComponent(usernameToAdd), true);
        checkReq.send();
    }

    function sendFriendRequest(username) {
        const postRequest = new XMLHttpRequest();
        postRequest.onreadystatechange = function () {
            if (postRequest.readyState == 4) {
                if (postRequest.status == 204 || postRequest.status == 200) {
                    alert(`Request has been sent to ${username}!`);
                    friendInputElement.value = "";
                    friendInputElement.style.border = "";
                    loadFriends();
                } else {
                    alert("Error while sending the request.");
                }
            }
        };

        postRequest.open("POST", "friends.php", true);
        postRequest.setRequestHeader("Content-type", "application/json");
        postRequest.send(JSON.stringify({ username: username, action: 'add' }));
    }

    addButtonElement.addEventListener("click", handleAddFriendClick);

    loadFriends();

    window.setInterval(function () {
        loadFriends();
    }, 1000);
});
