<?php 
  session_start();
  include_once "php/config.php";
  if(!isset($_SESSION['unique_id'])){
    header("location: login.php");
  }
?>
<?php include_once "header.php"; ?>
<body>
  <div class="teams-wrapper">
    <nav class="sidebar">
      <div class="user-profile">
        <?php 
          $sql = mysqli_query($conn, "SELECT * FROM users WHERE unique_id = {$_SESSION['unique_id']}");
          if(mysqli_num_rows($sql) > 0){
            $row = mysqli_fetch_assoc($sql);
          }
        ?>
        <img src="php/images/<?php echo $row['img']; ?>" alt="">
      </div>
      <ul>
        <li><a href="#" class="active"><i class="fas fa-comments"></i></a></li>
        <li><a href="#"><i class="fas fa-users"></i></a></li>
        <li><a href="#"><i class="fas fa-calendar"></i></a></li>
      </ul>
      <a href="php/logout.php?logout_id=<?php echo $row['unique_id']; ?>" class="logout"><i class="fas fa-sign-out-alt"></i></a>
    </nav>
    <div class="main-content">
      <div class="chat-list">
        <header>
          <h2>Chat</h2>
          <div class="user-info">
            <?php 
              $sql = mysqli_query($conn, "SELECT * FROM users WHERE unique_id = {$_SESSION['unique_id']}");
              if(mysqli_num_rows($sql) > 0){
                $row = mysqli_fetch_assoc($sql);
              }
            ?>
            <span><?php echo $row['fname']. " " . $row['lname'] ?></span>
            <small><?php echo $row['status']; ?></small>
          </div>
        </header>
        <div class="search">
          <input type="text" placeholder="Search">
          <button><i class="fas fa-search"></i></button>
        </div>
        <div class="users-list">
          <!-- Users will be loaded here -->
        </div>
      </div>
      <div class="chat-area" id="chat-area">
        <header>
          <!-- El encabezado del chat se actualizará dinámicamente -->
        </header>
        <div class="chat-box">
          <!-- Los mensajes se cargarán aquí -->
        </div>
        <form action="#" class="typing-area">
          <input type="text" class="incoming_id" name="incoming_id" hidden>
          <input type="text" name="message" class="input-field" placeholder="Type a message here..." autocomplete="off">
          <button><i class="fab fa-telegram-plane"></i></button>
        </form>
      </div>
    </div>
  </div>

  <script src="javascript/users.js"></script>
  <script src="javascript/chat.js"></script>

</body>
</html>
