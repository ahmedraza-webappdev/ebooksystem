<?php
include("../config/db.php");
$id = $_GET['id'];
$sql = "SELECT * FROM books WHERE id=$id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo htmlspecialchars($row['title']); ?> | Book-Astra</title>
<style>
.detail-page{max-width:1000px;margin:0 auto;padding:48px 30px;}
.back-link{display:inline-flex;align-items:center;gap:8px;color:rgba(255,255,255,0.38);text-decoration:none;font-size:0.78rem;background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.07);padding:8px 16px;border-radius:5px;transition:all 0.2s;margin-bottom:32px;}
.back-link:hover{color:#fff;border-color:rgba(255,255,255,0.18);}
.detail-wrap{display:grid;grid-template-columns:280px 1fr;gap:48px;align-items:start;}
.cover-col img{width:100%;border-radius:8px;box-shadow:0 24px 48px rgba(0,0,0,0.5);}
.info-col .tag{font-size:0.6rem;letter-spacing:0.2em;text-transform:uppercase;color:var(--gold);font-weight:700;margin-bottom:14px;display:block;}
.info-col h1{font-family:'Cormorant Garamond',serif;font-size:2.4rem;font-weight:700;color:#fff;line-height:1.15;margin-bottom:10px;}
.info-col .author{font-size:0.9rem;color:rgba(255,255,255,0.42);margin-bottom:24px;}
.info-col .desc{font-size:0.84rem;color:rgba(255,255,255,0.48);line-height:1.85;margin-bottom:28px;}
.price-row{display:flex;align-items:center;gap:20px;margin-bottom:28px;}
.price{font-family:'Cormorant Garamond',serif;font-size:2rem;font-weight:700;color:var(--gold);}
.price.free-price{color:#6abd7c;}
.btn-buy{display:inline-flex;align-items:center;gap:9px;background:var(--gold);color:#0d0d0d;font-size:0.78rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;padding:13px 28px;border-radius:6px;text-decoration:none;transition:background 0.2s;}
.btn-buy:hover{background:var(--gold-light);}
.btn-buy.free-btn{background:#4a7c59;color:#fff;}
.btn-buy.free-btn:hover{background:#3d6b4a;}
.meta-row{display:flex;gap:12px;flex-wrap:wrap;margin-top:16px;}
.meta-tag{background:#1c2333;border:1px solid rgba(255,255,255,0.07);border-radius:4px;padding:5px 12px;font-size:0.7rem;color:rgba(255,255,255,0.42);}
.meta-tag strong{color:rgba(255,255,255,0.7);}
@media(max-width:700px){.detail-wrap{grid-template-columns:1fr;}.cover-col img{max-width:220px;display:block;margin:0 auto;}}
</style>
</head>
<body>
<?php include("navbar.php"); ?>
<div class="detail-page">
  <a href="javascript:history.back()" class="back-link"><i class="fa-solid fa-arrow-left"></i> Go Back</a>
  <div class="detail-wrap">
    <div class="cover-col">
      <img src="../uploads/covers/<?php echo $row['book_image']; ?>" alt="<?php echo htmlspecialchars($row['title']); ?>" onerror="this.src='../assets/img/default-cover.jpg'">
    </div>
    <div class="info-col">
      <span class="tag">✦ Book Details</span>
      <h1><?php echo htmlspecialchars($row['title']); ?></h1>
      <div class="author">By <strong style="color:rgba(255,255,255,0.65);"><?php echo htmlspecialchars($row['author']); ?></strong></div>
      <p class="desc"><?php echo nl2br(htmlspecialchars($row['description'])); ?></p>
      <div class="price-row">
        <?php $isFree = ($row['price'] <= 0); ?>
        <div class="price <?php echo $isFree ? 'free-price' : ''; ?>">
          <?php echo $isFree ? 'Free' : 'Rs. '.number_format($row['price'], 0); ?>
        </div>
      </div>
      <a href="<?php echo $isFree ? 'view_book.php?id='.$row['id'] : 'order.php?book_id='.$row['id']; ?>" class="btn-buy <?php echo $isFree ? 'free-btn' : ''; ?>">
        <i class="fa-solid <?php echo $isFree ? 'fa-book-open' : 'fa-cart-shopping'; ?>"></i>
        <?php echo $isFree ? 'Read Free' : 'Buy Now'; ?>
      </a>
      <div class="meta-row">
        <span class="meta-tag"><strong>Format:</strong> <?php echo $isFree ? 'Free PDF' : 'PDF / Hard Copy'; ?></span>
        <span class="meta-tag"><strong>Author:</strong> <?php echo htmlspecialchars($row['author']); ?></span>
      </div>
    </div>
  </div>
</div>
<?php include("footer.php"); ?>
</body>
</html>
