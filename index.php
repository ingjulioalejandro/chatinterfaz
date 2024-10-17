<?php 
  session_start();
  var_dump($_SESSION);  // This will show all session variables
  if(!isset($_SESSION['unique_id'])){
    header("location: login.php");
    exit;
  }
?>

<?php include_once "header.php"; ?>
<body>
  <div class="wrapper">
    <section class="users">
      <header>
        <!-- ... (código del usuario actual) ... -->
      </header>
      <div class="search">
        <span class="text">Select a user to start chat</span>
        <input type="text" placeholder="Enter name to search...">
        <button><i class="fas fa-search"></i></button>
      </div>
      <div class="users-list">
        <!-- Los usuarios se cargarán aquí -->
      </div>
    </section>
    <section class="chat-area">
      <!-- El chat se cargará aquí -->
    </section>
  </div>
  
  <script src="javascript/users.js"></script>
  <script src="javascript/chat.js"></script>
</body>
</html>
