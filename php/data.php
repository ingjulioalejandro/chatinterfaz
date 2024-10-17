<?php
    while($row = mysqli_fetch_assoc($query)){
        if(isset($row['unique_id'])) {
            $sql2 = "SELECT * FROM messages WHERE (incoming_msg_id = {$row['unique_id']}
                    OR outgoing_msg_id = {$row['unique_id']}) AND (outgoing_msg_id = {$outgoing_id} 
                    OR incoming_msg_id = {$outgoing_id}) ORDER BY msg_id DESC LIMIT 1";
            $query2 = mysqli_query($conn, $sql2);
            $row2 = mysqli_fetch_assoc($query2);
            
            $result = (mysqli_num_rows($query2) > 0) ? $row2['msg'] : "No message available";
            $msg = (strlen($result) > 28) ? substr($result, 0, 28) . '...' : $result;
            
            $you = (isset($row2['outgoing_msg_id']) && $outgoing_id == $row2['outgoing_msg_id']) ? "You: " : "";
            
            $offline = ($row['status'] == "Offline now") ? "offline" : "";
            
            $output .= '<a href="#" class="user-item" data-user-id="'.$row['unique_id'].'">
                        <div class="content">
                        <img src="php/images/'. (file_exists('images/'.$row['img']) ? $row['img'] : 'default.png') .'" alt="">
                        <div class="details">
                            <span>'. $row['fname']. " " . $row['lname'] .'</span>
                            <p>'. $you . $msg .'</p>
                        </div>
                        </div>
                        <div class="status-dot '. $offline .'"><i class="fas fa-circle"></i></div>
                    </a>';
        }
    }
?>
