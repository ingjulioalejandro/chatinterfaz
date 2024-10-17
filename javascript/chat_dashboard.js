const usersList = document.querySelector(".users-list"),
  searchBar = document.querySelector(".search input"),
  searchButton = document.querySelector(".search button"),
  chatArea = document.querySelector(".chat-area"),
  chatBox = chatArea.querySelector(".chat-box"),
  typingArea = chatArea.querySelector(".typing-area"),
  inputField = typingArea.querySelector(".input-field"),
  sendBtn = typingArea.querySelector("button"),
  attachmentInput = typingArea.querySelector("#attachment");

let currentChatId = null;
let isGroup = false;

searchButton.onclick = () => {
  searchBar.classList.toggle("active");
  searchBar.focus();
  searchButton.classList.toggle("active");
  searchBar.value = "";
};

searchBar.onkeyup = () => {
  let searchTerm = searchBar.value;
  if (searchTerm != "") {
    searchBar.classList.add("active");
  } else {
    searchBar.classList.remove("active");
  }
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "php/search.php", true);
  xhr.onload = () => {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        let data = xhr.response;
        usersList.innerHTML = data;
      }
    }
  };
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.send("searchTerm=" + searchTerm);
};

setInterval(() => {
  let xhr = new XMLHttpRequest();
  xhr.open("GET", "php/users.php", true);
  xhr.onload = () => {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        let data = xhr.response;
        if (!searchBar.classList.contains("active")) {
          usersList.innerHTML = data;
        }
      }
    }
  };
  xhr.send();
}, 500);

usersList.onclick = (e) => {
  let userItem = e.target.closest('.user-item');
  if (userItem) {
    currentChatId = userItem.getAttribute('data-id');
    isGroup = userItem.getAttribute('data-is-group') === 'true';
    loadChat(currentChatId, isGroup);
  }
};

function loadChat(chatId, isGroupChat) {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "php/get-chat.php", true);
  xhr.onload = () => {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        chatBox.innerHTML = xhr.response;
        scrollToBottom();
      }
    }
  };
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.send("incoming_id=" + chatId + "&is_group=" + isGroupChat);

  // Update chat header
  updateChatHeader(chatId, isGroupChat);
}

function updateChatHeader(chatId, isGroupChat) {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "php/get-chat-header.php", true);
  xhr.onload = () => {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        let data = JSON.parse(xhr.response);
        chatArea.querySelector("header img").src = data.img;
        chatArea.querySelector("header .details span").textContent = data.name;
        chatArea.querySelector("header .details p").textContent = isGroupChat ? "Group" : data.status;
      }
    }
  };
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.send("chat_id=" + chatId + "&is_group=" + isGroupChat);
}

typingArea.onsubmit = (e) => {
  e.preventDefault();
};

inputField.focus();
inputField.onkeyup = () => {
  if (inputField.value != "") {
    sendBtn.classList.add("active");
  } else {
    sendBtn.classList.remove("active");
  }
};

sendBtn.onclick = () => {
  let formData = new FormData(typingArea);
  formData.append("outgoing_id", currentChatId);
  formData.append("is_group", isGroup);
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "php/insert-chat.php", true);
  xhr.onload = () => {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        inputField.value = "";
        attachmentInput.value = "";
        if (!isGroup) {
          loadChat(currentChatId, false);
        }
      }
    }
  };
  xhr.send(formData);
};

setInterval(() => {
  if (currentChatId) {
    loadChat(currentChatId, isGroup);
  }
}, 500);

function scrollToBottom() {
  chatBox.scrollTop = chatBox.scrollHeight;
}

document.addEventListener('DOMContentLoaded', function() {
    const chatItems = document.querySelector('.chat-items');
    const searchInput = document.querySelector('.search-bar input');
    const newChatBtn = document.querySelector('.new-chat-btn');
    const messageInput = document.querySelector('.message-input textarea');
    const sendBtn = document.querySelector('.send-btn');

    // Cargar chats
    function loadChats() {
        fetch('php/get_chats.php')
            .then(response => response.text())
            .then(data => {
                chatItems.innerHTML = data;
            });
    }

    loadChats();

    // Búsqueda de chats
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value;
        fetch('php/search_chats.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'searchTerm=' + searchTerm
        })
        .then(response => response.text())
        .then(data => {
            chatItems.innerHTML = data;
        });
    });

    // Nuevo chat
    newChatBtn.addEventListener('click', function() {
        // Implementar lógica para crear nuevo chat
    });

    // Enviar mensaje
    sendBtn.addEventListener('click', sendMessage);
    messageInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });

    function sendMessage() {
        const message = messageInput.value.trim();
        if (message) {
            // Implementar lógica para enviar mensaje
            messageInput.value = '';
        }
    }
});

function loadChat(userId) {
  const chatArea = document.querySelector(".chat-area");
  const xhr = new XMLHttpRequest();
  xhr.open("GET", `php/get-chat.php?user_id=${userId}`, true);
  xhr.onload = function() {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        try {
          const data = JSON.parse(xhr.response);
          
          // Actualizar el encabezado del chat
          const chatHeader = `
            <header>
              <img src="php/images/${data.user.img}" alt="">
              <div class="details">
                <span>${data.user.fname} ${data.user.lname}</span>
                <p>${data.user.status}</p>
              </div>
            </header>
          `;
          
          // Actualizar el área de chat
          chatArea.innerHTML = chatHeader + `
            <div class="chat-box">
              ${data.chat}
            </div>
            <form action="#" class="typing-area">
              <input type="text" class="incoming_id" name="incoming_id" value="${data.user.unique_id}" hidden>
              <input type="text" name="message" class="input-field" placeholder="Type a message here..." autocomplete="off">
              <button><i class="fab fa-telegram-plane"></i></button>
            </form>
          `;
          
          // Inicializar la funcionalidad del chat
          initChat();
        } catch (e) {
          console.error("Error parsing JSON:", e);
          chatArea.innerHTML = "Error loading chat.";
        }
      }
    };
  };
  xhr.send();
}

function initChat() {
  // Aquí va el código para inicializar la funcionalidad del chat
  // (manejo de envío de mensajes, etc.)
}
