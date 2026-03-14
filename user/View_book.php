<?php 
ob_start(); // 1. Start buffering to prevent header errors
if (session_status() === PHP_SESSION_NONE) { session_start(); } 

include("../config/db.php"); 

// 2. Perform ALL redirect checks BEFORE including the navbar
if(!isset($_SESSION['user_id'])){ 
    header("Location: login.php"); 
    exit(); 
}

$user_id = $_SESSION['user_id'];
$book_id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : 0;

$query = "SELECT books.* FROM books 
          LEFT JOIN orders ON books.id = orders.book_id AND orders.user_id = '$user_id'
          WHERE books.id = '$book_id' 
          AND (books.price <= 0 OR orders.id IS NOT NULL)";

$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) > 0){
    $book = mysqli_fetch_assoc($result);
    $pdf_name = $book['pdf_file']; 
    $pdf_file = "../uploads/pdf/" . $pdf_name; 
} else {
    // Note: This uses JS alert which is fine after headers, 
    // but the header() check above MUST come before the include.
    echo "<script>alert('Access Denied!'); window.location.href='index.php';</script>";
    exit();
}

// 3. NOW it is safe to include the navbar
include("navbar.php"); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reading | <?php echo htmlspecialchars($book['title']); ?></title>
<style>
/* ... (Your styles remain exactly the same) ... */
body{background:#0d0d0d;margin:0;padding:0;overflow:hidden;}
.reader-bar{background:#141920;border-bottom:1px solid rgba(255,255,255,0.07);height:58px;display:flex;align-items:center;justify-content:space-between;padding:0 20px;}
.reader-left{display:flex;align-items:center;gap:12px;}
.btn-back{display:inline-flex;align-items:center;gap:7px;background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);color:rgba(255,255,255,0.55);font-size:0.75rem;font-weight:600;font-family:'DM Sans',sans-serif;padding:7px 13px;border-radius:5px;text-decoration:none;transition:all 0.2s;}
.btn-back:hover{color:#fff;border-color:rgba(255,255,255,0.2);}
.reader-title{font-family:'Cormorant Garamond',serif;font-size:1.05rem;font-weight:700;color:#fff;}
.reader-right{display:flex;align-items:center;gap:10px;}
.auth-badge{background:rgba(74,124,89,0.1);border:1px solid rgba(74,124,89,0.2);color:#6abd7c;font-size:0.65rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;padding:5px 10px;border-radius:3px;}
.btn-dl{background:rgba(201,168,76,0.1);border:1px solid rgba(201,168,76,0.25);color:#c9a84c;font-size:0.72rem;font-weight:700;letter-spacing:0.06em;text-transform:uppercase;padding:7px 14px;border-radius:5px;text-decoration:none;transition:all 0.2s;}
.btn-dl:hover{background:rgba(201,168,76,0.18);}
.pdf-viewer{width:100%;height:calc(100vh - 58px);border:none;background:#1c2333;}
.error-state{height:calc(100vh - 58px);display:flex;align-items:center;justify-content:center;flex-direction:column;gap:14px;color:rgba(255,255,255,0.3);}
.error-state i{font-size:2.5rem;color:rgba(201,168,76,0.3);}
.error-state h3{font-family:'Cormorant Garamond',serif;font-size:1.5rem;color:rgba(255,255,255,0.4);}
.error-state a{display:inline-block;padding:10px 24px;background:var(--gold);color:#0d0d0d;border-radius:5px;text-decoration:none;font-size:0.78rem;font-weight:700;}
</style>
</head>
<body>
<div class="reader-bar">
  <div class="reader-left">
    <a href="books.php" class="btn-back"><i class="fa-solid fa-chevron-left"></i> Back</a>
    <div class="reader-title"><?php echo htmlspecialchars($book['title']); ?></div>
  </div>
  <div class="reader-right">
    <span class="auth-badge">✓ Authorized</span>
    <?php if(file_exists($pdf_file)): ?>
    <a href="<?php echo $pdf_file; ?>" download="<?php echo htmlspecialchars($book['title']); ?>.pdf" class="btn-dl"><i class="fa-solid fa-download" style="margin-right:5px;"></i>Download</a>
    <?php endif; ?>
  </div>
</div>
<?php if(!empty($pdf_name) && file_exists($pdf_file)): ?>
  <iframe src="<?php echo $pdf_file; ?>#toolbar=0" class="pdf-viewer" type="application/pdf"></iframe>
<?php else: ?>
  <div class="error-state">
    <i class="fa-solid fa-file-circle-exclamation"></i>
    <h3>File Not Found</h3>
    <a href="books.php">Back to Library</a>
  </div>
<?php endif; ?>
</body>
</html>