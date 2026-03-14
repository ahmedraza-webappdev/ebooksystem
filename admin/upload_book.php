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

<?php if($success_msg): ?>
<div class="alert-success"><i class="fa-solid fa-circle-check"></i> Book published successfully! <a href="view_books.php" style="color:#15803d;font-weight:800;margin-left:8px;">View Books →</a></div>
<?php endif; ?>
<?php if($error_msg): ?>
<div class="alert-error"><i class="fa-solid fa-circle-exclamation"></i> <?php echo $error_msg; ?></div>
<?php endif; ?>

<div style="display:grid;grid-template-columns:1fr 300px;gap:24px;max-width:960px;">
    <div class="section-card" style="padding:30px;">
        <h3 style="font-size:1.1rem;font-weight:800;color:#1e293b;margin-bottom:22px;"><i class="fa-solid fa-book" style="color:#6366f1;margin-right:8px;"></i>Book Details</h3>

        <form method="POST" enctype="multipart/form-data">
            <!-- Free Toggle -->
            <div style="background:#eef2ff;border:1px solid #c7d2fe;border-radius:14px;padding:16px;display:flex;justify-content:space-between;align-items:center;margin-bottom:22px;">
                <div>
                    <div style="font-weight:800;color:#3730a3;font-size:0.88rem;">Mark as Free Content</div>
                    <div style="font-size:0.72rem;color:#6366f1;margin-top:2px;">Toggle to make this book freely available</div>
                </div>
                <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                    <input type="checkbox" name="is_free" value="1" id="freeCheck" onchange="togglePrice()" style="width:18px;height:18px;accent-color:#6366f1;">
                    <span style="font-size:0.8rem;font-weight:700;color:#4f46e5;">Free</span>
                </label>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                <div style="grid-column:span 2;">
                    <label class="form-label">Book Title *</label>
                    <input type="text" name="title" required class="form-input" placeholder="e.g. Advanced PHP Patterns">
                </div>
                <div>
                    <label class="form-label">Author *</label>
                    <input type="text" name="author" required class="form-input" placeholder="Author name">
                </div>
                <div>
                    <label class="form-label">Category</label>
                    <input type="text" name="category" class="form-input" placeholder="e.g. Technology">
                </div>
                <div id="priceDiv">
                    <label class="form-label">Price (₹)</label>
                    <input type="number" name="price" id="priceInput" step="0.01" class="form-input" placeholder="0.00">
                </div>
                <div>
                    <label class="form-label">Weight</label>
                    <input type="text" name="weight" class="form-input" placeholder="e.g. 500g">
                </div>
                <div style="grid-column:span 2;">
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="4" class="form-input" placeholder="Describe the book..."></textarea>
                </div>
                <div style="border:2px dashed #e2e8f0;border-radius:14px;padding:20px;text-align:center;cursor:pointer;transition:0.2s;position:relative;" onmouseover="this.style.borderColor='#6366f1'" onmouseout="this.style.borderColor='#e2e8f0'">
                    <input type="file" name="pdf_file" required style="position:absolute;inset:0;opacity:0;cursor:pointer;">
                    <i class="fa-solid fa-file-pdf" style="color:#f43f5e;font-size:1.8rem;margin-bottom:8px;display:block;"></i>
                    <div style="font-size:0.78rem;font-weight:700;color:#64748b;">Upload PDF *</div>
                    <div style="font-size:0.68rem;color:#94a3b8;margin-top:3px;">Click to browse</div>
                </div>
                <div style="border:2px dashed #e2e8f0;border-radius:14px;padding:20px;text-align:center;cursor:pointer;transition:0.2s;position:relative;" onmouseover="this.style.borderColor='#6366f1'" onmouseout="this.style.borderColor='#e2e8f0'">
                    <input type="file" name="book_image" id="imgInput" required style="position:absolute;inset:0;opacity:0;cursor:pointer;" onchange="previewImage(this)">
                    <i class="fa-solid fa-image" style="color:#3b82f6;font-size:1.8rem;margin-bottom:8px;display:block;"></i>
                    <div style="font-size:0.78rem;font-weight:700;color:#64748b;">Cover Image *</div>
                    <div style="font-size:0.68rem;color:#94a3b8;margin-top:3px;">Click to browse</div>
                </div>
            </div>
            <button name="add_book" style="width:100%;background:linear-gradient(135deg,#6366f1,#4f46e5);color:white;padding:14px;border-radius:14px;font-weight:800;font-size:0.95rem;border:none;cursor:pointer;box-shadow:0 4px 15px rgba(99,102,241,0.3);margin-top:20px;transition:0.2s;" onmouseover="this.style.transform='translateY(-1px)'" onmouseout="this.style.transform='translateY(0)'">
                <i class="fa-solid fa-plus mr-2"></i> Add to Collection
            </button>
        </form>
    </div>

    <!-- Preview -->
    <div class="section-card" style="padding:22px;text-align:center;align-self:start;position:sticky;top:20px;">
        <div style="font-size:0.68rem;font-weight:800;color:#94a3b8;text-transform:uppercase;letter-spacing:2px;margin-bottom:14px;">Cover Preview</div>
        <div style="width:100%;aspect-ratio:3/4;background:#f8fafc;border-radius:14px;overflow:hidden;border:2px dashed #e2e8f0;display:flex;align-items:center;justify-content:center;margin-bottom:14px;">
            <img id="previewImg" src="" style="width:100%;height:100%;object-fit:cover;display:none;">
            <div id="previewPlaceholder" style="text-align:center;color:#c7d2fe;">
                <i class="fa-solid fa-image" style="font-size:2.5rem;display:block;margin-bottom:10px;"></i>
                <div style="font-size:0.78rem;font-weight:600;">No Image</div>
            </div>
        </div>
        <p style="font-size:0.72rem;color:#94a3b8;font-style:italic;">This is how it appears in the library</p>
    </div>
</div>

<script>
function previewImage(input){
    if(input.files && input.files[0]){
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('previewImg').style.display = 'block';
            document.getElementById('previewPlaceholder').style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
function togglePrice(){
    const isFree = document.getElementById('freeCheck').checked;
    const priceDiv = document.getElementById('priceDiv');
    const priceInput = document.getElementById('priceInput');
    if(isFree){ priceDiv.style.opacity='0.3'; priceInput.readOnly=true; priceInput.value='0'; }
    else { priceDiv.style.opacity='1'; priceInput.readOnly=false; }
}
</script>
<?php include("admin_footer.php"); ?>
