const chatWindow = document.getElementById('chat-window');
const base_urlComment = document.body.dataset.baseurl;
let chatIntervalId;

function getMessages(id) {
    axios.get(base_urlComment + "/api/messages/list?id=" + id).then(res => {
        showMessages(res.data, id);
    })
}

function showMessages(messages, receiver) {
    const messagesDiv = document.getElementById('messages');

    let messagesHTML = ``;

    for (let i = 0; i < messages.length; i++) {
        let messageText;
        if(messages[i].received_user_id == receiver) {
            messageText = 
            `
            <p class="chat-window--message sent">${messages[i].text}</p>
            `;
        } else {
            messageText = 
            `
            <p class="chat-window--message received">${messages[i].text}</p>
            `;
        }
        messagesHTML += messageText;
    }

    messagesDiv.innerHTML = messagesHTML;
    messagesDiv.scrollTop = messagesDiv.scrollHeight;
}

function sendMessage(receiver){
    const textarea = document.getElementById('message');

    axios.post(base_urlComment + "/api/messages/send", {
        text: textarea.value,
        receiver: receiver
    }).then(res => {
        getMessages(receiver);
        // console.log("ok");
        // console.log(res);
        // messages.innerHTML += 
        // `
        // <p class="chat-window--message sent">${textarea.value}</p>
        // `;
        textarea.value = '';
    })
}

function openChat(name, id){
    chatWindow.innerHTML = 
    `
    <div class="user">
        <p id="chat-window-name">${name}</p>
    </div>
    <div class="messages" id="messages">
    </div>
    <div class="send">
        <textarea type="text" placeholder="Сообщение" id="message"></textarea>
        <button id="send-button" onclick='sendMessage()'>Отправить</button>
    </div>
    `;

    const chatWindowName = document.getElementById('chat-window-name');
    
    chatWindowName.innerText = name;

    clearInterval(chatIntervalId);
    
    chatIntervalId = setInterval(function(){
        openSendButton(id);
        
        getMessages(id);
    }, 1000);

    chatIntervalId;
}

function openSendButton(id) {
    console.log(id);
    const chatSendButton = document.getElementById('send-button');
    
    chatSendButton.setAttribute("onclick", `sendMessage(${id})`);
}