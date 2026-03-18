<?php
// Session start hamesha sab se pehle
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION['admin'])){ 
    header("Location: admin_login.php"); 
    exit(); 
}

include("../config/db.php");
$page_title = "Orders";
$page_subtitle = "Track all book purchases and transactions";
include("admin_header.php");

$sql = "SELECT orders.*, books.title FROM orders JOIN books ON orders.book_id=books.id ORDER BY orders.id DESC";
$result = mysqli_query($conn,$sql);
?>

<style>
    /* Table Styling */
    table { width: 100%; border-collapse: separate; border-spacing: 0; margin-top: 10px; }
    thead th { 
        background: #0d1117; 
        color: var(--gold); 
        font-size: 0.7rem; 
        text-transform: uppercase; 
        letter-spacing: 1.5px; 
        padding: 16px 20px; 
        border-bottom: 2px solid #1e2530;
        text-align: left;
    }
    tbody td { 
        padding: 16px 20px; 
        border-bottom: 1px solid rgba(255,255,255,0.03); 
        vertical-align: middle;
        color: #94a3b8;
        font-size: 0.85rem;
    }
    tbody tr:hover { background: rgba(255,255,255,0.02); }
    
    /* Status Badges */
    .status-badge {
        padding: 5px 12px;
        border-radius: 6px;
        font-size: 0.65rem;
        font-weight: 800;
        text-transform: uppercase;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .badge-paid { background: rgba(34,197,94,0.1); color: #4ade80; border: 1px solid rgba(34,197,94,0.2); }
    .badge-pending { background: rgba(245,158,11,0.1); color: #fbbf24; border: 1px solid rgba(245,158,11,0.2); }
    .badge-type { background: rgba(201,168,76,0.1); color: var(--gold); border: 1px solid rgba(201,168,76,0.2); }
</style>

<div class="section-card" style="background: #141920; border: 1px solid #1e2530; border-radius: 15px; overflow: hidden; padding: 0;">
    <div class="section-header" style="padding: 22px 25px; border-bottom: 1px solid #1e2530; display: flex; align-items: center; justify-content: space-between;">
        <h3 style="margin:0; font-size: 1.1rem; color: #fff; font-weight: 700;">
            <i class="fa-solid fa-receipt" style="color:var(--gold); margin-right:10px;"></i>Transaction History
        </h3>
        <span style="font-size: 0.75rem; color: #94a3b8; font-weight: 500;">Total Orders: <?php echo mysqli_num_rows($result); ?></span>
    </div>

    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Book Title & Date</th>
                    <th>Order Type</th>
                    <th>Payment Status</th>
                </tr>
            </thead>
            <tbody>
            <?php if(mysqli_num_rows($result) > 0): ?>
            <?php while($row=mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td style="font-family: 'JetBrains Mono', monospace; font-weight: 700; color: #e2e8f0;">
                        #ORD-<?php echo str_pad($row['id'], 3, '0', STR_PAD_LEFT); ?>
                    </td>
                    <td>
                        <div style="font-weight: 700; color: #f1f5f9; margin-bottom: 4px;"><?php echo htmlspecialchars($row['title']); ?></div>
                        <!-- Date color changed to #94a3b8 for better visibility -->
                        <div style="font-size: 0.7rem; color: #94a3b8; font-weight: 500; display: flex; align-items: center; gap: 5px;">
                            <i class="fa-regular fa-calendar" style="font-size: 0.65rem; color: var(--gold);"></i>
                            Ordered on: <?php echo date('d M, Y', strtotime($row['created_at'] ?? 'now')); ?>
                        </div>
                    </td>
                    <td>
                        <span class="status-badge badge-type">
                            <i class="fa-solid fa-tags" style="font-size: 0.6rem;"></i>
                            <?php echo htmlspecialchars($row['order_type']); ?>
                        </span>
                    </td>
                    <td>
                        <?php if(strtolower($row['payment_status']) == 'paid'): ?>
                            <span class="status-badge badge-paid">
                                <i class="fa-solid fa-circle-check"></i> Paid
                            </span>
                        <?php else: ?>
                            <span class="status-badge badge-pending">
                                <i class="fa-solid fa-clock"></i> <?php echo htmlspecialchars($row['payment_status']); ?>
                            </span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" style="text-align: center; padding: 60px 0;">
                        <i class="fa-solid fa-box-open" style="font-size: 2.5rem; color: #1e2530; display: block; margin-bottom: 15px;"></i>
                        <span style="color: #64748b;">No transaction records found.</span>
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include("admin_footer.php"); ?>