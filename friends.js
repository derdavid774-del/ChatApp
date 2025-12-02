document.addEventListener("DOMContentLoaded", () => {
    const currentUsername = "Tom";

    const friendsListContainer = document.querySelector(
        "#friends-page ul.container-wide"
    );
    const requestsListContainer = document.querySelector(
        "#friends-page div.friend-request ol"
    );
    const requestsHeader = document.querySelector("#friends-page h2");
    const friendInputElement = document.getElementById("friend-request-name");
    const datalistElement = document.getElementById("friend-selector");
    const addButtonElement = document.getElementById("add-friend-btn");

    let currentFriendUsernames = [];

    function loadFriends() {
        const friendsRequest = new XMLHttpRequest();
        friendsRequest.onreadystatechange = function() {
            if (friendsRequest.readyState == 4 && friendsRequest.status == 200) {
                const friends = JSON.parse(friendsRequest.responseText);
                currentFriendUsernames = friends.map((friend) => friend.username);

                friendsListContainer.innerHTML = "";
                requestsListContainer.innerHTML = "";
                requestsHeader.style.display = "none";

                let hasRequests = false;

                friends.forEach((friend) => {
                    if (friend.status === "accepted") {
                        const li = document.createElement("li");
                        li.className = "content";

                        const userSpan = document.createElement("span");
                        userSpan.className = "friend-user";

                        //Query-Parameter
                        const userLink = document.createElement("a");
                        userLink.href = "chat.php?friend=" + friend.username;
                        userLink.textContent = friend.username;
                        userSpan.appendChild(userLink);
                        li.appendChild(userSpan);

                        if (friend.unread && friend.unread > 0) {
                            const notifySpan = document.createElement("span");
                            notifySpan.className = "notification";
                            notifySpan.textContent = friend.unread;
                            li.appendChild(notifySpan);
                        }
                        friendsListContainer.appendChild(li);

                    } else if (friend.status === "requested") {
                        hasRequests = true;

                        const li = document.createElement("li");

                        const msgSpan = document.createElement("span");
                        msgSpan.className = "request-msg";
                        msgSpan.textContent = "Friend Request from ";
                        li.appendChild(msgSpan);

                        const nameSpan = document.createElement("span");
                        nameSpan.className = "requestee";
                        nameSpan.name = "requestee";
                        const strong = document.createElement("strong");
                        strong.textContent = friend.username;
                        nameSpan.appendChild(strong);
                        li.appendChild(nameSpan);

                        const btnContainer = document.createElement("div");
                        btnContainer.className = "container-rqst-btn";

                        const acceptBtn = document.createElement("button");
                        acceptBtn.className = "btn-small";
                        acceptBtn.name = "acceptBtn";
                        acceptBtn.textContent = "Accept";
                        acceptBtn.onclick = function() { 
                            handleRequestAction(friend.username, 'accept'); 
                        };
                        btnContainer.appendChild(acceptBtn);

                        const rejectBtn = document.createElement("button");
                        rejectBtn.className = "btn-small";
                        rejectBtn.name = "rejectBtn";
                        rejectBtn.textContent = "Reject";
                        rejectBtn.onclick = function() { 
                            handleRequestAction(friend.username, 'reject'); 
                        };
                        btnContainer.appendChild(rejectBtn);

                        li.appendChild(btnContainer);
                        requestsListContainer.appendChild(li);
                    }
                });

                if (hasRequests) {
                    requestsHeader.style.display = "block";
                }

            } else if (friendsRequest.readyState == 4) {
                console.error("Error updating friend list:", friendsRequest.status);
            }
        };

        friendsRequest.open("GET", "ajax_load_friends.php", true);
        friendsRequest.send();
    }

    function handleRequestAction(username, action) {
        const req = new XMLHttpRequest();
        req.onreadystatechange = function() {
            if (req.readyState == 4) {
                if(req.status == 200 || req.status == 204) {
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
        checkReq.onreadystatechange = function() {
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
        postRequest.onreadystatechange = function() {
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

    window.setInterval(function() {
        loadFriends();
        loadUsers();
    }, 1000);
});
