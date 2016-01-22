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
  
  if (empty($_REQUEST['id'])) {
      header('Location: index.php');
      exit();
  }else{
      $id = $_REQUEST["id"];
  }
  // 投稿送信時のバリデーション
  if (isset($_POST['name'])||isset($_POST['password'])||isset($_POST['comment'])) {

    // 入力有無の確認
    if ($_POST["name"] == '') {
      $error["name"] = 'blank';
    }
    if ($_POST["password"] == '') {
      $error["password"] = 'blank';
    }
    

    // 文字の長さを検証
    if (strlen($_POST["name"]) > 20) {
      $error["name"] = 'length';
    }
    if (strlen($_POST["password"]) < 4 && strlen($_POST["password"]) < 8) {
      $error["password"] = 'length';
    }
    
  }

  if (empty($error)) {
    //投稿を記録する
    if (isset($_POST['comment'])) {
      if ($_POST['comment'] != '') {
        $sql = sprintf('INSERT INTO comments SET name="%s", password="%s", comment="%s",reply_post_id=%d ,created=NOW()',
                      mysqli_real_escape_string($db, $_POST['name']),
                      mysqli_real_escape_string($db, sha1($_POST['password'])),
                      mysqli_real_escape_string($db, $_POST['comment']),
                      mysqli_real_escape_string($db, $id)
                      );

      }

        mysqli_query($db, $sql) or die(mysqli_error($db));

        header('Location: index.php');
        exit();
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
        <p>コメントしてください</p>
        <!-- パスワード入力フォーム -->
        <div>
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
                <!-- コメント本文入力欄 -->
                <label for="">コメント</label><br>
                <?php
                    if (isset($_POST["comment"])) {
                        echo sprintf('<textarea name="comment" cols="250" rows="10">%s</textarea>',
                        h($_POST["comment"])
                        ) ;
                    } else {
                      echo '<textarea name="comment" cols="250" rows="10"></textarea>' ;
                    }
                ?>
                
              </div>
              <!-- 投稿ボタン設置 -->
              <input type="hidden" name="reply_post_id" value="<?php echo $id ;?>">
              <div>
                <input type="submit" value="投稿する">
              </div>
            </div>
          </form>
        </div>

        <div>
          <a href="index.php">Topへ戻る</a>
        </div>
      </div>
    </div>
  </div>
</body>
