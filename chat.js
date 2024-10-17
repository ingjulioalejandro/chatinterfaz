document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector(".typing-area");
    const inputField = form.querySelector(".input-field");
    const sendBtn = form.querySelector("button");
    const chatBox = document.querySelector(".chat-box");

    form.onsubmit = (e) => {
        e.preventDefault();
    }

    sendBtn.onclick = () => {
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "php/insert-chat.php", true);
        xhr.onload = () => {
            if(xhr.readyState === XMLHttpRequest.DONE){
                if(xhr.status === 200){
                    inputField.value = "";
                    scrollToBottom();
                    loadChat(form.querySelector(".incoming_id").value);
                }
            }
        }
        let formData = new FormData(form);
        xhr.send(formData);
    }

    chatBox.onmouseenter = ()=>{
        chatBox.classList.add("active");
    }

    chatBox.onmouseleave = ()=>{
        chatBox.classList.remove("active");
    }

    function scrollToBottom(){
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    // Si necesitas la función loadChat aquí, agrégala:
    function loadChat(userId) {
        // ... código de loadChat ...
    }
});
