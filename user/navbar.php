<?php 
if (session_status() === PHP_SESSION_NONE) { session_start(); } 
?>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="icon" type="image/png" href="ebook.png">
<title>Book-Astra | Your Premium Book Store</title>
<style>
:root {
    --gold: #c9a84c; --gold-light: #dfc17b;
    --bg-dark: #0d0d0d; --surface: #141920; --surface-2: #1c2333;
    --border: rgba(255,255,255,0.08); --muted: rgba(255,255,255,0.5);
}
body { margin: 0; font-family: 'DM Sans', sans-serif; background: var(--bg-dark); color: #fff; }
.e-nav { background: rgba(13,13,13,0.95); backdrop-filter: blur(10px); border-bottom: 1px solid var(--border); position: sticky; top: 0; z-index: 1000; height: 70px; }
.nav-inner { max-width: 1200px; margin: 0 auto; padding: 0 30px; display: flex; align-items: center; justify-content: space-between; height: 100%; }
.brand { font-family: 'Cormorant Garamond', serif; font-size: 1.6rem; font-weight: 700; color: #fff; text-decoration: none; }
.brand span { color: var(--gold); }
.nav-links { display: flex; align-items: center; gap: 20px; }
.nav-links a { color: var(--muted); text-decoration: none; font-size: 0.82rem; font-weight: 500; letter-spacing: 0.05em; text-transform: uppercase; transition: 0.3s; }
.nav-links a:hover { color: #fff; }
.nav-right { display: flex; align-items: center; gap: 15px; }
.btn-join { background: var(--gold); color: #0d0d0d; padding: 8px 20px; border-radius: 4px; text-decoration: none; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; }
.login-link { color: var(--muted); text-decoration: none; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; }
.user-dropdown { position: relative; }
.user-btn { background: var(--surface-2); border: 1px solid var(--border); color: #fff; padding: 8px 15px; border-radius: 5px; cursor: pointer; font-size: 0.8rem; display: flex; align-items: center; gap: 8px; }
.dropdown-menu { position: absolute; top: calc(100% + 10px); right: 0; background: var(--surface-2); border: 1px solid var(--border); border-radius: 8px; min-width: 180px; display: none; box-shadow: 0 10px 30px rgba(0,0,0,0.5); overflow: hidden; }
.dropdown-menu.active { display: block; }
.dropdown-menu a { display: block; padding: 12px 15px; color: rgba(255,255,255,0.8); text-decoration: none; font-size: 0.85rem; border-bottom: 1px solid var(--border); }
.dropdown-menu a:hover { background: rgba(255,255,255,0.05); color: var(--gold); }
@media(max-width: 900px) { .nav-links { display: none; } }
</style>

<nav class="e-nav">
  <div class="nav-inner">
    <a href="index.php" class="brand" style="display:flex;align-items:center;">
      <img src="file.svg" alt="E-Library" style="height:50px;width:auto;display:block;">
    </a>
    <div class="nav-links">
      <a href="index.php">Home</a>
      <a href="index.php?filter=free">Free Books</a>
      <a href="competition.php">Competitions</a>
      <a href="winners.php">Winners</a>
      <a href="about.php">About Us</a>
    </div>
    <div class="nav-right">
      <?php if(isset($_SESSION['user_id'])): ?>
        <div class="user-dropdown">
          <button class="user-btn" id="myDropdownBtn">
            <i class="fa-solid fa-circle-user" style="color:var(--gold);"></i>
            Hi, <?php echo htmlspecialchars(explode(' ', $_SESSION['user_name'])[0]); ?>
            <i class="fa-solid fa-chevron-down" style="font-size:0.6rem;opacity:0.5;"></i>
          </button>
          <div class="dropdown-menu" id="myDropdownMenu">
            <a href="user_dashboard.php"><i class="fa-solid fa-gauge-high"></i> Dashboard</a>
            <a href="books.php"><i class="fa-solid fa-book"></i> My Books</a>
            <a href="logout.php" style="color:#e05c5c;border-bottom:none;"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
          </div>
        </div>
      <?php else: ?>
        <a href="login.php" class="login-link">Login</a>
        <a href="register.php" class="btn-join">Join Now</a>
      <?php endif; ?>
    </div>
  </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const btn = document.getElementById('myDropdownBtn');
    const menu = document.getElementById('myDropdownMenu');
    if(btn && menu) {
        btn.onclick = (e) => { e.stopPropagation(); menu.classList.toggle('active'); };
        document.onclick = () => menu.classList.remove('active');
    }
});
</script>