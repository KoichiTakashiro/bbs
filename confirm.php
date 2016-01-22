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

  //confirm.php以外から来た場合にはリダイレクト
  if (empty($_REQUEST['id'])) {
      header('Location: index.php');
      exit();
  }else{
      $_SESSION['update']['id'] = $_REQUEST['id'];
  }

  //選択した投稿のパスワード情報取得
  $sql = sprintf('SELECT password FROM posts WHERE id=%d ',
                  mysqli_real_escape_string($db, $_REQUEST['id'])
                );
  $recordSet = mysqli_query($db, $sql) or die(mysqli_error($db));
  $data = mysqli_fetch_assoc($recordSet);


  
  if (isset($_POST["password"])) {

      //入力有無の確認
      if ($_POST["password"] == '') {
        $error = 'blank';
      }

      //入力したパスワードと登録済みパスワードが一致するか確認
      if ($data["password"] == sha1($_POST["password"])) {
          header('Location: update.php');
          exit();
      }else{
        $error = "wrong";
      }
  }





?>

<?php 
  require('head.php');
?>
<body>
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <p>投稿時に入力したパスワードを入力してください。</p>
        <!-- パスワード入力フォーム -->
        <div>
          <form action="" method="post">
            <?php if (isset($error)) :?>
                <?php if($error == 'blank'): ?>
                  <p>パスワードを入力してください</p>
                <?php endif; ?>
                <?php if($error == "wrong"): ?>
                  <p>パスワードが間違っています</p>
                <?php endif; ?>
            <?php endif ;?>
            <label for="">パスワード：</label>
            <input type="password" name="password" >
            <input type="submit" value="編集へ進む">
          </form>
        </div>

        <div>
          <a href="index.php">Topへ戻る</a>
        </div>
      </div>
    </div>
  </div>
</body>
