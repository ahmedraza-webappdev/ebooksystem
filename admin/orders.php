<?php
// Session start hamesha sab se pehle
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Logic: Agar session nahi hai to login par bhej do
// Note: Check karein ke aapne login page par $_SESSION['users'] hi set kiya hai ya $_SESSION['admin']
if(!isset($_SESSION['users'])){ 
    header("Location: admin_login.php"); 
    exit(); 
}

include("../config/db.php");
$page_title = "Orders";
$page_subtitle = "Track all book purchases";
include("admin_header.php");

// Query wahi hai jo aapne di thi
$sql = "SELECT orders.*,books.title FROM orders JOIN books ON orders.book_id=books.id ORDER BY orders.id DESC";
$result = mysqli_query($conn,$sql);
?>

<div class="section-card">
    <div class="section-header">
        <h3><i class="fa-solid fa-cart-shopping" style="color:#6366f1;margin-right:8px;"></i>All Orders</h3>
    </div>
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Book</th>
                <th>Order Type</th>
                <th>Payment</th>
            </tr>
        </thead>
        <tbody>
        <?php if(mysqli_num_rows($result)>0): ?>
        <?php while($row=mysqli_fetch_assoc($result)): ?>
            <tr>
                <td style="font-size:0.75rem;font-weight:800;color:#94a3b8;">#ORD-<?php echo str_pad($row['id'],3,'0',STR_PAD_LEFT); ?></td>
                <td style="font-weight:700;color:#1e293b;font-size:0.875rem;"><?php echo htmlspecialchars($row['title']); ?></td>
                <td><span class="badge badge-category"><?php echo htmlspecialchars($row['order_type']); ?></span></td>
                <td>
                    <?php if(strtolower($row['payment_status']) == 'paid'): ?>
                        <span class="badge badge-free">Paid</span>
                    <?php else: ?>
                        <span class="badge badge-pending"><?php echo htmlspecialchars($row['payment_status']); ?></span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="4" style="text-align:center;padding:40px;color:#94a3b8;">No orders found.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include("admin_footer.php"); ?>