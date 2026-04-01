<?php
include("../config/db.php");
$cat_name = isset($_GET['name']) ? mysqli_real_escape_string($conn, $_GET['name']) : '';
if(empty($cat_name)) {
    header("Location: index.php");
    exit();
}

$sql = "SELECT * FROM books WHERE category = '$cat_name' ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" type="image/png" href="ebook.png">
<title><?php echo htmlspecialchars($cat_name); ?> Books | Book-Astra</title>
<style>
.page-hero{background:#141920;border-bottom:1px solid rgba(255,255,255,0.07);padding:56px 30px;text-align:center;position:relative;overflow:hidden;}
.page-hero::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 60% 70% at 50% 0%,rgba(201,168,76,0.08) 0%,transparent 70%);pointer-events:none;}
.page-hero .eyebrow{font-size:0.62rem;letter-spacing:0.22em;text-transform:uppercase;color:var(--gold);font-weight:700;margin-bottom:14px;display:block;}
.page-hero h1{font-family:'Cormorant Garamond',serif;font-size:clamp(2rem,5vw,3rem);font-weight:700;color:#fff;}
.category-container{max-width:1200px;margin:0 auto;padding:52px 30px;}
.books-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:20px;}
.bcard{background:#141920;border:1px solid rgba(255,255,255,0.07);border-radius:8px;overflow:hidden;transition:all 0.25s;display:flex;flex-direction:column;}
.bcard:hover{border-color:rgba(201,168,76,0.35);transform:translateY(-4px);}
.bcover{aspect-ratio:3/4;overflow:hidden;background:#0d0d0d;position:relative;}
.bcover img{width:100%;height:100%;object-fit:cover;transition:transform 0.4s;}
.bcard:hover .bcover img{transform:scale(1.05);}
.free-badge{position:absolute;top:10px;right:10px;background:#4a7c59;color:#fff;font-size:0.6rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;padding:4px 8px;border-radius:3px;}
.binfo{padding:14px;flex:1;display:flex;flex-direction:column;}
.btitle{font-size:0.83rem;font-weight:600;color:#fff;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin-bottom:3px;}
.bauthor{font-size:0.72rem;color:rgba(255,255,255,0.38);margin-bottom:14px;}
.book-price{font-family:'Cormorant Garamond',serif;font-size:1rem;font-weight:700;color:#fff;margin-bottom:12px;}
.book-price.free{color:#6abd7c;}
.btn-read{display:block;text-align:center;background:rgba(201,168,76,0.12);border:1px solid rgba(201,168,76,0.25);color:var(--gold);font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;padding:9px;border-radius:5px;text-decoration:none;transition:all 0.2s;margin-top:auto;}
.btn-read:hover{background:var(--gold);color:#0d0d0d;border-color:var(--gold);}
.empty-state{text-align:center;padding:80px 20px;grid-column:1/-1;color:rgba(255,255,255,0.28);}
.empty-state i{font-size:2.5rem;display:block;margin-bottom:16px;opacity:0.25;}
.empty-state h4{font-family:'Cormorant Garamond',serif;font-size:1.5rem;color:rgba(255,255,255,0.3);}
@media(max-width:900px){.books-grid{grid-template-columns:repeat(3,1fr);}}
@media(max-width:600px){.books-grid{grid-template-columns:repeat(2,1fr);}}
</style>
</head>
<body>
<?php include("navbar.php"); ?>
<div class="page-hero">
  <span class="eyebrow">✦ Browse Category</span>
  <h1><?php echo htmlspecialchars($cat_name); ?></h1>
</div>
<div class="category-container">
  <div class="books-grid">
    <?php if(mysqli_num_rows($result) > 0): ?>
      <?php while($row = mysqli_fetch_assoc($result)): 
          $isFree = ($row['price'] <= 0);
          $isLoggedIn = isset($_SESSION['user_id']);
      ?>
      <div class="bcard">
        <div class="bcover">
          <?php if($isFree): ?><span class="free-badge">Free</span><?php endif; ?>
          <img src="../uploads/covers/<?php echo $row['book_image']; ?>" alt="<?php echo htmlspecialchars($row['title']); ?>" onerror="this.src='../assets/img/default-cover.jpg'">
        </div>
        <div class="binfo">
          <div class="btitle" title="<?php echo htmlspecialchars($row['title']); ?>"><?php echo htmlspecialchars($row['title']); ?></div>
          <div class="bauthor">By <?php echo htmlspecialchars($row['author']); ?></div>
          <div class="book-price <?php echo $isFree?'free':''; ?>"><?php echo $isFree ? 'Free' : 'Rs. '.$row['price']; ?></div>
          
          <a href="<?php echo $isLoggedIn ? ($isFree ? 'view_book.php?id='.$row['id'] : 'order.php?book_id='.$row['id']) : 'javascript:void(0)'; ?>" 
             class="btn-read"
             <?php if(!$isLoggedIn) echo 'onclick="showAuthModal(event, \''.($isFree ? 'Read' : 'Buy').'\')"'; ?>>
            <i class="fa-solid <?php echo $isFree ? 'fa-book-open' : 'fa-cart-shopping'; ?>" style="margin-right:5px;"></i>
            <?php echo $isFree ? 'Read' : 'Buy'; ?>
          </a>
        </div>
      </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="empty-state">
        <i class="fa-solid fa-folder-open"></i>
        <h4>No books found in this category.</h4>
        <a href="index.php" style="display:inline-block;margin-top:20px;color:var(--gold);text-decoration:none;font-weight:700;">← Back to Home</a>
      </div>
    <?php endif; ?>
  </div>
</div>
<?php include("footer.php"); ?>
</body>
</html>
