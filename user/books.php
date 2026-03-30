<?php 
if (session_status() === PHP_SESSION_NONE) { session_start(); } 
include("../config/db.php"); 
include("navbar.php"); 
if(!isset($_SESSION['user_id'])){ header("Location: login.php"); exit(); }
$user_id = $_SESSION['user_id'];
$search = "";
$image_path = "../uploads/covers/";
if(isset($_GET['search'])){
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $sql = "SELECT books.* FROM books 
            INNER JOIN orders ON books.id = orders.book_id 
            WHERE orders.user_id = '$user_id' 
            AND orders.order_status = 'Delivered' 
            AND (books.title LIKE '%$search%' OR books.author LIKE '%$search%')
            GROUP BY books.id";
} else {
    $sql = "SELECT books.* FROM books 
            INNER JOIN orders ON books.id = orders.book_id 
            WHERE orders.user_id = '$user_id'
            AND orders.order_status = 'Delivered' 
            GROUP BY books.id 
            ORDER BY orders.id DESC";
}
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Library | Book-Astra</title>
<style>
.page-hero{background:#141920;border-bottom:1px solid rgba(255,255,255,0.07);padding:52px 30px;text-align:center;}
.page-hero h1{font-family:'Cormorant Garamond',serif;font-size:2.4rem;font-weight:700;color:#fff;margin-bottom:8px;}
.page-hero p{font-size:0.82rem;color:rgba(255,255,255,0.38);}
.search-wrap{margin-top:24px;display:flex;max-width:420px;margin-left:auto;margin-right:auto;background:#1c2333;border:1px solid rgba(255,255,255,0.08);border-radius:7px;overflow:hidden;}
.search-wrap input{flex:1;background:none;border:none;padding:11px 16px;color:#fff;font-size:0.83rem;font-family:'DM Sans',sans-serif;outline:none;}
.search-wrap input::placeholder{color:rgba(255,255,255,0.22);}
.search-wrap button{background:var(--gold);border:none;padding:0 18px;color:#0d0d0d;cursor:pointer;font-weight:700;font-size:0.8rem;transition:background 0.2s;}
.search-wrap button:hover{background:var(--gold-light);}
.lib-container{max-width:1200px;margin:0 auto;padding:48px 30px;}
.books-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:20px;}
.bcard{background:#141920;border:1px solid rgba(255,255,255,0.07);border-radius:8px;overflow:hidden;transition:all 0.25s;display:flex;flex-direction:column;}
.bcard:hover{border-color:rgba(201,168,76,0.25);transform:translateY(-4px);}
.bcover{aspect-ratio:3/4;overflow:hidden;background:#0d0d0d;}
.bcover img{width:100%;height:100%;object-fit:cover;transition:transform 0.4s;}
.bcard:hover .bcover img{transform:scale(1.05);}
.binfo{padding:14px;}
.btitle{font-size:0.83rem;font-weight:600;color:#fff;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin-bottom:3px;}
.bauthor{font-size:0.72rem;color:rgba(255,255,255,0.38);margin-bottom:14px;}
.btn-read{display:block;text-align:center;background:rgba(74,124,89,0.12);border:1px solid rgba(74,124,89,0.25);color:#6abd7c;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;padding:8px;border-radius:5px;text-decoration:none;transition:all 0.2s;}
.btn-read:hover{background:#4a7c59;color:#fff;border-color:#4a7c59;}
.empty-state{text-align:center;padding:80px 20px;color:rgba(255,255,255,0.28);}
.empty-state i{font-size:3rem;display:block;margin-bottom:18px;opacity:0.25;}
.empty-state h4{font-family:'Cormorant Garamond',serif;font-size:1.5rem;color:rgba(255,255,255,0.35);margin-bottom:14px;}
.empty-state a{display:inline-block;padding:10px 24px;background:var(--gold);color:#0d0d0d;border-radius:5px;text-decoration:none;font-size:0.78rem;font-weight:700;margin-top:4px;}
@media(max-width:900px){.books-grid{grid-template-columns:repeat(3,1fr);}}
@media(max-width:600px){.books-grid{grid-template-columns:repeat(2,1fr);}}
</style>
</head>
<body>
<div class="page-hero">
  <h1>My Digital Library</h1>
  <p>Your personal collection of purchased books</p>
  <div class="search-wrap" style="margin-top:20px;">
    <form method="GET" style="display:flex;width:100%;">
      <input type="text" name="search" placeholder="Search title or author…" value="<?php echo htmlspecialchars($search); ?>">
      <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
    </form>
  </div>
</div>
<div class="lib-container">
  <div class="books-grid">
    <?php 
    if(mysqli_num_rows($result) > 0):
      while($row = mysqli_fetch_assoc($result)): ?>
    <div class="bcard">
      <div class="bcover">
        <img src="<?php echo $image_path . $row['book_image']; ?>" alt="Cover" onerror="this.src='../assets/img/default-cover.jpg'">
      </div>
      <div class="binfo">
        <div class="btitle" title="<?php echo htmlspecialchars($row['title']); ?>"><?php echo htmlspecialchars($row['title']); ?></div>
        <div class="bauthor">By <?php echo htmlspecialchars($row['author']); ?></div>
        <a href="view_book.php?id=<?php echo $row['id']; ?>" class="btn-read"><i class="fa-solid fa-book-open" style="margin-right:5px;"></i>Read Now</a>
      </div>
    </div>
    <?php endwhile; else: ?>
    <div class="empty-state" style="grid-column:1/-1;">
      <i class="fa-solid fa-book-bookmark"></i>
      <h4>No books in your library yet.</h4>
      <a href="index.php">Explore Store</a>
    </div>
    <?php endif; ?>
  </div>
</div>
<?php include("footer.php"); ?>
</body>
</html>
