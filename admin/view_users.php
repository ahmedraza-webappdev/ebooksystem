<?php
session_start();
if(!isset($_SESSION['admin'])){ header("Location: admin_login.php"); exit(); }
include("../config/db.php");
$page_title = "Users";
$page_subtitle = "Manage all registered community members";
include("admin_header.php");
$result = mysqli_query($conn,"SELECT * FROM users ORDER BY id DESC");
$total = mysqli_num_rows($result);
// Professional Gold & Muted Colors for Avatars
$av_colors = ['#c9a84c','#8b7332','#4a5568','#1e2530','#dfc070','#6366f1'];
$i=0;
?>

<style>
    /* Dark Theme Table & Text Adjustments */
    .user-name { font-weight: 700; color: #ffffff !important; font-size: 0.875rem; }
    .user-meta { font-size: 0.78rem; color: #94a3b8 !important; display: flex; flex-direction: column; gap: 4px; }
    .user-meta i { width: 14px; }
    .user-address { font-size: 0.8rem; color: #cbd5e1 !important; max-width: 160px; line-height: 1.4; }
    .user-date { font-size: 0.8rem; font-weight: 600; color: #e2e8f0 !important; }
    .user-time { font-size: 0.68rem; color: #64748b !important; }
    
    /* Badge styling matching the theme */
    .badge-active { background: rgba(34, 197, 94, 0.15) !important; color: #4ade80 !important; border: 1px solid rgba(34, 197, 94, 0.3); }
    
    /* ID column */
    .user-id { font-size: 0.75rem; font-weight: 800; color: var(--gold) !important; opacity: 0.8; }
</style>

<div class="section-card">
    <div class="section-header">
        <div>
            <h3 style="color:var(--gold);"><i class="fa-solid fa-users" style="margin-right:8px;"></i>Community Directory</h3>
            <p style="font-size:0.75rem; color:#94a3b8; margin:4px 0 0;"><?php echo $total; ?> registered members</p>
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
                <td class="user-id">#<?php echo $row['id']; ?></td>
                <td>
                    <div style="display:flex;align-items:center;gap:12px;">
                        <!-- Avatar with dynamic colors -->
                        <div style="width:40px;height:40px;background:<?php echo $av_colors[$i%6];?>;border-radius:10px;display:flex;align-items:center;justify-content:center;color:<?php echo ($i%6 == 0 || $i%6 == 4) ? '#000' : '#fff'; ?>;font-weight:800;font-size:0.9rem;flex-shrink:0;box-shadow: 0 4px 10px rgba(0,0,0,0.3);">
                            <?php echo strtoupper(substr($row['name'],0,1)); ?>
                        </div>
                        <div>
                            <div class="user-name"><?php echo htmlspecialchars($row['name']); ?></div>
                            <span class="badge badge-active" style="font-size:0.6rem;padding:1px 8px;margin-top:4px;">Active Member</span>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="user-meta">
                        <div><i class="fa-regular fa-envelope" style="color:var(--gold);"></i> <?php echo htmlspecialchars($row['email']); ?></div>
                        <div><i class="fa-solid fa-phone" style="color:#4ade80;"></i> <?php echo htmlspecialchars($row['phone']); ?></div>
                    </div>
                </td>
                <td class="user-address">
                    <i class="fa-solid fa-location-dot" style="color:#f43f5e;margin-right:4px;"></i><?php echo htmlspecialchars($row['address']); ?>
                </td>
                <td>
                    <div class="user-date"><?php echo date('M d, Y',strtotime($row['created_at'])); ?></div>
                    <div class="user-time"><?php echo date('h:i A',strtotime($row['created_at'])); ?></div>
                </td>
            </tr>
        <?php $i++; endwhile; ?>
        <?php else: ?>
            <tr><td colspan="5" style="text-align:center;padding:60px;color:var(--muted); font-size:0.9rem;">No members found in the directory.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include("admin_footer.php"); ?>