<?php
include("../config/db.php");
$id = (int)$_GET['id'];
mysqli_query($conn,"DELETE FROM books WHERE id=$id");
header("Location: books_list.php?msg=deleted");
exit();
?>
