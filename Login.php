<html>

<head>
    <meta name="viewport"
        content="width=320, height=480, initial-scale=1.0, minimum-scale=1.0, maximum-scale=2.0, user-scalable=yes">
    <meta charset="utf-8">
    <title>Login</title>
</head>

<body>

<form action="Talk.php" method="post">
    Name<input type="text" name="Name" value=""><br>
    Pass<input type="text" name="Pass" value=""><br>
    <input type="submit" name="login" value="ログイン">
</form>
<a href='QreateAccount.php'>アカウント登録<a><br>

</body>

</html>

<?php
    //DB接続
    $dsn = 'DB名';
	$user = 'ユーザ名';
	$password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

    //アカウント登録
    if(isset($_POST['InTo'])){
        if($_POST['Name']=="" || $_POST['Pass']==""){
            //Error処理（リダイレクト）
            header('Location: QreateAccount.php',true, 301);
            exit;
        }else{
            //insert
            $sql = $pdo -> prepare("INSERT INTO Account (name, pass) VALUES (:name, :pass)");
	        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
	        $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
	        $name = $_POST['Name'];
	        $pass = $_POST['Pass'];
            $sql -> execute();
            $OK = "<script type='text/javascript'>alert('登録完了');</script>";
            echo $OK;
        }
    }
?>


