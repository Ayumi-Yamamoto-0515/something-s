<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>mission_5-1</title>
</head>
<body>
<?php
// DB接続設定
	$dsn = 'データベース名';
	$user = 'ユーザー名';
	$password = 'パスワード';
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE =>PDO::ERRMODE_WARNING));
//テーブル作成
    $sql = "CREATE TABLE IF NOT EXISTS tbtest"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"//番号のカラム
	. "name char(32),"//名前のカラム
	. "comment TEXT,"//コメントのカラム
	. "pass TEXT"//パスワードのカラム
	.");";
	$stmt = $pdo->query($sql);

//編集フォームから送信されたとき（確認用フォームに入れる）
if(!empty($_POST["num2"]) && !empty($_POST["edit_pass"])){
    $sql = 'SELECT * FROM tbtest';
	$stmt = $pdo->query($sql);//sql文を実行して，データを取得
	$results = $stmt->fetchAll();//fetchAllで結果を全て配列で取得
	//投稿番号とパスが一致
	foreach ($results as $row){
	    if($row['id']==$_POST["num2"]){
	        if($row['pass']==$_POST["edit_pass"]){
	            $editdate0=$row['id'];//投稿番号
                $editdate1=$row['name'];//名前
                $editdate2=$row['comment'];//コメント
                $editdate3=$row['pass'];//パスワード
	        }
	    }
	    
	}
}
//データの入力(＝新規投稿)
 //新規投稿＝確認用ボックスが空の時
 if(empty($_POST["check"])){
     if(!empty($_POST["name"]) && !empty($_POST["textbox"]) && !empty($_POST["pass"])){
         //prepare:それぞれのテーブル名にパラメータを与える
         $sql = $pdo -> prepare("INSERT INTO tbtest (name,  comment, pass) VALUES (:name, :comment, :pass)");
	     //パラメータを指定し，変数を代入する
	     $sql -> bindParam(':name', $name, PDO::PARAM_STR);
	     $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
	     $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
	     //受信した名前
	     $name = $_POST["name"];
	     //受信したコメント
	     $comment = $_POST["textbox"]; 
	     //受信したパスワード
	     $pass =$_POST["pass"];
	     $sql -> execute(); //sql文を実行
    }
 }
elseif(!empty($_POST["check"])){//確認用フォームが空じゃない=編集
    if(!empty($_POST["textbox"]) && !empty($_POST["name"]) && !empty($_POST["pass"])){
        //データの編集
            //＄idを確認フォームから受信したものと定義
            $id = $_POST["check"]; 
            //入力フォームから受信した新しい名前に変更
            $name = $_POST["name"];
            //入力フォームから受信した新しいコメントに変更
            $comment = $_POST["textbox"];
            //入力フォームから受信した新しいパスに変更
            $pass = $_POST["pass"]; 
            $sql = 'UPDATE tbtest SET name=:name,comment=:comment,pass=:pass WHERE id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
            $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();   
    }
}
//データの削除
if(!empty($_POST["num"]) && !empty($_POST["del_pass"])){
    $sql = 'SELECT * FROM tbtest';
	$stmt = $pdo->query($sql);//sql文を実行して，データを取得
	$results = $stmt->fetchAll();//fetchAllで結果を全て配列で取得
	foreach ($results as $row){
        //passが一致したら削除
        if($row['pass'] = $_POST["del_pass"]){
            $id = $_POST["num"];
            $sql = 'delete from tbtest where id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();   
        }
    }
}
?>
<!--フォーム作成-->
<form action="" method="post">
    <!--編集番号確認用（あとで見えなくする）-->
    <input type="hidden" name="check" value="<?php echo $editdate0;?>">
    入力用フォーム：
    <input type="text" name="name" placeholder="名前" 
    value="<?php echo $editdate1 ?>">
    <input type="text" name="textbox" placeholder="コメント"
    value="<?php echo $editdate2 ?>">
    <input type="text" name="pass" placeholder="パスワード"
    value="<?php echo $editdate3 ?>">
    <input type="submit" value="送信"><br>
    削除用フォーム：
    <input type="number" name="num" placeholder="投稿番号">
    <input type="text" name="del_pass" placeholder="パスワード">
    <input type="submit" value="削除"><br>
    編集用フォーム：
    <input type="number" name="num2" placeholder="投稿番号">
    <input type="text" name="edit_pass" placeholder="パスワード">
    <input type="submit" value="編集">
</form>
<?php
//表示
    //テーブルを選ぶ
    $sql = 'SELECT * FROM tbtest';
	$stmt = $pdo->query($sql);//sql文を実行して，データを取得
	$results = $stmt->fetchAll();//fetchAllで結果を全て配列で取得
	foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
		echo $row['id'].',';
		echo $row['name'].',';
		echo $row['comment'].'<br>';
	echo "<hr>";
	}
?>

</body>
</html>