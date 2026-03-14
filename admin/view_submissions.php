<?php
session_start();
if(!isset($_SESSION['admin'])){ header("Location: admin_login.php"); exit(); }
include("../config/db.php");

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

<div class="section-card">
    <div class="section-header">
        <h3><i class="fa-solid fa-file-lines" style="color:#6366f1;margin-right:8px;"></i>Essay Submissions</h3>
    </div>
    <table>
        <thead>
            <tr>
                <th>Participant</th>
                <th>Competition</th>
                <th>File</th>
                <th style="text-align:center;">Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php if(mysqli_num_rows($result)>0): ?>
        <?php while($row=mysqli_fetch_assoc($result)): ?>
            <tr>
                <td>
                    <div style="font-weight:700;color:#1e293b;font-size:0.875rem;"><?php echo htmlspecialchars($row['user_name']); ?></div>
                    <div style="font-size:0.72rem;color:#94a3b8;">UID: #<?php echo $row['user_id']; ?></div>
                </td>
                <td><span class="badge badge-category"><?php echo htmlspecialchars($row['comp_title']); ?></span></td>
                <td>
                    <div style="font-size:0.8rem;font-weight:700;color:#1e293b;"><?php echo htmlspecialchars($row['file']); ?></div>
                    <div style="font-size:0.72rem;color:#94a3b8;"><?php echo date('d M, Y',strtotime($row['submitted_at'])); ?></div>
                </td>
                <td>
                    <div style="display:flex;justify-content:center;align-items:center;gap:8px;flex-wrap:wrap;">
                        <a href="read_essay.php?file=<?php echo $row['file']; ?>" class="btn-primary" style="padding:6px 14px;font-size:0.75rem;border-radius:8px;"><i class="fa-solid fa-eye mr-1"></i>Read</a>

                        <?php if(!empty($row['comp_winner_id']) && $row['comp_winner_id']==$row['user_id']): ?>
                            <span class="badge badge-winner"><i class="fa-solid fa-crown mr-1"></i>WINNER</span>
                            <button onclick="confirmReset(<?php echo $row['competition_id']; ?>)" class="action-btn" style="background:#f1f5f9;color:#64748b;" title="Reset winner"><i class="fa-solid fa-rotate-left" style="font-size:0.7rem;"></i></button>
                        <?php else: ?>
                            <button onclick="confirmWinner(<?php echo $row['competition_id']; ?>,<?php echo $row['user_id']; ?>)" style="background:#fef9c3;color:#92400e;border:none;padding:6px 12px;border-radius:8px;font-size:0.72rem;font-weight:800;cursor:pointer;transition:0.2s;" onmouseover="this.style.background='#f59e0b';this.style.color='white'" onmouseout="this.style.background='#fef9c3';this.style.color='#92400e'">
                                <i class="fa-solid fa-trophy mr-1"></i>Set Winner
                            </button>
                        <?php endif; ?>

                        <button onclick="confirmDelete(<?php echo $row['id']; ?>)" class="action-btn btn-delete"><i class="fa-solid fa-trash" style="font-size:0.7rem;"></i></button>
                    </div>
                </td>
            </tr>
        <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="4" style="text-align:center;padding:40px;color:#94a3b8;">No submissions found.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
<?php if(isset($_GET['msg'])): ?>
Swal.fire({ icon:'success', title:'Done!', text:'<?php echo $_GET['msg']=="winner_set"?"Winner announced!":($_GET['msg']=="deleted"?"Submission deleted!":"Winner reset!"); ?>', timer:2000, showConfirmButton:false });
<?php endif; ?>
function confirmDelete(id){ Swal.fire({title:'Delete?',text:"Can't undo this!",icon:'warning',showCancelButton:true,confirmButtonColor:'#f43f5e',confirmButtonText:'Yes, delete!'}).then(r=>{if(r.isConfirmed) window.location.href='view_submissions.php?delete_id='+id;}); }
function confirmWinner(cid,uid){ Swal.fire({title:'Announce Winner?',icon:'question',showCancelButton:true,confirmButtonColor:'#6366f1',confirmButtonText:'Set Winner!'}).then(r=>{if(r.isConfirmed) window.location.href=`view_submissions.php?set_winner=1&comp_id=${cid}&user_id=${uid}`;}); }
function confirmReset(cid){ Swal.fire({title:'Reset Winner?',icon:'warning',showCancelButton:true,confirmButtonText:'Yes, reset'}).then(r=>{if(r.isConfirmed) window.location.href='view_submissions.php?reset_winner=1&comp_id='+cid;}); }
</script>
<?php include("admin_footer.php"); ?>
