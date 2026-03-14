<?php
session_start();
if(!isset($_SESSION['admin'])){ header("Location: admin_login.php"); exit(); }

$file = basename($_GET['file']);
$path = "../uploads/essays/".$file;
if(!file_exists($path)) die("File not found.");
$content = file_get_contents($path);

$page_title = "Read Essay";
$page_subtitle = htmlspecialchars($file);
include("admin_header.php");
?>

<div style="max-width:800px;margin:0 auto;">
    <a href="view_submissions.php" class="btn-primary" style="display:inline-flex;margin-bottom:20px;"><i class="fa-solid fa-arrow-left mr-2"></i>Back to Submissions</a>

    <div style="background:white;border-radius:20px;padding:50px;box-shadow:0 4px 20px rgba(0,0,0,0.06);border:1px solid #f1f5f9;min-height:60vh;white-space:pre-wrap;font-size:1.1rem;line-height:1.85;font-family:Georgia,serif;color:#1e293b;">
        <?php echo htmlspecialchars($content); ?>
    </div>
</div>

<?php include("admin_footer.php"); ?>
