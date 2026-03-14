<?php
session_start();
if(!isset($_SESSION['admin'])){ header("Location: admin_login.php"); exit(); }
include("../config/db.php");
$page_title = "Books List";
$page_subtitle = "Edit and manage all books in the library";
include("admin_header.php");
$result = mysqli_query($conn,"SELECT * FROM books ORDER BY id DESC");
?>

<?php if(isset($_GET['msg'])): ?>
<div class="alert-success"><i class="fa-solid fa-circle-check"></i>
    <?php echo $_GET['msg']=='deleted' ? 'Book deleted successfully!' : 'Book updated successfully!'; ?>
</div>
<?php endif; ?>

<div class="section-card">
    <div class="section-header">
        <h3><i class="fa-solid fa-list" style="color:#6366f1;margin-right:8px;"></i>Books Inventory</h3>
        <a href="upload_book.php" class="btn-primary"><i class="fa-solid fa-plus"></i> Add New Book</a>
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
                <td style="text-align:center;font-size:0.75rem;font-weight:800;color:#94a3b8;">#<?php echo $row['id']; ?></td>
                <td>
                    <div style="display:flex;align-items:center;gap:12px;">
                        <img src="../uploads/covers/<?php echo $row['book_image']; ?>" style="width:40px;height:55px;object-fit:cover;border-radius:6px;box-shadow:0 4px 10px rgba(0,0,0,0.1);">
                        <div>
                            <div style="font-weight:700;color:#1e293b;font-size:0.875rem;"><?php echo htmlspecialchars($row['title']); ?></div>
                            <div style="font-size:0.75rem;color:#94a3b8;"><i class="fa-solid fa-user-pen mr-1"></i><?php echo htmlspecialchars($row['author']); ?></div>
                        </div>
                    </div>
                </td>
                <td><span class="badge badge-category"><?php echo htmlspecialchars($row['category']); ?></span></td>
                <td>
                    <?php if($row['is_free']==1): ?>
                        <span class="badge badge-free"><i class="fa-solid fa-unlock-fill mr-1"></i>FREE</span>
                    <?php else: ?>
                        <span style="font-weight:800;color:#4f46e5;">₹<?php echo number_format($row['price'],2); ?></span>
                    <?php endif; ?>
                </td>
                <td style="font-size:0.8rem;color:#64748b;"><?php echo htmlspecialchars($row['weight']); ?></td>
                <td style="text-align:center;">
                    <div style="display:flex;justify-content:center;gap:6px;">
                        <a href="edit_book.php?id=<?php echo $row['id']; ?>" class="action-btn btn-edit" title="Edit"><i class="fa-solid fa-pen"></i></a>
                        <a href="delete_book.php?id=<?php echo $row['id']; ?>" class="action-btn btn-delete" title="Delete" onclick="return confirm('Delete this book?')"><i class="fa-solid fa-trash"></i></a>
                    </div>
                </td>
            </tr>
        <?php endwhile; ?>
        <?php if(mysqli_num_rows($result)==0): ?>
            <tr><td colspan="6" style="text-align:center;padding:40px;color:#94a3b8;"><i class="fa-solid fa-book" style="font-size:2.5rem;display:block;margin-bottom:12px;opacity:0.3;"></i>No books found.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include("admin_footer.php"); ?>
