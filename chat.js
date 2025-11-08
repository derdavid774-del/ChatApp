document.addEventListener('DOMContentLoaded', () => {
    const chatList = document.getElementById('chat');
    const messageForm = document.querySelector('form');
    const messageInput = document.getElementById('send-msg');

    function loadMessages() {
        const TOKEN = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyIjoiVG9tIiwiaWF0IjoxNzYyNjMyNTE2fQ.0IdQopPOUYM_WFuxoF5idej0_ezOKm1RGQL1uYEcD84';
        const url = 'https://online-lectures-cs.thi.de/chat/104012ba-aafc-4150-8e92-7c78aa9b19f5/message/Jerry';

        fetch(url, {
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + TOKEN
            }
        })
            .then(response => response.json())
            .then(messages => {
                chatList.innerHTML = '';
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
                });
            })
            .catch(error => {
                console.error('Fehler beim Laden der Nachrichten:', error);
            });
    }

    function sendMessage(event) {
        event.preventDefault();

        const TOKEN = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyIjoiSmVycnkiLCJpYXQiOjE3NjI2MzI1MTZ9.1DwfaDMRkVu4cLn5BeOfSITCpjRjaizghTXiYm4X_Rs';
        const url = 'https://online-lectures-cs.thi.de/chat/104012ba-aafc-4150-8e92-7c78aa9b19f5/message';
        
        const text = messageInput.value;
        if (text.trim() === '') return;

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + TOKEN
            },
            body: JSON.stringify({
                message: text,
                to: 'Tom'
            })
        })
            .then(response => {
                if (response.ok) {
                    messageInput.value = '';
                    loadMessages();
                }
            })
            .catch(error => {
                console.error('Fehler beim Senden der Nachricht:', error);
            });
    }

    messageForm.addEventListener('submit', sendMessage);

    loadMessages();
    setInterval(loadMessages, 1000);
});