<?php
session_start();
session_destroy();
header('Content-Type:text/html;charset=utf-8');
echo "<script>alert('安全退出~');location.href='login.php'</script>";