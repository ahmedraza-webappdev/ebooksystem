<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if(!isset($_SESSION['admin'])){ header("Location: admin_login.php"); exit(); }
include("../config/db.php");

// ─── ACTION HANDLERS ─────────────────────────────────────────────────────────

if(isset($_GET['approve']) && is_numeric($_GET['approve'])){
    $id = (int)$_GET['approve'];
    mysqli_query($conn, "UPDATE orders SET payment_status='Paid', order_status='Confirmed' WHERE id=$id");
    header("Location: orders.php?msg=approved"); exit();
}

if(isset($_GET['reject']) && is_numeric($_GET['reject'])){
    $id = (int)$_GET['reject'];
    mysqli_query($conn, "UPDATE orders SET payment_status='Rejected', order_status='Cancelled' WHERE id=$id");
    header("Location: orders.php?msg=rejected"); exit();
}

if(isset($_GET['dispatch']) && is_numeric($_GET['dispatch'])){
    $id = (int)$_GET['dispatch'];
    mysqli_query($conn, "UPDATE orders SET order_status='Dispatched' WHERE id=$id");
    header("Location: orders.php?msg=dispatched"); exit();
}

if(isset($_GET['deliver']) && is_numeric($_GET['deliver'])){
    $id = (int)$_GET['deliver'];
    $ord = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM orders WHERE id=$id"));
    if($ord){
        mysqli_query($conn, "UPDATE orders SET order_status='Delivered' WHERE id=$id");
        $exists = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id FROM user_books WHERE user_id='{$ord['user_id']}' AND book_id='{$ord['book_id']}'"));
        if(!$exists){
            mysqli_query($conn, "INSERT INTO user_books (user_id, book_id, unlocked_at) VALUES ('{$ord['user_id']}', '{$ord['book_id']}', NOW())");
        }
    }
    header("Location: orders.php?msg=delivered"); exit();
}

$page_title = "Order Management";
include("admin_header.php");

// Stats Logic
$c_pending   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) c FROM orders WHERE order_status='Pending' OR order_status='' OR order_status IS NULL"))['c'];
$c_confirmed = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) c FROM orders WHERE order_status='Confirmed'"))['c'];
$c_dispatch  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) c FROM orders WHERE order_status='Dispatched'"))['c'];
$c_delivered = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) c FROM orders WHERE order_status='Delivered'"))['c'];

$sql = "SELECT o.*, b.title AS book_title, b.book_image, u.name AS user_name, u.phone AS user_phone
        FROM orders o
        JOIN books b ON o.book_id = b.id
        LEFT JOIN users u ON o.user_id = u.id
        ORDER BY o.id DESC";
$result = mysqli_query($conn, $sql);
?>

<style>
    :root {
        --gold: #c9a84c;
        --success: #00ff87;
        --danger: #ff4b2b;
        --info: #00d2ff;
        --warning: #f9d423;
    }

    /* 💎 Premium Action Buttons */
    .btn-action {
        border: none;
        padding: 9px 16px;
        border-radius: 8px;
        font-size: 0.7rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        cursor: pointer;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        color: #fff;
    }

    .btn-action:hover {
        transform: translateY(-3px) scale(1.02);
        box-shadow: 0 8px 25px rgba(0,0,0,0.4);
        filter: brightness(1.2);
    }

    /* Color Palette */
    .btn-approve { background: linear-gradient(45deg, #00b09b, #96c93d); } /* Green Mint */
    .btn-reject { background: linear-gradient(45deg, #ee0979, #ff6a00); } /* Hot Coral */
    .btn-dispatch { background: linear-gradient(45deg, #f9d423, #ff4e50); color: #000; } /* Sunset Yellow */
    .btn-deliver { background: linear-gradient(45deg, #00c6ff, #0072ff); } /* Royal Blue */

    .completed-text {
        background: rgba(0, 255, 135, 0.1);
        color: var(--success);
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.65rem;
        font-weight: 900;
        border: 1px solid rgba(0, 255, 135, 0.2);
    }

    /* Proof Link Styling */
    .view-proof-link {
        color: var(--gold);
        font-size: 0.68rem;
        font-weight: 700;
        cursor: pointer;
        display: block;
        margin-top: 6px;
        transition: 0.3s;
    }
    .view-proof-link:hover { color: #fff; text-shadow: 0 0 5px var(--gold); }

    /* Modal Glassmorphism */
    #proofModal { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.9); z-index:9999; justify-content:center; align-items:center; backdrop-filter: blur(8px); }
    .modal-box { position:relative; max-width:420px; width:90%; background:#1a1f28; padding:15px; border-radius:20px; border:1px solid rgba(201,168,76,0.3); box-shadow: 0 20px 50px rgba(0,0,0,0.5); }
    .close-btn { position:absolute; top:-10px; right:-10px; background:var(--gold); color:#000; border:none; width:32px; height:32px; border-radius:50%; cursor:pointer; font-weight:900; }
</style>

<div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap:15px; margin-bottom:30px;">
    <div style="background: linear-gradient(135deg, #141920, #1c2333); padding:20px; border-radius:15px; border-bottom:3px solid var(--warning);">
        <div style="font-size:0.6rem; color:#8b949e; letter-spacing:1px;">WAITING</div>
        <h2 style="margin:5px 0; color:var(--warning);"><?php echo $c_pending; ?></h2>
    </div>
    <div style="background: linear-gradient(135deg, #141920, #1c2333); padding:20px; border-radius:15px; border-bottom:3px solid var(--success);">
        <div style="font-size:0.6rem; color:#8b949e; letter-spacing:1px;">CONFIRMED</div>
        <h2 style="margin:5px 0; color:var(--success);"><?php echo $c_confirmed; ?></h2>
    </div>
    <div style="background: linear-gradient(135deg, #141920, #1c2333); padding:20px; border-radius:15px; border-bottom:3px solid var(--gold);">
        <div style="font-size:0.6rem; color:#8b949e; letter-spacing:1px;">ON THE WAY</div>
        <h2 style="margin:5px 0; color:var(--gold);"><?php echo $c_dispatch; ?></h2>
    </div>
    <div style="background: linear-gradient(135deg, #141920, #1c2333); padding:20px; border-radius:15px; border-bottom:3px solid var(--info);">
        <div style="font-size:0.6rem; color:#8b949e; letter-spacing:1px;">DELIVERED</div>
        <h2 style="margin:5px 0; color:var(--info);"><?php echo $c_delivered; ?></h2>
    </div>
</div>

<div class="section-card" style="background:#141920; border-radius:18px; padding:25px; border:1px solid rgba(255,255,255,0.05);">
    <h3 style="margin-bottom:25px; font-family:'Cormorant Garamond',serif; color:#fff; font-size:1.5rem;"><i class="fa-solid fa-receipt" style="color:var(--gold);"></i> Manage All Orders</h3>
    
    <table style="width:100%; border-collapse: separate; border-spacing: 0 12px;">
        <thead>
            <tr style="text-align:left; font-size:0.65rem; color:#4a5568; text-transform:uppercase; letter-spacing:1.5px;">
                <th style="padding:0 15px;">ID</th>
                <th>User Details</th>
                <th>Order Items</th>
                <th style="text-align:center;">Payment</th>
                <th style="text-align:center;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = mysqli_fetch_assoc($result)): 
                $os = $row['order_status'] ?: 'Pending';
                $ps = $row['payment_status'] ?: 'Pending';
            ?>
            <tr style="background: rgba(255,255,255,0.02); transition: 0.3s;">
                <td style="padding:20px 15px; border-radius:12px 0 0 12px; font-weight:900; color:var(--gold);">#<?php echo $row['id']; ?></td>
                <td>
                    <div style="font-weight:700; font-size:0.9rem;"><?php echo htmlspecialchars($row['user_name']); ?></div>
                    <div style="font-size:0.75rem; color:#8b949e;"><i class="fa-solid fa-phone" style="font-size:0.6rem;"></i> <?php echo htmlspecialchars($row['user_phone']); ?></div>
                </td>
                <td>
                    <div style="font-weight:600; font-size:0.85rem;"><?php echo htmlspecialchars($row['book_title']); ?></div>
                    <span style="font-size:0.6rem; color:var(--gold); border:1px solid var(--gold); padding:1px 6px; border-radius:4px;"><?php echo $row['order_type']; ?></span>
                </td>
                <td style="text-align:center;">
                    <?php if($ps === 'Paid'): ?>
                        <div style="color:var(--success); font-size:0.7rem; font-weight:700;"><i class="fa-solid fa-check-circle"></i> VERIFIED</div>
                    <?php elseif($ps === 'Rejected'): ?>
                        <div style="color:var(--danger); font-size:0.7rem; font-weight:700;"><i class="fa-solid fa-ban"></i> REJECTED</div>
                    <?php else: ?>
                        <div style="color:var(--warning); font-size:0.7rem; font-weight:700;"><i class="fa-solid fa-hourglass"></i> PENDING</div>
                        <?php if(!empty($row['payment_proof'])): ?>
                            <span class="view-proof-link" onclick="viewProof('../uploads/payments/<?php echo $row['payment_proof']; ?>')"><i class="fa-solid fa-eye"></i> View Proof</span>
                        <?php endif; ?>
                    <?php endif; ?>
                </td>
                <td style="text-align:center; border-radius:0 12px 12px 0;">
                    <?php if($os === 'Pending' || $os === ''): ?>
                        <button class="btn-action btn-approve" onclick="doApprove(<?php echo $row['id']; ?>)"><i class="fa-solid fa-check"></i></button>
                        <button class="btn-action btn-reject" onclick="doReject(<?php echo $row['id']; ?>)"><i class="fa-solid fa-xmark"></i></button>
                    <?php elseif($os === 'Confirmed'): ?>
                        <button class="btn-action btn-dispatch" onclick="doDispatch(<?php echo $row['id']; ?>)"><i class="fa-solid fa-truck-fast"></i> Dispatch</button>
                    <?php elseif($os === 'Dispatched'): ?>
                        <button class="btn-action btn-deliver" onclick="doDeliver(<?php echo $row['id']; ?>)"><i class="fa-solid fa-box-check"></i> Mark Delivered</button>
                    <?php else: ?>
                        <span class="completed-text"><i class="fa-solid fa-circle-check"></i> DELIVERED</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<div id="proofModal">
    <div class="modal-box">
        <button class="close-btn" onclick="closeProof()">×</button>
        <h4 style="color:var(--gold); margin-bottom:15px; font-family:'Cormorant Garamond',serif; text-align:center;">Payment Verification</h4>
        <img id="proofImg" src="" style="width:100%; border-radius:12px; filter: drop-shadow(0 0 10px rgba(0,0,0,0.5));">
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function viewProof(url) {
    document.getElementById('proofImg').src = url;
    document.getElementById('proofModal').style.display = 'flex';
}
function closeProof() {
    document.getElementById('proofModal').style.display = 'none';
}

function doApprove(id){
    Swal.fire({
        title: 'Confirm Payment?', text: "This will unlock the book for the user.",
        icon: 'success', showCancelButton: true, confirmButtonColor: '#00b09b', confirmButtonText: 'Yes, Approve'
    }).then((r) => { if (r.isConfirmed) window.location.href = 'orders.php?approve=' + id; });
}
function doReject(id){
    Swal.fire({
        title: 'Reject Order?', text: "Use this for fake or missing payments.",
        icon: 'error', showCancelButton: true, confirmButtonColor: '#ee0979', confirmButtonText: 'Yes, Reject'
    }).then((r) => { if (r.isConfirmed) window.location.href = 'orders.php?reject=' + id; });
}
function doDispatch(id){ window.location.href = 'orders.php?dispatch=' + id; }
function doDeliver(id){ window.location.href = 'orders.php?deliver=' + id; }

<?php if(isset($_GET['msg'])): ?>
    Swal.fire({ title: 'Updated!', icon: 'success', timer: 1500, showConfirmButton: false, background: '#141920', color: '#fff' });
<?php endif; ?>
</script>

<?php include("admin_footer.php"); ?>