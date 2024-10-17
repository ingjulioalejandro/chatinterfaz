<?php
session_start();
include_once "config.php";

$searchTerm = mysqli_real_escape_string($conn, $_POST['searchTerm']);
$output = "";
$user_id = $_SESSION['unique_id'];

// Buscar usuarios
$sql = "SELECT * FROM users 
        WHERE NOT unique_id = {$user_id}
        AND (fname LIKE '%{$searchTerm}%' OR lname LIKE '%{$searchTerm}%')";
$query = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($query)) {
    $output .= '<div class="chat-item">
                    <img src="php/images/'.$row['img'].'" alt="">
                    <div class="chat-info">
                        <h3>'.$row['fname'].' '.$row['lname'].'</h3>
                        <p>User</p>
                    </div>
                </div>';
}

// Buscar grupos
$sql = "SELECT * FROM chatrooms 
        WHERE room_id IN (SELECT group_id FROM group_members WHERE user_id = {$user_id})
        AND room_name LIKE '%{$searchTerm}%'";
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
