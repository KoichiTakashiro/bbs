<?php
  session_start();
  require('dbconnect.php');

  // htmlspecialcharsのショートカット
  function h($value) {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
  }

  function m($db, $value){
    return mysqli_real_escape_string($db, $value);
  }

  // 投稿送信時のバリデーション
  if (isset($_POST['name'])||isset($_POST['password'])||isset($_POST['message'])) {

    // 入力有無の確認
    if ($_POST["name"] == '') {
      $error["name"] = 'blank';
    }
    if ($_POST["password"] == '') {
      $error["password"] = 'blank';
    }
    if ($_POST["message"] == '') {
      $error["message"] = 'blank';
    }

    // 文字の長さを検証
    if (strlen($_POST["name"]) > 20) {
      $error["name"] = 'length';
    }
    if (strlen($_POST["password"]) < 4 && strlen($_POST["password"]) < 8) {
      $error["password"] = 'length';
    }
    if (strlen($_POST["message"]) > 400) {
      $error["message"] = 'length';
    }
  }

  if (empty($error)) {
    //投稿を記録する
    if (isset($_POST['message'])) {
      if ($_POST['message'] != '') {
        $sql = sprintf('INSERT INTO posts SET name="%s", password="%s", message="%s", created=NOW()',
                      mysqli_real_escape_string($db, $_POST['name']),
                      mysqli_real_escape_string($db, sha1($_POST['password'])),
                      mysqli_real_escape_string($db, $_POST['message'])
                      );

      }

        mysqli_query($db, $sql) or die(mysqli_error($db));

        header('Location: index.php');
        exit();
    }
  }

  //記事一覧情報の取得
  $sql = 'SELECT * FROM posts WHERE del_flg=0 ORDER BY created DESC' ;
  $posts = mysqli_query($db, $sql);

  //返信の場合
  if (isset($_REQUEST['res'])) {
    $sql = sprintf('SELECT * FROM  posts WHEREid=%d ORDER BY created DESC',
        mysqli_real_escape_string($db, $_REQUEST['res'])
      );
    $record = mysqli_query($db, $sql) or die(mysqli_error($db));
    $table = mysqli_fetch_assoc($record);
    $message = '@'.$table['name'].' '.$table['message'];
  }

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>投稿画面</title>
  <link rel="stylesheet" type="text/css" href="assets/css/main.css">
  <link rel="stylesheet" type="text/css" href="assets/bootstrap/css/bootstrap.min.css">
</head>
<body>
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <h1>記事を投稿する</h1>
        <div>
          <label for="">投稿のために必要な情報を入力してください</label><br>
        </div>

        <!-- 記事投稿フォーム -->
        <form action="" method="post">
          <div>
            <div>
              <!-- ニックネーム入力欄 -->
              <label for="">ニックネーム</label>
              <?php
                  if (isset($_POST["name"])) {
                      echo sprintf('<input type="text" name="name" value="%s" require>',
                      h($_POST["name"])
                      ) ;
                  } else {
                    echo '<input type="text" name="name" require>' ;
                    echo  '*20文字以内で使用してください';
                  }
              ?>

              <!-- ニックネームに対するエラー表示 -->
              <?php if (isset($error["name"])):?>
                <?php if ($error["name"] == 'blank' ): ?>
                  <p class="error">* ニックネームを入力してください。</p>
                <?php endif; ?>
              <?php endif; ?>
            </div>
            
            <div>
              <!-- パスワード入力欄 -->
              <label for="">パスワード</label>
              <?php
                  if (isset($_POST["password"])) {
                    echo sprintf('<input type="password" name="password" value="%s">',
                       h($_POST["password"])
                       );
                  } else {
                    echo '<input type="password" name="password">';
                    echo  '*4~8文字の英数字かつ大文字小文字を使用してください';
                  }
              ?>

              <!-- パスワードに対するエラー表示 -->
              <?php if (isset($error["password"])): ?>
                   <?php if ($error["password"] == 'blank'): ?>
                       <p class="error">* パスワードを入力してください</p>
                   <?php endif ;?>
                   <?php if ($error["password"] == 'length'): ?>
                       <p class="error">* パスワードは4~8文字の英数字かつ大文字小文字を使用してください</p>
                   <?php endif ;?>
               <?php endif; ?>
            </div>
            
            <div>
              <!-- 記事本文入力欄 -->
              <label for="">本文(400字以内)</label><br>
              <?php
                  if (isset($_POST["message"])) {
                      echo sprintf('<textarea name="message" cols="250" rows="10">%s</textarea>',
                      h($_POST["message"])
                      ) ;
                  } else {
                    echo '<textarea name="message" cols="250" rows="10"></textarea>' ;
                  }
              ?>
              <!-- コメントに対するエラー表示 -->
              <?php if (isset($error["message"])): ?>
                   <?php if ($error["message"] == 'blank'): ?>
                       <p class="error">* 本文を入力してください</p>
                   <?php endif ;?>
                   <?php if ($error["message"] == 'length'): ?>
                       <p class="error">* 本文は400文字以下で入力してください</p>
                   <?php endif ;?>
               <?php endif; ?>
            </div>
            <!-- 投稿ボタン設置 -->
            <div>
              <input type="submit" value="投稿する">
            </div>
          </div>
        </form>
      </div>
    </div><!-- 投稿部分のrowここまで -->   

    <div class="row">
      <div class="col-sm-12">
        <div>
          <h2>記事一覧</h2>
        </div>
        <!-- 記事一覧の表示 -->
        <?php while($post=mysqli_fetch_assoc($posts)) :?>
            <div>
              <a href="view.php?id=<?php echo $post["id"];?> "><?php echo h($post["message"])?></a>
              <a href="confirm.php?id=<?php echo $post["id"];?>">[編集]</a>
              <a href="delete.php?id=<?php echo $post["id"];?>">[削除]</a>
            </div>
        <?php endwhile; ?>
      </div>
    </div>
  </div>
</body>
</html>
