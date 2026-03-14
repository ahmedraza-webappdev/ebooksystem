<?php
session_start();
if(!isset($_SESSION['admin'])){ header("Location: admin_login.php"); exit(); }
include("../config/db.php");
$page_title = "Users";
$page_subtitle = "Manage all registered community members";
include("admin_header.php");
$result = mysqli_query($conn,"SELECT * FROM users ORDER BY id DESC");
$total = mysqli_num_rows($result);
$av_colors = ['#6366f1','#22c55e','#f59e0b','#ec4899','#3b82f6','#a855f7'];
$i=0;
?>

<div class="section-card">
    <div class="section-header">
        <div>
            <h3><i class="fa-solid fa-users" style="color:#6366f1;margin-right:8px;"></i>Community Directory</h3>
            <p style="font-size:0.75rem;color:#94a3b8;margin:4px 0 0;"><?php echo $total; ?> registered members</p>
        </div>
        </div>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>User Details</th>
                <th>Contact</th>
                <th>Address</th>
                <th>Joined</th>
            </tr>
        </thead>
        <tbody>
        <?php if($total>0): ?>
        <?php while($row=mysqli_fetch_assoc($result)): ?>
            <tr>
                <td style="font-size:0.75rem;font-weight:800;color:#94a3b8;">#<?php echo $row['id']; ?></td>
                <td>
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div style="width:40px;height:40px;background:<?php echo $av_colors[$i%6];?>;border-radius:12px;display:flex;align-items:center;justify-content:center;color:white;font-weight:800;font-size:0.85rem;flex-shrink:0;">
                            <?php echo strtoupper(substr($row['name'],0,1)); ?>
                        </div>
                        <div>
                            <div style="font-weight:700;color:#1e293b;font-size:0.875rem;"><?php echo htmlspecialchars($row['name']); ?></div>
                            <span class="badge badge-active" style="font-size:0.62rem;padding:2px 8px;">Active</span>
                        </div>
                    </div>
                </td>
                <td>
                    <div style="font-size:0.78rem;color:#64748b;display:flex;flex-direction:column;gap:4px;">
                        <div><i class="fa-regular fa-envelope mr-1" style="color:#6366f1;"></i><?php echo htmlspecialchars($row['email']); ?></div>
                        <div><i class="fa-solid fa-phone mr-1" style="color:#22c55e;"></i><?php echo htmlspecialchars($row['phone']); ?></div>
                    </div>
                </td>
                <td style="font-size:0.8rem;color:#64748b;max-width:160px;">
                    <i class="fa-solid fa-location-dot mr-1" style="color:#f43f5e;"></i><?php echo htmlspecialchars($row['address']); ?>
                </td>
                <td>
                    <div style="font-size:0.8rem;font-weight:600;color:#475569;"><?php echo date('M d, Y',strtotime($row['created_at'])); ?></div>
                    <div style="font-size:0.68rem;color:#94a3b8;"><?php echo date('h:i A',strtotime($row['created_at'])); ?></div>
                </td>
            </tr>
        <?php $i++; endwhile; ?>
        <?php else: ?>
            <tr><td colspan="5" style="text-align:center;padding:50px;color:#94a3b8;">No users found.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include("admin_footer.php"); ?>