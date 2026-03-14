<?php
ob_start(); 
session_start();
include("../config/db.php");

$msg = "";

if (isset($_POST['login'])) {
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = md5($_POST['password']);
    
    $sql      = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result   = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        
        // Try PHP redirect first
        header("Location: index.php");
        
        // If PHP redirect fails because of the header error, this JS will run:
        echo "<script>window.location.href='index.php';</script>";
        exit();
    } else {
        $msg = "Invalid Email or Password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sign In | E-Library</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;0,700;1,400;1,600&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
:root{
  --gold:#c9a84c;--gold-light:#e8c96a;
  --ink:#0d0d0d;--surface:#141920;--surface2:#1c2333;
  --border:rgba(255,255,255,0.08);--muted:rgba(255,255,255,0.38);
}
html,body{height:100%;}
body{
  background:var(--ink);color:#f0ece4;
  font-family:'DM Sans',sans-serif;
  display:flex;min-height:100vh;overflow:hidden;
}
.left-panel{width:46%;flex-shrink:0;position:relative;background:#06080b;overflow:hidden;display:flex;align-items:center;justify-content:center;}
.left-panel::after{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 60% 50% at 20% 30%, rgba(99,102,241,0.09) 0%,transparent 60%),radial-gradient(ellipse 55% 45% at 85% 70%, rgba(201,168,76,0.1) 0%,transparent 60%);pointer-events:none;z-index:1;}
.watermark{position:absolute;z-index:1;font-family:'Cormorant Garamond',serif;font-size:30vw;font-weight:700;color:rgba(255,255,255,0.016);top:50%;left:50%;transform:translate(-50%,-50%);pointer-events:none;user-select:none;}
.panel-inner{position:relative;z-index:4;padding:52px 48px;max-width:410px;width:100%;}
.p-eyebrow{font-size:0.58rem;letter-spacing:0.28em;text-transform:uppercase;color:var(--gold);font-weight:700;margin-bottom:18px;display:flex;align-items:center;gap:10px;}
.p-eyebrow::before{content:'';width:24px;height:1px;background:var(--gold);}
.p-title{font-family:'Cormorant Garamond',serif;font-size:clamp(2.1rem,3.2vw,2.9rem);font-weight:700;color:#fff;line-height:1.12;margin-bottom:14px;}
.p-title em{color:var(--gold);font-style:italic;}
.p-desc{font-size:0.79rem;color:rgba(255,255,255,0.36);line-height:1.85;margin-bottom:34px;}
.right-panel{flex:1;background:var(--ink);display:flex;align-items:center;justify-content:center;padding:40px 52px;overflow-y:auto;position:relative;}
.form-wrap{width:100%;max-width:370px;}
.r-topbar{display:flex;justify-content:space-between;align-items:center;margin-bottom:40px;}
.r-brand{font-family:'Cormorant Garamond',serif;font-size:1.05rem;font-weight:700;color:#fff;text-decoration:none;}
.r-brand span{color:var(--gold);}
.r-register{font-size:0.72rem;color:var(--muted);text-decoration:none;border:1px solid var(--border);padding:6px 13px;border-radius:5px;}
.r-title{font-family:'Cormorant Garamond',serif;font-size:2.1rem;font-weight:700;color:#fff;margin-bottom:5px;}
.alert-err{background:rgba(224,92,92,0.07);border:1px solid rgba(224,92,92,0.18);color:#e05c5c;font-size:0.77rem;padding:11px 13px;border-radius:7px;margin-bottom:20px;}
.fg{position:relative;margin-bottom:18px;}
.fg .fi{width:100%;background:var(--surface);border:1px solid var(--border);border-radius:8px;padding:21px 16px 8px 44px;color:#fff;font-size:0.85rem;outline:none;}
.fg .fi:focus{border-color:var(--gold);}
.fg .fl{position:absolute;left:44px;top:50%;transform:translateY(-50%);font-size:0.82rem;color:rgba(255,255,255,0.28);pointer-events:none;transition:all 0.22s ease;}
.fg .fi:focus ~ .fl, .fg .fi:not(:placeholder-shown) ~ .fl{top:9px;transform:none;font-size:0.6rem;color:var(--gold);}
.fg .fic{position:absolute;left:15px;top:50%;transform:translateY(-50%);color:rgba(255,255,255,0.2);font-size:0.78rem;}
.btn-go{width:100%;background:var(--gold);color:#0d0d0d;border:none;border-radius:8px;padding:14px;font-weight:700;cursor:pointer;letter-spacing:0.05em;}
.btn-go:hover{background:var(--gold-light);}
@media(max-width:860px){.left-panel{display:none;}}
</style>
</head>
<body>

<div class="left-panel">
  <div class="watermark">L</div>
  <div class="panel-inner">
    <div class="p-eyebrow">Welcome Back</div>
    <h1 class="p-title">Continue your<br><em>reading</em><br>journey.</h1>
    <p class="p-desc">Sign back in and pick up right where you left off. Your library is waiting.</p>
  </div>
</div>

<div class="right-panel">
  <div class="form-wrap">
    <div class="r-topbar">
      <a href="index.php" class="r-brand">📚 <span>E-Library</span></a>
      <a href="register.php" class="r-register">Register</a>
    </div>

    <h2 class="r-title">Welcome Back</h2>
    <p style="color:var(--muted); font-size:0.8rem; margin-bottom:20px;">Sign in to your account</p>

    <?php if($msg != ""): ?>
      <div class="alert-err"><i class="fa-solid fa-triangle-exclamation"></i> <?php echo $msg; ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="fg">
        <i class="fic fa-regular fa-envelope"></i>
        <input type="email" name="email" class="fi" placeholder=" " required>
        <label class="fl">Email Address</label>
      </div>
      <div class="fg">
        <i class="fic fa-solid fa-lock"></i>
        <input type="password" name="password" class="fi" placeholder=" " required>
        <label class="fl">Password</label>
      </div>
      <button type="submit" name="login" class="btn-go">Sign In →</button>
    </form>
  </div>
</div>

</body>
</html>
<?php ob_end_flush(); ?>