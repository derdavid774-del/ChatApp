document.addEventListener("DOMContentLoaded", () => {
    window.backendUrl =
        "https://online-lectures-cs.thi.de/chat/964c76c0-6ea1-4d09-81b7-befae5c6a327";
    window.token =
        "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyIjoiVG9tIiwiaWF0IjoxNzYyNDY1ODYxfQ.M-09MTe2cdaNVEQ-jkTsMfp_LOyeUb--RPCYrLJV0fI";
    const currentUsername = "Tom";

    const friendInputElement = document.getElementById("friend-request-name");
    const datalistElement = document.getElementById("friend-selector");
    const addButtonElement = document.getElementById("add-friend-btn");

    if (friendInputElement && datalistElement && addButtonElement) {
        let allUsers = [];
        let currentFriendUsernames = [];

        function loadAndPopulateDatalist() {
            const usersRequest = new XMLHttpRequest();

            usersRequest.onreadystatechange = function () {
                if (usersRequest.readyState == 4 && usersRequest.status == 200) {
                    allUsers = JSON.parse(usersRequest.responseText);

                    const friendsRequest = new XMLHttpRequest();

                    friendsRequest.onreadystatechange = function () {
                        if (friendsRequest.readyState == 4 && friendsRequest.status == 200) {
                            const friends = JSON.parse(friendsRequest.responseText);
                            currentFriendUsernames = friends.map(
                                (friend) => friend.username
                            );

                            datalistElement.innerHTML = "";
                            const filteredUsers = allUsers.filter((user) => {
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
                                "Fehler beim Laden der Freundesliste:",
                                friendsRequest.status,
                                friendsRequest.statusText
                            );
                            alert("Fehler beim Laden der Freundesliste.");
                        }
                    };

                    friendsRequest.open("GET", window.backendUrl + "/friend", true);
                    friendsRequest.setRequestHeader("Authorization", "Bearer " + window.token);
                    friendsRequest.send();
                } else if (usersRequest.readyState == 4) {
                    // Fehler bei der 1. Anfrage (Benutzer)
                    console.error(
                        "Fehler beim Laden der Benutzerliste:",
                        usersRequest.status,
                        usersRequest.statusText
                    );
                    alert("Fehler beim Laden der Benutzerdaten.");
                }
            };

            usersRequest.open("GET", window.backendUrl + "/user", true);
            usersRequest.setRequestHeader(
                "Authorization",
                "Bearer " + window.token
            );
            usersRequest.send();
        }

        function handleAddFriendClick() {
            const usernameToAdd = friendInputElement.value.trim();

            if (!usernameToAdd) {
                alert("Bitte geben Sie einen Benutzernamen ein.");
                return;
            }

            if (usernameToAdd === currentUsername) {
                alert("Sie können sich nicht selbst als Freund hinzufügen.");
                return;
            }

            if (currentFriendUsernames.includes(usernameToAdd)) {
                alert("Dieser Benutzer ist bereits Ihr Freund.");
                return;
            }

            if (!allUsers.includes(usernameToAdd)) {
                alert("Dieser Benutzer existiert nicht.");
                return;
            }

            const postRequest = new XMLHttpRequest();
            postRequest.onreadystatechange = function () {
                if (postRequest.readyState == 4 && postRequest.status == 204) {
                    alert(`Freundschaftsanfrage an ${usernameToAdd} gesendet!`);
                    friendInputElement.value = "";
                    loadAndPopulateDatalist();

                } else if (postRequest.readyState == 4) {
                    // Fehler beim Senden
                    console.error(
                        "Fehler beim Senden der Freundschaftsanfrage:",
                        postRequest.status,
                        postRequest.statusText
                    );
                    alert("Die Freundschaftsanfrage konnte nicht gesendet werden.");
                }
            };

            postRequest.open("POST", window.backendUrl + "/friend", true);
            postRequest.setRequestHeader("Content-type", "application/json");
            postRequest.setRequestHeader("Authorization", "Bearer " + window.token);
            let data = {
                username: usernameToAdd,
            };
            let jsonString = JSON.stringify(data);
            postRequest.send(jsonString);
        }

        addButtonElement.addEventListener("click", handleAddFriendClick);
        loadAndPopulateDatalist();
    }
});