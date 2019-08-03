<html>
	<head>
		<meta charset="utf-8">
		<title>mission_5</title>
		<h1>夏休みに行くところor行きたいことを教えてください！</h1>
		パスワード: 1
	</head>
	<body>
			<?php
			//datebase接続
			$dsn = 'データベース名';
			$user = 'ユーザ名';
			$password = 'パスワード';
			$pdo = new PDO($dsn, $user, $password,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
			$sql = "CREATE TABLE IF NOT EXISTS mission_5"." ("
			. "id INT AUTO_INCREMENT PRIMARY KEY,"
			. "name char(32),"
			. "comment TEXT,"
			. "time DATETIME"
			.");";
			$stmt = $pdo->query($sql);
			
			//パスワードの設定
			$pass=1;
			//送信ボタン
			//編集boxが空＋パスワードの照合
			if(isset($_POST['sousin']) && empty($_POST['edit_num']) && !empty($_POST['passward'])&& $_POST['passward'] == $pass){
				if(!empty($_POST['name'])  && !empty($_POST['comment']) ){
				$sql = $pdo -> prepare("INSERT INTO mission_5 (name, comment, time) VALUES (:name, :comment, :time)");
				$sql -> bindParam(':name', $name, PDO::PARAM_STR);
				$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
				$sql -> bindParam(':time', $time, PDO::PARAM_STR);
				$name = $_POST['name'];
				$comment = $_POST['comment']; 
				$time =  date( "Y-m-d H:i:s" );
				$sql -> execute();
				}
			}
			
			//編集boxが空でない
			elseif(isset($_POST['sousin']) && !empty($_POST['edit_num']) && !empty($_POST['passward']) && $_POST['passward'] == $pass){
				$id = $_POST['edit_num'] ; //変更する投稿番号
				$name = $_POST['name'];
				$comment = $_POST['comment']; 
				$time = date( "Y-m-d H:i:s" );
				$sql = 'update mission_5 set name=:name,comment=:comment,time=:time where id=:id';
				$stmt = $pdo->prepare($sql);
				$stmt->bindParam(':name', $name, PDO::PARAM_STR);
				$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
				$stmt->bindParam(':time', $time, PDO::PARAM_STR);
				$stmt->bindParam(':id', $id, PDO::PARAM_INT);
				$stmt->execute();
			}
			//削除ボタン
			elseif(isset($_POST['sakujo']) && !empty($_POST['delete'])  && !empty($_POST['passward']) && $_POST['passward'] == $pass){
				$id = $_POST['delete'];
			    $sql = 'delete from mission_5 where id=:id';
        		$stmt = $pdo->prepare($sql);
	        	$stmt->bindParam(':id', $id, PDO::PARAM_INT); //パラメータに値を代入
	        	$stmt->execute();
	        	
	        	//最新投稿を削除したときに、idも消す(DBの最大id＝)
	        		//idの逆順でソートし、先頭を取り出す
	        	$sql='select * from mission_5 order by id desc limit 1';	        	 
	        		//$stmtにidのMAXの値を代入
	        	$stmt= $pdo->query($sql);
	        		//fetch(PDO::FETCH_ASSOC)で、すべての行を取り出し表示
	        	$result = $stmt->fetch(PDO::FETCH_ASSOC);	
	        	//auto_incrementの値を変更する。	(削除num=MAXのみ)
	        	$a=$result["id"];
	        	if($_POST['delete']===$a){
					$sql="ALTER TABLE mission_5 AUTO_INCREMENT = $a" ;
					$stmt = $pdo->query($sql);
				}
				//DBにデータがないとき
				if(empty($a)){
					$sql="ALTER TABLE mission_5 AUTO_INCREMENT = 1" ;
					$stmt = $pdo->query($sql);
				}
				
			}
			//編集ボタン
			elseif(isset($_POST['hensyuu']) && empty($_POST['edit']==FALSE) && !empty($_POST['passward']) && $_POST['passward'] == $pass){
					$sql = 'SELECT * FROM mission_5';
					$stmt = $pdo->query($sql);
					$results = $stmt->fetchAll();
					foreach ($results as $row){
						if($row['id'] === $_POST['edit']){
							$edit_num = $row['id'] ;
							$edit_name = $row['name'];
							$edit_comment = $row['comment'];
						}
					}
			}
			
			elseif(!empty($_POST['passward']) && $_POST['passward'] !== $pass){
				echo "Password Error";
			}
			elseif(isset($_POST['hensyuu']) || isset($_POST['sakujo']) || isset($_POST['sousin'])){
				if(empty($_POST['passward']))
				echo "Password Error";
			}
			
			?>
		
		<form action="#" method="post">
		<!-- 登録フォーム -->
		<!--入力ボタン(タグ="name" , 初期値："名前")-->
				<?php if(empty($edit_name)) : ?>
				<p><input type = "text" name="name" value="名前"><br>
				</p>
				<?php else : ?>
				<p><input type = "text" name="name" value="<?php echo $edit_name; ?>" ><br>
				</p>
				<?php endif ; ?>
				
				<p><!--入力ボタン(タグ="comment" , 初期値："コメント")-->
				<?php if(empty($edit_comment)) : ?>
				<p><input type = "text" name="comment" value="コメント"><br><br>
				</p>
				<?php else : ?>
				<p><input type = "text" name="comment" value="<?php echo $edit_comment; ?>"><br><br>
				</p>
				<?php endif ; ?> <!-- endifは" ; "！ --->
				
				<p><!--パスワードボタン(タグ="passward" ,初期値:"")-->
				(パスワード)
				<input type = "password" name="passward">
				<!--送信ボタン(初期値:"送信")-->
				<input type="submit" name="sousin" value="送信">
				
					<!--あれば、編集したい番号を表示(hidden)-->
					<p><input type = "hidden"  name="edit_num" value="<?php 
					if(!empty($edit_num)) echo $edit_num; 
					 ?>"></p>
				</p>
				削除対象番号
				<p><!--削除ボタン(タグ="delete" (削除対象番号) )-->
				<input type="text" name="delete" >
				<!--削除ボタン(初期値:"削除")-->
				<input type="submit" name="sakujo" value="削除"><br>
				<p/>
				編集対象番号
				<p><!--編集ボタン(タグ="edit" (削除対象番号) )-->
				<input type = "text" name="edit">
				<!--編集ボタン(初期値:"編集")-->
					<input type="submit" name="hensyuu" value="編集"><br><br>
				</p>
		</form>
		<?php
			//入力したデータをselectによって表示する
			$sql = 'SELECT * FROM mission_5';
			$stmt = $pdo->query($sql);
			$results = $stmt->fetchAll();
			foreach ($results as $row){
				//$rowの中にはテーブルのカラム名が入る
				echo $row['id'].'	';
				echo $row['name'].'		';
				echo $row['comment'].'	';
				echo date('Y/m/d H:i:s',strtotime($row['time'])).'<br/>';
					//	strtotime : yyyy-mm-dd hh:mm:ssといった時間をUnixタイムスタンプに変換する
					//	date : Unixタイムスタンプを整形する
			}
		?>
		</body>
</html>