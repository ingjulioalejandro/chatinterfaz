<?php
session_start();
include_once "config.php";

if(isset($_SESSION['unique_id'])){
    $chat_id = mysqli_real_escape_string($conn, $_POST['chat_id']);
    $is_group = $_POST['is_group'] === 'true';

    if($is_group){
        $sql = mysqli_query($conn, "SELECT * FROM chatrooms WHERE room_id = {$chat_id}");
    } else {
        $sql = mysqli_query($conn, "SELECT * FROM users WHERE unique_id = {$chat_id}");
    }

    if(mysqli_num_rows($sql) > 0){
        $row = mysqli_fetch_assoc($sql);
        $response = array(
            "name" => $is_group ? $row['room_name'] : $row['fname'] . " " . $row['lname'],
            "img" => $is_group ? "default_group.png" : $row['img'],
            "status" => $is_group ? "Group" : $row['status']
        );
        echo json_encode($response);
    } else {
        echo "Chat not found";
    }
} else {
    header("location: ../login.php");
}
?>
