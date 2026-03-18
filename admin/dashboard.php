<?php
session_start();
if(!isset($_SESSION['admin'])){ header("Location: admin_login.php"); exit(); }
$page_title = "Dashboard";
$page_subtitle = "Welcome back! Here's your library overview.";
include("admin_header.php");
?>

<!-- Statistics Grid -->
<div style="display:grid; grid-template-columns:repeat(4,1fr); gap:20px; margin-bottom:28px;">
    <?php include("../config/db.php"); ?>

    <?php
    $stats = [
        ['table'=>'books','icon'=>'fa-book','label'=>'Total Books','link'=>'view_books.php','color'=>'#c9a84c','bg'=>'rgba(201,168,76,0.12)'],
        ['table'=>'users','icon'=>'fa-users','label'=>'Registered Users','link'=>'view_users.php','color'=>'#818cf8','bg'=>'rgba(129,140,248,0.12)'],
        ['table'=>'competitions','icon'=>'fa-trophy','label'=>'Competitions','link'=>'manage_competitions.php','color'=>'#4ade80','bg'=>'rgba(74,222,128,0.12)'],
        ['table'=>'orders','icon'=>'fa-cart-shopping','label'=>'Total Orders','link'=>'orders.php','color'=>'#f59e0b','bg'=>'rgba(245,158,11,0.12)'],
    ];
    foreach($stats as $s):
        $r = mysqli_query($conn,"SELECT COUNT(*) as c FROM ".$s['table']);
        $count = mysqli_fetch_assoc($r)['c'];
    ?>
    <div onclick="window.location='<?php echo $s['link']; ?>'" 
         style="background:#141920; border:1px solid #1e2530; border-radius:14px; padding:24px; cursor:pointer; transition:all 0.3s ease; box-shadow: 0 4px 15px rgba(0,0,0,0.15);" 
         onmouseover="this.style.borderColor='rgba(201,168,76,0.4)'; this.style.transform='translateY(-5px)';" 
         onmouseout="this.style.borderColor='#1e2530'; this.style.transform='translateY(0)';"
    >
        <div style="width:46px; height:46px; background:<?php echo $s['bg'];?>; border-radius:10px; display:flex; align-items:center; justify-content:center; color:<?php echo $s['color'];?>; font-size:1.1rem; margin-bottom:16px; border:1px solid <?php echo str_replace('0.12','0.2',$s['bg']);?>;">
            <i class="fa-solid <?php echo $s['icon'];?>"></i>
        </div>
        <div style="font-family:'DM Sans', sans-serif; font-size:2.2rem; font-weight:800; color:#ffffff; line-height:1;"><?php echo $count; ?></div>
        <div style="font-size:0.75rem; color:#94a3b8; margin-top:6px; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;"><?php echo $s['label']; ?></div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Main Content Grid -->
<div style="display:grid; grid-template-columns:1fr 1fr; gap:24px;">
    
    <!-- Quick Actions Card -->
    <div class="section-card" style="background:#141920; border:1px solid #1e2530; border-radius:15px; overflow:hidden;">
        <div class="section-header" style="padding:18px 22px; border-bottom:1px solid #1e2530; display:flex; align-items:center; justify-content:space-between;">
            <h3 style="margin:0; font-size:1rem; color:#f8fafc; font-weight:700;"><i class="fa-solid fa-bolt" style="color:var(--gold); margin-right:10px;"></i>Quick Actions</h3>
        </div>
        <div style="padding:22px; display:grid; grid-template-columns:1fr 1fr; gap:14px;">
            <?php
            $actions = [
                ['href'=>'upload_book.php','icon'=>'fa-upload','title'=>'Upload Book','sub'=>'Add to library','color'=>'#c9a84c','bg'=>'rgba(201,168,76,0.1)'],
                ['href'=>'manage_competitions.php','icon'=>'fa-trophy','title'=>'Competition','sub'=>'Manage events','color'=>'#4ade80','bg'=>'rgba(74,222,128,0.1)'],
                ['href'=>'view_users.php','icon'=>'fa-users','title'=>'View Users','sub'=>'Community','color'=>'#818cf8','bg'=>'rgba(129,140,248,0.1)'],
            ];
            foreach($actions as $a): ?>
            <a href="<?php echo $a['href']; ?>" style="background:<?php echo $a['bg'];?>; border:1px solid <?php echo str_replace('0.1','0.2',$a['bg']);?>; border-radius:12px; padding:20px; text-decoration:none; display:block; transition:0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.03)';" onmouseout="this.style.background='<?php echo $a['bg'];?>';">
                <i class="fa-solid <?php echo $a['icon'];?>" style="font-size:1.3rem; color:<?php echo $a['color'];?>;"></i>
                <div style="font-weight:700; margin-top:12px; font-size:0.9rem; color:#f1f5f9;"><?php echo $a['title']; ?></div>
                <div style="font-size:0.72rem; color:#64748b; margin-top:4px;"><?php echo $a['sub']; ?></div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Recent Books Card -->
    <div class="section-card" style="background:#141920; border:1px solid #1e2530; border-radius:15px; overflow:hidden;">
        <div class="section-header" style="padding:18px 22px; border-bottom:1px solid #1e2530; display:flex; align-items:center; justify-content:space-between;">
            <h3 style="margin:0; font-size:1rem; color:#f8fafc; font-weight:700;"><i class="fa-solid fa-clock-rotate-left" style="color:var(--gold); margin-right:10px;"></i>Recent Books</h3>
            <a href="view_books.php" style="color:var(--gold); font-size:0.72rem; font-weight:700; text-decoration:none; text-transform:uppercase; letter-spacing:1px;">View All</a>
        </div>
        <div style="padding:10px 0;">
            <?php
            $books = mysqli_query($conn,"SELECT * FROM books ORDER BY id DESC LIMIT 6");
            while($b=mysqli_fetch_assoc($books)):
            ?>
            <div style="display:flex; align-items:center; gap:16px; padding:12px 22px; border-bottom:1px solid rgba(255,255,255,0.03); transition:0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.02)';" onmouseout="this.style.background='transparent';">
                <div style="width:36px; height:48px; background:#0d0d0d; border:1px solid #1e2530; border-radius:6px; display:flex; align-items:center; justify-content:center; color:var(--gold); flex-shrink:0; font-size:0.8rem; overflow:hidden;">
                    <?php if(!empty($b['book_image'])): ?>
                        <img src="../uploads/covers/<?php echo $b['book_image']; ?>" style="width:100%; height:100%; object-fit:cover;">
                    <?php else: ?>
                        <i class="fa-solid fa-book"></i>
                    <?php endif; ?>
                </div>
                <div style="flex:1; min-width:0;">
                    <div style="font-weight:600; color:#f1f5f9; font-size:0.85rem; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;"><?php echo htmlspecialchars($b['title']); ?></div>
                    <div style="font-size:0.72rem; color:#64748b; margin-top:2px;">by <?php echo htmlspecialchars($b['author']); ?></div>
                </div>
                <?php if($b['is_free']==1): ?>
                    <span style="font-size:0.65rem; font-weight:800; color:#4ade80; background:rgba(74,222,128,0.1); padding:4px 8px; border-radius:5px; text-transform:uppercase;">Free</span>
                <?php else: ?>
                    <span style="font-size:0.85rem; font-weight:700; color:var(--gold); font-family:'DM Sans', sans-serif;">Rs. <?php echo number_format($b['price'],0); ?></span>
                <?php endif; ?>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<?php include("admin_footer.php"); ?>