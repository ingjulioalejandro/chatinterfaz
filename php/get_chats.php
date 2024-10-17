<?php
session_start();
include_once "config.php";

$output = "";
$user_id = $_SESSION['unique_id'];

// Obtener chats individuales
$sql = "SELECT * FROM users WHERE NOT unique_id = {$user_id}";
$query = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($query)) {
    $output .= '<div class="chat-item">
                    <img src="php/images/'.$row['img'].'" alt="">
                    <div class="chat-info">
                        <h3>'.$row['fname'].' '.$row['lname'].'</h3>
                        <p>Last message...</p>
                    </div>
                </div>';
}

// Obtener chats grupales
$sql = "SELECT * FROM chatrooms WHERE room_id IN (SELECT group_id FROM group_members WHERE user_id = {$user_id})";
$query = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($query)) {
    $output .= '<div class="chat-item">
                    <img src="php/images/group.png" alt="">
                    <div class="chat-info">
                        <h3>'.$row['room_name'].'</h3>
                        <p>Group chat</p>
                    </div>
                </div>';
}

echo $output;
?>
