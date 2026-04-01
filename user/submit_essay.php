<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['user_id'])) {
    die("Access Denied! Please login.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $competition_id = isset($_POST['comp_id']) ? mysqli_real_escape_string($conn, $_POST['comp_id']) : 1; 
    $essay_content = mysqli_real_escape_string($conn, $_POST['essay']);
    $filename = "essay_" . $user_id . "_" . time() . ".txt";
    $target_dir = "../uploads/essays/";
    if (!is_dir($target_dir)) { mkdir($target_dir, 0777, true); }
    $file_path = $target_dir . $filename;
    $original_essay_content = $_POST['essay'];

    if (file_put_contents($file_path, $original_essay_content)) {
        $query = "INSERT INTO submissions (user_id, competition_id, file, submitted_at) 
                  VALUES ('$user_id', '$competition_id', '$filename', NOW())";
        if (mysqli_query($conn, $query)) {
            // SUCCESS
            ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Essay Submitted | Book-Astra</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@700&family=DM+Sans:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
body{background:#0d0d0d;color:#f0ece4;font-family:'DM Sans',sans-serif;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:30px;}
.wrap{max-width:480px;width:100%;text-align:center;}
.check-icon{width:80px;height:80px;background:rgba(74,124,89,0.1);border:1px solid rgba(74,124,89,0.25);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 24px;font-size:2rem;color:#6abd7c;}
.wrap h1{font-family:'Cormorant Garamond',serif;font-size:2.4rem;font-weight:700;color:#fff;margin-bottom:10px;}
.wrap p{font-size:0.82rem;color:rgba(255,255,255,0.4);line-height:1.75;margin-bottom:28px;}
.file-info{background:#141920;border:1px solid rgba(255,255,255,0.07);border-radius:8px;padding:14px 18px;margin-bottom:24px;font-size:0.75rem;color:rgba(255,255,255,0.3);font-family:monospace;}
.btn-home{display:inline-flex;align-items:center;gap:8px;background:#c9a84c;color:#0d0d0d;font-size:0.78rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;padding:12px 28px;border-radius:6px;text-decoration:none;transition:background 0.2s;}
.btn-home:hover{background:#e8c96a;}
</style>
</head>
<body>
<div class="wrap">
  <div class="check-icon"><i class="fa-solid fa-check"></i></div>
  <h1>Submission Received!</h1>
  <p>Your essay has been successfully submitted for the competition. Good luck!</p>
  <div class="file-info">📄 <?php echo $filename; ?></div>
  <a href="index.php" class="btn-home"><i class="fa-solid fa-house"></i> Return to Home</a>
</div>
</body>
</html>
            <?php
        } else {
            echo "Database Error: " . mysqli_error($conn);
        }
    } else {
        echo "File Error: Could not save the essay file. Check folder permissions.";
    }
}
?>
