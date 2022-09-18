<?php 
    function getComments()
    {
        include('connection.php');
        $stmt = $conn->prepare("SELECT * FROM comments ORDER BY id DESC");
        $stmt -> execute();

        while($row = $stmt -> fetch(PDO::FETCH_ASSOC))
        {
            ?>
                <div class="item">

                    <div class="item-top">
                        <h1>Posted by <span><?php echo $row['uname'] ?></span></h1>
                        <h2>Posted at <?php echo $row['post_date'] ?></h2> 
                    </div>

                    <p><?php echo $row['ucomm'] ?></p>
                </div>
            <?php
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comment section</title>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-json/2.6.0/jquery.json.min.js" integrity="sha512-QE2PMnVCunVgNeqNsmX6XX8mhHW+OnEhUhAWxlZT0o6GFBJfGRCfJ/Ut3HGnVKAxt8cArm7sEqhq2QdSF0R7VQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Quicksand:wght@300&family=Roboto&display=swap');

        * {
            margin: 0;
            font-family: 'Quicksand', sans-serif;
            padding: 0;
        }

        *:focus {
            outline: none;
        }

        body {
            background-color: rgb(15, 10, 41);
        }

        .item {
            color: white;
            background-color: rgb(30, 23, 65);
            width: 900px;
            min-height: 100px;
            margin: 100px auto;
            padding: 20px;
            border-radius: 7px;
            box-shadow: 0 7px 10px rgba(0, 0, 0, 0.411);
        }

        .item span {
            color:rgb(67, 32, 241);
            font-family: 'Roboto', sans-serif;
            font-weight: 700;
        }

        .item span:hover {
            cursor: pointer;
        }

        .item h1 {
            font-size: 25px;
            font-weight: 100;
        }

        .item p {
            padding: 14px;
            font-weight: 500;
            font-size: 17px;
        }

        .item-top {
            display: flex;
            justify-content: space-between;
        }

        .item-top h2{
            font-size: 20px;
            color: rgb(124, 124, 124);
        }

        .add-container {
            width: fit-content;
            height: fit-content;
            margin: 120px auto;
            background-color: rgb(57, 45, 117);
            padding: 20px;
            border-radius: 7px;
            box-shadow: 0 7px 10px rgba(0, 0, 0, 0.411);
            color: white;
        }

        .add-container textarea {
            background-color: rgb(30, 23, 65);
            border: none;
            padding: 10px;
            color: white;
        }

        .add-container h1 {
            margin-bottom: 10px;
        }

        .add-container form {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 20px;
        }

        .add-container input {
            padding: 7px;
            width: 70%;
            text-transform: uppercase;
            font-family: 'Roboto', sans-serif;
            font-weight: bold;
            background-color: rgb(0, 127, 185);
            border: none;
            border-radius: 7px;
            color: white;
            font-size: 17px;
            transition: 0.6s;
        }

        .add-container input:hover {
            cursor: pointer;
            background-color: rgb(48, 188, 253);
            color: black;
        }

        #user_name {
            width: 200px;
            height: 10px;
            position: absolute;
            margin-left: auto;
            margin-right: auto;
            margin-top: -70px;
            left: 0;
            right: 0;
            text-align: center;
            padding: 10px;
            background-color: black;
            color: white !important; 
            font-weight: bold !important;
            border: none;
            border-radius: 5px;
        }

        .warning {
            padding: 10px;
            width: fit-content;
            height: fit-content;
            background-color: rgb(153, 38, 38);
            position: absolute;
            right: 70px;
            top: 50px;
            border-radius: 8px;
            color: white;
            transition: 0.6s;
            opacity: 0;
        }

        .warning:hover {
            user-select: none;
        }

    </style>
</head>
<body>
    <input type="text" id="user_name">
    
    <div class="warning">
        You must
    </div>
    
    <div class="add-container">
        <h1>Add a comment!</h1>
        <form action="addcomment.php" method="post">
            <textarea name="comment" cols="100" rows="5"></textarea>
            <input type="submit" name="submitbtn" value="Post">
        </form>
    </div>

    <div class="comment-container">
        <?php getComments(); ?>
    </div>

    <script>

        const ws = new WebSocket("ws://localhost:8082/Panel/comment_test/");
        ws.binaryType = "json";
        ws.addEventListener("open", () => {
            console.log("We are connected");
        });

        let intervalID = 0;

        ws.onmessage = function(e) {
            let parseData = JSON.parse(e.data);
            addComment(parseData);
        };

        function showWarningMessage(type)
        {
            const msgContainer = document.querySelector('.warning');

            if(type == 1)   msgContainer.innerHTML = "You must type a username first!"; else msgContainer.innerHTML = "You must type a comment!";

            msgContainer.style.opacity = "1"

            intervalID = setInterval(function() {
                msgContainer.style.opacity = "0";
                clearInterval(intervalID)
            }, 3000)
        }

        function addComment(obj)
        {
            let item, p, h1, span, item_top, h2;
            const container = document.querySelector('.comment-container');
            
            item = document.createElement('div');
            item.classList.add('item');
            
            item_top = document.createElement('div');
            item_top.classList.add('item-top');

            h1 = document.createElement('h1');
            h1.innerHTML = 'Posted by ';

            span = document.createElement('span');
            span.innerHTML = obj.uname;
            h1.append(span);
            item_top.appendChild(h1);

            h2 = document.createElement('h2');
            h2.innerHTML = 'Posted at ' + obj.date;

            item_top.appendChild(h2);

            p = document.createElement('p');
            p.innerHTML = obj.comment;

            item_top.appendChild(h1);
            item_top.appendChild(h2);
            item.appendChild(item_top);
            item.appendChild(p);
            container.prepend(item);
        }

        $(document).ready(function () {

            document.getElementById('user_name').placeholder = "Username";

            $('form').submit(function(e) {
                e.preventDefault();
                $data = $(this).serialize();
                $data += '&username=' + $("#user_name").val();
                $.ajax({
                    type: "POST",
                    data: $data,
                    dataType:"json",
                    url: 'addcomment.php',
                    success: function(retrieveddata){
                        let data = JSON.stringify(retrieveddata);

                        if(data != 1 && data != 2)
                        {
                            const obj = JSON.parse(data);
                            ws.send(data);
                        }
                        else 
                        {
                            showWarningMessage(data);
                        }
                    },
                    error: function()
                    {
                        console.log("error");
                    }
                })

            })
        })
    </script>

</body>
</html>