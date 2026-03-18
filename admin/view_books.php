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

<!-- Top Bar Fix -->
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px; padding: 0 4px;">
    <p style="color:#94a3b8; font-size:0.85rem; font-weight:500;">
        <i class="fa-solid fa-layer-group" style="color:var(--gold); margin-right:8px;"></i>
        <span style="color:#e2e8f0; font-weight:700;"><?php echo mysqli_num_rows($result); ?></span> books in collection
    </p>
    <a href="upload_book.php" class="btn-primary" style="padding: 10px 20px;"><i class="fa-solid fa-plus"></i> Add New Book</a>
</div>

<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:20px;">
<?php if(mysqli_num_rows($result)>0): ?>
<?php while($row=mysqli_fetch_assoc($result)): ?>
    <div style="background:#141920; border:1px solid #1e2530; border-radius:12px; overflow:hidden; transition:all 0.3s ease; box-shadow: 0 4px 15px rgba(0,0,0,0.2);" 
         onmouseover="this.style.borderColor='rgba(201,168,76,0.4)'; this.style.transform='translateY(-6px)'; this.style.boxShadow='0 10px 25px rgba(0,0,0,0.4)';" 
         onmouseout="this.style.borderColor='#1e2530'; this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(0,0,0,0.2)';"
    >
        <!-- Book Cover Area -->
        <div style="height:220px; overflow:hidden; position:relative; background:#0a0a0a; display:flex; align-items:center; justify-content:center; border-bottom: 1px solid #1e2530;">
            <img src="../uploads/covers/<?php echo $row['book_image']; ?>" alt="cover" style="max-width:100%; max-height:100%; width:auto; height:90%; object-fit:contain; transition:transform 0.5s ease;" onmouseover="this.style.transform='scale(1.08)'" onmouseout="this.style.transform='scale(1)'">
            
            <!-- Category Badge -->
            <span style="position:absolute; top:12px; left:12px; background:rgba(201,168,76,0.15); color:#c9a84c; font-size:0.62rem; font-weight:800; padding:4px 10px; border-radius:6px; text-transform:uppercase; letter-spacing:0.06em; border:1px solid rgba(201,168,76,0.3); backdrop-filter: blur(4px);">
                <?php echo htmlspecialchars($row['category']); ?>
            </span>
        </div>

        <!-- Book Details Area -->
        <div style="padding:16px;">
            <div style="font-weight:700; color:#ffffff; font-size:0.9rem; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; margin-bottom:4px;" title="<?php echo htmlspecialchars($row['title']); ?>">
                <?php echo htmlspecialchars($row['title']); ?>
            </div>
            
            <div style="font-size:0.75rem; color:#94a3b8; margin-bottom:16px; display:flex; align-items:center;">
                <i class="fa-solid fa-user-pen" style="margin-right:6px; font-size:0.7rem; color:var(--gold);"></i>
                <?php echo htmlspecialchars($row['author']); ?>
            </div>

            <!-- Pricing & Actions -->
            <div style="display:flex; justify-content:space-between; align-items:center; border-top:1px solid rgba(255,255,255,0.05); padding-top:14px;">
                <?php if($row['is_free']==1): ?>
                    <span style="background:rgba(34,197,94,0.1); color:#4ade80; border:1px solid rgba(34,197,94,0.2); padding:3px 10px; border-radius:6px; font-size:0.65rem; font-weight:800; text-transform:uppercase;">
                        <i class="fa-solid fa-unlock-keyhole" style="margin-right:4px;"></i>Free
                    </span>
                <?php else: ?>
                    <span style="font-family:'DM Sans', sans-serif; color:var(--gold); font-weight:800; font-size:0.95rem;">
                        <span style="font-size:0.75rem; margin-right:2px; opacity:0.8;">Rs.</span><?php echo number_format($row['price'],0); ?>
                    </span>
                <?php endif; ?>

                <div style="display:flex; gap:8px;">
                    <a href="edit_book.php?id=<?php echo $row['id']; ?>" style="color:var(--gold); background:rgba(201,168,76,0.1); border:1px solid rgba(201,168,76,0.2); width:32px; height:32px; border-radius:8px; display:flex; align-items:center; justify-content:center; transition:0.2s;" onmouseover="this.style.background='var(--gold)'; this.style.color='#000';"><i class="fa-solid fa-pen" style="font-size:0.75rem;"></i></a>
                    <a href="delete_book.php?id=<?php echo $row['id']; ?>" style="color:#ef4444; background:rgba(239,68,68,0.1); border:1px solid rgba(239,68,68,0.2); width:32px; height:32px; border-radius:8px; display:flex; align-items:center; justify-content:center; transition:0.2s;" onmouseover="this.style.background='#ef4444'; this.style.color='#fff';" onclick="return confirm('Delete this book?')"><i class="fa-solid fa-trash" style="font-size:0.75rem;"></i></a>
                </div>
            </div>
        </div>
    </div>
<?php $i++; endwhile; ?>
<?php else: ?>
    <!-- Empty State -->
    <div style="grid-column:span 4; text-align:center; padding:100px 20px; background:#141920; border-radius:15px; border:1px dashed #1e2530;">
        <i class="fa-solid fa-book-open" style="font-size:3rem; display:block; margin-bottom:16px; opacity:0.2; color:var(--gold);"></i>
        <p style="color:#94a3b8; font-size:0.95rem;">Your library is currently empty.</p>
        <a href="upload_book.php" style="color:var(--gold); font-weight:700; text-decoration:none; margin-top:10px; display:inline-block; border-bottom:1px solid var(--gold); padding-bottom:2px;">Add your first book →</a>
    </div>
<?php endif; ?>
</div>

<?php include("admin_footer.php"); ?>