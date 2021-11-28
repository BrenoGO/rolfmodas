<head>
  <meta charset="UTF-8"/>
  <title>Logging Out</title>
</head>
<?php
session_start();
session_destroy();
session_unset();
setcookie('id','',time()-3600*24);
echo "<script>alert('Você finalizou seu acesso');top.location.href='index.php';</script>";



?>
