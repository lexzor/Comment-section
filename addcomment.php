<?php 
    include('connection.php');

    if(isset($_POST['comment']))
    {
        $comment = $_POST['comment'];
        $user_name = $_POST['username'];

        if(strlen($comment) == 0)
        {
            echo 2;
            return;
        }

        if(strlen($user_name) == 0)
        {
            echo 1; 
            return;
        }

        $currDate = date('H:i:s - d/m/Y');
        $stmt = $conn->prepare("INSERT INTO `comments` (`uname`, `ucomm`, `post_date`) VALUES (?,?,?)");
        $stmt->execute([$user_name, $comment,$currDate]);

        $data['type'] = "0";
        $data['uname'] = $user_name;
        $data['comment'] = $comment;
        $data['date'] = $currDate;
        echo json_encode($data);
    } else echo "Erorr";
?>