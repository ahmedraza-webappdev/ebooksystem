<?php
session_start();
if(!isset($_SESSION['admin'])){ header("Location: admin_login.php"); exit(); }
include("../config/db.php");

if(!isset($_GET['id'])) die("Book ID missing");
$id = (int)$_GET['id'];
$result = mysqli_query($conn,"SELECT * FROM books WHERE id='$id'");
$row = mysqli_fetch_assoc($result);
$success_msg = false;

if(isset($_POST['update'])){
    $title       = mysqli_real_escape_string($conn,$_POST['title']);
    $author      = mysqli_real_escape_string($conn,$_POST['author']);
    $category    = mysqli_real_escape_string($conn,$_POST['category']);
    $description = mysqli_real_escape_string($conn,$_POST['description']);
    $weight      = mysqli_real_escape_string($conn,$_POST['weight']);
    $is_free     = isset($_POST['is_free']) ? 1 : 0;
    $price       = ($is_free==1) ? 0 : floatval($_POST['price']);

    $pdf   = $_FILES['pdf_file']['name'];
    $image = $_FILES['book_image']['name'];
    $pdf_name   = $pdf   ? time()."_".$pdf   : $row['pdf_file'];
    $image_name = $image ? time()."_".$image : $row['book_image'];
    if($pdf)   move_uploaded_file($_FILES['pdf_file']['tmp_name'],"../uploads/pdf/".$pdf_name);
    if($image) move_uploaded_file($_FILES['book_image']['tmp_name'],"../uploads/covers/".$image_name);

    $sql="UPDATE books SET title='$title',author='$author',category='$category',description='$description',price='$price',pdf_file='$pdf_name',book_image='$image_name',weight='$weight',is_free='$is_free' WHERE id=$id";
    if(mysqli_query($conn,$sql)){
        $success_msg = true;
        $result = mysqli_query($conn,"SELECT * FROM books WHERE id='$id'");
        $row = mysqli_fetch_assoc($result);
    }
}

$page_title = "Edit Book";
$page_subtitle = "Update book information";
include("admin_header.php");
?>

<?php if($success_msg): ?>
<div class="alert-success"><i class="fa-solid fa-circle-check"></i> Book updated successfully!</div>
<?php endif; ?>

<div style="display:grid;grid-template-columns:1fr 300px;gap:24px;max-width:960px;">
    <div class="section-card" style="padding:30px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:22px;">
            <h3 style="font-size:1.1rem;font-weight:800;color:#1e293b;"><i class="fa-solid fa-pen" style="color:#6366f1;margin-right:8px;"></i>Edit Book</h3>
            <a href="books_list.php" class="btn-primary" style="background:white;color:#64748b;border:1px solid #e2e8f0;box-shadow:none;padding:8px 16px;font-size:0.8rem;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='white'">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>
        </div>

        <form method="POST" enctype="multipart/form-data">
            <!-- Free Toggle -->
            <div style="background:#eef2ff;border:1px solid #c7d2fe;border-radius:14px;padding:16px;display:flex;justify-content:space-between;align-items:center;margin-bottom:22px;">
                <div>
                    <div style="font-weight:800;color:#3730a3;font-size:0.88rem;">Price Status</div>
                    <div style="font-size:0.72rem;color:#6366f1;margin-top:2px;">Switch between Free and Paid</div>
                </div>
                <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                    <input type="checkbox" name="is_free" value="1" id="freeCheck" onchange="togglePrice()" <?php echo ($row['is_free']==1)?'checked':''; ?> style="width:18px;height:18px;accent-color:#6366f1;">
                    <span style="font-size:0.8rem;font-weight:700;color:#4f46e5;">Free</span>
                </label>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                <div style="grid-column:span 2;">
                    <label class="form-label">Book Title *</label>
                    <input type="text" name="title" required value="<?php echo htmlspecialchars($row['title']); ?>" class="form-input">
                </div>
                <div>
                    <label class="form-label">Author *</label>
                    <input type="text" name="author" required value="<?php echo htmlspecialchars($row['author']); ?>" class="form-input">
                </div>
                <div>
                    <label class="form-label">Category</label>
                    <input type="text" name="category" value="<?php echo htmlspecialchars($row['category']); ?>" class="form-input">
                </div>
                <div id="priceDiv" style="<?php echo ($row['is_free']==1)?'opacity:0.3;':''; ?>">
                    <label class="form-label">Price (₹)</label>
                    <input type="number" name="price" id="priceInput" step="0.01" value="<?php echo $row['price']; ?>" <?php echo ($row['is_free']==1)?'readonly':''; ?> class="form-input">
                </div>
                <div>
                    <label class="form-label">Weight</label>
                    <input type="text" name="weight" value="<?php echo htmlspecialchars($row['weight']); ?>" class="form-input">
                </div>
                <div style="grid-column:span 2;">
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="4" class="form-input"><?php echo htmlspecialchars($row['description']); ?></textarea>
                </div>
                <div style="border:2px dashed #e2e8f0;border-radius:14px;padding:16px;position:relative;" onmouseover="this.style.borderColor='#6366f1'" onmouseout="this.style.borderColor='#e2e8f0'">
                    <input type="file" name="book_image" id="imgInput" style="position:absolute;inset:0;opacity:0;cursor:pointer;" onchange="previewImage(this)">
                    <div style="font-size:0.7rem;font-weight:800;color:#94a3b8;text-transform:uppercase;margin-bottom:4px;">Update Cover</div>
                    <div style="font-size:0.78rem;color:#64748b;">Click to change image</div>
                </div>
                <div style="border:2px dashed #e2e8f0;border-radius:14px;padding:16px;position:relative;" onmouseover="this.style.borderColor='#6366f1'" onmouseout="this.style.borderColor='#e2e8f0'">
                    <input type="file" name="pdf_file" style="position:absolute;inset:0;opacity:0;cursor:pointer;">
                    <div style="font-size:0.7rem;font-weight:800;color:#94a3b8;text-transform:uppercase;margin-bottom:4px;">Update PDF</div>
                    <div style="font-size:0.78rem;color:#64748b;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?php echo $row['pdf_file']; ?></div>
                </div>
            </div>
            <button name="update" style="width:100%;background:linear-gradient(135deg,#6366f1,#4f46e5);color:white;padding:14px;border-radius:14px;font-weight:800;font-size:0.95rem;border:none;cursor:pointer;box-shadow:0 4px 15px rgba(99,102,241,0.3);margin-top:20px;">
                <i class="fa-solid fa-floppy-disk mr-2"></i> Save Changes
            </button>
        </form>
    </div>

    <div class="section-card" style="padding:22px;text-align:center;align-self:start;position:sticky;top:20px;">
        <div style="font-size:0.68rem;font-weight:800;color:#94a3b8;text-transform:uppercase;letter-spacing:2px;margin-bottom:14px;">Cover Preview</div>
        <div style="width:100%;aspect-ratio:3/4;background:#f8fafc;border-radius:14px;overflow:hidden;border:1px solid #e2e8f0;margin-bottom:14px;">
            <img id="previewImg" src="../uploads/covers/<?php echo $row['book_image']; ?>" style="width:100%;height:100%;object-fit:cover;">
        </div>
    </div>
</div>

<script>
function previewImage(input){
    if(input.files && input.files[0]){
        const r = new FileReader();
        r.onload = e => { document.getElementById('previewImg').src = e.target.result; };
        r.readAsDataURL(input.files[0]);
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
