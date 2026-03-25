<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if(!isset($_SESSION['admin'])){ header("Location: admin_login.php"); exit(); }
include("../config/db.php");

// ─── ACTION HANDLERS ─────────────────────────────────────────────────────────

// STEP 1: Admin Payment Approve kare → payment_status = Paid, order_status = Confirmed
if(isset($_GET['approve']) && is_numeric($_GET['approve'])){
    $id = (int)$_GET['approve'];
    mysqli_query($conn, "UPDATE orders SET payment_status='Paid', order_status='Confirmed' WHERE id=$id");
    header("Location: orders.php?msg=approved"); exit();
}

// STEP 2: Admin Dispatch kare → order_status = Dispatched
if(isset($_GET['dispatch']) && is_numeric($_GET['dispatch'])){
    $id = (int)$_GET['dispatch'];
    mysqli_query($conn, "UPDATE orders SET order_status='Dispatched' WHERE id=$id");
    header("Location: orders.php?msg=dispatched"); exit();
}

// STEP 3: Admin Deliver kare → order_status = Delivered + user_books mein insert
if(isset($_GET['deliver']) && is_numeric($_GET['deliver'])){
    $id = (int)$_GET['deliver'];
    $ord = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM orders WHERE id=$id"));
    if($ord){
        mysqli_query($conn, "UPDATE orders SET order_status='Delivered' WHERE id=$id");
        $exists = mysqli_fetch_assoc(mysqli_query($conn,
            "SELECT id FROM user_books WHERE user_id='{$ord['user_id']}' AND book_id='{$ord['book_id']}'"
        ));
        if(!$exists){
            mysqli_query($conn,
                "INSERT INTO user_books (user_id, book_id, unlocked_at)
                 VALUES ('{$ord['user_id']}', '{$ord['book_id']}', NOW())"
            );
        }
    }
    header("Location: orders.php?msg=delivered"); exit();
}

// ─── PAGE SETUP ──────────────────────────────────────────────────────────────
$page_title    = "Orders";
$page_subtitle = "Manage and track all book purchases";
include("admin_header.php");

$sql = "SELECT o.*, b.title AS book_title, b.book_image,
               u.name AS user_name, u.phone AS user_phone
        FROM orders o
        JOIN books b ON o.book_id = b.id
        LEFT JOIN users u ON o.user_id = u.id
        ORDER BY o.id DESC";
$result = mysqli_query($conn, $sql);

$c_pending   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) c FROM orders WHERE order_status='Pending' OR order_status IS NULL OR order_status=''"))['c'];
$c_confirmed = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) c FROM orders WHERE order_status='Confirmed'"))['c'];
$c_dispatch  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) c FROM orders WHERE order_status='Dispatched'"))['c'];
$c_delivered = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) c FROM orders WHERE order_status='Delivered'"))['c'];
?>

<script>
<?php if(isset($_GET['msg'])): $m = $_GET['msg']; ?>
const alerts = {
    approved:   { icon:'success', title:'Payment Approved!',  text:'Order is now Confirmed.' },
    dispatched: { icon:'info',    title:'Order Dispatched!',  text:'Marked as Shipped.' },
    delivered:  { icon:'success', title:'Order Delivered!',   text:"Book unlocked in user's My Books!" },
};
if(alerts['<?php echo htmlspecialchars($m); ?>'])
    Swal.fire({ ...alerts['<?php echo htmlspecialchars($m); ?>'], timer:2800, showConfirmButton:false });
<?php endif; ?>
</script>

<!-- ── STAT CARDS ─────────────────────────────────────────────────────────── -->
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:22px;">
    <div style="background:#141920;border:1px solid #1e2530;border-radius:10px;padding:18px;">
        <div style="width:38px;height:38px;background:rgba(245,158,11,0.08);border:1px solid rgba(245,158,11,0.18);border-radius:8px;display:flex;align-items:center;justify-content:center;color:#f59e0b;margin-bottom:12px;">
            <i class="fa-solid fa-clock" style="font-size:0.9rem;"></i>
        </div>
        <div style="font-family:'Cormorant Garamond',serif;font-size:1.9rem;font-weight:700;color:#fff;line-height:1;"><?php echo $c_pending; ?></div>
        <div style="font-size:0.7rem;color:#4a5568;margin-top:4px;font-weight:600;">Pending</div>
    </div>
    <div style="background:#141920;border:1px solid #1e2530;border-radius:10px;padding:18px;">
        <div style="width:38px;height:38px;background:rgba(99,102,241,0.08);border:1px solid rgba(99,102,241,0.18);border-radius:8px;display:flex;align-items:center;justify-content:center;color:#818cf8;margin-bottom:12px;">
            <i class="fa-solid fa-circle-check" style="font-size:0.9rem;"></i>
        </div>
        <div style="font-family:'Cormorant Garamond',serif;font-size:1.9rem;font-weight:700;color:#fff;line-height:1;"><?php echo $c_confirmed; ?></div>
        <div style="font-size:0.7rem;color:#4a5568;margin-top:4px;font-weight:600;">Confirmed</div>
    </div>
    <div style="background:#141920;border:1px solid #1e2530;border-radius:10px;padding:18px;">
        <div style="width:38px;height:38px;background:rgba(201,168,76,0.08);border:1px solid rgba(201,168,76,0.18);border-radius:8px;display:flex;align-items:center;justify-content:center;color:#c9a84c;margin-bottom:12px;">
            <i class="fa-solid fa-truck" style="font-size:0.9rem;"></i>
        </div>
        <div style="font-family:'Cormorant Garamond',serif;font-size:1.9rem;font-weight:700;color:#fff;line-height:1;"><?php echo $c_dispatch; ?></div>
        <div style="font-size:0.7rem;color:#4a5568;margin-top:4px;font-weight:600;">Dispatched</div>
    </div>
    <div style="background:#141920;border:1px solid #1e2530;border-radius:10px;padding:18px;">
        <div style="width:38px;height:38px;background:rgba(74,122,89,0.08);border:1px solid rgba(74,122,89,0.18);border-radius:8px;display:flex;align-items:center;justify-content:center;color:#4a7a59;margin-bottom:12px;">
            <i class="fa-solid fa-box-open" style="font-size:0.9rem;"></i>
        </div>
        <div style="font-family:'Cormorant Garamond',serif;font-size:1.9rem;font-weight:700;color:#fff;line-height:1;"><?php echo $c_delivered; ?></div>
        <div style="font-size:0.7rem;color:#4a5568;margin-top:4px;font-weight:600;">Delivered</div>
    </div>
</div>

<!-- ── ORDERS TABLE ───────────────────────────────────────────────────────── -->
<div class="section-card">
    <div class="section-header">
        <h3><i class="fa-solid fa-cart-shopping" style="color:#6366f1;margin-right:8px;"></i>All Orders</h3>
    </div>
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Book</th>
                <th>Type</th>
                <th style="text-align:center;">Payment</th>
                <th style="text-align:center;">Order Status</th>
                <th style="text-align:center;">Action</th>
            </tr>
        </thead>
        <tbody>
        <?php if(mysqli_num_rows($result) > 0): ?>
        <?php while($row = mysqli_fetch_assoc($result)):
            $os = trim($row['order_status']);
            $ps = trim($row['payment_status']);
            if(empty($os)) $os = 'Pending';
            if(empty($ps)) $ps = 'Pending';
        ?>
        <tr>
            <td style="font-size:0.75rem;font-weight:800;color:#c9a84c;">
                #ORD-<?php echo str_pad($row['id'],3,'0',STR_PAD_LEFT); ?>
            </td>
            <td>
                <div style="font-weight:700;color:#e2e8f0;font-size:0.82rem;"><?php echo htmlspecialchars($row['user_name'] ?? 'Guest'); ?></div>
                <div style="font-size:0.7rem;color:#4a5568;"><?php echo htmlspecialchars($row['user_phone'] ?? ''); ?></div>
            </td>
            <td>
                <div style="display:flex;align-items:center;gap:9px;">
                    <img src="../uploads/covers/<?php echo htmlspecialchars($row['book_image']); ?>"
                         style="width:32px;height:42px;object-fit:cover;border-radius:4px;flex-shrink:0;"
                         onerror="this.style.display='none'">
                    <div style="font-weight:600;color:#e2e8f0;font-size:0.8rem;max-width:130px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                        <?php echo htmlspecialchars($row['book_title']); ?>
                    </div>
                </div>
            </td>
            <td><span class="badge badge-category"><?php echo htmlspecialchars($row['order_type']); ?></span></td>

            <!-- Payment Badge -->
            <td style="text-align:center;">
                <?php if($ps === 'Paid'): ?>
                    <span style="background:rgba(74,122,89,0.1);color:#4a7a59;border:1px solid rgba(74,122,89,0.25);padding:4px 10px;border-radius:20px;font-size:0.62rem;font-weight:700;display:inline-block;">
                        <i class="fa-solid fa-check" style="margin-right:3px;"></i>Paid
                    </span>
                <?php else: ?>
                    <span style="background:rgba(245,158,11,0.1);color:#f59e0b;border:1px solid rgba(245,158,11,0.25);padding:4px 10px;border-radius:20px;font-size:0.62rem;font-weight:700;display:inline-block;">
                        <i class="fa-solid fa-clock" style="margin-right:3px;"></i>Pending
                    </span>
                <?php endif; ?>
            </td>

            <!-- Order Status Badge -->
            <td style="text-align:center;">
                <?php if($os === 'Pending'): ?>
                    <span style="background:rgba(245,158,11,0.1);color:#f59e0b;border:1px solid rgba(245,158,11,0.25);padding:4px 10px;border-radius:20px;font-size:0.62rem;font-weight:700;display:inline-block;">
                        <i class="fa-solid fa-hourglass-half" style="margin-right:3px;"></i>Pending
                    </span>
                <?php elseif($os === 'Confirmed'): ?>
                    <span style="background:rgba(99,102,241,0.1);color:#818cf8;border:1px solid rgba(99,102,241,0.25);padding:4px 10px;border-radius:20px;font-size:0.62rem;font-weight:700;display:inline-block;">
                        <i class="fa-solid fa-circle-check" style="margin-right:3px;"></i>Confirmed
                    </span>
                <?php elseif($os === 'Dispatched'): ?>
                    <span style="background:rgba(201,168,76,0.1);color:#c9a84c;border:1px solid rgba(201,168,76,0.25);padding:4px 10px;border-radius:20px;font-size:0.62rem;font-weight:700;display:inline-block;">
                        <i class="fa-solid fa-truck" style="margin-right:3px;"></i>Dispatched
                    </span>
                <?php elseif($os === 'Delivered'): ?>
                    <span style="background:rgba(74,122,89,0.1);color:#4a7a59;border:1px solid rgba(74,122,89,0.25);padding:4px 10px;border-radius:20px;font-size:0.62rem;font-weight:700;display:inline-block;">
                        <i class="fa-solid fa-box-open" style="margin-right:3px;"></i>Delivered
                    </span>
                <?php endif; ?>
            </td>

            <!-- ACTION BUTTONS -->
            <td style="text-align:center;min-width:160px;">
                <?php if($os === 'Pending'): ?>
                    <button onclick="doApprove(<?php echo $row['id']; ?>)"
                        style="background:rgba(74,122,89,0.15);color:#4a7a59;border:1px solid rgba(74,122,89,0.35);padding:7px 13px;border-radius:7px;font-size:0.72rem;font-weight:700;cursor:pointer;transition:0.2s;white-space:nowrap;"
                        onmouseover="this.style.background='#4a7a59';this.style.color='#fff'"
                        onmouseout="this.style.background='rgba(74,122,89,0.15)';this.style.color='#4a7a59'">
                        <i class="fa-solid fa-check"></i> Approve Payment
                    </button>

                <?php elseif($os === 'Confirmed'): ?>
                    <button onclick="doDispatch(<?php echo $row['id']; ?>)"
                        style="background:rgba(201,168,76,0.12);color:#c9a84c;border:1px solid rgba(201,168,76,0.35);padding:7px 13px;border-radius:7px;font-size:0.72rem;font-weight:700;cursor:pointer;transition:0.2s;white-space:nowrap;"
                        onmouseover="this.style.background='#c9a84c';this.style.color='#0d0d0d'"
                        onmouseout="this.style.background='rgba(201,168,76,0.12)';this.style.color='#c9a84c'">
                        <i class="fa-solid fa-truck"></i> Mark Dispatched
                    </button>

                <?php elseif($os === 'Dispatched'): ?>
                    <button onclick="doDeliver(<?php echo $row['id']; ?>)"
                        style="background:rgba(99,102,241,0.12);color:#818cf8;border:1px solid rgba(99,102,241,0.35);padding:7px 13px;border-radius:7px;font-size:0.72rem;font-weight:700;cursor:pointer;transition:0.2s;white-space:nowrap;"
                        onmouseover="this.style.background='#6366f1';this.style.color='#fff'"
                        onmouseout="this.style.background='rgba(99,102,241,0.12)';this.style.color='#818cf8'">
                        <i class="fa-solid fa-box-open"></i> Mark Delivered
                    </button>

                <?php else: ?>
                    <span style="font-size:0.72rem;color:#4a7a59;font-weight:700;">
                        <i class="fa-solid fa-circle-check"></i> Complete
                    </span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="7" style="text-align:center;padding:50px;color:#4a5568;">
                    <i class="fa-solid fa-cart-shopping" style="font-size:2.5rem;display:block;margin-bottom:12px;opacity:0.2;color:var(--gold);"></i>
                    No orders found.
                </td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Flow Legend -->
<div style="background:#141920;border:1px solid #1e2530;border-radius:10px;padding:14px 20px;margin-top:16px;display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
    <span style="font-size:0.65rem;font-weight:700;color:#4a5568;text-transform:uppercase;letter-spacing:0.1em;">Order Flow:</span>
    <span style="background:rgba(245,158,11,0.1);color:#f59e0b;border:1px solid rgba(245,158,11,0.2);padding:3px 10px;border-radius:20px;font-size:0.62rem;font-weight:700;"><i class="fa-solid fa-hourglass-half" style="margin-right:3px;"></i>Pending</span>
    <i class="fa-solid fa-arrow-right" style="color:#2d3748;font-size:0.65rem;"></i>
    <span style="background:rgba(74,122,89,0.1);color:#4a7a59;border:1px solid rgba(74,122,89,0.2);padding:3px 10px;border-radius:20px;font-size:0.62rem;font-weight:700;"><i class="fa-solid fa-check" style="margin-right:3px;"></i>Approve → Paid + Confirmed</span>
    <i class="fa-solid fa-arrow-right" style="color:#2d3748;font-size:0.65rem;"></i>
    <span style="background:rgba(201,168,76,0.1);color:#c9a84c;border:1px solid rgba(201,168,76,0.2);padding:3px 10px;border-radius:20px;font-size:0.62rem;font-weight:700;"><i class="fa-solid fa-truck" style="margin-right:3px;"></i>Dispatched</span>
    <i class="fa-solid fa-arrow-right" style="color:#2d3748;font-size:0.65rem;"></i>
    <span style="background:rgba(74,122,89,0.1);color:#4a7a59;border:1px solid rgba(74,122,89,0.2);padding:3px 10px;border-radius:20px;font-size:0.62rem;font-weight:700;"><i class="fa-solid fa-box-open" style="margin-right:3px;"></i>Delivered + Book Unlocked</span>
</div>

<script>
function doApprove(id){
    Swal.fire({
        title: 'Approve Payment?',
        html: 'Payment <b>Paid</b> ho jayegi aur Order <b>Confirmed</b> ho jayega.',
        icon: 'question', showCancelButton: true,
        confirmButtonColor: '#4a7a59', cancelButtonColor: '#374151',
        confirmButtonText: '<i class="fa-solid fa-check"></i> Approve Karo',
        cancelButtonText: 'Cancel'
    }).then(r => { if(r.isConfirmed) window.location.href = 'orders.php?approve=' + id; });
}
function doDispatch(id){
    Swal.fire({
        title: 'Mark as Dispatched?',
        html: 'Order <b>ship ho gaya</b> confirm karo.',
        icon: 'info', showCancelButton: true,
        confirmButtonColor: '#c9a84c', cancelButtonColor: '#374151',
        confirmButtonText: '<i class="fa-solid fa-truck"></i> Dispatch Karo',
        cancelButtonText: 'Cancel'
    }).then(r => { if(r.isConfirmed) window.location.href = 'orders.php?dispatch=' + id; });
}
function doDeliver(id){
    Swal.fire({
        title: 'Mark as Delivered?',
        html: 'Order <b>Delivered</b> hoga aur Book user ke <b>My Books</b> mein <b>unlock</b> ho jayegi!',
        icon: 'success', showCancelButton: true,
        confirmButtonColor: '#6366f1', cancelButtonColor: '#374151',
        confirmButtonText: '<i class="fa-solid fa-box-open"></i> Delivered Mark Karo',
        cancelButtonText: 'Cancel'
    }).then(r => { if(r.isConfirmed) window.location.href = 'orders.php?deliver=' + id; });
}
</script>

<?php include("admin_footer.php"); ?>