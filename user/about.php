<?php
session_start();
include("../config/db.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>About Us | Book-Astra</title>
<link rel="icon" type="image/x-icon" href="file.svg">
<style>
.about-hero{background:#141920;border-bottom:1px solid rgba(255,255,255,0.07);padding:72px 30px;text-align:center;position:relative;overflow:hidden;}
.about-hero::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 70% 80% at 50% 0%,rgba(201,168,76,0.07) 0%,transparent 70%);pointer-events:none;}
.about-hero .eyebrow{font-size:0.62rem;letter-spacing:0.22em;text-transform:uppercase;color:var(--gold);font-weight:700;margin-bottom:14px;display:block;}
.about-hero h1{font-family:'Cormorant Garamond',serif;font-size:clamp(2.2rem,5vw,3.4rem);font-weight:700;color:#fff;margin-bottom:14px;}
.about-hero p{font-size:0.88rem;color:rgba(255,255,255,0.4);max-width:520px;margin:0 auto;line-height:1.75;}

.about-sec{max-width:1200px;margin:0 auto;padding:64px 30px;}

/* STORY */
.story-grid{display:grid;grid-template-columns:1fr 1fr;gap:60px;align-items:center;margin-bottom:72px;}
.story-text h2{font-family:'Cormorant Garamond',serif;font-size:2rem;font-weight:700;color:#fff;margin-bottom:18px;}
.story-text p{font-size:0.83rem;color:rgba(255,255,255,0.45);line-height:1.85;margin-bottom:14px;}
.story-text p:last-child{margin-bottom:0;}
.story-img{position:relative;}
.story-img img{width:100%;height:340px;object-fit:cover;border-radius:10px;opacity:0.75;}
.story-img .img-badge{position:absolute;bottom:-16px;left:-16px;background:#141920;border:1px solid rgba(255,255,255,0.07);border-radius:8px;padding:14px 18px;text-align:center;}
.story-img .img-badge .num{font-family:'Cormorant Garamond',serif;font-size:1.8rem;font-weight:700;color:var(--gold);line-height:1;}
.story-img .img-badge .lbl{font-size:0.65rem;color:rgba(255,255,255,0.35);margin-top:2px;letter-spacing:0.06em;text-transform:uppercase;}

/* STATS */
.stats-sec{background:#141920;border:1px solid rgba(255,255,255,0.07);border-radius:10px;padding:40px;margin-bottom:72px;}
.stats-sec h2{font-family:'Cormorant Garamond',serif;font-size:1.6rem;font-weight:700;color:#fff;margin-bottom:28px;text-align:center;}
.stats-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:2px;background:rgba(255,255,255,0.05);border-radius:6px;overflow:hidden;}
.sbox{background:#141920;padding:24px;text-align:center;}
.sbox i{font-size:1.4rem;color:var(--gold);margin-bottom:10px;display:block;}
.sbox .snum{font-family:'Cormorant Garamond',serif;font-size:2rem;font-weight:700;color:#fff;line-height:1;}
.sbox .slbl{font-size:0.7rem;color:rgba(255,255,255,0.35);margin-top:4px;letter-spacing:0.05em;}

/* FEATURES */
.features-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:18px;margin-bottom:72px;}
.fbox{background:#141920;border:1px solid rgba(255,255,255,0.07);border-radius:8px;padding:26px;transition:all 0.25s;}
.fbox:hover{border-color:rgba(201,168,76,0.25);transform:translateY(-3px);}
.fbox-icon{width:44px;height:44px;border-radius:7px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;margin-bottom:16px;}
.fbox h5{font-size:0.9rem;font-weight:600;color:#fff;margin-bottom:7px;}
.fbox p{font-size:0.76rem;color:rgba(255,255,255,0.38);line-height:1.65;}

/* MISSION */
.mission-box{background:linear-gradient(135deg,#141920 0%,#1c2333 100%);border:1px solid rgba(255,255,255,0.07);border-radius:10px;padding:50px;text-align:center;margin-bottom:72px;position:relative;overflow:hidden;}
.mission-box::before{content:'✦';position:absolute;font-size:8rem;color:rgba(201,168,76,0.04);top:50%;left:50%;transform:translate(-50%,-50%);pointer-events:none;}
.mission-box .eyebrow{font-size:0.6rem;letter-spacing:0.22em;text-transform:uppercase;color:var(--gold);font-weight:700;margin-bottom:14px;display:block;}
.mission-box h2{font-family:'Cormorant Garamond',serif;font-size:2rem;font-weight:700;color:#fff;margin-bottom:18px;}
.mission-box p{font-size:0.85rem;color:rgba(255,255,255,0.42);max-width:580px;margin:0 auto 28px;line-height:1.8;}
.cta-btns{display:flex;gap:12px;justify-content:center;flex-wrap:wrap;}
.btn-primary-gold{background:var(--gold);color:#0d0d0d;font-size:0.75rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;padding:12px 26px;border-radius:5px;text-decoration:none;transition:background 0.2s;}
.btn-primary-gold:hover{background:var(--gold-light);}
.btn-outline{background:transparent;border:1px solid rgba(255,255,255,0.12);color:rgba(255,255,255,0.55);font-size:0.75rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;padding:12px 26px;border-radius:5px;text-decoration:none;transition:all 0.2s;}
.btn-outline:hover{border-color:rgba(255,255,255,0.3);color:#fff;}

/* SECTION DIVIDER */
.sdiv{border:none;border-top:1px solid rgba(255,255,255,0.06);margin:0 0 64px;}

@media(max-width:900px){
  .story-grid{grid-template-columns:1fr;gap:36px;}
  .story-img{display:none;}
  .features-grid{grid-template-columns:1fr 1fr;}
  .stats-grid{grid-template-columns:repeat(2,1fr);}
}
@media(max-width:600px){
  .features-grid{grid-template-columns:1fr;}
  .stats-grid{grid-template-columns:1fr 1fr;}
}
</style>
</head>
<body>

<?php include("navbar.php"); ?>

<!-- HERO -->
<div class="about-hero">
  <span class="eyebrow">✦ Our Story</span>
  <h1>About Book-Astra</h1>
  <p>Your trusted partner in the world of books, knowledge, and literary excellence in Pakistan.</p>
</div>

<div class="about-sec">

  <!-- STORY -->
  <div class="story-grid">
    <div class="story-text">
      <h2>Our Story</h2>
      <p>Book-Astra is a leading online publishing platform dedicated to connecting readers with quality books across all genres. From novels to academic journals, we bring the world of literature right to your fingertips.</p>
      <p>Founded with a deep passion for reading and learning, we have grown to serve thousands of readers across Pakistan and around the globe. Our platform offers books in multiple formats — PDF and Hard Copy — to suit every reader's preference.</p>
      <p>We also run exciting essay competitions that give talented writers a platform to showcase their skills and win meaningful prizes — nurturing the next generation of Pakistani authors.</p>
    </div>
    <div class="story-img">
      <img src="https://images.unsplash.com/photo-1481627834876-b7833e8f5570?auto=format&fit=crop&w=700&q=70" alt="Library">
      <div class="img-badge">
        <div class="num">2020</div>
        <div class="lbl">Founded</div>
      </div>
    </div>
  </div>

  <!-- LIVE STATS FROM DB -->
  <div class="stats-sec">
    <h2>E-Library by the Numbers</h2>
    <div class="stats-grid">
      <?php
      $books_count  = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM books"))[0] ?? '0';
      $users_count  = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM users"))[0] ?? '0';
      $comp_count   = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM competitions"))[0] ?? '0';
      $orders_count = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM orders"))[0] ?? '0';
      ?>
      <div class="sbox">
        <i class="fa-solid fa-book"></i>
        <div class="snum"><?php echo $books_count; ?>+</div>
        <div class="slbl">Books Available</div>
      </div>
      <div class="sbox">
        <i class="fa-solid fa-users"></i>
        <div class="snum"><?php echo $users_count; ?>+</div>
        <div class="slbl">Registered Readers</div>
      </div>
      <div class="sbox">
        <i class="fa-solid fa-trophy"></i>
        <div class="snum"><?php echo $comp_count; ?>+</div>
        <div class="slbl">Competitions Held</div>
      </div>
      <div class="sbox">
        <i class="fa-solid fa-bag-shopping"></i>
        <div class="snum"><?php echo $orders_count; ?>+</div>
        <div class="slbl">Orders Placed</div>
      </div>
    </div>
  </div>

  <hr class="sdiv">

  <!-- FEATURES -->
  <div class="features-grid">
    <div class="fbox">
      <div class="fbox-icon" style="background:rgba(201,168,76,0.1);">🏆</div>
      <h5>Quality Content</h5>
      <p>Every book on our platform is carefully curated for quality and authenticity.</p>
    </div>
    <div class="fbox">
      <div class="fbox-icon" style="background:rgba(74,124,89,0.12);">🔒</div>
      <h5>Secure Payments</h5>
      <p>Multiple payment options with secure transaction processing and verification.</p>
    </div>
    <div class="fbox">
      <div class="fbox-icon" style="background:rgba(99,102,241,0.12);">🚚</div>
      <h5>Fast Delivery</h5>
      <p>Quick delivery to your doorstep across Pakistan and internationally.</p>
    </div>
    <div class="fbox">
      <div class="fbox-icon" style="background:rgba(224,92,92,0.1);">💬</div>
      <h5>24/7 Support</h5>
      <p>Our support team is available around the clock to assist you.</p>
    </div>
  </div>

  <!-- MISSION / CTA -->
  <div class="mission-box">
    <span class="eyebrow">✦ Join Us</span>
    <h2>Become Part of Our Story</h2>
    <p>Whether you want to explore thousands of books, participate in competitions, or simply grow your knowledge — E-Library is the place for you.</p>
    <div class="cta-btns">
      <?php if(!isset($_SESSION['user_id'])): ?>
        <a href="register.php" class="btn-primary-gold">Create Free Account</a>
        <a href="login.php" class="btn-outline">Sign In</a>
      <?php else: ?>
        <a href="index.php" class="btn-primary-gold">Browse Books</a>
        <a href="competition.php" class="btn-outline">Join Competition</a>
      <?php endif; ?>
    </div>
  </div>

</div>

<?php include("footer.php"); ?>
</body>
</html>
