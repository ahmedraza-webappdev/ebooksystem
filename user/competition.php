<?php
session_start();
include("../config/db.php");
date_default_timezone_set('Asia/Karachi');

// User login check (Optional: Agar aap chahte hain sirf logged-in users participate karein)
$is_logged_in = isset($_SESSION['user_id']);

$today = date('Y-m-d');
$query = "SELECT * FROM competitions WHERE end_date >= '$today' ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Competitions | E-Library</title>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* ... (Aapka CSS jo pehle tha wahi rahega) ... */
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
        :root{--gold:#c9a84c;--gold-light:#e8c96a;--surface:#141920;--surface-2:#1c2333;--border:rgba(255,255,255,0.07);--muted:rgba(255,255,255,0.38);}
        body{background:#0d0d0d;color:#f0ece4;font-family:'DM Sans',sans-serif;min-height:100vh;}
        .page-hero{background:#141920;border-bottom:1px solid var(--border);padding:60px 30px;text-align:center;position:relative;overflow:hidden;}
        .comp-container{max-width:1200px;margin:0 auto;padding:56px 30px;}
        .comp-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:20px;}
        .comp-card{background:var(--surface);border:1px solid var(--border);border-radius:10px;padding:28px;display:flex;flex-direction:column;transition:all 0.25s;}
        .comp-card:hover{border-color:rgba(201,168,76,0.3);transform:translateY(-4px);}
        .live-badge{display:inline-flex;align-items:center;gap:5px;background:rgba(74,124,89,0.12);border:1px solid rgba(74,124,89,0.25);color:#6abd7c;font-size:0.62rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;padding:4px 10px;border-radius:3px;margin-bottom:14px;}
        .comp-title{font-family:'Cormorant Garamond',serif;font-size:1.35rem;font-weight:700;color:#fff;margin-bottom:8px;}
        .comp-desc{font-size:0.78rem;color:var(--muted);line-height:1.7;margin-bottom:20px;flex:1;}
        .comp-meta{background:#1c2333;border-radius:6px;padding:14px;margin-bottom:20px;}
        .meta-row{display:flex;align-items:flex-start;gap:8px;font-size:0.76rem;color:var(--muted);margin-bottom:8px;}
        .meta-row i{color:var(--gold);width:12px;font-size:0.7rem;margin-top:3px;}
        .prize-text{color:var(--gold-light) !important; font-size:0.72rem; line-height:1.4;}
        .btn-participate{background:var(--gold);color:#0d0d0d;border:none;font-size:0.75rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;padding:12px;border-radius:6px;cursor:pointer;width:100%;transition:0.2s;}
        
        /* WRITING INTERFACE */
        #writingInterface{display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:#0d0d0d;z-index:1000;overflow-y:auto;}
        .writing-wrap{max-width:860px;margin:0 auto;padding:40px 30px;}
        #timer{font-size:3.5rem;font-weight:700;color:var(--gold);font-family:'Cormorant Garamond',serif;text-align:center;margin-bottom:20px;}
        .essay-area{width:100%;background:#141920;border:1px solid var(--border);border-radius:8px;padding:25px;color:#fff;font-size:1rem;line-height:1.8;min-height:450px;outline:none;}
        .btn-row{display:grid;grid-template-columns:1fr 1fr;gap:15px;margin-top:20px;}
        .btn-cancel{background:rgba(255,255,255,0.05); color:#fff; border:1px solid var(--border); padding:15px; border-radius:8px; cursor:pointer;}
        .btn-submit-essay{background:#4a7c59; color:#fff; border:none; padding:15px; border-radius:8px; font-weight:700; cursor:pointer;}
    </style>
</head>
<body>

<?php include("navbar.php"); ?>

<div class="comp-container" id="listView">
    <div class="page-hero" style="margin-bottom: 40px;">
        <span class="eyebrow">✦ Active Events</span>
        <h1>Writing Competitions</h1>
        <p>Pick a topic and start your journey towards excellence.</p>
    </div>

    <div class="comp-grid">
        <?php if(mysqli_num_rows($result) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
            <div class="comp-card">
                <div><span class="live-badge"><span class="live-dot"></span> Live Now</span></div>
                <div class="comp-title"><?php echo htmlspecialchars($row['title']); ?></div>
                <p class="comp-desc"><?php echo nl2br(htmlspecialchars($row['description'])); ?></p>
                
                <div class="comp-meta">
                    <div class="meta-row">
                        <i class="fa-solid fa-gift"></i> 
                        <div class="prize-text"><strong>Awards:</strong><br><?php echo nl2br(htmlspecialchars($row['prize'])); ?></div>
                    </div>
                    <div class="meta-row">
                        <i class="fa-regular fa-clock"></i> 
                        <span>Ends: <strong><?php echo date('d M, Y', strtotime($row['end_date'])); ?></strong></span>
                    </div>
                </div>

                <button onclick="openWritingArea(<?php echo $row['id']; ?>, '<?php echo addslashes($row['title']); ?>')" class="btn-participate">
                    Participate Now →
                </button>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="empty-state" style="text-align:center; width:100%; grid-column:1/-1; padding:100px 0;">
                <i class="fa-solid fa-hourglass-half" style="font-size:3rem; color:var(--muted);"></i>
                <h3 style="margin-top:20px;">No active competitions found.</h3>
            </div>
        <?php endif; ?>
    </div>
</div>

<div id="writingInterface">
    <div class="writing-wrap">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h3 id="displayTitle" style="font-family:'Cormorant Garamond',serif; font-size:1.8rem; color:var(--gold);"></h3>
            <span style="background:var(--surface-2); padding:5px 15px; border-radius:20px; font-size:0.8rem; border:1px solid var(--border);">
                Words: <span id="wordCount" style="color:#fff; font-weight:700;">0</span>
            </span>
        </div>

        <div id="timer">03:00:00</div>

        <form id="essayForm" method="POST" action="submit_essay.php">
            <input type="hidden" name="comp_id" id="comp_id_input">
            <textarea name="essay" id="essayText" class="essay-area" placeholder="Write your masterpiece here..." onkeyup="countWords()" required></textarea>
            
            <div class="btn-row">
                <button type="button" onclick="confirmCancel()" class="btn-cancel">Exit Writing</button>
                <button type="submit" class="btn-submit-essay">Submit Final Essay ✓</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
var time = 10800; // 3 Hours in seconds
var timerInterval;

function openWritingArea(id, title) {
    // Check if user is logged in (Simple JS check)
    <?php if(!$is_logged_in): ?>
        Swal.fire({
            title: 'Login Required',
            text: 'Please login to participate in competitions.',
            icon: 'info',
            confirmButtonText: 'Go to Login',
            background: '#141920', color: '#fff'
        }).then(() => { window.location.href = 'login.php'; });
        return;
    <?php endif; ?>

    // Hide list, Show writing area
    document.getElementById("listView").style.display = "none";
    document.getElementById("writingInterface").style.display = "block";
    document.getElementById("displayTitle").innerText = title;
    document.getElementById("comp_id_input").value = id;

    // Start Timer
    timerInterval = setInterval(updateTimer, 1000);
}

function updateTimer() {
    var hours = Math.floor(time / 3600);
    var minutes = Math.floor((time % 3600) / 60);
    var seconds = time % 60;
    
    document.getElementById("timer").innerHTML = 
        (hours < 10 ? "0" + hours : hours) + ":" + 
        (minutes < 10 ? "0" + minutes : minutes) + ":" + 
        (seconds < 10 ? "0" + seconds : seconds);
    
    if (time <= 0) { 
        clearInterval(timerInterval); 
        document.getElementById("essayForm").submit(); 
    }
    time--;
}

function countWords() {
    let text = document.getElementById("essayText").value.trim();
    let words = text ? text.split(/\s+/).length : 0;
    document.getElementById("wordCount").innerText = words;
}

function confirmCancel() {
    Swal.fire({
        title: 'Discard Progress?',
        text: "Your writing will be lost!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonText: 'Keep Writing',
        background: '#141920', color: '#fff'
    }).then((result) => {
        if (result.isConfirmed) {
            location.reload();
        }
    });
}
</script>

</body>
</html>