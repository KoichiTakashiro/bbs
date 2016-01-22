<?php

function h($value) {
  return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function m($db, $value){
  return mysqli_real_escape_string($db, $value);
}

mb_regex_encoding( "UTF-8" );
function makeLink($value){
  return mb_ereg_replace("(https?)(://[[:alnum:]\+\$\;\?\.%,!#~*:@&+_-]+)", '<a href="\1\2">\1\2</a>', $value);
}

//ログイン判定
function isLoginSuccess(){
    if(isset($_SESSION['join']['id']) && $_SESSION['time']+3600 > time()){
      //ログインしている
        return true;
    }else{
      //ログインしていない
      return false;
  }
}

?>
