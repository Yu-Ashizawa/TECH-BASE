何かコメントを送信して欲しいです！
パスワードは全部aです！




    <?php
    

    
    //データベースに接続
    $dsn = 'データベース名';
    $user = "ユーザー名";
    $password = "パスワード";
    //「PDO」というのは、PHPからデータベースを操作する機能のことです。本名は「PHP Data Objects」
                                            //データベース操作でエラーが発生した場合に警告（Worning: ）として表示する
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
       $sql = "CREATE TABLE IF NOT EXISTS keijiban3"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(32),"
    . "comment TEXT,"
    . "created_at DATETIME"
    .");";
    $stmt = $pdo->query($sql);
    
    //定義
    $password = "a";
    $editName="";
    $editComment="";
    $editNum = $_POST["editNumber"];


//新規投稿機能
if(isset($_POST["submit"]) &&  $_POST["pass_p"] == $password) {
    $sql = $pdo -> prepare("INSERT INTO keijiban3 (name, comment,created_at) VALUES (:name, :comment, :created_at)");
    $sql -> bindParam(':name', $name, PDO::PARAM_STR);
    $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
    $sql -> bindParam(':created_at', $created_at, PDO::PARAM_STR);
    $name = $_POST["name"];
    $comment = $_POST["comment"];   //好きな名前、好きな言葉は自分で決めること
   $created_at = date("Y-m-d H:i:s");
    $sql -> execute();
}

//削除機能
elseif(isset($_POST["deleteBotton"]) && $_POST["pass_d"] == $password ) {
    $id = $_POST["deleteNumber"];
    $sql = 'delete from keijiban3 where id=:id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
}

//編集機能
 if(!empty($_POST["judge"]) && isset($_POST["submit"])  ) {
    $id = $_POST["judge"];
    $name = $_POST["name"];
    $comment = $_POST["comment"]; //変更したい名前、変更したいコメントは自分で決めること
    $created_at = date("Y-m-d H:i:s");
    $sql = 'UPDATE keijiban3 SET name=:name,comment=:comment, created_at=:created_at WHERE id=:id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
    $stmt->bindParam(':created_at', $created_at, PDO::PARAM_STR);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
}   
    
   //データーベースの内容を全て表示
   $sql = 'SELECT * FROM keijiban3';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        //$rowの中にはテーブルのカラム名が入る
        echo $row['id'].',';
        echo $row['name'].',';
        echo $row['comment'].',';
        echo $row['created_at'].'<br>';
    echo "<hr>";
    }  
    
    
             //編集したいデータをフォームに表示
if(!empty($_POST["editNumber"]) && $_POST["pass_e"] == $password)  {
 $sql = 'SELECT * FROM keijiban3 WHERE id=:id';
    $id = $_POST["editNumber"];
    $stmt = $pdo->prepare($sql);
    $stmt -> bindParam(":id",$id);
    $stmt -> execute(); 
    $results = $stmt->fetch();
    
    if(empty($results)) {
        echo "投稿データを取得できませんでした";
    }
}

?>
    
    

    
<html>
    <head>
        <meta charset="UTF-8">
  <title>mission_3_5</title>
    </head>
    <body>
            <form method="post" action="">
       <input type = "hidden" name = "judge" value="<?php  if($editNum != 0) { echo $editNum; }?>"> 
       <input type ="text" name="name" placeholder= "名前" value="<?php if(!empty($results['name'])) {echo $results["name"]; }?>" ><br>
       <input type="text" name="comment" placeholder="コメント" value="<?php if(!empty($results['comment'])) {echo $results["comment"]; }?>" ><br>
       <input type= "password" name = "pass_p" placeholder = "投稿パスワード"> 
       <input type="submit" name="submit" value="送信"><br><br>
      <input type = "number" name="deleteNumber" placeholder="削除対象番号"><br>
      <input type = "password" name = "pass_d" placeholder ="削除パスワード">
      <input type = "submit" name= "deleteBotton" value="削除"><br><br>
      <input type = "number" name= "editNumber" placeholder="編集対象番号" ><br>
      <input type = "password" name = "pass_e" placeholder ="編集パスワード">
      <input type = "submit" name= "editBotton" value = "編集">
     
    </form>
    </body>