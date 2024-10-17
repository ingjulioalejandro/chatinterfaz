<?php
session_start();
include_once "config.php";

if(!isset($_SESSION['unique_id'])){
    echo "Session unique_id not set";
    exit;
}

$outgoing_id = $_SESSION['unique_id'];
$sql = "SELECT * FROM users WHERE NOT unique_id = ? AND unique_id IS NOT NULL";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $outgoing_id);
mysqli_stmt_execute($stmt);
$query = mysqli_stmt_get_result($stmt);

if(!$query){
    echo "Query failed: " . mysqli_error($conn);
    exit;
}

$output = "";

if(mysqli_num_rows($query) == 0){
    $output .= "No users are available to chat";
}elseif(mysqli_num_rows($query) > 0){
    while($row = mysqli_fetch_assoc($query)){
        if(!isset($row['unique_id'])){
            continue;  // Skip users without unique_id
        }
        
        // Rest of your code to generate user list
        $sql2 = "SELECT * FROM messages 
                 WHERE (incoming_msg_id = ? OR outgoing_msg_id = ?) 
                 AND (outgoing_msg_id = ? OR incoming_msg_id = ?) 
                 ORDER BY msg_id DESC LIMIT 1";
        $stmt2 = mysqli_prepare($conn, $sql2);
        mysqli_stmt_bind_param($stmt2, "ssss", $row['unique_id'], $row['unique_id'], $outgoing_id, $outgoing_id);
        mysqli_stmt_execute($stmt2);
        $query2 = mysqli_stmt_get_result($stmt2);
        $row2 = mysqli_fetch_assoc($query2);

        $result = (mysqli_num_rows($query2) > 0) ? $row2['msg'] : "No message available";
        $msg = (strlen($result) > 28) ? substr($result, 0, 28) . '...' : $result;
        
        $you = (isset($row2['outgoing_msg_id']) && $outgoing_id == $row2['outgoing_msg_id']) ? "You: " : "";
        
        $offline = ($row['status'] == "Offline now") ? "offline" : "";
        
        $output .= '<a href="#" class="user-item" data-user-id="'.$row['unique_id'].'">
                    <div class="content">
                    <img src="php/images/'. (isset($row['img']) ? $row['img'] : 'default.png') .'" alt="">
                    <div class="details">
                        <span>'. $row['fname']. " " . $row['lname'] .'</span>
                        <p>'. $you . $msg .'</p>
                    </div>
                    </div>
                    <div class="status-dot '. $offline .'"><i class="fas fa-circle"></i></div>
                </a>';
    }
}

echo $output;
?>
