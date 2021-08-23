<?php

// 【サンプル】
    // ・データベース名：tb230279db
    // ・ユーザー名：tb-230279
    // ・パスワード：Bk8fQNFEvA
    // の学生の場合：

    // DB接続設定
    $dsn = 'データベース名';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

if (!$pdo){
    die("connection failed".mysql_error());
}

$num_h=filter_input(INPUT_POST, "num_h");
$num_e=filter_input(INPUT_POST, "num_e");
$num_d=filter_input(INPUT_POST, "num_d");
$pas_d=filter_input(INPUT_POST, "pas_d");


//フォームからのデータをテーブルに格納する
if(filter_input(INPUT_POST, "name") && empty($num_h)){
    
    $sql='INSERT INTO mission5 (name, comment, password) VALUES (:name, :comment, :password)';
    $stmt=$pdo->prepare($sql);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
    $stmt->bindParam(':password', $password, PDO::PARAM_STR);
    $name=filter_input(INPUT_POST, "name");
    $comment=filter_input(INPUT_POST, "com");
    $password=filter_input(INPUT_POST, "pas");
    $stmt->execute();
    
}


//編集機能
//指定されたnumberから要素を抽出する
if(isset($num_e)){
    $sql='SELECT * FROM mission5 WHERE number=:num_e';
    $stmt=$pdo->prepare($sql);
    $stmt->bindParam(':num_e',$num_e, PDO::PARAM_INT);
    $stmt->execute();
    $result=$stmt->fetch(PDO::FETCH_ASSOC);
    $num_s=$num_e;
    $name_s=$result['name'];
    $com_s=$result['comment'];

}
//編集する
if(!empty($num_h)){
    
    $pas2=filter_input(INPUT_POST, "pas");
    
    //パスワード抽出
    $sql='select password from mission5 WHERE number=:num_h';
    $stmt=$pdo->prepare($sql);
    $stmt->bindParam(':num_h',$num_h, PDO::PARAM_INT);
    $stmt->execute();
    $result=$stmt->fetch(PDO::FETCH_ASSOC);
    $pas_s=$result['password'];
    //抽出したパスワードと受け取ったものが一致すれば更新する
    if ($pas2==$pas_s){
        $sql='update mission5 set name=:name2, comment=:comment2 where number=:num_h';
        $stmt=$pdo->prepare($sql);
        $stmt->bindParam(':name2', $name2, PDO::PARAM_STR);
        $stmt->bindParam(':comment2', $comment2, PDO::PARAM_STR);
        $stmt->bindParam(':num_h',$num_h, PDO::PARAM_INT);
        $name2=filter_input(INPUT_POST, "name");
        $comment2=filter_input(INPUT_POST, "com");
        $stmt->execute();
    }
}

//削除する
if (isset($num_d)){
    //パスワード抽出
    $sql='select password from mission5 WHERE number=:num_d';
    $stmt=$pdo->prepare($sql);
    $stmt->bindParam(':num_d',$num_d, PDO::PARAM_INT);
    $stmt->execute();
    $result=$stmt->fetch(PDO::FETCH_ASSOC);
    $pas_s=$result['password'];
    
    //抽出したパスワードと受け取ったパスワードが一致すれば削除する
    if($pas_s==$pas_d){
        $sql='delete from mission5 where number=:num_d';
        $stmt=$pdo->prepare($sql);
        $stmt->bindParam(':num_d',$num_d, PDO::PARAM_INT);
        $stmt->execute();
    }
}  

//内容表示
$sql='select number, name, comment from mission5';
$stmt=$pdo->query($sql);
while($result=$stmt->fetch(PDO::FETCH_BOTH)){
    echo $result["number"]."<>".$result["name"]."<>".$result["comment"]."<br>";
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title> mission5_1 </title>
    </head>
    <body>
        <form action="" method="post">
            <p>名前</p><input type="text" name="name" 
            value=<?php if (isset($name_s)){echo $name_s;}?>><br>
            <p>コメント</p><input type="text" name="com"
            value=<?php if (isset($com_s)){echo $com_s;}?>><br>
            <p>パスワード</p><input type="password" name="pas" >
            <input type="hidden" name="num_h" 
            value =<?php if (isset($num_s)){echo $num_s;}?>>
            <input type="submit" value="送信">
            
        </form>
        <form action="" method="post">
            <p>編集する投稿番号</p><input type="num_e" name="num_e">
            <input type="submit" value="編集">
        </form>
        <form action="" method="post">
            <p>削除する投稿番号</p><input type="number" name="num_d"><br>
            <p>パスワード</p><input type="password" name="pas_d">
            <input type="submit" value="削除">
        </form>
    </body>
</html>
         