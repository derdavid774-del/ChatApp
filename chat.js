document.addEventListener('DOMContentLoaded', () => {
    const friend = document.querySelector('h1')
    const chatList = document.getElementById('chat');
    const messageForm = document.querySelector('form');
    const messageInput = document.getElementById('send-msg');

    function getChatpartner() {
        const url = new URL(window.location.href);
        const queryParams = url.searchParams;
        const friendValue = queryParams.get("friend");
        console.log("Friend:", friendValue);
        return friendValue;
    }
    const chatpartner = getChatpartner();
    if (chatpartner) {
        friend.textContent = 'Chat with ' + chatpartner;
    }

    function loadMessages() {
        const url = 'ajax_load_messages.php?to=' + encodeURIComponent(chatpartner);

        fetch(url, {
            method: 'GET',
        })
            .then(response => response.json())
            .then(messages => {
                chatList.innerHTML = '';
                if (messages.length === 0) {
                    chatList.innerHTML = `
                        <li class="content">
                            <div class="msg">
                                <span class="text">No messages received.</span>
                            </div>
                        </li>`;
                } else {
                    messages.forEach(message => {
                        const li = document.createElement('li');
                        li.classList.add('content');
                        chatList.appendChild(li);

                        const msgDiv = document.createElement('div');
                        msgDiv.classList.add('msg');
                        li.appendChild(msgDiv);

                        const authorSpan = document.createElement('span');
                        authorSpan.classList.add('author');
                        authorSpan.textContent = message.from + ': ';
                        msgDiv.appendChild(authorSpan);

                        const textSpan = document.createElement('span');
                        textSpan.classList.add('text');
                        textSpan.textContent = message.msg;
                        msgDiv.appendChild(textSpan);

                        const timeElem = document.createElement('time');
                        timeElem.classList.add('timestamp');
                        const timestamp = new Date(message.time);
                        const timeString = timestamp.toTimeString().split(' ')[0];
                        timeElem.setAttribute('datetime', timeString);
                        timeElem.textContent = timeString;
                        li.appendChild(timeElem);
                    })
                };
            })
            .catch(error => {
                console.error('Error on loading messages:', error);
            })
    }

    function sendMessage(event) {
        event.preventDefault();

        const url = 'ajax_send_message.php';

        const text = messageInput.value;
        if (text.trim() === '') return;

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                msg: text,
                to: chatpartner
            })
        })
            .then(response => {
                if (response.ok || response.status === 204) {
                    messageInput.value = '';
                    loadMessages();
                }
            })
            .catch(error => {
                console.error('Error when sending message:', error);
            });
    }
    messageForm.addEventListener('submit', sendMessage);

    if (chatpartner) {
        loadMessages();
        setInterval(loadMessages, 1000);
    }
});