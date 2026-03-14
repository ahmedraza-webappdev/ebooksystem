<?php
session_start();
include("../config/db.php");

$email    = mysqli_real_escape_string($conn, $_POST['email']);
$password = md5($_POST['password']); // md5 se match karega register ke saath

$sql    = "SELECT * FROM users WHERE email='$email' AND password='$password'";
$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) > 0){
    $row = mysqli_fetch_assoc($result);
    $_SESSION['user']      = $row['name'];   // dashboard.php ke liye
    $_SESSION['user_id']   = $row['id'];     // orders/books ke liye
    $_SESSION['user_name'] = $row['name'];   // navbar ke liye
    header("Location: index.php");
    exit();
} else {
    header("Location: login.php?error=1");
    exit();
}
?>
