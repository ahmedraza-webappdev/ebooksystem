<?php
session_start();
if(!isset($_SESSION['admin'])){ header("Location: admin_login.php"); exit(); }
include("../config/db.php");
date_default_timezone_set('Asia/Karachi');

// ... (Baaki INSERT aur DELETE logic purana hi rahega) ...

if(isset($_POST['add_comp'])){
    $title = mysqli_real_escape_string($conn,$_POST['title']);
    $desc  = mysqli_real_escape_string($conn,$_POST['description']);
    $s_date= $_POST['start_date'];
    $e_date= $_POST['end_date'];
    $prize = mysqli_real_escape_string($conn,$_POST['prize']);
    $q = "INSERT INTO competitions (title,description,start_date,end_date,prize) VALUES ('$title','$desc','$s_date','$e_date','$prize')";
    if(mysqli_query($conn,$q)) { header("Location: manage_competitions.php?msg=added"); exit(); }
}

if(isset($_GET['delete_id'])){
    $id = mysqli_real_escape_string($conn,$_GET['delete_id']);
    mysqli_query($conn,"DELETE FROM competitions WHERE id='$id'");
    header("Location: manage_competitions.php?msg=deleted"); exit();
}

$page_title = "Competitions";
$page_subtitle = "Create and manage writing competitions";
include("admin_header.php");
$comps = mysqli_query($conn,"SELECT * FROM competitions ORDER BY id DESC");
?>

<style>
    /* Dark Theme Input Fixes */
    .form-label { color: var(--gold); font-size: 0.75rem; font-weight: 700; text-transform: uppercase; margin-bottom: 5px; display: block; }
    .form-input { 
        background: #0d0d0d !important; 
        border: 1px solid var(--border) !important; 
        color: #e2e8f0 !important; 
        padding: 10px; 
        border-radius: 8px; 
        width: 100%; 
        outline: none;
    }
    .form-input:focus { border-color: var(--gold) !important; }
    
    /* Table Text Colors */
    .comp-title-text { color: #ffffff !important; font-weight: 700; font-size: 0.875rem; }
    .comp-desc-text { color: #94a3b8 !important; font-size: 0.72rem; margin-top: 2px; }
    .comp-date-text { color: #cbd5e1 !important; font-size: 0.78rem; font-weight: 600; }
    
    /* Prize Badge Fix */
    .prize-badge { background: rgba(201,168,76,0.15) !important; color: var(--gold) !important; border: 1px solid rgba(201,168,76,0.3); padding: 4px 10px; border-radius: 20px; font-size: 0.72rem; font-weight: 800; }
</style>

<div style="display:grid;grid-template-columns:380px 1fr;gap:24px;">
    <!-- LEFT SIDE: FORM -->
    <div class="section-card" style="padding:28px; align-self:start; background: var(--dark2);">
        <h3 style="font-size:1.05rem; font-weight:800; color:var(--gold); margin-bottom:20px;">
            <i class="fa-solid fa-plus-circle" style="margin-right:8px;"></i>Add New Competition
        </h3>
        
        <form method="POST" style="display:flex; flex-direction:column; gap:14px;">
            <div>
                <label class="form-label">Title *</label>
                <select name="title" id="comp_title" required class="form-input" onchange="updateDetails()">
                    <option value="" disabled selected>Select Competition Type</option>
                    <option value="Essay Writing">Essay Writing</option>
                    <option value="Story Writing">Story Writing</option>
                    <option value="Custom">Other (Custom)</option>
                </select>
            </div>

            <div>
                <label class="form-label">Description *</label>
                <textarea name="description" id="comp_desc" required class="form-input" rows="4" placeholder="Description will auto-fill..."></textarea>
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                <div>
                    <label class="form-label">Start Date</label>
                    <input type="date" name="start_date" required class="form-input">
                </div>
                <div>
                    <label class="form-label">End Date</label>
                    <input type="date" name="end_date" required class="form-input">
                </div>
            </div>

            <div>
                <label class="form-label">Prize Details</label>
                <input type="text" name="prize" id="comp_prize" class="form-input" placeholder="e.g. 1st: 5000 PKR">
            </div>

            <button name="add_comp" class="btn-primary" style="width:100%; justify-content:center; padding:13px; margin-top:10px;">
                <i class="fa-solid fa-trophy mr-2"></i> Create Competition
            </button>
        </form>
    </div>

    <!-- RIGHT SIDE: TABLE -->
    <div class="section-card">
        <div class="section-header">
            <h3 style="color:var(--gold);"><i class="fa-solid fa-list-ul" style="margin-right:8px;"></i>Active Competitions</h3>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Prize</th>
                    <th>Dates</th>
                    <th style="text-align:center;">Action</th>
                </tr>
            </thead>
            <tbody>
            <?php if(mysqli_num_rows($comps)>0): ?>
            <?php while($row=mysqli_fetch_assoc($comps)): ?>
                <tr>
                    <td>
                        <div class="comp-title-text"><?php echo htmlspecialchars($row['title']); ?></div>
                        <div class="comp-desc-text"><?php echo htmlspecialchars(substr($row['description'],0,60)).'...'; ?></div>
                    </td>
                    <td><span class="prize-badge"><?php echo htmlspecialchars($row['prize']); ?></span></td>
                    <td>
                        <div class="comp-date-text"><?php echo date('d M',strtotime($row['start_date'])); ?> – <?php echo date('d M, Y',strtotime($row['end_date'])); ?></div>
                    </td>
                    <td style="text-align:center;">
                        <button onclick="confirmDelete(<?php echo $row['id']; ?>)" style="color:#ef4444; background:rgba(239,68,68,0.1); border:1px solid rgba(239,68,68,0.2); padding:8px 12px; border-radius:8px; cursor:pointer;"><i class="fa-solid fa-trash"></i></button>
                    </td>
                </tr>
            <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="4" style="text-align:center;padding:40px;color:var(--muted);">No competitions found.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
// Auto-fill Logic
function updateDetails() {
    const title = document.getElementById('comp_title').value;
    const descField = document.getElementById('comp_desc');
    const prizeField = document.getElementById('comp_prize');

    if (title === "Essay Writing") {
        descField.value = "Submit a compelling essay (500-800 words) on social impact; focus on originality and clear structure.";
        prizeField.value = "1st: 5000, 2nd: 3000, 3rd: 1000 PKR";
    } else if (title === "Story Writing") {
        descField.value = "Craft a unique short story (1000-1500 words) with a captivating plot; focus on character depth and flow.";
        prizeField.value = "1st: 10000, 2nd: 5000, 3rd: 2000 PKR";
    } else {
        descField.value = "";
        prizeField.value = "";
    }
}

function confirmDelete(id){
    Swal.fire({ 
        title:'Are you sure?', 
        text:'This competition will be removed!', 
        icon:'warning', 
        showCancelButton:true, 
        confirmButtonColor:'#ef4444', 
        cancelButtonColor: '#4a5568',
        confirmButtonText:'Yes, delete!',
        background: '#141920',
        color: '#fff'
    })
    .then(r => { if(r.isConfirmed) window.location.href='manage_competitions.php?delete_id='+id; });
}
</script>

<?php include("admin_footer.php"); ?>