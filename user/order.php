<?php 
ob_start(); // 1. Sab se pehle output buffering start karein
if (session_status() === PHP_SESSION_NONE) { session_start(); } 

include("../config/db.php"); 

// 2. Security Checks (Headers se pehle)
if(!isset($_SESSION['user_id'])){ 
    header("Location: login.php"); 
    exit(); 
}

if(!isset($_GET['book_id'])){ 
    header("Location: index.php"); 
    exit(); 
}

$validation_error = false;
$book_id = mysqli_real_escape_string($conn, $_GET['book_id']);
$book_query = mysqli_query($conn, "SELECT * FROM books WHERE id='$book_id'");
$book_data = mysqli_fetch_assoc($book_query);

if(!$book_data) {
    header("Location: index.php");
    exit();
}

$image_path = "../uploads/covers/" . $book_data['book_image'];

// 3. Form Processing Logic (Redirects header se pehle handle honi chahiye)
if(isset($_POST['go_to_review'])){
    $type = $_POST['type'];
    $full_name = trim($_POST['full_name']);
    $qty = ($type == 'PDF') ? 1 : (int)$_POST['qty'];
    
    if($type == 'Hard Copy'){
        $phone = trim($_POST['phone']);
        $address = trim($_POST['address']);
        if(empty($full_name) || empty($phone) || empty($address) || $qty < 1){ $validation_error = true; }
    } else {
        if(empty($full_name)){ $validation_error = true; }
        $phone = "N/A";
        $address = "Digital Delivery";
    }
    
    if(!$validation_error){
        $_SESSION['temp_order'] = [
            'book_id' => $book_id,
            'full_name' => $full_name,
            'phone' => $phone,
            'qty' => $qty,
            'address' => $address,
            'type' => $type,
            'total' => $book_data['price'] * $qty
        ];
        header("Location: review_order.php");
        exit();
    }
}

// 4. Ab Navbar include karein (Jab saare header() redirects khatam ho chukay hon)
include("navbar.php"); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order | <?php echo htmlspecialchars($book_data['title']); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    
    <style>
        :root { --gold: #c9a84c; --gold-light: #e8c96a; --surface: #141920; }
        body { background: #0d0d0d; color: #fff; font-family: 'DM Sans', sans-serif; margin:0; }
        .order-page { max-width: 900px; margin: 50px auto; padding: 0 20px; }
        .order-wrap { display: grid; grid-template-columns: 300px 1fr; background: var(--surface); border-radius: 12px; border: 1px solid rgba(255,255,255,0.08); overflow: hidden; }
        .book-side { background: #080a0d; padding: 40px; text-align: center; border-right: 1px solid rgba(255,255,255,0.05); }
        .book-side img { width: 100%; border-radius: 8px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); margin-bottom: 20px; }
        .form-side { padding: 40px; }
        .f-group { margin-bottom: 20px; }
        .f-group label { display: block; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px; color: var(--gold); margin-bottom: 8px; font-weight: 700; }
        .f-group input, .f-group textarea { width: 100%; background: #1c2333; border: 1px solid rgba(255,255,255,0.1); border-radius: 6px; padding: 12px; color: #fff; outline: none; box-sizing: border-box; }
        .f-group input:focus { border-color: var(--gold); }
        .btn-next { width: 100%; background: var(--gold); color: #000; border: none; padding: 15px; border-radius: 8px; font-weight: 700; cursor: pointer; transition: 0.3s; }
        .btn-next:hover { background: var(--gold-light); transform: translateY(-2px); }
        
        /* Format Selector */
        .format-selector { display: flex; gap: 10px; margin-bottom: 25px; }
        .format-selector input { display: none; }
        .format-selector label { flex: 1; padding: 15px; background: #1c2333; border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; text-align: center; cursor: pointer; font-size: 0.85rem; }
        .format-selector input:checked + label { border-color: var(--gold); background: rgba(201,168,76,0.1); color: var(--gold); }
        
        /* Navbar Dropdown Fix */
        .dropdown-menu { z-index: 9999 !important; background: #1c2333 !important; border: 1px solid rgba(255,255,255,0.1) !important; }
        .dropdown-item { color: #fff !important; }
        .dropdown-item:hover { background: var(--gold) !important; color: #000 !important; }
    </style>
</head>
<body>

<div class="order-page">
    <div class="order-wrap">
        <div class="book-side">
            <img src="<?php echo $image_path; ?>" alt="Book Cover">
            <h3 style="font-family:'Cormorant Garamond'"><?php echo htmlspecialchars($book_data['title']); ?></h3>
            <p style="color: var(--gold); font-weight: bold;">Rs. <?php echo $book_data['price']; ?></p>
        </div>

        <div class="form-side">
            <h2 style="margin-top:0; font-family:'Cormorant Garamond'">Order Details</h2>
            <form method="POST">
                <div class="f-group">
                    <label>Select Format</label>
                    <div class="format-selector">
                        <input type="radio" name="type" id="pdf" value="PDF" checked onclick="toggleHardCopy(false)">
                        <label for="pdf"><i class="fa-solid fa-file-pdf"></i> PDF</label>
                        
                        <input type="radio" name="type" id="hard" value="Hard Copy" onclick="toggleHardCopy(true)">
                        <label for="hard"><i class="fa-solid fa-truck"></i> Hard Copy</label>
                    </div>
                </div>

                <div class="f-group">
                    <label>Your Full Name</label>
                    <input type="text" name="full_name" required>
                </div>

                <div id="hard-copy-fields" style="display:none;">
                    <div class="f-group">
                        <label>Phone Number</label>
                        <input type="text" name="phone" id="phone_field">
                    </div>
                    <div class="f-group">
                        <label>Shipping Address</label>
                        <textarea name="address" id="address_field"></textarea>
                    </div>
                    <div class="f-group">
                        <label>Quantity</label>
                        <input type="number" name="qty" value="1" min="1">
                    </div>
                </div>

                <button type="submit" name="go_to_review" class="btn-next">Continue to Review →</button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
function toggleHardCopy(show) {
    const fields = document.getElementById('hard-copy-fields');
    const phone = document.getElementById('phone_field');
    const addr = document.getElementById('address_field');
    
    if(show) {
        fields.style.display = 'block';
        phone.required = true;
        addr.required = true;
    } else {
        fields.style.display = 'none';
        phone.required = false;
        addr.required = false;
    }
}
</script>

</body>
</html>
<?php ob_end_flush(); ?>