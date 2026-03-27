<?php
session_start();
if(!isset($_SESSION['admin'])){ header("Location: admin_login.php"); exit(); }
include("../config/db.php");
date_default_timezone_set('Asia/Karachi');

// --- ACTION HANDLERS ---

if(isset($_POST['add_comp'])){
    $title = mysqli_real_escape_string($conn,$_POST['title']);
    // Agar custom title select kiya hai toh custom wala uthao
    if($title == "Custom") {
        $title = mysqli_real_escape_string($conn,$_POST['custom_title']);
    }
    $desc  = mysqli_real_escape_string($conn,$_POST['description']);
    $s_date= $_POST['start_date'];
    $e_date= $_POST['end_date'];
    $prize = mysqli_real_escape_string($conn,$_POST['prize']); // User jo prize likhega wahi save hoga
    
    $q = "INSERT INTO competitions (title,description,start_date,end_date,prize) VALUES ('$title','$desc','$s_date','$e_date','$prize')";
    if(mysqli_query($conn,$q)) { header("Location: manage_competitions.php?msg=added"); exit(); }
}

if(isset($_GET['delete_id'])){
    $id = mysqli_real_escape_string($conn,$_GET['delete_id']);
    mysqli_query($conn,"DELETE FROM competitions WHERE id='$id'");
    header("Location: manage_competitions.php?msg=deleted"); exit();
}

$page_title = "Competitions";
include("admin_header.php");
$comps = mysqli_query($conn,"SELECT * FROM competitions ORDER BY id DESC");
?>

<style>
    .form-label { color: var(--gold); font-size: 0.75rem; font-weight: 700; text-transform: uppercase; margin-bottom: 5px; display: block; }
    .form-input { background: #0d0d0d !important; border: 1px solid #1e2530 !important; color: #e2e8f0 !important; padding: 12px; border-radius: 8px; width: 100%; outline: none; transition: 0.3s; }
    .form-input:focus { border-color: var(--gold) !important; box-shadow: 0 0 10px rgba(201,168,76,0.1); }
    
    /* Prize Column Styling */
    .prize-display {
        background: rgba(201,168,76,0.05);
        border-left: 3px solid var(--gold);
        padding: 8px 12px;
        border-radius: 4px;
        font-size: 0.75rem;
        color: #f1f5f9;
        line-height: 1.4;
    }
    .prize-tag { color: var(--gold); font-weight: 800; font-size: 0.65rem; display: block; margin-bottom: 2px; }
</style>

<div style="display:grid; grid-template-columns: 400px 1fr; gap:25px;">
    <div class="section-card" style="padding:25px; background: #141920; border: 1px solid #1e2530;">
        <h3 style="font-size:1.1rem; color:#fff; margin-bottom:20px;"><i class="fa-solid fa-trophy" style="color:var(--gold); margin-right:10px;"></i>New Competition</h3>
        
        <form method="POST" style="display:flex; flex-direction:column; gap:15px;">
            <div>
                <label class="form-label">Category *</label>
                <select name="title" id="comp_title" required class="form-input" onchange="toggleCustomTitle()">
                    <option value="Essay Writing">Essay Writing</option>
                    <option value="Story Writing">Story Writing</option>
                    <option value="Custom">Custom Title (Type Below)</option>
                </select>
                <input type="text" name="custom_title" id="custom_title_box" class="form-input" style="display:none; margin-top:10px;" placeholder="Enter custom competition name">
            </div>

            <div>
                <label class="form-label">Prize Details (1st, 2nd, 3rd) *</label>
                <textarea name="prize" id="comp_prize" required class="form-input" rows="2" placeholder="e.g. 1st: 5000, 2nd: 3000, 3rd: 1000 PKR"></textarea>
                <small style="color:#64748b; font-size:0.65rem;">Describe prizes for all ranks clearly.</small>
            </div>

            <div>
                <label class="form-label">Description</label>
                <textarea name="description" id="comp_desc" required class="form-input" rows="3"></textarea>
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

            <button name="add_comp" class="btn-primary" style="width:100%; padding:14px; font-weight:800; text-transform:uppercase; letter-spacing:1px;">
                Launch Competition
            </button>
        </form>
    </div>

    <div class="section-card">
        <div class="section-header" style="padding:20px; border-bottom:1px solid #1e2530;">
            <h3 style="color:#fff; font-size:1rem;">Active Competitions</h3>
        </div>
        <div style="overflow-x:auto;">
            <table>
                <thead>
                    <tr>
                        <th style="padding:15px;">Competition Details</th>
                        <th>Award Pool</th>
                        <th>Timeline</th>
                        <th style="text-align:center;">Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php while($row=mysqli_fetch_assoc($comps)): ?>
                    <tr>
                        <td style="padding:15px;">
                            <div style="color:#fff; font-weight:700;"><?php echo htmlspecialchars($row['title']); ?></div>
                            <div style="color:#64748b; font-size:0.7rem; margin-top:4px;"><?php echo htmlspecialchars(substr($row['description'],0,50)).'...'; ?></div>
                        </td>
                        <td>
                            <div class="prize-display">
                                <span class="prize-tag"><i class="fa-solid fa-gift"></i> REWARDS</span>
                                <?php echo htmlspecialchars($row['prize']); ?>
                            </div>
                        </td>
                        <td>
                            <div style="font-size:0.75rem; color:#cbd5e1; font-weight:600;">
                                <i class="fa-regular fa-calendar" style="font-size:0.65rem; color:var(--gold);"></i> 
                                <?php echo date('M d',strtotime($row['start_date'])); ?> - <?php echo date('M d, Y',strtotime($row['end_date'])); ?>
                            </div>
                        </td>
                        <td style="text-align:center;">
                            <button onclick="confirmDelete(<?php echo $row['id']; ?>)" style="color:#64748b; background:transparent; border:none; cursor:pointer; font-size:1rem;"><i class="fa-solid fa-trash-can"></i></button>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function toggleCustomTitle() {
    const val = document.getElementById('comp_title').value;
    const customBox = document.getElementById('custom_title_box');
    const prizeField = document.getElementById('comp_prize');
    const descField = document.getElementById('comp_desc');

    if(val === "Custom") {
        customBox.style.display = "block";
        customBox.required = true;
    } else {
        customBox.style.display = "none";
        customBox.required = false;
        
        // Auto-fill suggestions based on type
        if(val === "Essay Writing") {
            prizeField.value = "1st: 5000, 2nd: 3000, 3rd: 1500 PKR";
            descField.value = "Write an essay on 'The Future of AI'. Min 500 words.";
        } else if(val === "Story Writing") {
            prizeField.value = "1st: 10,000, 2nd: 5000, 3rd: 2000 PKR";
            descField.value = "Create a mystery short story. Max 2000 words.";
        }
    }
}

function confirmDelete(id){
    Swal.fire({ 
        title:'Delete Competition?', text:'This cannot be undone!', icon:'warning', 
        showCancelButton:true, confirmButtonColor:'#ef4444', background:'#141920', color:'#fff'
    }).then(r => { if(r.isConfirmed) window.location.href='manage_competitions.php?delete_id='+id; });
}

// Set initial auto-fill on page load
window.onload = toggleCustomTitle;
</script>

<?php include("admin_footer.php"); ?>