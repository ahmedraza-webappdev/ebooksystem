<?php
session_start();
if(!isset($_SESSION['admin'])){ header("Location: admin_login.php"); exit(); }
include("../config/db.php");

$success_msg = false;
$error_msg = false;

if(isset($_POST['add_book'])){
    $title       = mysqli_real_escape_string($conn, $_POST['title']);
    $author      = mysqli_real_escape_string($conn, $_POST['author']);
    $category    = mysqli_real_escape_string($conn, $_POST['category']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $weight      = mysqli_real_escape_string($conn, $_POST['weight']);
    $is_free     = isset($_POST['is_free']) ? 1 : 0;
    $price       = ($is_free==1) ? 0 : floatval($_POST['price']);

    if(!is_dir("../uploads/pdf")) mkdir("../uploads/pdf", 0777, true);
    if(!is_dir("../uploads/covers")) mkdir("../uploads/covers", 0777, true);

    $pdf_name   = time()."_".$_FILES['pdf_file']['name'];
    $image_name = time()."_".$_FILES['book_image']['name'];

    if(move_uploaded_file($_FILES['pdf_file']['tmp_name'],"../uploads/pdf/".$pdf_name) &&
       move_uploaded_file($_FILES['book_image']['tmp_name'],"../uploads/covers/".$image_name)){
        $sql = "INSERT INTO books (title,author,category,description,price,pdf_file,book_image,weight,is_free,created_at)
                VALUES ('$title','$author','$category','$description','$price','$pdf_name','$image_name','$weight','$is_free',NOW())";
        if(mysqli_query($conn,$sql)) $success_msg = true;
        else $error_msg = "Database error: ".mysqli_error($conn);
    } else {
        $error_msg = "File upload failed.";
    }
}

$page_title = "Upload Book";
$page_subtitle = "Add a new book to the library collection";
include("admin_header.php");
?>

<style>
    /* Dark Theme Inputs */
    .form-label { color: var(--gold); font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; display: block; }
    .form-input { background: #0d0d0d !important; border: 1px solid #1e2530 !important; color: #e2e8f0 !important; padding: 12px; border-radius: 10px; width: 100%; outline: none; transition: 0.2s; }
    .form-input:focus { border-color: var(--gold) !important; box-shadow: 0 0 0 3px rgba(201,168,76,0.1); }
    
    /* Upload Boxes */
    .upload-box { border: 2px dashed #1e2530; border-radius: 14px; padding: 20px; text-align: center; cursor: pointer; transition: 0.2s; position: relative; background: #0d0d0d; }
    .upload-box:hover { border-color: var(--gold); background: rgba(201,168,76,0.02); }
    .file-name-display { font-size: 0.65rem; color: #4ade80; margin-top: 8px; font-weight: 600; display: block; word-break: break-all; }
    
    /* Free Toggle Area */
    .toggle-card { background: rgba(201,168,76,0.05); border: 1px solid rgba(201,168,76,0.2); border-radius: 14px; padding: 16px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 22px; }
    
    /* Success Alert Fix */
    .alert-success { background: rgba(34,197,94,0.1); border: 1px solid rgba(34,197,94,0.2); color: #4ade80; padding: 15px; border-radius: 12px; margin-bottom: 20px; font-weight: 600; display: flex; align-items: center; gap: 10px; }
</style>

<?php if($success_msg): ?>
<div class="alert-success"><i class="fa-solid fa-circle-check"></i> Book published successfully! <a href="view_books.php" style="color:var(--gold); font-weight:800; margin-left:8px;">View Books →</a></div>
<?php endif; ?>

<div style="display:grid; grid-template-columns:1fr 320px; gap:24px; max-width:1000px;">
    <!-- LEFT: FORM SECTION -->
    <div class="section-card" style="padding:30px; background: var(--dark2);">
        <h3 style="font-size:1.1rem; font-weight:800; color:var(--gold); margin-bottom:22px;"><i class="fa-solid fa-cloud-arrow-up" style="margin-right:10px;"></i>Book Information</h3>

        <form method="POST" enctype="multipart/form-data">
            <div class="toggle-card">
                <div>
                    <div style="font-weight:800; color:#e2e8f0; font-size:0.88rem;">Set as Free Content</div>
                    <div style="font-size:0.72rem; color:#94a3b8; margin-top:2px;">Users can download this without payment</div>
                </div>
                <label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
                    <input type="checkbox" name="is_free" value="1" id="freeCheck" onchange="togglePrice()" style="width:20px; height:20px; accent-color:var(--gold);">
                </label>
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                <div style="grid-column:span 2;">
                    <label class="form-label">Book Title *</label>
                    <input type="text" name="title" required class="form-input" placeholder="e.g. The Great Gatsby">
                </div>
                <div>
                    <label class="form-label">Author *</label>
                    <input type="text" name="author" required class="form-input" placeholder="Writer name">
                </div>
                <div>
                    <label class="form-label">Category</label>
                    <input type="text" name="category" class="form-input" placeholder="e.g. Fiction">
                </div>
                <div id="priceDiv">
                    <label class="form-label">Price (PKR)</label>
                    <input type="number" name="price" id="priceInput" step="0.01" class="form-input" placeholder="0.00">
                </div>
                <div>
                    <label class="form-label">Weight</label>
                    <input type="text" name="weight" class="form-input" placeholder="e.g. 450g">
                </div>
                <div style="grid-column:span 2;">
                    <label class="form-label">Brief Description</label>
                    <textarea name="description" rows="4" class="form-input" placeholder="What is this book about?"></textarea>
                </div>

                <!-- PDF Upload -->
                <div class="upload-box" id="pdfBox">
                    <input type="file" name="pdf_file" required style="position:absolute; inset:0; opacity:0; cursor:pointer;" onchange="updateFileName(this, 'pdfStatus')">
                    <i class="fa-solid fa-file-pdf" style="color:#f43f5e; font-size:1.8rem; margin-bottom:8px; display:block;"></i>
                    <div style="font-size:0.78rem; font-weight:700; color:#cbd5e1;">Attach PDF *</div>
                    <div id="pdfStatus" class="file-name-display" style="display:none;"></div>
                </div>

                <!-- Image Upload -->
                <div class="upload-box" id="imgBox">
                    <input type="file" name="book_image" id="imgInput" required style="position:absolute; inset:0; opacity:0; cursor:pointer;" onchange="previewImage(this); updateFileName(this, 'imgStatus')">
                    <i class="fa-solid fa-image" style="color:#3b82f6; font-size:1.8rem; margin-bottom:8px; display:block;"></i>
                    <div style="font-size:0.78rem; font-weight:700; color:#cbd5e1;">Cover Image *</div>
                    <div id="imgStatus" class="file-name-display" style="display:none;"></div>
                </div>
            </div>

            <button name="add_book" class="btn-primary" style="width:100%; padding:15px; border-radius:12px; margin-top:25px; justify-content:center; font-size:1rem;">
                <i class="fa-solid fa-check-double" style="margin-right:8px;"></i> Publish to Library
            </button>
        </form>
    </div>

    <!-- RIGHT: PREVIEW SIDEBAR -->
    <div class="section-card" style="padding:22px; text-align:center; align-self:start; position:sticky; top:20px; background: #141920;">
        <div style="font-size:0.65rem; font-weight:800; color:var(--gold); text-transform:uppercase; letter-spacing:2px; margin-bottom:20px;">Live Preview</div>
        <div style="width:100%; aspect-ratio:3/4; background:#0d0d0d; border-radius:12px; overflow:hidden; border:2px dashed #1e2530; display:flex; align-items:center; justify-content:center; margin-bottom:14px; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
            <img id="previewImg" src="" style="width:100%; height:100%; object-fit:cover; display:none;">
            <div id="previewPlaceholder" style="text-align:center; color:#2a3445;">
                <i class="fa-solid fa-book-open" style="font-size:3rem; display:block; margin-bottom:12px;"></i>
                <div style="font-size:0.8rem; font-weight:600;">No Cover Selected</div>
            </div>
        </div>
        <p style="font-size:0.7rem; color:#64748b; font-style:italic; line-height:1.5;">Ensure your cover image is high quality (Portrait mode recommended).</p>
    </div>
</div>

<script>
// File Name Display Logic
function updateFileName(input, statusId) {
    const statusDiv = document.getElementById(statusId);
    if (input.files && input.files.length > 0) {
        statusDiv.innerText = "Selected: " + input.files[0].name;
        statusDiv.style.display = 'block';
    } else {
        statusDiv.style.display = 'none';
    }
}

// Image Preview Logic
function previewImage(input){
    if(input.files && input.files[0]){
        const reader = new FileReader();
        reader.onload = e => {
            const img = document.getElementById('previewImg');
            img.src = e.target.result;
            img.style.display = 'block';
            document.getElementById('previewPlaceholder').style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Price Toggle Logic
function togglePrice(){
    const isFree = document.getElementById('freeCheck').checked;
    const priceDiv = document.getElementById('priceDiv');
    const priceInput = document.getElementById('priceInput');
    if(isFree){ 
        priceDiv.style.opacity='0.2'; 
        priceInput.readOnly=true; 
        priceInput.value='0'; 
    } else { 
        priceDiv.style.opacity='1'; 
        priceInput.readOnly=false; 
    }
}
</script>
<?php include("admin_footer.php"); ?>