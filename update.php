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

  // このページにアクセスした際にログインしていなければindex.phpにリダイレクト
  if (empty($_SESSION['update']['id'])) {
      header('Location: index.php');
      exit();
  }

  //編集する記事情報の取得
  $sql = sprintf('SELECT * FROM posts WHERE id=%d',
                 $_SESSION['update']['id'] 
                 );
  $posts = mysqli_query($db, $sql);
  $post = mysqli_fetch_assoc($posts);

  //
  if (isset($_POST['message'])) {
    if ($_POST["message"] == '') {
      $error["message"] = 'blank';
    }

    if (strlen($_POST["message"]) > 400) {
      $error["message"] = 'length';
    }   
  }

  if (empty($error)) {
    $sql = sprintf('UPDATE posts SET message="%s" WHERE id=%d',
                    mysqli_real_escape_string($db, $_POST["message"]),
                    mysqli_real_escape_string($db, $_SESSION['update']['id'])
                  );
    mysqli_query($db, $sql) or die(mysqli_error($db));
    unset($_SESSION['join']);
    header('Location: index.php');
    exit();
  }

?>

<?php 
  require('head.php');
?>
<body>
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <p>記事を編集してください</p>
        <!-- パスワード入力フォーム -->
        <div>
          <form action="" method="post">
            <!-- 記事本文入力欄 -->
            <label for="">本文を編集(400字以内)</label><br>
            <?php if (isset($error["message"])): ?>
                 <?php if ($error["message"] == 'blank'): ?>
                     <p class="error">* 本文を入力してください</p>
                 <?php endif ;?>
                 <?php if ($error["message"] == 'length'): ?>
                     <p class="error">* 本文は400文字以下で入力してください</p>
                 <?php endif ;?>
             <?php endif; ?>
            <?php
                if (isset($post["message"])) {
                    echo sprintf('<textarea name="message" cols="250" rows="10">%s</textarea>',
                                  h($post["message"])
                                  ) ;
                }
            ?>
            <input type="submit" value="編集完了">
          </form>
        </div>

        <div>
          <a href="index.php">Topへ戻る</a>
        </div>
      </div>
    </div>
  </div>
</body>
