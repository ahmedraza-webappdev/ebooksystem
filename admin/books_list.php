<?php
session_start();
if(!isset($_SESSION['admin'])){ header("Location: admin_login.php"); exit(); }
include("../config/db.php");
$page_title = "Books List";
$page_subtitle = "Edit and manage all books in the library";
include("admin_header.php");
$result = mysqli_query($conn,"SELECT * FROM books ORDER BY id DESC");
?>

<style>
    /* Dark Theme Visibility Fixes */
    .book-title { font-weight: 700; color: #ffffff !important; font-size: 0.875rem; }
    .book-author { font-size: 0.75rem; color: #94a3b8 !important; margin-top: 2px; }
    .book-author i { color: var(--gold); margin-right: 4px; }
    
    .price-text { font-weight: 800; color: var(--gold) !important; font-size: 0.9rem; }
    .weight-text { font-size: 0.8rem; color: #cbd5e1 !important; }
    
    .id-column { text-align: center; font-size: 0.75rem; font-weight: 800; color: var(--gold); opacity: 0.7; }
    
    /* Category Badge Update */
    .badge-category { background: rgba(99, 102, 241, 0.1) !important; color: #818cf8 !important; border: 1px solid rgba(99, 102, 241, 0.2); }
    .badge-free { background: rgba(34, 197, 94, 0.15) !important; color: #4ade80 !important; border: 1px solid rgba(34, 197, 94, 0.2); font-weight: 800; }

    /* Custom Alert for Dark Theme */
    .alert-success { background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.2); color: #4ade80; padding: 12px 20px; border-radius: 10px; margin-bottom: 20px; font-weight: 600; display: flex; align-items: center; gap: 10px; }
</style>

<?php if(isset($_GET['msg'])): ?>
<div class="alert-success">
    <i class="fa-solid fa-circle-check"></i>
    <?php echo $_GET['msg']=='deleted' ? 'Book deleted successfully!' : 'Book updated successfully!'; ?>
</div>
<?php endif; ?>

<div class="section-card">
    <div class="section-header">
        <h3 style="color:var(--gold);"><i class="fa-solid fa-list" style="margin-right:8px;"></i>Books Inventory</h3>
        <a href="upload_book.php" class="btn-primary" style="padding: 8px 16px; font-size: 0.85rem;"><i class="fa-solid fa-plus"></i> Add New Book</a>
    </div>
    <table>
        <thead>
            <tr>
                <th style="text-align:center;">ID</th>
                <th>Book Info</th>
                <th>Category</th>
                <th>Price</th>
                <th>Weight</th>
                <th style="text-align:center;">Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td class="id-column">#<?php echo $row['id']; ?></td>
                <td>
                    <div style="display:flex;align-items:center;gap:12px;">
                        <img src="../uploads/covers/<?php echo $row['book_image']; ?>" style="width:42px;height:58px;object-fit:cover;border-radius:6px;box-shadow:0 4px 12px rgba(0,0,0,0.4); border: 1px solid rgba(255,255,255,0.05);">
                        <div>
                            <div class="book-title"><?php echo htmlspecialchars($row['title']); ?></div>
                            <div class="book-author"><i class="fa-solid fa-user-pen"></i><?php echo htmlspecialchars($row['author']); ?></div>
                        </div>
                    </div>
                </td>
                <td><span class="badge badge-category"><?php echo htmlspecialchars($row['category']); ?></span></td>
                <td>
                    <?php if($row['is_free']==1): ?>
                        <span class="badge badge-free"><i class="fa-solid fa-unlock-fill" style="margin-right:4px;"></i>FREE</span>
                    <?php else: ?>
                        <span class="price-text">₹<?php echo number_format($row['price'],2); ?></span>
                    <?php endif; ?>
                </td>
                <td class="weight-text"><?php echo htmlspecialchars($row['weight']); ?></td>
                <td style="text-align:center;">
                    <div style="display:flex;justify-content:center;gap:8px;">
                        <a href="edit_book.php?id=<?php echo $row['id']; ?>" class="action-btn" style="color:var(--gold); background:rgba(201,168,76,0.1); border:1px solid rgba(201,168,76,0.2); padding:7px 10px; border-radius:8px;" title="Edit"><i class="fa-solid fa-pen"></i></a>
                        <a href="delete_book.php?id=<?php echo $row['id']; ?>" class="action-btn" style="color:#ef4444; background:rgba(239,68,68,0.1); border:1px solid rgba(239,68,68,0.2); padding:7px 10px; border-radius:8px;" title="Delete" onclick="return confirm('Delete this book?')"><i class="fa-solid fa-trash"></i></a>
                    </div>
                </td>
            </tr>
        <?php endwhile; ?>
        <?php if(mysqli_num_rows($result)==0): ?>
            <tr><td colspan="6" style="text-align:center;padding:60px;color:var(--muted);"><i class="fa-solid fa-book" style="font-size:2.5rem;display:block;margin-bottom:15px;opacity:0.2;"></i>No books found in inventory.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include("admin_footer.php"); ?>