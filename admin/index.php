<?php
/**
 * 后台入口文件
 */
@session_start();
$_SESSION['adminlogin'] = 1;
header("Location: ../index.php?g=admin");