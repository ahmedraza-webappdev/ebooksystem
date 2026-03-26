<?php 
if (session_status() === PHP_SESSION_NONE) { session_start(); } 
include("../config/db.php"); 
include("navbar.php"); 
$image_path = "../uploads/covers/";
$filter = isset($_GET['filter']) ? mysqli_real_escape_string($conn, $_GET['filter']) : 'all';
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- <title>EBook Library | Home</title>  -->
<style>
html{scroll-behavior:smooth;}
.hero{position:relative;padding:110px 30px 90px;text-align:center;overflow:hidden;}
.hero::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 80% 60% at 50% 0%, rgba(201,168,76,0.08) 0%, transparent 70%),url('https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?auto=format&fit=crop&w=1400&q=60');background-size:cover;background-position:center;opacity:0.18;z-index:0;}
.hero::after{content:'';position:absolute;inset:0;background:linear-gradient(to bottom,rgba(13,13,13,0.5) 0%,#0d0d0d 100%);z-index:1;}
.hero .hc{position:relative;z-index:2;max-width:760px;margin:0 auto;}
.hero .eyebrow{font-size:0.65rem;letter-spacing:0.25em;text-transform:uppercase;color:var(--gold);font-weight:700;margin-bottom:20px;display:block;}
.hero h1{font-family:'Cormorant Garamond',serif;font-size:clamp(2.6rem,6vw,4.2rem);font-weight:700;color:#fff;line-height:1.12;margin-bottom:20px;}
.hero h1 em{color:var(--gold);font-style:normal;}
.hero p{color:rgba(255,255,255,0.45);font-size:1rem;max-width:520px;margin:0 auto 36px;line-height:1.7;}
.search-form{display:flex;max-width:520px;margin:0 auto;background:#1c2333;border:1px solid rgba(255,255,255,0.1);border-radius:8px;overflow:hidden;}
.search-form input{flex:1;background:none;border:none;padding:13px 18px;color:#fff;font-size:0.85rem;font-family:'DM Sans',sans-serif;outline:none;}
.search-form input::placeholder{color:rgba(255,255,255,0.28);}
.search-form button{background:var(--gold);border:none;padding:0 22px;color:#0d0d0d;cursor:pointer;font-weight:700;font-size:0.8rem;transition:background 0.2s;}
.search-form button:hover{background:var(--gold-light);}

.stats-row{max-width:1200px;margin:-1px auto 0;padding:0 30px;display:grid;grid-template-columns:repeat(3,1fr);gap:1px;background:var(--border);}
.stat-box{background:#141920;padding:24px 30px;display:flex;align-items:center;gap:16px;}
.stat-icon{width:42px;height:42px;background:rgba(201,168,76,0.08);border-radius:6px;display:flex;align-items:center;justify-content:center;color:var(--gold);font-size:1rem;flex-shrink:0;}
.stat-num{font-family:'Cormorant Garamond',serif;font-size:1.6rem;font-weight:700;color:#fff;line-height:1;}
.stat-lbl{font-size:0.7rem;color:var(--muted);margin-top:2px;letter-spacing:0.04em;}

section.sec{max-width:1200px;margin:0 auto;padding:64px 30px;}
.sec-head{display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:36px;flex-wrap:wrap;gap:16px;}
.sec-title{font-family:'Cormorant Garamond',serif;font-size:2rem;font-weight:700;color:#fff;line-height:1;}
.sec-sub{font-size:0.75rem;color:var(--muted);margin-top:5px;letter-spacing:0.04em;}

.winners-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;}
.winner-card{background:#141920;border:1px solid var(--border);border-radius:8px;padding:22px 20px;display:flex;align-items:flex-start;gap:14px;transition:border-color 0.2s;}
.winner-card:hover{border-color:rgba(201,168,76,0.3);}
.winner-crown{width:40px;height:40px;background:rgba(201,168,76,0.1);border-radius:6px;display:flex;align-items:center;justify-content:center;color:var(--gold);font-size:1rem;flex-shrink:0;}
.winner-name{font-weight:600;color:#fff;font-size:0.88rem;margin-bottom:2px;}
.winner-comp{font-size:0.72rem;color:var(--muted);}
.winner-prize{display:inline-block;margin-top:7px;font-size:0.66rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:var(--sage);background:rgba(74,124,89,0.1);border:1px solid rgba(74,124,89,0.2);padding:3px 9px;border-radius:3px;}

.filter-bar{display:flex;gap:6px;flex-wrap:wrap;}
.filter-bar a{font-size:0.72rem;font-weight:600;letter-spacing:0.06em;text-transform:uppercase;padding:7px 16px;border-radius:5px;text-decoration:none;transition:all 0.2s;border:1px solid var(--border);color:var(--muted);}
.filter-bar a.active{background:var(--gold);color:#0d0d0d;border-color:var(--gold);}
.filter-bar a:hover:not(.active){color:#fff;border-color:rgba(255,255,255,0.2);}

.books-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:20px;}
.book-card{background:#141920;border:1px solid var(--border);border-radius:8px;overflow:hidden;transition:all 0.25s;display:flex;flex-direction:column;}
.book-card:hover{border-color:rgba(201,168,76,0.25);transform:translateY(-4px);}
.book-cover{aspect-ratio:3/4;overflow:hidden;position:relative;background:#0d0d0d;}
.book-cover img{width:100%;height:100%;object-fit:cover;transition:transform 0.4s;}
.book-card:hover .book-cover img{transform:scale(1.05);}
.book-cover .badge-free{position:absolute;top:10px;right:10px;background:var(--sage);color:#fff;font-size:0.6rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;padding:4px 8px;border-radius:3px;}
.book-info{padding:14px;flex:1;display:flex;flex-direction:column;}
.book-title{font-size:0.83rem;font-weight:600;color:#fff;margin-bottom:3px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.book-author{font-size:0.72rem;color:var(--muted);margin-bottom:12px;}
.book-footer{display:flex;align-items:center;justify-content:space-between;margin-top:auto;}
.book-price{font-family:'Cormorant Garamond',serif;font-size:1rem;font-weight:700;color:#fff;}
.book-price.free{color:var(--sage);}
.btn-book{background:#1c2333;border:1px solid rgba(255,255,255,0.1);color:#fff;font-size:0.68rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;padding:6px 12px;border-radius:4px;text-decoration:none;transition:all 0.2s;}
.btn-book:hover{background:var(--gold);color:#0d0d0d;border-color:var(--gold);}
.btn-book.read{background:rgba(74,124,89,0.15);border-color:rgba(74,124,89,0.3);color:var(--sage);}
.btn-book.read:hover{background:var(--sage);color:#fff;}

.why-box{background:#141920;border:1px solid var(--border);border-radius:10px;padding:56px 60px;display:grid;grid-template-columns:1.2fr 1fr;gap:50px;align-items:center;}
.why-box h2{font-family:'Cormorant Garamond',serif;font-size:2.2rem;font-weight:700;color:#fff;margin-bottom:28px;}
.why-item{display:flex;gap:14px;margin-bottom:20px;}
.why-icon{width:34px;height:34px;background:rgba(201,168,76,0.1);border-radius:5px;display:flex;align-items:center;justify-content:center;color:var(--gold);font-size:0.8rem;flex-shrink:0;}
.why-text h6{font-size:0.85rem;font-weight:600;color:#fff;margin-bottom:3px;}
.why-text p{font-size:0.76rem;color:var(--muted);line-height:1.6;}
.why-img img{width:100%;height:320px;object-fit:cover;border-radius:8px;opacity:0.85;}

.testimonials{background:#0a0a0a;border-top:1px solid var(--border);border-bottom:1px solid var(--border);padding:64px 0;}
.testimonials .ti{max-width:1200px;margin:0 auto;padding:0 30px;}
.tgrid{display:grid;grid-template-columns:repeat(3,1fr);gap:20px;margin-top:36px;}
.tcard{background:#141920;border:1px solid var(--border);border-radius:8px;padding:26px;}
.tcard .stars{color:var(--gold);font-size:0.75rem;margin-bottom:14px;}
.tcard p{font-size:0.82rem;color:rgba(255,255,255,0.5);line-height:1.7;margin-bottom:16px;}
.tcard .author{font-size:0.76rem;font-weight:600;color:rgba(255,255,255,0.7);}

.empty-state{text-align:center;padding:60px 20px;color:var(--muted);}
.empty-state i{font-size:2.5rem;margin-bottom:16px;display:block;opacity:0.4;}
.empty-state h4{font-family:'Cormorant Garamond',serif;font-size:1.4rem;color:rgba(255,255,255,0.4);margin-bottom:12px;}

@media(max-width:900px){
  .books-grid{grid-template-columns:repeat(3,1fr);}
  .winners-grid{grid-template-columns:1fr 1fr;}
  .stats-row{grid-template-columns:1fr;}
  .why-box{grid-template-columns:1fr;padding:32px;}
  .why-img{display:none;}
}
@media(max-width:600px){
  .books-grid{grid-template-columns:repeat(2,1fr);}
  .winners-grid{grid-template-columns:1fr;}
  .tgrid{grid-template-columns:1fr;}
}
</style>
</head>
<body>

<!-- HERO -->
<section class="hero">
  <div class="hc">
    <span class="eyebrow">✦ Pakistan's Digital Library</span>
    <h1>Unlock a World of<br><em>Unlimited</em> Reading</h1>
    <p>Join thousands of readers and access your favourite eBooks anywhere, anytime — free and paid.</p>
    <form method="GET" action="index.php" class="search-form">
      <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search books, authors…">
      <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
    </form>
  </div>
</section>

<!-- STATS -->
<div class="stats-row">
  <div class="stat-box">
    <div class="stat-icon"><i class="fa-solid fa-book"></i></div>
    <div><div class="stat-num">12k+</div><div class="stat-lbl">Total EBooks</div></div>
  </div>
  <div class="stat-box">
    <div class="stat-icon"><i class="fa-solid fa-users"></i></div>
    <div><div class="stat-num">45k+</div><div class="stat-lbl">Happy Readers</div></div>
  </div>
  <div class="stat-box">
    <div class="stat-icon"><i class="fa-solid fa-star"></i></div>
    <div><div class="stat-num">4.9</div><div class="stat-lbl">Average Rating</div></div>
  </div>
</div>

<!-- HALL OF FAME -->
<section class="sec">
  <div class="sec-head">
    <div>
      <div class="sec-title">🏆 Hall of Fame</div>
      <div class="sec-sub">Our latest competition champions</div>
    </div>
    <a href="winners.php" style="font-size:0.72rem;color:var(--gold);text-decoration:none;letter-spacing:0.06em;text-transform:uppercase;font-weight:700;">View All →</a>
  </div>
  <div class="winners-grid">
    <?php 
    $winner_query = "SELECT c.title as comp_title, c.prize, u.name 
                     FROM competitions c 
                     JOIN users u ON c.winner_id = u.id 
                     WHERE c.winner_id IS NOT NULL 
                     ORDER BY c.id DESC LIMIT 3";
    $winner_res = mysqli_query($conn, $winner_query);
    if($winner_res && mysqli_num_rows($winner_res) > 0):
      while($w = mysqli_fetch_assoc($winner_res)): ?>
    <div class="winner-card">
      <div class="winner-crown"><i class="fa-solid fa-crown"></i></div>
      <div>
        <div class="winner-name"><?php echo htmlspecialchars($w['name']); ?></div>
        <div class="winner-comp"><?php echo htmlspecialchars($w['comp_title']); ?></div>
        <span class="winner-prize">Prize: <?php echo htmlspecialchars($w['prize']); ?></span>
      </div>
    </div>
    <?php endwhile; else: ?>
    <div class="empty-state" style="grid-column:1/-1;">
      <i class="fa-solid fa-trophy"></i>
      <h4>Winners will be announced soon!</h4>
    </div>
    <?php endif; ?>
  </div>
</section>

<!-- FEATURED BOOKS -->
<section class="sec" style="padding-top:0;" id="featured-section">
  <div class="sec-head">
    <div>
      <div class="sec-title">Featured Books</div>
      <div class="sec-sub">Hand-picked titles for every reader</div>
    </div>
    <div class="filter-bar">
      <a href="index.php?filter=all#featured-section" class="<?php echo ($filter=='all')?'active':''; ?>">All</a>
      <a href="index.php?filter=new#featured-section" class="<?php echo ($filter=='new')?'active':''; ?>">New</a>
      <a href="index.php?filter=free#featured-section" class="<?php echo ($filter=='free')?'active':''; ?>">Free</a>
      <a href="index.php?filter=paid#featured-section" class="<?php echo ($filter=='paid')?'active':''; ?>">Paid</a>
    </div>
  </div>
  <div class="books-grid">
    <?php
    $query = "SELECT * FROM books WHERE 1=1";
    if ($search) { $query .= " AND (title LIKE '%$search%' OR author LIKE '%$search%')"; }
    if ($filter == 'free') { $query .= " AND price <= 0"; } 
    elseif ($filter == 'paid') { $query .= " AND price > 0"; }
    $query .= ($filter == 'new') ? " ORDER BY id DESC LIMIT 10" : " ORDER BY id DESC";
    $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result) > 0):
      while($row = mysqli_fetch_assoc($result)):
        $isFree = ($row['price'] <= 0);
    ?>
    <div class="book-card">
      <div class="book-cover">
        <?php if($isFree): ?><span class="badge-free">Free</span><?php endif; ?>
        <img src="<?php echo $image_path . $row['book_image']; ?>" alt="<?php echo htmlspecialchars($row['title']); ?>" onerror="this.src='../assets/img/default-cover.jpg'">
      </div>
      <div class="book-info">
        <div class="book-title" title="<?php echo htmlspecialchars($row['title']); ?>"><?php echo htmlspecialchars($row['title']); ?></div>
        <div class="book-author">By <?php echo htmlspecialchars($row['author']); ?></div>
        <div class="book-footer">
          <div class="book-price <?php echo $isFree?'free':''; ?>"><?php echo $isFree ? 'Free' : 'Rs. '.$row['price']; ?></div>
          <a href="<?php echo $isFree ? 'view_book.php?id='.$row['id'] : 'order.php?book_id='.$row['id']; ?>" class="btn-book <?php echo $isFree?'read':''; ?>">
            <?php echo $isFree ? 'Read' : 'Buy'; ?>
          </a>
        </div>
      </div>
    </div>
    <?php endwhile; else: ?>
    <div class="empty-state" style="grid-column:1/-1;">
      <i class="fa-solid fa-magnifying-glass"></i>
      <h4>No books found.</h4>
      <a href="index.php" style="display:inline-block;margin-top:12px;padding:10px 24px;background:var(--gold);color:#0d0d0d;border-radius:5px;text-decoration:none;font-size:0.8rem;font-weight:700;">Reset Filters</a>
    </div>
    <?php endif; ?>
  </div>
</section>

<!-- WHY US -->
<section class="sec" style="padding-top:0;">
  <div class="why-box">
    <div>
      <h2>Why choose<br>E-Library?</h2>
      <div class="why-item">
        <div class="why-icon"><i class="fa-solid fa-bolt"></i></div>
        <div class="why-text"><h6>Instant Access</h6><p>Get your PDF right after purchase — no waiting, no delays.</p></div>
      </div>
      <div class="why-item">
        <div class="why-icon"><i class="fa-solid fa-mobile-screen"></i></div>
        <div class="why-text"><h6>Read Anywhere</h6><p>Works on mobile, tablet and desktop seamlessly.</p></div>
      </div>
      <div class="why-item">
        <div class="why-icon"><i class="fa-solid fa-trophy"></i></div>
        <div class="why-text"><h6>Essay Competitions</h6><p>Participate in live competitions and win exciting prizes.</p></div>
      </div>
    </div>
    <div class="why-img">
      <img src="https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&w=600&q=70" alt="Reading">
    </div>
  </div>
</section>

<!-- TESTIMONIALS -->
<div class="testimonials">
  <div class="ti">
    <div class="sec-head" style="margin-bottom:0;">
      <div>
        <div class="sec-title">What our readers say</div>
        <div class="sec-sub">Trusted by thousands across Pakistan</div>
      </div>
    </div>
    <div class="tgrid">
      <div class="tcard">
        <div class="stars">★★★★★</div>
        <p>"The best place to find niche tech books. Selection is incredible and delivery was fast."</p>
        <div class="author">— Rahul Verma</div>
      </div>
      <div class="tcard">
        <div class="stars">★★★★★</div>
        <p>"So easy to buy and read. Love the dark interface, very easy on the eyes at night."</p>
        <div class="author">— Priya Sharma</div>
      </div>
      <div class="tcard">
        <div class="stars">★★★★★</div>
        <p>"Free section is a lifesaver for students. Participated in a competition too, great experience!"</p>
        <div class="author">— Aman Gupta</div>
      </div>
    </div>
  </div>
</div>

<?php include("footer.php"); ?>
</body>
</html>
