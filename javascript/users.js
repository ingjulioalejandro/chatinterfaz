let selectedUserId = null;

document.addEventListener('DOMContentLoaded', function() {
  const searchBar = document.querySelector(".search input"),
  searchIcon = document.querySelector(".search button"),
  usersList = document.querySelector(".users-list"),
  typingArea = document.querySelector(".typing-area");

  searchIcon.onclick = ()=>{
    searchBar.classList.toggle("show");
    searchIcon.classList.toggle("active");
    searchBar.focus();
    if(searchBar.classList.contains("active")){
      searchBar.value = "";
      searchBar.classList.remove("active");
    }
  }

  searchBar.onkeyup = ()=>{
    let searchTerm = searchBar.value;
    if(searchTerm != ""){
      searchBar.classList.add("active");
    }else{
      searchBar.classList.remove("active");
    }
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "php/search.php", true);
    xhr.onload = ()=>{
      if(xhr.readyState === XMLHttpRequest.DONE){
          if(xhr.status === 200){
            let data = xhr.response;
            usersList.innerHTML = data;
          }
      }
    }
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("searchTerm=" + searchTerm);
  }

  setInterval(() =>{
    let xhr = new XMLHttpRequest();
    xhr.open("GET", "php/users.php", true);
    xhr.onload = ()=>{
      if(xhr.readyState === XMLHttpRequest.DONE){
          if(xhr.status === 200){
            let data = xhr.response;
            if(!searchBar.classList.contains("active")){
              usersList.innerHTML = data;
            }
          }
      }
    }
    xhr.send();
  }, 500);

  // Agregar manejo de clic en usuarios
  usersList.addEventListener('click', function(e) {
    const userItem = e.target.closest('.user-item');
    if (userItem) {
      selectedUserId = userItem.getAttribute('data-user-id');
      typingArea.querySelector(".incoming_id").value = selectedUserId;
      loadChat(selectedUserId);
    }
  });

  // Manejar el envío de mensajes
  typingArea.onsubmit = function(e) {
    e.preventDefault();
    if (!selectedUserId) {
      console.error("No user selected");
      return;
    }
    sendMessage();
  }
});

function loadChat(userId) {
  if (!userId) {
    console.error("No user ID provided");
    return;
  }
  const chatBox = document.querySelector(".chat-box");
  const xhr = new XMLHttpRequest();
  xhr.open("POST", "php/get-chat.php", true);
  xhr.onload = ()=>{
    if(xhr.readyState === XMLHttpRequest.DONE){
      if(xhr.status === 200){
        chatBox.innerHTML = xhr.response;
        scrollToBottom();
      }
    }
  }
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.send("incoming_id="+userId);
}

function sendMessage() {
  const form = document.querySelector(".typing-area");
  const inputField = form.querySelector(".input-field");
  const xhr = new XMLHttpRequest();
  xhr.open("POST", "php/insert-chat.php", true);
  xhr.onload = ()=>{
    if(xhr.readyState === XMLHttpRequest.DONE){
      if(xhr.status === 200){
        inputField.value = "";
        loadChat(selectedUserId);
      }
    }
  }
  let formData = new FormData(form);
  formData.set('incoming_id', selectedUserId);
  xhr.send(formData);
}

function scrollToBottom(){
  const chatBox = document.querySelector(".chat-box");
  chatBox.scrollTop = chatBox.scrollHeight;
}
