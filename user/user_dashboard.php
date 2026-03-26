<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if(!isset($_SESSION['user_id'])){ header("Location: login.php"); exit(); }
include("../config/db.php");
include("navbar.php"); 


$user_id = (int)$_SESSION['user_id'];
$success_msg = '';
$error_msg   = '';

if(isset($_POST['update_profile'])){
    $name    = mysqli_real_escape_string($conn, trim($_POST['name']));
    $phone   = mysqli_real_escape_string($conn, trim($_POST['phone']));
    $address = mysqli_real_escape_string($conn, trim($_POST['address']));
    if(empty($name)){ $error_msg = "Name cannot be empty."; }
    else {
        mysqli_query($conn, "UPDATE users SET name='$name', phone='$phone', address='$address' WHERE id=$user_id");
        $_SESSION['user_name'] = $name;
        $success_msg = "Profile updated successfully!";
    }
}

if(isset($_POST['change_password'])){
    $current = md5($_POST['current_password']);
    $new     = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];
    $check   = mysqli_fetch_assoc(mysqli_query($conn,"SELECT password FROM users WHERE id=$user_id"));
    if($check['password'] !== $current){ $error_msg = "Current password is incorrect."; }
    elseif(strlen($new) < 6){ $error_msg = "New password must be at least 6 characters."; }
    elseif($new !== $confirm){ $error_msg = "Passwords do not match."; }
    else { mysqli_query($conn,"UPDATE users SET password='".md5($new)."' WHERE id=$user_id"); $success_msg = "Password changed successfully!"; }
}

$user         = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM users WHERE id=$user_id"));
$total_orders = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) c FROM orders WHERE user_id=$user_id"))['c'];
$total_books  = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) c FROM orders WHERE user_id=$user_id AND order_status='Delivered'"))['c'];
$total_essays = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) c FROM submissions WHERE user_id=$user_id"))['c'];
$c_pending   = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) c FROM orders WHERE user_id=$user_id AND (order_status='Pending' OR order_status='Processing' OR order_status='' OR order_status IS NULL)"))['c'];
$c_confirmed = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) c FROM orders WHERE user_id=$user_id AND order_status='Confirmed'"))['c'];
$c_dispatch  = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) c FROM orders WHERE user_id=$user_id AND order_status='Dispatched'"))['c'];
$c_delivered = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) c FROM orders WHERE user_id=$user_id AND order_status='Delivered'"))['c'];
$orders = mysqli_query($conn,"SELECT o.*, b.title AS book_title, b.book_image FROM orders o JOIN books b ON o.book_id=b.id WHERE o.user_id=$user_id ORDER BY o.id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Dashboard | E-Library</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
:root{--gold:#c9a84c;--gold-light:#e8c96a;--surface:#141920;--surface2:#1c2333;--border:rgba(255,255,255,0.07);--muted:rgba(255,255,255,0.38);}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
body{background:#0d0d0d;color:#f0ece4;font-family:'DM Sans',sans-serif;min-height:100vh;}
.topbar{background:var(--surface);border-bottom:1px solid var(--border);height:64px;display:flex;align-items:center;justify-content:space-between;padding:0 30px;position:sticky;top:0;z-index:100;}
.topbar-brand{font-family:'Cormorant Garamond',serif;font-size:1.4rem;font-weight:700;color:#fff;text-decoration:none;}
.topbar-brand span{color:var(--gold);}
.topbar-right{display:flex;align-items:center;gap:12px;}
.topbar-user{font-size:0.82rem;color:var(--muted);}
.topbar-user strong{color:#fff;}
.btn-logout{background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.2);color:#f87171;font-size:0.72rem;font-weight:700;padding:7px 14px;border-radius:6px;text-decoration:none;}
.btn-home{background:rgba(201,168,76,0.08);border:1px solid rgba(201,168,76,0.2);color:var(--gold);font-size:0.72rem;font-weight:700;padding:7px 14px;border-radius:6px;text-decoration:none;}
.dash-wrap{max-width:1100px;margin:0 auto;padding:32px 24px;}
.profile-card{background:var(--surface);border:1px solid var(--border);border-radius:14px;padding:28px 32px;display:flex;align-items:center;gap:24px;margin-bottom:24px;position:relative;overflow:hidden;}
.profile-card::before{content:'';position:absolute;top:-60px;right:-60px;width:220px;height:220px;background:radial-gradient(circle,rgba(201,168,76,0.06) 0%,transparent 70%);pointer-events:none;}
.avatar{width:72px;height:72px;border-radius:14px;background:rgba(201,168,76,0.1);border:2px solid rgba(201,168,76,0.25);display:flex;align-items:center;justify-content:center;font-family:'Cormorant Garamond',serif;font-size:2rem;font-weight:700;color:var(--gold);flex-shrink:0;}
.profile-info h2{font-family:'Cormorant Garamond',serif;font-size:1.7rem;font-weight:700;color:#fff;margin-bottom:3px;}
.profile-info .email{font-size:0.78rem;color:var(--muted);}
.profile-info .since{font-size:0.66rem;color:rgba(255,255,255,0.2);text-transform:uppercase;letter-spacing:0.07em;margin-top:4px;}
.stat-strip{display:flex;gap:1px;background:var(--border);border-radius:10px;overflow:hidden;margin-left:auto;flex-shrink:0;}
.stat-box{background:var(--surface2);padding:14px 22px;text-align:center;}
.stat-num{font-family:'Cormorant Garamond',serif;font-size:1.5rem;font-weight:700;color:var(--gold);line-height:1;}
.stat-lbl{font-size:0.6rem;color:var(--muted);margin-top:2px;text-transform:uppercase;letter-spacing:0.07em;}
.pills-row{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:24px;}
.pill-card{background:var(--surface);border:1px solid var(--border);border-radius:10px;padding:16px 18px;display:flex;align-items:center;gap:14px;}
.pill-icon{width:38px;height:38px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:0.9rem;flex-shrink:0;}
.pill-num{font-family:'Cormorant Garamond',serif;font-size:1.6rem;font-weight:700;color:#fff;line-height:1;}
.pill-lbl{font-size:0.65rem;color:var(--muted);margin-top:2px;font-weight:600;}
.tabs{display:flex;gap:4px;background:var(--surface);border:1px solid var(--border);border-radius:10px;padding:5px;margin-bottom:20px;}
.tab-btn{flex:1;padding:10px;border:none;background:transparent;color:var(--muted);font-size:0.78rem;font-weight:600;cursor:pointer;border-radius:7px;transition:all 0.2s;font-family:'DM Sans',sans-serif;display:flex;align-items:center;justify-content:center;gap:7px;}
.tab-btn.active{background:var(--surface2);color:#fff;border:1px solid var(--border);}
.tab-panel{display:none;}
.tab-panel.active{display:block;}
.order-card{background:var(--surface);border:1px solid var(--border);border-radius:12px;overflow:hidden;margin-bottom:14px;transition:border-color 0.2s,transform 0.2s;}
.order-card:hover{border-color:rgba(201,168,76,0.2);transform:translateY(-2px);}
.order-top{display:grid;grid-template-columns:auto 1fr auto;gap:14px;padding:18px 20px;align-items:center;}
.book-thumb{width:48px;height:62px;border-radius:5px;object-fit:cover;border:1px solid var(--border);flex-shrink:0;}
.book-title{font-weight:700;color:#fff;font-size:0.88rem;margin-bottom:4px;font-family:'Cormorant Garamond',serif;}
.order-meta{font-size:0.7rem;color:var(--muted);display:flex;align-items:center;gap:8px;flex-wrap:wrap;}
.type-badge{background:rgba(99,102,241,0.1);color:#818cf8;border:1px solid rgba(99,102,241,0.2);padding:2px 8px;border-radius:20px;font-size:0.6rem;font-weight:700;text-transform:uppercase;}
.oid{text-align:right;}
.oid-lbl{font-size:0.6rem;color:var(--muted);letter-spacing:0.08em;text-transform:uppercase;margin-bottom:2px;}
.oid-val{font-family:'Cormorant Garamond',serif;font-size:1rem;font-weight:700;color:var(--gold);}
.progress-wrap{padding:2px 20px 18px;}
.steps{display:flex;align-items:flex-start;}
.step{display:flex;flex-direction:column;align-items:center;flex:1;z-index:2;}
.sc{width:30px;height:30px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.72rem;border:2px solid;flex-shrink:0;}
.sl{font-size:0.58rem;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;margin-top:6px;text-align:center;line-height:1.35;}
.step.inactive .sc{background:var(--surface2);border-color:rgba(255,255,255,0.1);color:rgba(255,255,255,0.15);}
.step.inactive .sl{color:rgba(255,255,255,0.18);}
.step.active .sc{background:rgba(201,168,76,0.12);border-color:var(--gold);color:var(--gold);}
.step.active .sl{color:var(--gold);}
.step.done .sc{background:#4a7a59;border-color:#4a7a59;color:#fff;}
.step.done .sl{color:#4a7a59;}
.sline{flex:1;height:2px;margin:0 -2px;position:relative;top:15px;z-index:1;}
.sline.off{background:rgba(255,255,255,0.07);}
.sline.on{background:#4a7a59;}
.order-bot{display:flex;justify-content:space-between;align-items:center;padding:10px 20px;background:rgba(0,0,0,0.2);border-top:1px solid var(--border);flex-wrap:wrap;gap:8px;}
.price-txt{font-size:0.78rem;color:var(--muted);}
.price-txt strong{color:var(--gold);font-family:'Cormorant Garamond',serif;font-size:1rem;}
.pay-pill{display:inline-flex;align-items:center;gap:5px;padding:3px 10px;border-radius:20px;font-size:0.62rem;font-weight:700;text-transform:uppercase;}
.pay-ok{background:rgba(74,122,89,0.1);color:#4a7a59;border:1px solid rgba(74,122,89,0.25);}
.pay-no{background:rgba(245,158,11,0.1);color:#f59e0b;border:1px solid rgba(245,158,11,0.25);}
.read-link{background:rgba(74,122,89,0.12);color:#4a7a59;border:1px solid rgba(74,122,89,0.28);padding:5px 12px;border-radius:6px;font-size:0.7rem;font-weight:700;text-decoration:none;}
.read-link:hover{background:#4a7a59;color:#fff;}
.form-card{background:var(--surface);border:1px solid var(--border);border-radius:12px;padding:28px;}
.form-card h3{font-family:'Cormorant Garamond',serif;font-size:1.15rem;font-weight:700;color:#fff;margin-bottom:4px;}
.form-card .sub{font-size:0.74rem;color:var(--muted);margin-bottom:20px;padding-bottom:16px;border-bottom:1px solid var(--border);}
.fgrid{display:grid;grid-template-columns:1fr 1fr;gap:14px;}
.fg{display:flex;flex-direction:column;gap:6px;}
.fg.full{grid-column:span 2;}
.fg label{font-size:0.63rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:var(--muted);}
.fg input,.fg textarea{background:var(--surface2);border:1px solid var(--border);border-radius:7px;padding:10px 13px;color:#fff;font-size:0.83rem;font-family:'DM Sans',sans-serif;outline:none;transition:border-color 0.2s;width:100%;}
.fg input:focus,.fg textarea:focus{border-color:rgba(201,168,76,0.4);}
.fg input::placeholder,.fg textarea::placeholder{color:rgba(255,255,255,0.15);}
.fg .ro{opacity:0.45;cursor:not-allowed;}
.fg textarea{resize:vertical;min-height:75px;}
.btn-save{background:var(--gold);color:#0d0d0d;border:none;padding:11px 26px;border-radius:7px;font-size:0.82rem;font-weight:700;cursor:pointer;font-family:'DM Sans',sans-serif;margin-top:18px;display:inline-flex;align-items:center;gap:7px;}
.btn-save:hover{background:var(--gold-light);}
.alert-ok{background:rgba(74,122,89,0.08);border:1px solid rgba(74,122,89,0.25);color:#4a7a59;padding:11px 15px;border-radius:8px;margin-bottom:16px;font-size:0.8rem;font-weight:600;display:flex;align-items:center;gap:8px;}
.alert-err{background:rgba(239,68,68,0.07);border:1px solid rgba(239,68,68,0.18);color:#f87171;padding:11px 15px;border-radius:8px;margin-bottom:16px;font-size:0.8rem;font-weight:600;display:flex;align-items:center;gap:8px;}
.sbar{height:3px;border-radius:3px;background:var(--border);margin-top:5px;overflow:hidden;}
.sfill{height:100%;border-radius:3px;transition:width 0.3s,background 0.3s;width:0%;}
.empty{text-align:center;padding:60px 20px;color:var(--muted);}
.empty i{font-size:2.8rem;display:block;margin-bottom:14px;opacity:0.2;color:var(--gold);}
.empty h4{font-family:'Cormorant Garamond',serif;font-size:1.4rem;color:rgba(255,255,255,0.25);margin-bottom:10px;}
.empty a{display:inline-block;padding:10px 24px;background:var(--gold);color:#0d0d0d;border-radius:6px;text-decoration:none;font-size:0.76rem;font-weight:700;}
.note{background:rgba(201,168,76,0.05);border:1px solid rgba(201,168,76,0.15);border-radius:8px;padding:11px 15px;margin-bottom:20px;font-size:0.76rem;color:rgba(255,255,255,0.4);display:flex;align-items:center;gap:9px;}
.note i{color:var(--gold);flex-shrink:0;}
@media(max-width:700px){
    .pills-row{grid-template-columns:1fr 1fr;}
    .profile-card{flex-direction:column;text-align:center;}
    .stat-strip{margin-left:0;width:100%;}
    .fgrid{grid-template-columns:1fr;}
    .fg.full{grid-column:span 1;}
    .order-top{grid-template-columns:auto 1fr;}
    .oid{display:none;}
}
</style>
</head>
<body>


<div class="dash-wrap">

    <div class="profile-card">
        <div class="avatar"><?php echo strtoupper(substr($user['name'],0,1)); ?></div>
        <div class="profile-info">
            <h2><?php echo htmlspecialchars($user['name']); ?></h2>
            <div class="email"><i class="fa-regular fa-envelope" style="margin-right:5px;opacity:0.5;"></i><?php echo htmlspecialchars($user['email']); ?></div>
            <div class="since"><i class="fa-regular fa-calendar" style="margin-right:4px;"></i>Member since <?php echo date('F Y',strtotime($user['created_at'])); ?></div>
        </div>
        <div class="stat-strip">
            <div class="stat-box"><div class="stat-num"><?php echo $total_orders; ?></div><div class="stat-lbl">Orders</div></div>
            <div class="stat-box"><div class="stat-num"><?php echo $total_books; ?></div><div class="stat-lbl">Books</div></div>
            <div class="stat-box"><div class="stat-num"><?php echo $total_essays; ?></div><div class="stat-lbl">Essays</div></div>
        </div>
    </div>

    <div class="pills-row">
        <div class="pill-card"><div class="pill-icon" style="background:rgba(245,158,11,0.08);border:1px solid rgba(245,158,11,0.18);color:#f59e0b;"><i class="fa-solid fa-clock"></i></div><div><div class="pill-num"><?php echo $c_pending; ?></div><div class="pill-lbl">Pending</div></div></div>
        <div class="pill-card"><div class="pill-icon" style="background:rgba(99,102,241,0.08);border:1px solid rgba(99,102,241,0.18);color:#818cf8;"><i class="fa-solid fa-circle-check"></i></div><div><div class="pill-num"><?php echo $c_confirmed; ?></div><div class="pill-lbl">Confirmed</div></div></div>
        <div class="pill-card"><div class="pill-icon" style="background:rgba(201,168,76,0.08);border:1px solid rgba(201,168,76,0.18);color:#c9a84c;"><i class="fa-solid fa-truck"></i></div><div><div class="pill-num"><?php echo $c_dispatch; ?></div><div class="pill-lbl">Dispatched</div></div></div>
        <div class="pill-card"><div class="pill-icon" style="background:rgba(74,122,89,0.08);border:1px solid rgba(74,122,89,0.18);color:#4a7a59;"><i class="fa-solid fa-box-open"></i></div><div><div class="pill-num"><?php echo $c_delivered; ?></div><div class="pill-lbl">Delivered</div></div></div>
    </div>

    <?php if($success_msg): ?><div class="alert-ok"><i class="fa-solid fa-circle-check"></i><?php echo $success_msg; ?></div><?php endif; ?>
    <?php if($error_msg):   ?><div class="alert-err"><i class="fa-solid fa-circle-exclamation"></i><?php echo $error_msg; ?></div><?php endif; ?>

    <div class="tabs">
        <button class="tab-btn active" onclick="sw('orders',this)"><i class="fa-solid fa-bag-shopping"></i> My Orders</button>
        <button class="tab-btn" onclick="sw('profile',this)"><i class="fa-solid fa-user"></i> Edit Profile</button>
        <button class="tab-btn" onclick="sw('password',this)"><i class="fa-solid fa-lock"></i> Change Password</button>
    </div>

    <div class="tab-panel active" id="tab-orders">
        <?php if(mysqli_num_rows($orders) > 0): ?>
        <div class="note"><i class="fa-solid fa-circle-info"></i> Admin payment approve karega tab "Confirmed" dikhega. Delivery ke baad book "My Books" mein unlock ho jayegi.</div>
        <?php while($row = mysqli_fetch_assoc($orders)):
            $os_raw = strtolower(trim($row['order_status']));
            $ps_raw = strtolower(trim($row['payment_status']));
            if($os_raw==='confirmed') $os='Confirmed';
            elseif($os_raw==='dispatched') $os='Dispatched';
            elseif($os_raw==='delivered') $os='Delivered';
            else $os='Pending';
            $ps = ($ps_raw==='paid') ? 'Paid' : 'Pending';
            $s=['p'=>'inactive','c'=>'inactive','d'=>'inactive','del'=>'inactive'];
            $l=['l1'=>'off','l2'=>'off','l3'=>'off'];
            if($os==='Pending'){
                $s['p']='active';
            } elseif($os==='Confirmed'){
                $s['p']='done'; $s['c']='done'; $s['d']='active'; $l['l1']='on'; $l['l2']='on';
            } elseif($os==='Dispatched'){
                $s['p']='done'; $s['c']='done'; $s['d']='done'; $s['del']='active'; $l['l1']='on'; $l['l2']='on'; $l['l3']='on';
            } elseif($os==='Delivered'){
                $s['p']='done'; $s['c']='done'; $s['d']='done'; $s['del']='done'; $l['l1']='on'; $l['l2']='on'; $l['l3']='on';
            }
        ?>
        <div class="order-card">
            <div class="order-top">
                <img src="../uploads/covers/<?php echo htmlspecialchars($row['book_image']); ?>" class="book-thumb" onerror="this.style.display='none'">
                <div>
                    <div class="book-title"><?php echo htmlspecialchars($row['book_title']); ?></div>
                    <div class="order-meta">
                        <span class="type-badge"><?php echo htmlspecialchars($row['order_type']); ?></span>
                        <span><i class="fa-regular fa-calendar" style="margin-right:3px;opacity:0.5;"></i><?php echo date('d M Y',strtotime($row['created_at'])); ?></span>
                    </div>
                </div>
                <div class="oid">
                    <div class="oid-lbl">Order ID</div>
                    <div class="oid-val">#ORD-<?php echo str_pad($row['id'],3,'0',STR_PAD_LEFT); ?></div>
                </div>
            </div>
            <div class="progress-wrap">
                <div class="steps">
                    <div class="step <?php echo $s['p']; ?>"><div class="sc"><?php echo $s['p']==='done'?'<i class="fa-solid fa-check"></i>':'<i class="fa-solid fa-clock"></i>'; ?></div><div class="sl">Order<br>Placed</div></div>
                    <div class="sline <?php echo $l['l1']; ?>"></div>
                    <div class="step <?php echo $s['c']; ?>"><div class="sc"><?php echo $s['c']==='done'?'<i class="fa-solid fa-check"></i>':'<i class="fa-solid fa-circle-check"></i>'; ?></div><div class="sl">Payment<br>Confirmed</div></div>
                    <div class="sline <?php echo $l['l2']; ?>"></div>
                    <div class="step <?php echo $s['d']; ?>"><div class="sc"><?php echo $s['d']==='done'?'<i class="fa-solid fa-check"></i>':'<i class="fa-solid fa-truck"></i>'; ?></div><div class="sl">Order<br>Dispatched</div></div>
                    <div class="sline <?php echo $l['l3']; ?>"></div>
                    <div class="step <?php echo $s['del']; ?>"><div class="sc"><?php echo $s['del']==='done'?'<i class="fa-solid fa-check"></i>':'<i class="fa-solid fa-box-open"></i>'; ?></div><div class="sl">Delivered &<br>Unlocked</div></div>
                </div>
            </div>
            <div class="order-bot">
                <div class="price-txt">Total: <strong>Rs. <?php echo number_format($row['total_price']??0,0); ?></strong></div>
                <?php if($ps==='Paid'): ?>
                    <span class="pay-pill pay-ok"><i class="fa-solid fa-check"></i> Payment Received</span>
                <?php else: ?>
                    <span class="pay-pill pay-no"><i class="fa-solid fa-clock"></i> Payment Pending</span>
                <?php endif; ?>
                <?php if($os==='Delivered'): ?>
                    <a href="books.php" class="read-link"><i class="fa-solid fa-book-open" style="margin-right:4px;"></i>Read Book</a>
                <?php endif; ?>
            </div>
        </div>
        <?php endwhile; ?>
        <?php else: ?>
        <div class="empty"><i class="fa-solid fa-bag-shopping"></i><h4>No orders yet</h4><p style="font-size:0.78rem;margin-bottom:16px;">You haven't placed any orders yet.</p><a href="index.php">Browse Books</a></div>
        <?php endif; ?>
    </div>

    <div class="tab-panel" id="tab-profile">
        <div class="form-card">
            <h3>Personal Information</h3>
            <p class="sub">Update your name, phone and address</p>
            <form method="POST">
                <div class="fgrid">
                    <div class="fg"><label>Full Name *</label><input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required></div>
                    <div class="fg"><label>Email (Read Only)</label><input type="email" class="ro" value="<?php echo htmlspecialchars($user['email']); ?>" readonly></div>
                    <div class="fg"><label>Phone Number</label><input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone']??''); ?>" placeholder="+92 300 0000000"></div>
                    <div class="fg"><label>Member Since</label><input class="ro" value="<?php echo date('d M Y',strtotime($user['created_at'])); ?>" readonly></div>
                    <div class="fg full"><label>Default Shipping Address</label><textarea name="address" placeholder="Your delivery address..."><?php echo htmlspecialchars($user['address']??''); ?></textarea></div>
                </div>
                <button type="submit" name="update_profile" class="btn-save"><i class="fa-solid fa-floppy-disk"></i> Save Changes</button>
            </form>
        </div>
    </div>

    <div class="tab-panel" id="tab-password">
        <div class="form-card">
            <h3>Change Password</h3>
            <p class="sub">Keep your account secure</p>
            <form method="POST">
                <div class="fgrid">
                    <div class="fg full"><label>Current Password</label><input type="password" name="current_password" required placeholder="Enter current password"></div>
                    <div class="fg"><label>New Password</label><input type="password" name="new_password" required placeholder="Min 6 characters" onkeyup="checkStr(this.value)"><div class="sbar"><div class="sfill" id="sf"></div></div></div>
                    <div class="fg"><label>Confirm New Password</label><input type="password" name="confirm_password" required placeholder="Repeat new password"></div>
                </div>
                <button type="submit" name="change_password" class="btn-save"><i class="fa-solid fa-shield-halved"></i> Update Password</button>
            </form>
        </div>
    </div>

</div>

<script>
function sw(name,btn){
    document.querySelectorAll('.tab-panel').forEach(p=>p.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(b=>b.classList.remove('active'));
    document.getElementById('tab-'+name).classList.add('active');
    btn.classList.add('active');
}
function checkStr(v){
    const f=document.getElementById('sf'); let s=0;
    if(v.length>=6)s++;if(v.length>=10)s++;
    if(/[A-Z]/.test(v))s++;if(/[0-9]/.test(v))s++;if(/[^A-Za-z0-9]/.test(v))s++;
    f.style.width=(s/5*100)+'%';
    f.style.background=['#ef4444','#f59e0b','#f59e0b','#4a7a59','#4a7a59'][s-1]||'#ef4444';
}
<?php
if($success_msg && isset($_POST['change_password'])) echo "sw('password',document.querySelectorAll('.tab-btn')[2]);";
elseif($success_msg && isset($_POST['update_profile'])) echo "sw('profile',document.querySelectorAll('.tab-btn')[1]);";
elseif($error_msg && isset($_POST['change_password'])) echo "sw('password',document.querySelectorAll('.tab-btn')[2]);";
elseif($error_msg && isset($_POST['update_profile'])) echo "sw('profile',document.querySelectorAll('.tab-btn')[1]);";
?>
</script>

<?php include("footer.php"); ?>

</body>
</html>