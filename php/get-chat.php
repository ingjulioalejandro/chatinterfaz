<?php 
    session_start();
    if(isset($_SESSION['unique_id'])){
        include_once "config.php";
        $outgoing_id = $_SESSION['unique_id'];
        $incoming_id = mysqli_real_escape_string($conn, $_POST['incoming_id']);
        $output = "";
        $sql = "SELECT * FROM messages LEFT JOIN users ON users.unique_id = messages.outgoing_msg_id
                WHERE (outgoing_msg_id = {$outgoing_id} AND incoming_msg_id = {$incoming_id})
                OR (outgoing_msg_id = {$incoming_id} AND incoming_msg_id = {$outgoing_id}) ORDER BY msg_id";
        $query = mysqli_query($conn, $sql);
        if(mysqli_num_rows($query) > 0){
            while($row = mysqli_fetch_assoc($query)){
                if($row['outgoing_msg_id'] === $outgoing_id){
                    $output .= '<div class="chat outgoing">
                                <div class="details">
                                    <p>'.htmlspecialchars($row['msg']).'</p>
                                </div>
                                </div>';
                }else{
                    $output .= '<div class="chat incoming">
                                <img src="php/images/'.$row['img'].'" alt="">
                                <div class="details">
                                    <p>'.htmlspecialchars($row['msg']).'</p>
                                </div>
                                </div>';
                }
            }
        }else{
            $output .= '<div class="text">No messages are available. Once you send message they will appear here.</div>';
        }
        echo $output;
    }else{
        header("location: ../login.php");
    }

    function getFileIcon($extension) {
        $icon_classes = [
            'pdf' => 'far fa-file-pdf',
            'doc' => 'far fa-file-word',
            'docx' => 'far fa-file-word',
            'xls' => 'far fa-file-excel',
            'xlsx' => 'far fa-file-excel',
            'txt' => 'far fa-file-alt',
            'csv' => 'far fa-file-csv',
            'zip' => 'far fa-file-archive',
            'rar' => 'far fa-file-archive',
            'mp3' => 'far fa-file-audio',
            'mp4' => 'far fa-file-video',
            'avi' => 'far fa-file-video',
            'mov' => 'far fa-file-video',
            'jpg' => 'far fa-file-image',
            'jpeg' => 'far fa-file-image',
            'png' => 'far fa-file-image',
            'gif' => 'far fa-file-image'
        ];

        return isset($icon_classes[strtolower($extension)]) ? $icon_classes[strtolower($extension)] : 'far fa-file';
    }
?>
