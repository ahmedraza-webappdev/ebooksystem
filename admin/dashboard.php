<?php
session_start();
if(!isset($_SESSION['admin'])){ header("Location: admin_login.php"); exit(); }
$page_title = "Dashboard";
$page_subtitle = "Welcome back! Here's your library overview.";
include("admin_header.php");
?>

<div style="display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:22px;">
    <?php include("../config/db.php"); ?>

    <?php
    $stats = [
        ['table'=>'books','icon'=>'fa-book','label'=>'Total Books','link'=>'view_books.php','color'=>'#c9a84c','bg'=>'rgba(201,168,76,0.08)'],
        ['table'=>'users','icon'=>'fa-users','label'=>'Registered Users','link'=>'view_users.php','color'=>'#818cf8','bg'=>'rgba(99,102,241,0.08)'],
        ['table'=>'competitions','icon'=>'fa-trophy','label'=>'Competitions','link'=>'manage_competitions.php','color'=>'#4a7a59','bg'=>'rgba(74,122,89,0.08)'],
        ['table'=>'orders','icon'=>'fa-cart-shopping','label'=>'Total Orders','link'=>'orders.php','color'=>'#f59e0b','bg'=>'rgba(245,158,11,0.08)'],
    ];
    foreach($stats as $s):
        $r = mysqli_query($conn,"SELECT COUNT(*) as c FROM ".$s['table']);
        $count = mysqli_fetch_assoc($r)['c'];
    ?>
    <div onclick="window.location='<?php echo $s['link']; ?>'" style="background:#141920;border:1px solid #1e2530;border-radius:10px;padding:20px;cursor:pointer;transition:all 0.25s;" onmouseover="this.style.borderColor='rgba(201,168,76,0.25)';this.style.transform='translateY(-3px)'" onmouseout="this.style.borderColor='#1e2530';this.style.transform='translateY(0)'">
        <div style="width:42px;height:42px;background:<?php echo $s['bg'];?>;border-radius:8px;display:flex;align-items:center;justify-content:center;color:<?php echo $s['color'];?>;font-size:1rem;margin-bottom:14px;border:1px solid <?php echo str_replace('0.08','0.15',$s['bg']);?>;">
            <i class="fa-solid <?php echo $s['icon'];?>"></i>
        </div>
        <div style="font-family:'Cormorant Garamond',serif;font-size:2rem;font-weight:700;color:#fff;line-height:1;"><?php echo $count; ?></div>
        <div style="font-size:0.72rem;color:#4a5568;margin-top:4px;font-weight:600;"><?php echo $s['label']; ?></div>
    </div>
    <?php endforeach; ?>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
    <!-- Quick Actions -->
    <div class="section-card">
        <div class="section-header"><h3><i class="fa-solid fa-bolt" style="color:var(--gold);margin-right:8px;font-size:0.8rem;"></i>Quick Actions</h3></div>
        <div style="padding:18px;display:grid;grid-template-columns:1fr 1fr;gap:10px;">
            <?php
            $actions = [
                ['href'=>'upload_book.php','icon'=>'fa-upload','title'=>'Upload Book','sub'=>'Add to library','color'=>'#c9a84c','bg'=>'rgba(201,168,76,0.08)'],
                ['href'=>'manage_competitions.php','icon'=>'fa-trophy','title'=>'Competition','sub'=>'Manage events','color'=>'#4a7a59','bg'=>'rgba(74,122,89,0.08)'],
                ['href'=>'view_users.php','icon'=>'fa-users','title'=>'View Users','sub'=>'Community','color'=>'#818cf8','bg'=>'rgba(99,102,241,0.08)'],
                ['href'=>'view_submissions.php','icon'=>'fa-file-lines','title'=>'Submissions','sub'=>'Essay entries','color'=>'#f59e0b','bg'=>'rgba(245,158,11,0.08)'],
            ];
            foreach($actions as $a): ?>
            <a href="<?php echo $a['href']; ?>" style="background:<?php echo $a['bg'];?>;border:1px solid <?php echo str_replace('0.08','0.15',$a['bg']);?>;border-radius:10px;padding:16px;text-decoration:none;display:block;transition:0.2s;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                <i class="fa-solid <?php echo $a['icon'];?>" style="font-size:1.2rem;color:<?php echo $a['color'];?>;opacity:0.9;"></i>
                <div style="font-weight:700;margin-top:10px;font-size:0.84rem;color:#e2e8f0;"><?php echo $a['title']; ?></div>
                <div style="font-size:0.68rem;color:#4a5568;margin-top:2px;"><?php echo $a['sub']; ?></div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Recent Books -->
    <div class="section-card">
        <div class="section-header">
            <h3><i class="fa-solid fa-clock-rotate-left" style="color:var(--gold);margin-right:8px;font-size:0.8rem;"></i>Recent Books</h3>
            <a href="view_books.php" class="btn-primary" style="padding:6px 14px;font-size:0.72rem;">View All</a>
        </div>
        <?php
        $books = mysqli_query($conn,"SELECT * FROM books ORDER BY id DESC LIMIT 6");
        while($b=mysqli_fetch_assoc($books)):
        ?>
        <div style="display:flex;align-items:center;gap:12px;padding:11px 20px;border-bottom:1px solid rgba(30,37,48,0.6);">
            <div style="width:32px;height:42px;background:rgba(201,168,76,0.08);border:1px solid rgba(201,168,76,0.15);border-radius:5px;display:flex;align-items:center;justify-content:center;color:var(--gold);flex-shrink:0;font-size:0.7rem;">
                <i class="fa-solid fa-book"></i>
            </div>
            <div style="flex:1;min-width:0;">
                <div style="font-weight:600;color:#e2e8f0;font-size:0.8rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?php echo htmlspecialchars($b['title']); ?></div>
                <div style="font-size:0.68rem;color:#4a5568;margin-top:1px;"><?php echo htmlspecialchars($b['author']); ?></div>
            </div>
            <?php if($b['is_free']==1): ?>
                <span class="badge badge-free">Free</span>
            <?php else: ?>
                <span style="font-size:0.8rem;font-weight:700;color:var(--gold);">Rs. <?php echo $b['price']; ?></span>
            <?php endif; ?>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<?php include("admin_footer.php"); ?>
