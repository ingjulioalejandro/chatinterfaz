document.addEventListener('DOMContentLoaded', function() {
  const form = document.querySelector(".typing-area");
  if (form) {
    const inputField = form.querySelector(".input-field");
    const sendBtn = form.querySelector("button");
    const chatBox = document.querySelector(".chat-box");

    inputField.focus();
    inputField.onkeyup = ()=>{
        if(inputField.value != ""){
            sendBtn.classList.add("active");
        }else{
            sendBtn.classList.remove("active");
        }
    }

    chatBox.onmouseenter = ()=>{
        chatBox.classList.add("active");
    }

    chatBox.onmouseleave = ()=>{
        chatBox.classList.remove("active");
    }

    // Actualizar chat periódicamente
    setInterval(() => {
        if (selectedUserId) {
            loadChat(selectedUserId);
        }
    }, 5000); // Actualizar cada 5 segundos
  }
});
