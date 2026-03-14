<?php
include("../config/db.php");
session_start();

// 1. Get Submission Details
if (isset($_GET['submission_id'])) {
    $s_id = mysqli_real_escape_string($conn, $_GET['submission_id']);
    $query = "SELECT s.*, u.name as user_name, c.title as comp_title 
              FROM submissions s 
              JOIN users u ON s.user_id = u.id 
              JOIN competitions c ON s.competition_id = c.id 
              WHERE s.id = '$s_id'";
    $res = mysqli_query($conn, $query);
    $data = mysqli_fetch_assoc($res);
}

// 2. Announce Winner Logic
if (isset($_POST['announce_winner'])) {
    $submission_id = $_POST['submission_id'];
    $rank = mysqli_real_escape_string($conn, $_POST['rank']);

    // Update submission table with Rank (Winner status)
    $update = "UPDATE submissions SET rank = '$rank' WHERE id = '$submission_id'";
    
    if (mysqli_query($conn, $update)) {
        echo "<script>alert('Winner Announced Successfully!'); window.location.href='submissions.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Winner</title>
    <style>
        body { background: #0d0d0d; color: #fff; font-family: sans-serif; padding: 50px; }
        .card { background: #141920; padding: 30px; border-radius: 10px; max-width: 600px; margin: auto; border: 1px solid #c9a84c; }
        h2 { color: #c9a84c; margin-bottom: 20px; }
        .info { margin-bottom: 15px; border-bottom: 1px solid #222; padding-bottom: 10px; }
        select, input, button { width: 100%; padding: 12px; margin-top: 10px; border-radius: 5px; border: none; }
        select { background: #1c2333; color: #fff; border: 1px solid #333; }
        button { background: #c9a84c; color: #0d0d0d; font-weight: bold; cursor: pointer; margin-top: 20px; }
    </style>
</head>
<body>

<div class="card">
    <h2>🏆 Announce Winner</h2>
    
    <div class="info">
        <strong>Participant:</strong> <?php echo $data['user_name']; ?><br>
        <strong>Competition:</strong> <?php echo $data['comp_title']; ?>
    </div>

    <form method="POST">
        <input type="hidden" name="submission_id" value="<?php echo $data['id']; ?>">
        
        <label>Select Prize / Rank:</label>
        <select name="rank" required>
            <option value="">-- Choose Prize --</option>
            <option value="1st Prize">1st Prize 🥇</option>
            <option value="2nd Prize">2nd Prize 🥈</option>
            <option value="3rd Prize">3rd Prize 🥉</option>
            <option value="Special Mention">Special Mention</option>
            <option value="Runner Up">Runner Up</option>
        </select>

        <button type="submit" name="announce_winner">ANNOUNCE WINNER NOW</button>
        <a href="submissions.php" style="display:block; text-align:center; color:#aaa; margin-top:15px; text-decoration:none;">Cancel</a>
    </form>
</div>

</body>
</html>