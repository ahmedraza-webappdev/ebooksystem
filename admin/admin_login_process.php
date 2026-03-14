<?php
session_start();
$username = $_POST['username'];
$password = $_POST['password'];
if($username=="admin" && $password=="12345"){
    $_SESSION['admin'] = $username;
    header("Location: dashboard.php");
} else {
    header("Location: admin_login.php?error=1");
}
exit();
?>
