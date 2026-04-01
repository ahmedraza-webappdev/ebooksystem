<?php 
if (session_status() === PHP_SESSION_NONE) { session_start(); } 
include("../config/db.php"); 
include("navbar.php"); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Winners | E-Library</title>
    <style>
        body { background: #0d0d0d; color: #f0ece4; }
        .winners-container { max-width: 1000px; margin: 50px auto; padding: 0 20px; }
        .winner-row { 
            background: #141920; 
            border: 1px solid rgba(255,255,255,0.07); 
            padding: 20px; 
            margin-bottom: 15px; 
            border-radius: 10px; 
            display: flex; 
            justify-content: space-between; 
            align-items: center;
            transition: transform 0.2s;
        }
        .winner-row:hover { transform: scale(1.01); border-color: rgba(201,168,76,0.3); }
        .winner-info h3 { color: #fff; margin-bottom: 5px; font-family: 'Cormorant Garamond', serif; font-size: 1.5rem; }
        .winner-info p { color: rgba(255,255,255,0.4); font-size: 0.85rem; margin: 0; }
        .prize-tag { 
            background: rgba(201,168,76,0.1); 
            color: #c9a84c; 
            padding: 8px 18px; 
            border-radius: 6px; 
            font-weight: 700; 
            font-size: 0.75rem; 
            border: 1px solid rgba(201,168,76,0.2);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .page-title { text-align: center; margin-bottom: 10px; color: #fff; font-family: 'Cormorant Garamond', serif; font-size: 3rem; }
        .page-subtitle { text-align: center; color: rgba(255,255,255,0.4); margin-bottom: 40px; font-size: 0.9rem; }
        .no-data { text-align: center; padding: 80px 20px; color: #444; }
    </style>
</head>
<body>

<div class="winners-container">
    <h1 class="page-title">🏆 Hall of Fame</h1>
    <p class="page-subtitle">Celebrating our brilliant minds and creative writers</p>

    <?php 
    /**
     * UPDATED QUERY:
     * 1. Submissions table se wo entries uthayega jahan Rank set hai.
     * 2. Phir Competitions table se wo entries uthayega jahan winner_id set hai.
     */
    $q = "(SELECT s.rank as prize_title, c.title as competition, u.name as winner_name, s.id as sort_id
           FROM submissions s 
           JOIN competitions c ON s.competition_id = c.id 
           JOIN users u ON s.user_id = u.id 
           WHERE s.rank IS NOT NULL AND s.rank != '')
          
          UNION 
          
          (SELECT c.prize as prize_title, c.title as competition, u.name as winner_name, c.id as sort_id
           FROM competitions c 
           JOIN users u ON c.winner_id = u.id 
           WHERE c.winner_id IS NOT NULL)
          
          ORDER BY sort_id DESC";

    $res = mysqli_query($conn, $q);

    // Agar query fail ho jaye (maslan 'rank' column na ho), to ye error batayega
    if(!$res) {
        echo "<div class='no-data'>Database Error: " . mysqli_error($conn) . "</div>";
    } elseif(mysqli_num_rows($res) > 0) {
        while($w = mysqli_fetch_assoc($res)) {
            ?>
            <div class="winner-row">
                <div class="winner-info">
                    <h3><?php echo htmlspecialchars($w['winner_name']); ?></h3>
                    <p><i class="fa-solid fa-feather-pointed" style="color:var(--gold); margin-right:5px;"></i> Won in: <?php echo htmlspecialchars($w['competition']); ?></p>
                </div>
                <div class="prize-tag">
                    <i class="fa-solid fa-trophy" style="margin-right:5px;"></i> 
                    <?php echo htmlspecialchars($w['prize_title']); ?>
                </div>
            </div>
            <?php 
        }
    } else {
        ?>
        <div class="no-data">
            <i class="fa-solid fa-medal" style="font-size: 4rem; margin-bottom: 20px; opacity: 0.2;"></i>
            <p style="font-size: 1.2rem;">No winners have been crowned yet.</p>
            <p style="font-size: 0.9rem;">Check back after the current competition ends!</p>
        </div>
        <?php
    }
    ?>
</div>

</body>
</html>