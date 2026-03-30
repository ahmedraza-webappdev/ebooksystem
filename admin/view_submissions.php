<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}
include("../config/db.php");

// --- LOGIC (Updated for Submissions Table Rank) ---

// 1. Set Rank/Winner
if (isset($_GET['set_rank']) && isset($_GET['sub_id'])) {
    $sub_id = mysqli_real_escape_string($conn, $_GET['sub_id']);
    $rank = mysqli_real_escape_string($conn, $_GET['rank']); // 1, 2, or 3

    // Update rank in submissions table
    mysqli_query($conn, "UPDATE submissions SET rank='$rank' WHERE id='$sub_id'");
    header("Location: view_submissions.php?msg=rank_set");
    exit();
}

// 2. Reset Rank
if (isset($_GET['reset_rank']) && isset($_GET['sub_id'])) {
    $sub_id = mysqli_real_escape_string($conn, $_GET['sub_id']);
    mysqli_query($conn, "UPDATE submissions SET rank=NULL WHERE id='$sub_id'");
    header("Location: view_submissions.php?msg=reset");
    exit();
}

// 3. Delete Submission
if (isset($_GET['delete_id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    $fq = mysqli_query($conn, "SELECT file FROM submissions WHERE id='$id'");
    $fd = mysqli_fetch_assoc($fq);
    if ($fd) {
        $fp = "../uploads/essays/" . $fd['file'];
        if (file_exists($fp)) unlink($fp);
        mysqli_query($conn, "DELETE FROM submissions WHERE id='$id'");
    }
    header("Location: view_submissions.php?msg=deleted");
    exit();
}

// Fetch submissions
$result = mysqli_query($conn, "SELECT s.*, u.name as user_name, c.title as comp_title FROM submissions s INNER JOIN competitions c ON s.competition_id=c.id LEFT JOIN users u ON s.user_id=u.id ORDER BY s.submitted_at DESC");

$page_title = "Submissions";
include("admin_header.php");
?>

<style>
    .section-card {
        background: #141920;
        border: 1px solid #1e2530;
        border-radius: 15px;
        overflow: hidden;
    }

    table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

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
        border-bottom: 1px solid rgba(255, 255, 255, 0.03);
        color: #94a3b8;
        font-size: 0.85rem;
        vertical-align: middle;
    }

    /* Luxury Position Badges */
    .rank-badge {
        padding: 5px 12px;
        border-radius: 6px;
        font-size: 0.65rem;
        font-weight: 800;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-transform: uppercase;
    }

    .rank-1 {
        background: linear-gradient(45deg, #ffd700, #b8860b);
        color: #000;
        box-shadow: 0 0 15px rgba(255, 215, 0, 0.2);
    }

    .rank-2 {
        background: linear-gradient(45deg, #e2e8f0, #94a3b8);
        color: #000;
    }

    .rank-3  {
        background: linear-gradient(45deg, #cd7f32, #8b4513);
        color: #fff;
    }

    .btn-action {
        padding: 8px 15px;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 700;
        transition: 0.3s;
        cursor: pointer;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
    }

    .btn-read {
        background: #1e2530;
        color: #fff;
        border: 1px solid #334155;
    }

    .btn-rank {
        background: rgba(201, 168, 76, 0.1);
        color: var(--gold);
        border: 1px solid rgba(201, 168, 76, 0.2);
    }

    .btn-rank:hover {
        background: var(--gold);
        color: #000;
    }
    
    /* Dropdown ka background aur text color */
.my-swal-input {
    background-color: #1f262e !important; /* Thora light dark color */
    color: white !important;
    border: 1px solid #c9a84c !important; /* Gold border jo aapke button se match kare */
}

/* Dropdown ke options ka color (kuch browsers ke liye zaroori hai) */
.my-swal-input option {
    background-color: #141920;
    color: white;
}
    
</style>

<div class="section-card">
    <div class="section-header" style="padding: 22px 25px; border-bottom: 1px solid #1e2530;">
        <h3 style="margin:0; font-size: 1.1rem; color: #fff;"><i class="fa-solid fa-trophy" style="color:var(--gold); margin-right:10px;"></i>Submission Grading</h3>
    </div>
    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>Participant</th>
                    <th>Competition</th>
                    <th>File & Date</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td>
                            <div style="font-weight: 700; color: #f1f5f9;"><?php echo htmlspecialchars($row['user_name']); ?></div>
                            <div style="font-size: 0.7rem; color: #64748b;">ID: #USR-<?php echo $row['user_id']; ?></div>
                        </td>
                        <td><span style="color:var(--gold); font-weight:600;"><?php echo htmlspecialchars($row['comp_title']); ?></span></td>
                        <td>
                            <div style="font-weight: 600; color: #e2e8f0; font-size: 0.8rem;"><?php echo htmlspecialchars($row['file']); ?></div>
                            <div style="font-size: 0.7rem; color: #64748b;"><?php echo date('M d, Y', strtotime($row['submitted_at'])); ?></div>
                        </td>
                        <td>
                            <div style="display:flex; justify-content: flex-end; align-items:center; gap:12px;">
                                <a href="read_essay.php?file=<?php echo $row['file']; ?>" class="btn-action btn-read"><i class="fa-solid fa-eye"></i></a>

                               <?php if (!empty($row['rank'])): ?>
    <?php
    $r = $row['rank'];
    
    // Style class aur Label dono ke liye naya logic
    if ($r == '1' || $r == '1st place') {
        $badge_class = 'rank-1';
        $r_lbl = '1st';
    } elseif ($r == '2' || $r == '2nd place') {
        $badge_class = 'rank-2';
        $r_lbl = '2nd';
    } else {
        $badge_class = 'rank-3';
        $r_lbl = '3rd';
    }
    ?>
    
    <span class="rank-badge <?php echo $badge_class; ?>">
        <i class="fa-solid fa-crown"></i> <?php echo $r_lbl; ?> Place
    </span>
    
    <button onclick="resetRank(<?php echo $row['id']; ?>)" style="background:none; border:none; color:#ef4444; cursor:pointer;">
        <i class="fa-solid fa-xmark"></i>
    </button>

<?php else: ?>
    <button onclick="setRank(<?php echo $row['id']; ?>)" class="btn-action btn-rank">
        <i class="fa-solid fa-medal"></i> Grade
    </button>
<?php endif; ?>

                                <button onclick="confirmDelete(<?php echo $row['id']; ?>)" style="background:none; border:none; color:#475569; cursor:pointer;"><i class="fa-solid fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
   function setRank(subId) {
    Swal.fire({
        title: 'Award Position',
        text: 'Select the rank for this essay submission',
        icon: 'star',
        input: 'select',
        inputOptions: {
            '1st place': '🥇 1st Position (Gold)',
            '2nd place': '🥈 2nd Position (Silver)',
            '3rd place': '🥉 3rd Position (Bronze)'
        },
        inputPlaceholder: 'Select Rank',
        showCancelButton: true,
        background: '#141920',
        color: '#fff',
        confirmButtonColor: '#c9a84c',
        confirmButtonText: 'Confirm Rank',
        // --- Naya Section: Dropdown style change karne ke liye ---
        customClass: {
            input: 'my-swal-input' 
        },
        // -------------------------------------------------------
        inputValidator: (value) => {
            if (!value) {
                return 'Please select a position!';
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `view_submissions.php?set_rank=1&sub_id=${subId}&rank=${result.value}`;
        }
    });
}

    function resetRank(subId) {
        Swal.fire({
            title: 'Reset Rank?',
            text: "Remove the winning status of this essay?",
            icon: 'warning',
            showCancelButton: true,
            background: '#141920',
            color: '#fff',
            confirmButtonColor: '#ef4444'
        }).then(r => {
            if (r.isConfirmed) window.location.href = 'view_submissions.php?reset_rank=1&sub_id=' + subId;
        });
    }

    function confirmDelete(id) {
        Swal.fire({
            title: 'Delete?',
            icon: 'warning',
            showCancelButton: true,
            background: '#141920',
            color: '#fff',
            confirmButtonColor: '#ef4444'
        }).then(r => {
            if (r.isConfirmed) window.location.href = 'view_submissions.php?delete_id=' + id;
        });
    }
</script>

<?php include("admin_footer.php"); ?>