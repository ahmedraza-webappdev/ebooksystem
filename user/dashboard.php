<?php
session_start();
if(!isset($_SESSION['user'])){ header("Location: login.php"); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard | Book-Astra</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
:root{--gold:#c9a84c;--gold-light:#e8c96a;--border:rgba(255,255,255,0.07);--muted:rgba(255,255,255,0.38);}
body{background:#0d0d0d;color:#f0ece4;font-family:'DM Sans',sans-serif;min-height:100vh;}
.top-bar{background:#141920;border-bottom:1px solid var(--border);padding:0 30px;height:62px;display:flex;align-items:center;justify-content:space-between;}
.brand{font-family:'Cormorant Garamond',serif;font-size:1.3rem;font-weight:700;color:#fff;}
.brand span{color:var(--gold);}
.top-right{display:flex;align-items:center;gap:16px;}
.welcome{font-size:0.8rem;color:var(--muted);}
.btn-logout{background:rgba(224,92,92,0.08);border:1px solid rgba(224,92,92,0.2);color:#e05c5c;font-size:0.72rem;font-weight:700;letter-spacing:0.06em;text-transform:uppercase;padding:7px 14px;border-radius:5px;text-decoration:none;transition:all 0.2s;}
.btn-logout:hover{background:rgba(224,92,92,0.15);}
.dash-wrap{max-width:900px;margin:0 auto;padding:56px 30px;}
.dash-wrap h2{font-family:'Cormorant Garamond',serif;font-size:2rem;font-weight:700;color:#fff;margin-bottom:6px;}
.dash-wrap .sub{font-size:0.8rem;color:var(--muted);margin-bottom:40px;}
.cards{display:grid;grid-template-columns:repeat(3,1fr);gap:18px;}
.dcard{background:#141920;border:1px solid var(--border);border-radius:10px;padding:28px;transition:all 0.25s;}
.dcard:hover{border-color:rgba(201,168,76,0.3);transform:translateY(-4px);}
.dcard-icon{width:48px;height:48px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;margin-bottom:18px;}
.dcard h3{font-size:0.95rem;font-weight:600;color:#fff;margin-bottom:6px;}
.dcard p{font-size:0.76rem;color:var(--muted);line-height:1.6;margin-bottom:18px;}
.dcard a{display:inline-flex;align-items:center;gap:6px;background:rgba(201,168,76,0.08);border:1px solid rgba(201,168,76,0.2);color:var(--gold);font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;padding:7px 14px;border-radius:4px;text-decoration:none;transition:all 0.2s;}
.dcard a:hover{background:rgba(201,168,76,0.15);}
@media(max-width:700px){.cards{grid-template-columns:1fr;}}
</style>
</head>
<body>
<div class="top-bar">
  <div class="brand"> <span>Book-Astra</span></div>
  <div class="top-right">
    <span class="welcome">Welcome, <?php echo htmlspecialchars($_SESSION['user']); ?></span>
    <a href="logout.php" class="btn-logout"><i class="fa-solid fa-right-from-bracket" style="margin-right:5px;"></i>Logout</a>
  </div>
</div>
<div class="dash-wrap">
  <h2>Your Dashboard</h2>
  <p class="sub">Access all your library features from here.</p>
  <div class="cards">
    <div class="dcard">
      <div class="dcard-icon" style="background:rgba(74,124,89,0.12);">📚</div>
      <h3>Free Books</h3>
      <p>Browse and read free study books available in our library.</p>
      <a href="free_books.php">Read Books →</a>
    </div>
    <div class="dcard">
      <div class="dcard-icon" style="background:rgba(201,168,76,0.1);">🏆</div>
      <h3>Essay Competition</h3>
      <p>Participate in live essay competitions and win prizes.</p>
      <a href="competition.php">Start Writing →</a>
    </div>
    <div class="dcard">
      <div class="dcard-icon" style="background:rgba(99,102,241,0.12);">👤</div>
      <h3>My Profile</h3>
      <p>View and manage your personal account settings.</p>
      <a href="#">View Profile →</a>
    </div>
  </div>
</div>
</body>
</html>
