<?php
session_start();
if(!isset($_SESSION['admin'])){ header("Location: admin_login.php"); exit(); }
include("../config/db.php");
$page_title = "View Books";
$page_subtitle = "Browse your entire book collection";
include("admin_header.php");
$result = mysqli_query($conn,"SELECT * FROM books ORDER BY id DESC");
$i = 0;
?>

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;">
    <p style="color:#4a5568;font-size:0.82rem;"><?php echo mysqli_num_rows($result); ?> books in collection</p>
    <a href="upload_book.php" class="btn-primary"><i class="fa-solid fa-plus"></i> Add New Book</a>
</div>

<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;">
<?php if(mysqli_num_rows($result)>0): ?>
<?php while($row=mysqli_fetch_assoc($result)): ?>
    <div style="background:#141920;border:1px solid #1e2530;border-radius:10px;overflow:hidden;transition:all 0.25s;" onmouseover="this.style.borderColor='rgba(201,168,76,0.25)';this.style.transform='translateY(-4px)'" onmouseout="this.style.borderColor='#1e2530';this.style.transform='translateY(0)'">
        <div style="height:200px;overflow:hidden;position:relative;background:#0d0d0d;display:flex;align-items:center;justify-content:center;">
            <img src="../uploads/covers/<?php echo $row['book_image']; ?>" alt="cover" style="max-width:100%;max-height:100%;width:auto;height:100%;object-fit:contain;object-position:center;transition:transform 0.4s;" onmouseover="this.style.transform='scale(1.06)'" onmouseout="this.style.transform='scale(1)'">
            <span style="position:absolute;top:10px;left:10px;background:rgba(13,13,13,0.85);color:#c9a84c;font-size:0.6rem;font-weight:700;padding:3px 9px;border-radius:4px;text-transform:uppercase;letter-spacing:0.06em;border:1px solid rgba(201,168,76,0.2);">
                <?php echo htmlspecialchars($row['category']); ?>
            </span>
        </div>
        <div style="padding:14px;">
            <div style="font-weight:700;color:#e2e8f0;font-size:0.84rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;margin-bottom:3px;"><?php echo htmlspecialchars($row['title']); ?></div>
            <div style="font-size:0.72rem;color:#4a5568;margin-bottom:12px;"><i class="fa-solid fa-user-pen" style="margin-right:4px;font-size:0.65rem;"></i><?php echo htmlspecialchars($row['author']); ?></div>
            <div style="display:flex;justify-content:space-between;align-items:center;border-top:1px solid #1e2530;padding-top:11px;">
                <?php if($row['is_free']==1): ?>
                    <span class="badge badge-free"><i class="fa-solid fa-unlock" style="margin-right:3px;font-size:0.6rem;"></i>Free</span>
                <?php else: ?>
                    <span style="font-family:'Cormorant Garamond',serif;color:var(--gold);font-weight:700;font-size:1rem;">Rs. <?php echo number_format($row['price'],0); ?></span>
                <?php endif; ?>
                <div style="display:flex;gap:6px;">
                    <a href="edit_book.php?id=<?php echo $row['id']; ?>" class="action-btn btn-edit"><i class="fa-solid fa-pen"></i></a>
                    <a href="delete_book.php?id=<?php echo $row['id']; ?>" class="action-btn btn-delete" onclick="return confirm('Delete this book?')"><i class="fa-solid fa-trash"></i></a>
                </div>
            </div>
        </div>
    </div>
<?php $i++; endwhile; ?>
<?php else: ?>
    <div style="grid-column:span 4;text-align:center;padding:80px;color:#4a5568;">
        <i class="fa-solid fa-box-open" style="font-size:2.5rem;display:block;margin-bottom:14px;opacity:0.3;color:var(--gold);"></i>
        <p>No books available. <a href="upload_book.php" style="color:var(--gold);font-weight:700;">Add one now →</a></p>
    </div>
<?php endif; ?>
</div>

<?php include("admin_footer.php"); ?>
