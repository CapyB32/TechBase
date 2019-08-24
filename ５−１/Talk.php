<html>

<head>
    <meta name="viewport"
        content="width=320, height=480, initial-scale=1.0, minimum-scale=1.0, maximum-scale=2.0, user-scalable=yes">
    <meta charset="utf-8">
    <title>TalkRoom</title>
</head>

<body>
<?php
    //DB接続
    $dsn = 'DB名';
	$user = 'ユーザ名';
	$password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

    //ページ飛んだら
    if(isset($_POST['login'])){
        $name = $_POST['Name'];
        $pass = $_POST['Pass'];
        //Login
        $sql = 'SELECT * FROM Account WHERE name = "'.$name.'" AND pass = "'.$pass.'";';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        if(count($results)>0){
            echo "<script type='text/javascript'>alert('ログイン成功');</script>";
        }else{
            header('Location: Login.php');
        }

        //名前表示
        echo "<h1>".$name."さんのトークルーム"."</h1>";
        //ID習得
        $ID=$results[0]['id'];
    }

    //投稿されたら
    if(isset($_POST['Touko']) || isset($_POST['interval']) || isset($_POST['delete']) || isset($_POST['allange'])){
        $namazun=$_POST['namae'];
        echo "<h1>".$namazun."'s Talk Room"."</h1>";
    }
?>

<form action="Talk.php" method="post">
    Pass<input type="text" name="Pass" value="<?php
        //パスワード自動入力
        if(isset($_POST['login'])){
            $pass = $_POST['Pass'];
            echo $pass;
        }
        if(isset($_POST['Touko']) || isset($_POST['interval']) || isset($_POST['delete']) || isset($_POST['allange'])){
            $mPass=$_POST['Pass'];
            echo $mPass;
        }
    ?>"><br>
    Talk<input type="text" name="Talk" value="">
    <input type="submit" name="Touko" value="投稿"><br>
    Delete<input type="text" name="dell" value="">
    <input type="submit" name="delete" value="消去"><br>
    Allange<input type="text" name="alala" value="">
    <input type="submit" name="allange" value="編集">
    <?php
        //Delete
        if(isset($_POST['allange'])){
            $mPass=$_POST['Pass'];
            $nPass=$_POST['oriPass'];
            $talk=$_POST['Talk'];
            $namazun=$_POST['namae'];
            $IDnumber=$_POST['secretNumber'];
            $Aid=$_POST['alala'];
            $poa=Secret($Aid,$IDnumber);
            if($poa>0){
                //Pass照合投稿
             if($mPass==$nPass){
                    echo 'ID:'.$Aid.'を編集できます';
                }else{
                    echo "<script type='text/javascript'>alert('Passwordが違います');</script>";
                }
            }else{
                echo "<script type='text/javascript'>alert('編集できません');</script>";
            }
        }
    ?><br>
    <input type="hidden" name="namae" value="<?php
        if(isset($_POST['login'])){
            $namae = $_POST['Name'];
            //名前表示
            echo $namae;
        }
        if(isset($_POST['Touko']) || isset($_POST['interval']) || isset($_POST['delete']) || isset($_POST['allange'])){
            $namazun=$_POST['namae'];
            echo $namazun;
        }
    ?>">
    <input type="hidden" name="secretNumber" value="<?php
        if(isset($_POST['login'])){
            $name = $_POST['Name'];
            $pass = $_POST['Pass'];
            $sql = 'SELECT * FROM Account WHERE name = "'.$name.'" AND pass = "'.$pass.'";';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            //ID習得
            $ID=$results[0]['id'];
            echo $ID;
        }
        if(isset($_POST['Touko']) || isset($_POST['interval']) || isset($_POST['delete']) || isset($_POST['allange'])){
            $IDnumber=$_POST['secretNumber'];
            echo $IDnumber;
        }
    ?>">
    <input type="hidden" name="oriPass" value="<?php
        if(isset($_POST['login'])){
            $Passn = $_POST['Pass'];
            //Pass表示
            echo $Passn;
        }
        if(isset($_POST['Touko']) || isset($_POST['interval']) || isset($_POST['delete']) || isset($_POST['allange'])){
            $nPass=$_POST['oriPass'];
            echo $nPass;
        }
    ?>">
    <input type="hidden" name="alabox" value="<?php
        //Allange
        if(isset($_POST['allange'])){
            $mPass=$_POST['Pass'];
            $nPass=$_POST['oriPass'];
            $talk=$_POST['Talk'];
            $namazun=$_POST['namae'];
            $IDnumber=$_POST['secretNumber'];
            $Aid=$_POST['alala'];
            $poa=Secret($Aid,$IDnumber);
            if($poa>0){
                //Pass照合投稿
                if($mPass==$nPass){
                    //編集ID表示
                    echo $Aid;
                }
            }
        }
    ?>">
    <input type="submit" name="interval" value="更新">
</form><br>
<hr>

<div id="yohaku"></div>

<?php
    //insert into Talk
    if(isset($_POST['Touko'])){
        $mPass=$_POST['Pass'];
        $nPass=$_POST['oriPass'];
        $talk=$_POST['Talk'];
        $namazun=$_POST['namae'];
        $IDnumber=$_POST['secretNumber'];
        $Ajudge=$_POST['alabox'];

        //編集するか？
        if($Ajudge==''){
            //投稿
            //Pass照合投稿
            if($mPass==$nPass){
                $sql = $pdo -> prepare("INSERT INTO Talk (who, comment, time) VALUES (:who, :comment, :time)");
	            $sql -> bindParam(':who', $who, PDO::PARAM_STR);
                $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
                $sql -> bindParam(':time', $time, PDO::PARAM_STR);
	            $who = $IDnumber;
                $comment = $talk;
                $time = date('Y-m-d H:i:s');
                $sql -> execute();
            }else{
                echo "<script type='text/javascript'>alert('Passwordが違います');</script>";
            }
        }else{
            //編集
            if($mPass==$nPass){
                //updata文
                $id = $Ajudge; 
	            $who = $IDnumber;
                $comment = $talk; 
                //$time = date('Y-m-d H:i:s');
	            $sql = 'update Talk set comment=:comment where id=:id';
	            $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_STR);
                // $stmt->bindParam(':who', $who, PDO::PARAM_STR);
	            $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
	            // $stmt->bindParam(':time', $time, PDO::PARAM_INT);
                $stmt->execute();
            }else{
                echo "<script type='text/javascript'>alert('Passwordが違います');</script>";
            }
        }
    }

    //Delete
    if(isset($_POST['delete'])){
        $mPass=$_POST['Pass'];
        $nPass=$_POST['oriPass'];
        $talk=$_POST['Talk'];
        $namazun=$_POST['namae'];
        $IDnumber=$_POST['secretNumber'];
        $Did=$_POST['dell'];
        $poa=Secret($Did,$IDnumber);
        if($poa>0){
            //Pass照合投稿
            if($mPass==$nPass){
                $id = $Did;
	            $sql = 'delete from Talk where id=:id';
	            $stmt = $pdo->prepare($sql);
	            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
	            $stmt->execute();
            }else{
                echo "<script type='text/javascript'>alert('Passwordが違います');</script>";
            }
        }else{
            echo "<script type='text/javascript'>alert('編集できません');</script>";
        }
    }
?>

<script type='text/javascript'>
var funcy=setInterval(DD,1000);
function DD(){
    document.getElementById("yohaku").innerHTML="<?php 
        $dispData=Selecting();
        Disp($dispData);
    ?>";
}
</script>

<?php 
    // if(isset($_POST['login']) || isset($_POST['Touko']) || isset($_POST['interval'])){
    //     $dispData=Selecting();
    //     Disp($dispData);
    // }
    //Data習得
    function Selecting(){
        //DB接続
        $dsn = 'DB名';
	    $user = 'ユーザ名';
	    $password = 'パスワード';
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        //SELECT
        $sql = 'SELECT Talk.id AS num, name, comment, time FROM Talk INNER JOIN Account ON Talk.who = Account.id ORDER BY num DESC';
	    $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        return $results;
    }
    //ディスプレイ
    function Disp($tab){
        foreach ($tab as $row){
            echo $row['num'].',';
            echo $row['name'].',';
            echo $row['comment'].',';
            echo $row['time'].'<br>';
            echo "<hr>";
        }
    }

    //セキュリティ
    function Secret($x,$y){
        //DB接続
        $dsn = 'DB名';
	    $user = 'ユーザ名';
	    $password = 'パスワード';
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        //照合
        $sql = 'SELECT * FROM Talk WHERE id='.$x.' AND who='.$y.';';
	    $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        if(count($results)>0){
            return 1;//TRUE
        }else{
            return 0;//FALSE
        }
    }
?>

</body>

</html>

<?php
// //Pass照合
//         if($mPass==$nPass){
//             echo 'OK';
//         }else{
//             echo "<script type='text/javascript'>alert('Passwordが違います');</script>";
//         }
//     };
?>
