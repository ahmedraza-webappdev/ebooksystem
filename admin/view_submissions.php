<?php
// Session start
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION['admin'])){ header("Location: admin_login.php"); exit(); }
include("../config/db.php");

// --- LOGIC (NO CHANGES) ---
if(isset($_GET['delete_id'])){
    $id = mysqli_real_escape_string($conn,$_GET['delete_id']);
    $fq = mysqli_query($conn,"SELECT file FROM submissions WHERE id='$id'");
    $fd = mysqli_fetch_assoc($fq);
    if($fd){ $fp="../uploads/essays/".$fd['file']; if(file_exists($fp)) unlink($fp); mysqli_query($conn,"DELETE FROM submissions WHERE id='$id'"); }
    header("Location: view_submissions.php?msg=deleted"); exit();
}
if(isset($_GET['set_winner'])&&isset($_GET['comp_id'])&&isset($_GET['user_id'])){
    $cid=mysqli_real_escape_string($conn,$_GET['comp_id']);
    $uid=mysqli_real_escape_string($conn,$_GET['user_id']);
    mysqli_query($conn,"UPDATE competitions SET winner_id='$uid' WHERE id='$cid'");
    header("Location: view_submissions.php?msg=winner_set"); exit();
}
if(isset($_GET['reset_winner'])&&isset($_GET['comp_id'])){
    $cid=mysqli_real_escape_string($conn,$_GET['comp_id']);
    mysqli_query($conn,"UPDATE competitions SET winner_id=NULL WHERE id='$cid'");
    header("Location: view_submissions.php?msg=reset"); exit();
}

$result = mysqli_query($conn,"SELECT s.*,u.name as user_name,c.title as comp_title,c.winner_id as comp_winner_id FROM submissions s INNER JOIN competitions c ON s.competition_id=c.id LEFT JOIN users u ON s.user_id=u.id ORDER BY s.submitted_at DESC");

$page_title = "Submissions";
$page_subtitle = "Review and manage essay submissions";
include("admin_header.php");
?>

<style>
    /* Table & Card Overrides */
    .section-card { background: #141920; border: 1px solid #1e2530; border-radius: 15px; overflow: hidden; padding: 0; }
    
    table { width: 100%; border-collapse: separate; border-spacing: 0; }
    thead th { 
        background: #0d1117; 
        color: var(--gold); 
        font-size: 0.7rem; 
        text-transform: uppercase; 
        letter-spacing: 1.5px; 
        padding: 18px 20px; 
        border-bottom: 2px solid #1e2530;
    }
    tbody td { 
        padding: 16px 20px; 
        border-bottom: 1px solid rgba(255,255,255,0.03); 
        color: #94a3b8;
        font-size: 0.85rem;
        vertical-align: middle;
    }
    tbody tr:hover { background: rgba(255,255,255,0.02); }

    /* Custom Badges */
    .badge-comp { background: rgba(201,168,76,0.1); color: var(--gold); border: 1px solid rgba(201,168,76,0.2); padding: 4px 10px; border-radius: 6px; font-size: 0.7rem; font-weight: 700; }
    
    .badge-winner-gold { 
        background: linear-gradient(45deg, #c9a84c, #f1d58a); 
        color: #000; 
        padding: 5px 12px; 
        border-radius: 6px; 
        font-size: 0.65rem; 
        font-weight: 800; 
        box-shadow: 0 0 15px rgba(201,168,76,0.3);
        display: inline-flex; align-items: center; gap: 5px;
    }

    /* Buttons */
    .btn-action { 
        padding: 7px 14px; border-radius: 8px; font-size: 0.75rem; font-weight: 700; 
        transition: 0.3s; cursor: pointer; border: none; display: inline-flex; align-items: center; gap: 6px;
    }
    .btn-read { background: #1e2530; color: #fff; border: 1px solid #334155; text-decoration: none; }
    .btn-read:hover { background: #334155; }
    
    .btn-set-winner { background: rgba(34,197,94,0.1); color: #4ade80; border: 1px solid rgba(34,197,94,0.2); }
    .btn-set-winner:hover { background: #22c55e; color: #000; }

    .btn-reset { background: rgba(244,63,94,0.1); color: #fb7185; border: 1px solid rgba(244,63,94,0.2); width: 32px; height: 32px; justify-content: center; }
    .btn-reset:hover { background: #f43f5e; color: #fff; }

    .btn-trash { background: rgba(255,255,255,0.05); color: #64748b; width: 32px; height: 32px; justify-content: center; }
    .btn-trash:hover { background: #ef4444; color: #fff; }
</style>

<div class="section-card">
    <div class="section-header" style="padding: 22px 25px; border-bottom: 1px solid #1e2530;">
        <h3 style="margin:0; font-size: 1.1rem; color: #fff; font-weight: 700;">
            <i class="fa-solid fa-file-signature" style="color:var(--gold); margin-right:10px;"></i>Essay Submissions
        </h3>
    </div>
    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>Participant</th>
                    <th>Competition</th>
                    <th>Submission Details</th>
                    <th style="text-align:right;">Management</th>
                </tr>
            </thead>
            <tbody>
            <?php if(mysqli_num_rows($result) > 0): ?>
            <?php while($row=mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td>
                        <div style="font-weight: 700; color: #f1f5f9;"><?php echo htmlspecialchars($row['user_name']); ?></div>
                        <div style="font-size: 0.7rem; color: #64748b; font-family: 'JetBrains Mono';">ID: #USR-<?php echo $row['user_id']; ?></div>
                    </td>
                    <td>
                        <span class="badge-comp"><?php echo htmlspecialchars($row['comp_title']); ?></span>
                    </td>
                    <td>
                        <div style="font-weight: 600; color: #e2e8f0; font-size: 0.8rem;"><i class="fa-solid fa-paperclip" style="font-size:0.7rem; color:var(--gold);"></i> <?php echo htmlspecialchars($row['file']); ?></div>
                        <div style="font-size: 0.7rem; color: #94a3b8; margin-top:3px;"><?php echo date('M d, Y • h:i A', strtotime($row['submitted_at'])); ?></div>
                    </td>
                    <td>
                        <div style="display:flex; justify-content: flex-end; align-items:center; gap:10px;">
                            <!-- Read Button -->
                            <a href="read_essay.php?file=<?php echo $row['file']; ?>" class="btn-action btn-read">
                                <i class="fa-solid fa-book-open"></i> Read
                            </a>

                            <!-- Winner Management -->
                            <?php if(!empty($row['comp_winner_id']) && $row['comp_winner_id']==$row['user_id']): ?>
                                <span class="badge-winner-gold"><i class="fa-solid fa-crown"></i> WINNER</span>
                                <button onclick="confirmReset(<?php echo $row['competition_id']; ?>)" class="btn-action btn-reset" title="Reset Winner Status">
                                    <i class="fa-solid fa-rotate-left"></i>
                                </button>
                            <?php else: ?>
                                <button onclick="confirmWinner(<?php echo $row['competition_id']; ?>,<?php echo $row['user_id']; ?>)" class="btn-action btn-set-winner">
                                    <i class="fa-solid fa-trophy"></i> Set Winner
                                </button>
                            <?php endif; ?>

                            <!-- Delete Button -->
                            <button onclick="confirmDelete(<?php echo $row['id']; ?>)" class="btn-action btn-trash" title="Delete Submission">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" style="text-align:center; padding:60px 0;">
                        <i class="fa-solid fa-folder-open" style="font-size: 2.5rem; color: #1e2530; display: block; margin-bottom: 15px;"></i>
                        <span style="color: #64748b;">No essay submissions found.</span>
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
// Notification Styles for SweetAlert
const swalDark = {
    background: '#141920',
    color: '#fff',
    confirmButtonColor: '#c9a84c',
    cancelButtonColor: '#1e2530'
};

<?php if(isset($_GET['msg'])): ?>
Swal.fire({ 
    ...swalDark,
    icon:'success', 
    title:'Success!', 
    text:'<?php echo $_GET['msg']=="winner_set"?"Winner announced!":($_GET['msg']=="deleted"?"Submission deleted!":"Winner reset!"); ?>', 
    timer:2000, 
    showConfirmButton:false 
});
<?php endif; ?>

function confirmDelete(id){ 
    Swal.fire({
        ...swalDark,
        title:'Remove Essay?',
        text:"This submission will be permanently deleted.",
        icon:'warning',
        showCancelButton:true,
        confirmButtonText:'Delete Now',
        confirmButtonColor: '#ef4444'
    }).then(r=>{ if(r.isConfirmed) window.location.href='view_submissions.php?delete_id='+id; }); 
}

function confirmWinner(cid,uid){ 
    Swal.fire({
        ...swalDark,
        title:'Announce Winner?',
        text: "This user will be marked as the competition winner.",
        icon:'question',
        showCancelButton:true,
        confirmButtonText:'Announce Winner'
    }).then(r=>{ if(r.isConfirmed) window.location.href=`view_submissions.php?set_winner=1&comp_id=${cid}&user_id=${uid}`; }); 
}

function confirmReset(cid){ 
    Swal.fire({
        ...swalDark,
        title:'Reset Winner?',
        text: "This will clear the current winner for this competition.",
        icon:'warning',
        showCancelButton:true,
        confirmButtonText:'Yes, Reset'
    }).then(r=>{ if(r.isConfirmed) window.location.href='view_submissions.php?reset_winner=1&comp_id='+cid; }); 
}
</script>

<?php include("admin_footer.php"); ?>