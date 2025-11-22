document.addEventListener("DOMContentLoaded", () => {
    window.backendUrl = "https://online-lectures-cs.thi.de/chat/5929e8ea-f3a6-4ae3-b9b4-c0d0971e0f03";
    window.token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyIjoiVG9tIiwiaWF0IjoxNzYyODg5MzM3fQ.xmQhTmEqWai5zai8-U7_eV7bzCV1puiNRZmwl_vRYKo";

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

    let allUsersCache = [];
    let currentFriendUsernames = [];

    function loadFriends() {
        const friendsRequest = new XMLHttpRequest();

        friendsRequest.onreadystatechange = function () {
            if (friendsRequest.readyState == 4 && friendsRequest.status == 200) {
                const friends = JSON.parse(friendsRequest.responseText);

                currentFriendUsernames = friends.map(
                    (friend) => friend.username
                );

                friendsListContainer.innerHTML = "";
                requestsListContainer.innerHTML = "";
                requestsHeader.style.display = "none";

                let hasRequests = false;

                friends.forEach((friend) => {
                    if (friend.status !== "requested") {
                        const li = document.createElement("li");
                        li.className = "content";

                        const userSpan = document.createElement("span");
                        userSpan.className = "friend.user";

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
                        const strong = document.createElement("strong");
                        strong.textContent = friend.username;
                        nameSpan.appendChild(strong);
                        li.appendChild(nameSpan);

                        const btnContainer = document.createElement("div");
                        btnContainer.className = "container-rqst-btn";

                        const acceptBtn = document.createElement("button");
                        acceptBtn.className = "btn-small";
                        acceptBtn.textContent = "Accept";
                        btnContainer.appendChild(acceptBtn);

                        const rejectBtn = document.createElement("button");
                        rejectBtn.className = "btn-small";
                        rejectBtn.textContent = "Reject";
                        btnContainer.appendChild(rejectBtn);

                        li.appendChild(btnContainer);
                        requestsListContainer.appendChild(li);
                    }
                });

                if (hasRequests) {
                    requestsHeader.style.display = "block";
                }

                datalistElement.innerHTML = "";

                const filteredUsers = allUsersCache.filter((user) => {
                    const isCurrentUser = user === currentUsername;
                    const isAlreadyFriend = currentFriendUsernames.includes(user);
                    return !isCurrentUser && !isAlreadyFriend;
                });

                filteredUsers.forEach((user) => {
                    const option = document.createElement("option");
                    option.value = user;
                    datalistElement.appendChild(option);
                });

            } else if (friendsRequest.readyState == 4) {
                console.error(
                    "Error updating friend list:",
                    friendsRequest.status
                );
            }
        };

        friendsRequest.open("GET", window.backendUrl + "/friend", true);
        friendsRequest.setRequestHeader(
            "Authorization",
            "Bearer " + window.token
        );

        friendsRequest.send();
    }

    function loadUsers() {
        const req = new XMLHttpRequest();
        req.onreadystatechange = function () {
            if (req.readyState == 4 && req.status == 200) {
                allUsersCache = JSON.parse(req.responseText);
            } else if (req.readyState == 4) {
                console.error("Error updating user list:", req.status);
            }
        };
        req.open("GET", window.backendUrl + "/user", true);
        req.setRequestHeader("Authorization", "Bearer " + window.token);
        req.send();
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

        if (!allUsersCache.includes(usernameToAdd)) {
            alert("This user does not exist.");
            friendInputElement.style.border = "2px solid red";
            return;
        }

        const postRequest = new XMLHttpRequest();
        postRequest.onreadystatechange = function () {
            if (postRequest.readyState == 4 && postRequest.status == 204) {
                alert(`Friend request sent to ${usernameToAdd}!`);
                friendInputElement.value = "";
                friendInputElement.style.border = "";

                loadFriends();
            } else if (postRequest.readyState == 4) {
                alert("The friend request could not be sent.");
            }
        };

        postRequest.open("POST", window.backendUrl + "/friend", true);
        postRequest.setRequestHeader("Content-type", "application/json");
        postRequest.setRequestHeader(
            "Authorization",
            "Bearer " + window.token
        );
        let data = { username: usernameToAdd };
        let jsonString = JSON.stringify(data);
        postRequest.send(jsonString);
    }

    addButtonElement.addEventListener("click", handleAddFriendClick);

    const initialUsersRequest = new XMLHttpRequest();
    initialUsersRequest.onreadystatechange = function () {
        if (initialUsersRequest.readyState == 4 && initialUsersRequest.status == 200) {
            allUsersCache = JSON.parse(initialUsersRequest.responseText);

            window.setInterval(function () {
                loadFriends();
                loadUsers();
            }, 1000);

        } else if (initialUsersRequest.readyState == 4) {
            alert(
                "Critical Error: User list could not be loaded. The page will not function correctly."
            );
        }
    };
    initialUsersRequest.open("GET", window.backendUrl + "/user", true);
    initialUsersRequest.setRequestHeader("Authorization", "Bearer " + window.token);
    initialUsersRequest.send();

    loadFriends();
});
