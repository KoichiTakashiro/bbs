<?php
    session_start();
    require('dbconnect.php');

    // htmlspecialcharsのショートカット
    function h($value) {
      return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    // このページにアクセスした際にログインしていなければindex.phpにリダイレクト
    if (empty($_REQUEST['id'])) {
        header('Location: index.php');
        exit();
    }

    //選択した投稿の情報取得
    $sql = sprintf('SELECT * FROM posts WHERE id=%d ',
      mysqli_real_escape_string($db, $_REQUEST['id'])
      );
    $posts = mysqli_query($db, $sql) or die(mysqli_error($db));
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>詳細ページ</title>
  <link rel="stylesheet" type="text/css" href="assets/css/main.css">
  <link rel="stylesheet" type="text/css" href="assets/bootstrap/css/bootstrap.min.css">
</head>
<body>
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <h1>記事詳細</h1>
        <p>&laquo;<a href="index.php">一覧に戻る</a></p>
        <?php if(isset($posts)) :?>
          <?php if ($post = mysqli_fetch_assoc($posts)): ?>
            <div class="msg">
              <p class="name">ニックネーム：<?php echo h($post['name']); ?></p>
              <p class="day">投稿日時：<?php echo h($post['created']); ?></p>
              <p>本文：<?php echo h($post['message']); ?></p>
            </div>
          <?php else: ?>
          <p>そのページは存在しないかURLが間違っています。</p>
          <?php endif; ?>
        <?php endif ;?>
      </div>
    </div>
  </div>

</body>
</html>
