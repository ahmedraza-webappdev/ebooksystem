<?php
session_start();
session_unset();
session_destroy();

// Redirect with a logout success message
header("Location: admin_login.php?logout=1");
exit();
?>