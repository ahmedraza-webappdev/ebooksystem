<?php
session_start();
session_unset();
session_destroy();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- <title>Logged Out | E-Library</title> -->
<link rel="icon" type="image/png" href="ebook.png">
<title>Book-Astra | Your Premium Book Store</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;700&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
:root{--gold:#c9a84c;--gold-light:#e8c96a;--border:rgba(255,255,255,0.07);}
body{background:#0d0d0d;color:#f0ece4;font-family:'DM Sans',sans-serif;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:30px;}
.wrap{max-width:400px;width:100%;text-align:center;}
.icon-wrap{width:72px;height:72px;background:rgba(74,124,89,0.1);border:1px solid rgba(74,124,89,0.25);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 24px;font-size:1.8rem;}
.wrap h2{font-family:'Cormorant Garamond',serif;font-size:2rem;font-weight:700;color:#fff;margin-bottom:8px;}
.wrap p{font-size:0.8rem;color:rgba(255,255,255,0.38);margin-bottom:28px;line-height:1.7;}
.card{background:#141920;border:1px solid var(--border);border-radius:10px;padding:28px;}
.btn-login{display:block;background:var(--gold);color:#0d0d0d;font-weight:700;font-size:0.84rem;padding:13px;border-radius:7px;text-decoration:none;margin-bottom:10px;transition:background 0.2s;}
.btn-login:hover{background:var(--gold-light);}
.btn-home{display:block;background:rgba(255,255,255,0.04);border:1px solid var(--border);color:rgba(255,255,255,0.5);font-weight:600;font-size:0.84rem;padding:13px;border-radius:7px;text-decoration:none;transition:all 0.2s;}
.btn-home:hover{color:#fff;border-color:rgba(255,255,255,0.18);}
.progress-wrap{margin-top:20px;}
.progress-label{font-size:0.65rem;letter-spacing:0.1em;text-transform:uppercase;color:rgba(255,255,255,0.2);margin-bottom:8px;}
.progress-bar{height:2px;background:rgba(255,255,255,0.06);border-radius:1px;overflow:hidden;}
.progress-fill{height:100%;background:var(--gold);animation:shrink 5s linear forwards;}
@keyframes shrink{from{width:100%;}to{width:0%;}}
.time-stamp{margin-top:20px;font-size:0.65rem;color:rgba(255,255,255,0.15);letter-spacing:0.08em;}
</style>
</head>
<body>
<div class="wrap">
  <div class="icon-wrap"><i class="fa-solid fa-right-from-bracket"></i></div>
  <h2>Safe & Secure</h2>
  <p>You've been successfully logged out of your E-Library account.</p>
  <div class="card">
    <a href="login.php" class="btn-login"><i class="fa-solid fa-lock-open" style="margin-right:7px;"></i>Sign In Again</a>
    <a href="index.php" class="btn-home"><i class="fa-solid fa-house" style="margin-right:7px;opacity:0.5;"></i>Back to Home</a>
    <div class="progress-wrap">
      <div class="progress-label">Redirecting in 5s</div>
      <div class="progress-bar"><div class="progress-fill"></div></div>
    </div>
  </div>
  <div class="time-stamp">Session terminated · <?php echo date('H:i:s'); ?></div>
</div>
<script>setTimeout(function(){ window.location.href = 'login.php'; }, 5000);</script>
</body>
</html>
