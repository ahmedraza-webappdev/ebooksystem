<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// 1. Connection check karein
include("../config/db.php");
if (!$conn) { die("Connection failed: " . mysqli_connect_error()); }

if(!isset($_SESSION['temp_order'])){ 
    header("Location: index.php"); 
    exit(); 
}

$order = $_SESSION['temp_order'];
$book_id = $order['book_id'];
$order_type = $order['type'];

$book_res = mysqli_query($conn, "SELECT * FROM books WHERE id='$book_id'");
$book_data = mysqli_fetch_assoc($book_res);

$error_msg = '';
if(isset($_POST['confirm_final'])){
    // 2. Session check karein
    if(!isset($_SESSION['user_id'])){
        die("Error: User Session Expired! Please login again.");
    }
    
    $u_id = $_SESSION['user_id'];
    $payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);
    $total = $order['total'];
    $screenshot_name = NULL;

    if($payment_method !== 'Cash on Delivery'){
        if(!isset($_FILES['payment_screenshot']) || $_FILES['payment_screenshot']['error'] != 0){
            $error_msg = "Payment screenshot upload karna zaroori hai!";
        } else {
            $target_dir = "../uploads/payments/";
            if (!is_dir($target_dir)) { mkdir($target_dir, 0777, true); }
            $file_ext = pathinfo($_FILES["payment_screenshot"]["name"], PATHINFO_EXTENSION);
            $screenshot_name = "PAY_" . time() . "_" . $u_id . "." . $file_ext;
            move_uploaded_file($_FILES["payment_screenshot"]["tmp_name"], $target_dir . $screenshot_name);
        }
    }

    if($error_msg == ''){
        $addr = "Name: ".$order['full_name']." | Phone: ".$order['phone']." | Addr: ".$order['address'];
        $addr = mysqli_real_escape_string($conn, $addr);

        // 3. Query ko variable mein save karke print karwayein agar fail ho
        $sql = "INSERT INTO orders (user_id, book_id, order_type, shipping_address, total_price, payment_status, payment_proof, order_status, created_at) 
                VALUES ('$u_id', '$book_id', '$order_type', '$addr', '$total', 'Pending', '$screenshot_name', 'Pending', NOW())";
        
        if(mysqli_query($conn, $sql)){
            $_SESSION['last_order_id'] = mysqli_insert_id($conn);
            unset($_SESSION['temp_order']); 
            header("Location: success.php");
            exit();
        } else {
            // YAHAN ERROR MILEGA
            echo "<div style='background:white; color:red; padding:20px; border:5px solid red;'>";
            echo "<h3>SQL Query Failed!</h3>";
            echo "<b>Error:</b> " . mysqli_error($conn) . "<br><br>";
            echo "<b>Your Query:</b> " . $sql;
            echo "</div>";
            exit();
        }
    }
}
// ... Baqi niche ka HTML same ...
include("navbar.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Order | E-Library</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --gold: #c9a84c; --gold-light: #e8c96a; }
        body { background: #0d0d0d; color: #fff; font-family: 'DM Sans', sans-serif; }
        .review-page{max-width:720px;margin:0 auto;padding:48px 30px;}
        .review-page h1{font-family:'Cormorant Garamond',serif;font-size:2rem;font-weight:700;color:#fff;margin-bottom:6px;}
        .review-page .sub{font-size:0.78rem;color:rgba(255,255,255,0.38);margin-bottom:32px;}
        .rcard{background:#141920;border:1px solid rgba(255,255,255,0.07);border-radius:10px;overflow:hidden;margin-bottom:20px;}
        .rcard-head{padding:16px 22px;border-bottom:1px solid rgba(255,255,255,0.07);font-size:0.65rem;letter-spacing:0.18em;text-transform:uppercase;color:rgba(255,255,255,0.28);font-weight:700;}
        .rcard-body{padding:20px 22px;}
        .detail-row{display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid rgba(255,255,255,0.04);}
        .detail-row:last-child{border-bottom:none;}
        .detail-label{font-size:0.78rem;color:rgba(255,255,255,0.38);}
        .detail-val{font-size:0.84rem;font-weight:500;color:#fff;text-align:right;}
        .detail-row.total .detail-label{font-family:'Cormorant Garamond',serif;font-size:1rem;font-weight:700;color:#fff;}
        .detail-row.total .detail-val{font-family:'Cormorant Garamond',serif;font-size:1.4rem;font-weight:700;color:var(--gold);}
        .badge-type{background:rgba(201,168,76,0.1);border:1px solid rgba(201,168,76,0.2);color:var(--gold);font-size:0.68rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;padding:3px 10px;border-radius:3px;}
        .pay-grid{display:grid;gap:10px;margin-top:4px;}
        .pay-option{display:none;}
        .pay-label{display:flex;align-items:center;gap:12px;background:#1c2333;border:1px solid rgba(255,255,255,0.07);border-radius:7px;padding:14px 16px;cursor:pointer;transition:all 0.2s;}
        .pay-option:checked + .pay-label{background:rgba(201,168,76,0.08);border-color:rgba(201,168,76,0.3);}
        .pay-icon{width:36px;height:36px;border-radius:5px;display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0;}
        .pay-name{font-size:0.84rem;font-weight:600;color:#fff;}
        .pay-sub{font-size:0.72rem;color:rgba(255,255,255,0.35);}
        .radio-dot{width:16px;height:16px;border-radius:50%;border:2px solid rgba(255,255,255,0.2);margin-left:auto;transition:all 0.2s;flex-shrink:0;}
        .pay-option:checked + .pay-label .radio-dot{border-color:var(--gold);background:var(--gold);}
        .btn-row{display:grid;grid-template-columns:1fr 1.8fr;gap:12px;margin-top:24px;}
        .btn-edit{background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);color:rgba(255,255,255,0.5);font-size:0.8rem;font-weight:600;padding:13px;border-radius:7px;text-decoration:none;text-align:center;transition:all 0.2s;}
        .btn-place{background:var(--gold);color:#0d0d0d;border:none;font-size:0.84rem;font-weight:700;padding:13px;border-radius:7px;cursor:pointer;transition:background 0.2s;letter-spacing:0.04em;}
        .btn-place:hover{background:var(--gold-light);}
        .dropdown-menu { z-index: 9999 !important; background: #1c2333 !important; border: 1px solid rgba(255,255,255,0.1); }
        .dropdown-item { color: #fff; }
        .pay-info-card{background:rgba(201,168,76,0.05);border:1px solid rgba(201,168,76,0.2);border-radius:10px;padding:20px 22px;margin-bottom:20px;}
        .pay-info-title{font-family:'Cormorant Garamond',serif;font-size:1.1rem;font-weight:700;color:var(--gold);margin-bottom:14px;display:flex;align-items:center;gap:8px;}
        .pay-numbers{display:grid;gap:10px;}
        .pay-num-row{background:#1c2333;border:1px solid rgba(255,255,255,0.07);border-radius:7px;padding:14px 16px;display:flex;align-items:center;justify-content:space-between;gap:12px;}
        .pay-num-left{display:flex;align-items:center;gap:12px;}
        .pay-num-icon{width:36px;height:36px;border-radius:6px;background:rgba(201,168,76,0.1);display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0;}
        .pay-num-label{font-size:0.7rem;color:rgba(255,255,255,0.35);margin-bottom:2px;text-transform:uppercase;letter-spacing:0.06em;}
        .pay-num-val{font-size:0.95rem;font-weight:700;color:#fff;letter-spacing:0.04em;}
        .copy-btn{background:rgba(201,168,76,0.1);border:1px solid rgba(201,168,76,0.2);color:var(--gold);font-size:0.65rem;font-weight:700;padding:5px 10px;border-radius:5px;cursor:pointer;text-transform:uppercase;letter-spacing:0.06em;transition:all 0.2s;white-space:nowrap;}
        .copy-btn:hover{background:var(--gold);color:#0d0d0d;}
        .pay-note{font-size:0.74rem;color:rgba(255,255,255,0.35);margin-top:14px;padding-top:12px;border-top:1px solid rgba(255,255,255,0.06);display:flex;align-items:flex-start;gap:7px;line-height:1.6;}
        .pay-note i{color:var(--gold);margin-top:2px;flex-shrink:0;}
        .sent-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:4px;}
        .upload-wrap{margin-top:16px;}
        .upload-label-txt{font-size:0.65rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:rgba(255,255,255,0.35);margin-bottom:8px;display:block;}
        .upload-box{border:1.5px dashed rgba(201,168,76,0.25);border-radius:8px;padding:22px;text-align:center;transition:all 0.2s;position:relative;background:rgba(201,168,76,0.02);}
        .upload-box:hover{border-color:rgba(201,168,76,0.5);background:rgba(201,168,76,0.04);}
        .upload-box input[type=file]{position:absolute;inset:0;opacity:0;cursor:pointer;width:100%;height:100%;}
        .upload-icon{font-size:1.6rem;color:var(--gold);opacity:0.5;margin-bottom:6px;}
        .upload-txt{font-size:0.78rem;color:rgba(255,255,255,0.35);}
        .upload-txt strong{color:var(--gold);}
        .preview-img{max-width:100%;max-height:180px;border-radius:6px;margin-top:12px;display:none;border:1px solid rgba(201,168,76,0.2);}
        /* ✅ Error styles */
        .error-alert{background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.3);border-radius:7px;padding:12px 16px;margin-bottom:16px;display:flex;align-items:center;gap:10px;font-size:0.8rem;color:#f87171;}
        .upload-box.has-error{border-color:rgba(239,68,68,0.5) !important;background:rgba(239,68,68,0.04) !important;}
    </style>
</head>
<body>
<div class="review-page">
  <h1>Review Your Order</h1>
  <p class="sub">Check all details before placing your order</p>

  <div class="rcard">
    <div class="rcard-head">Order Details</div>
    <div class="rcard-body">
      <div class="detail-row"><span class="detail-label">Book</span><span class="detail-val"><?php echo htmlspecialchars($book_data['title']); ?></span></div>
      <div class="detail-row"><span class="detail-label">Customer</span><span class="detail-val"><?php echo htmlspecialchars($order['full_name']); ?></span></div>
      <?php if($order_type == 'Hard Copy'): ?>
      <div class="detail-row"><span class="detail-label">Phone</span><span class="detail-val"><?php echo htmlspecialchars($order['phone']); ?></span></div>
      <div class="detail-row"><span class="detail-label">Ship To</span><span class="detail-val" style="max-width:300px;"><?php echo htmlspecialchars($order['address']); ?></span></div>
      <?php endif; ?>
      <div class="detail-row"><span class="detail-label">Format</span><span class="detail-val"><span class="badge-type"><?php echo $order_type; ?></span></span></div>
      <div class="detail-row total"><span class="detail-label">Total Amount</span><span class="detail-val">Rs. <?php echo number_format($order['total'], 0); ?></span></div>
    </div>
  </div>

  <div class="pay-info-card">
    <div class="pay-info-title"><i class="fa-solid fa-money-bill-transfer"></i> Send Payment To</div>
    <div class="pay-numbers">
      <div class="pay-num-row">
        <div class="pay-num-left">
          <div class="pay-num-icon">📱</div>
          <div>
            <div class="pay-num-label">EasyPaisa / JazzCash</div>
            <div class="pay-num-val">0317-0010116</div>
          </div>
        </div>
        <button class="copy-btn" type="button" onclick="copyNum('03170010116', this)"><i class="fa-regular fa-copy"></i> Copy</button>
      </div>
      <div class="pay-num-row">
        <div class="pay-num-left">
          <div class="pay-num-icon">💳</div>
          <div>
            <div class="pay-num-label">EasyPaisa / JazzCash</div>
            <div class="pay-num-val">0327-2127783</div>
          </div>
        </div>
        <button class="copy-btn" type="button" onclick="copyNum('03272127783', this)"><i class="fa-regular fa-copy"></i> Copy</button>
      </div>
    </div>
    <div class="pay-note">
      <i class="fa-solid fa-circle-info"></i>
      Transfer the payment to any of the numbers above and upload the screenshot. Order processing will begin after admin verification.
    </div>
  </div>

  <div class="rcard">
    <div class="rcard-head">Select Payment Method</div>
    <div class="rcard-body">

      <!-- ✅ PHP Server-side error -->
      <?php if($error_msg != ''): ?>
      <div class="error-alert">
        <i class="fa-solid fa-triangle-exclamation"></i>
        <?php echo $error_msg; ?>
      </div>
      <?php endif; ?>

      <form method="POST" enctype="multipart/form-data" id="orderForm">
        <div class="pay-grid">
          <?php if($order_type == 'Hard Copy'): ?>
          <div>
            <input type="radio" class="pay-option" name="payment_method" id="cod" value="Cash on Delivery" checked>
            <label class="pay-label" for="cod">
              <div class="pay-icon" style="background:rgba(74,124,89,0.15);">🚚</div>
              <div><div class="pay-name">Cash on Delivery</div><div class="pay-sub">Pay when you receive</div></div>
              <div class="radio-dot"></div>
            </label>
          </div>
          <?php endif; ?>
          <div>
            <input type="radio" class="pay-option" name="payment_method" id="ep" value="EasyPaisa/JazzCash" <?php echo ($order_type == 'PDF') ? 'checked' : ''; ?>>
            <label class="pay-label" for="ep">
              <div class="pay-icon" style="background:rgba(201,168,76,0.1);">💸</div>
              <div><div class="pay-name">EasyPaisa / JazzCash</div><div class="pay-sub">Mobile payment</div></div>
              <div class="radio-dot"></div>
            </label>
          </div>
        </div>

        <div class="upload-wrap" id="screenshotWrap" style="<?php echo ($order_type=='Hard Copy') ? 'display:none;' : ''; ?>">
          <span class="upload-label-txt" style="margin-top:4px;"><i class="fa-solid fa-phone" style="margin-right:5px;color:var(--gold);"></i>Kis Number Pe Bheja?</span>
          <div class="sent-grid">
            <div>
              <input type="radio" class="pay-option" name="sent_to_number" id="sn1" value="0317-0010116" checked>
              <label class="pay-label" for="sn1" style="padding:10px 14px;">
                <div style="font-size:0.75rem;font-weight:700;color:#fff;">0317-0010116</div>
                <div class="radio-dot"></div>
              </label>
            </div>
            <div>
              <input type="radio" class="pay-option" name="sent_to_number" id="sn2" value="0327-2127783">
              <label class="pay-label" for="sn2" style="padding:10px 14px;">
                <div style="font-size:0.75rem;font-weight:700;color:#fff;">0327-2127783</div>
                <div class="radio-dot"></div>
              </label>
            </div>
          </div>

          <span class="upload-label-txt" style="margin-top:14px;"><i class="fa-solid fa-image" style="margin-right:5px;color:var(--gold);"></i>Payment Screenshot Upload Karein</span>
          <div class="upload-box" id="uploadBox">
            <input type="file" name="payment_screenshot" id="ssFile" accept="image/*">
            <div class="upload-icon"><i class="fa-solid fa-cloud-arrow-up"></i></div>
            <div class="upload-txt" id="uploadTxt"><strong>Click to upload</strong> ya drag & drop karein<br>JPG, PNG, WEBP supported</div>
            <img id="ssPreview" class="preview-img">
          </div>
          <!-- ✅ JS error -->
          <div class="error-alert" id="jsError" style="display:none;margin-top:10px;">
            <i class="fa-solid fa-triangle-exclamation"></i>
            It is mandatory to upload the payment screenshot!
          </div>
        </div>

        <div class="btn-row">
          <a href="order.php?book_id=<?php echo $book_id; ?>" class="btn-edit">← Edit Details</a>
          <button name="confirm_final" type="submit" class="btn-place">Place Order Now ✓</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function copyNum(num, btn) {
    navigator.clipboard.writeText(num).then(() => {
        btn.innerHTML = '<i class="fa-solid fa-check"></i> Copied!';
        btn.style.background = 'var(--gold)';
        btn.style.color = '#0d0d0d';
        setTimeout(() => {
            btn.innerHTML = '<i class="fa-regular fa-copy"></i> Copy';
            btn.style.background = '';
            btn.style.color = '';
        }, 2000);
    });
}

document.getElementById('ssFile').addEventListener('change', function() {
    const preview   = document.getElementById('ssPreview');
    const uploadTxt = document.getElementById('uploadTxt');
    const uploadBox = document.getElementById('uploadBox');
    const jsError   = document.getElementById('jsError');

    if(this.files && this.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
            preview.style.display = 'block';
            uploadTxt.innerHTML = '<strong style="color:var(--gold);">✓ ' + this.files[0].name + '</strong>';
        };
        reader.readAsDataURL(this.files[0]);
        uploadBox.classList.remove('has-error');
        jsError.style.display = 'none';
    }
});

document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const wrap      = document.getElementById('screenshotWrap');
        const jsError   = document.getElementById('jsError');
        const uploadBox = document.getElementById('uploadBox');
        if(wrap) wrap.style.display = (this.value !== 'Cash on Delivery') ? 'block' : 'none';
        if(jsError) jsError.style.display = 'none';
        if(uploadBox) uploadBox.classList.remove('has-error');
    });
});

// ✅ MAIN FIX: Form submit pe screenshot check
document.getElementById('orderForm').addEventListener('submit', function(e) {
    const selectedPayment = document.querySelector('input[name="payment_method"]:checked');
    const ssFile    = document.getElementById('ssFile');
    const jsError   = document.getElementById('jsError');
    const uploadBox = document.getElementById('uploadBox');

    if(selectedPayment && selectedPayment.value !== 'Cash on Delivery') {
        if(!ssFile.files || ssFile.files.length === 0) {
            e.preventDefault();
            e.stopPropagation();
            uploadBox.classList.add('has-error');
            jsError.style.display = 'flex';
            jsError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return false;
        }
    }
    jsError.style.display = 'none';
    uploadBox.classList.remove('has-error');
});
</script>
</body>
</html>
<?php ob_end_flush(); ?>