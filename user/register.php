<?php
session_start();
include("../config/db.php");

$msg = "";

if(isset($_POST['register'])){
    $name     = mysqli_real_escape_string($conn, $_POST['name']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = md5($_POST['password']);
    $phone    = mysqli_real_escape_string($conn, $_POST['phone']);
    $address  = isset($_POST['address']) ? mysqli_real_escape_string($conn, $_POST['address']) : '';

    // Pehle check karo email already exist karta hai ya nahi
    $check = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");
    if(mysqli_num_rows($check) > 0){
        $msg = "This email is already registered. Please login.";
    } else {
        // address aur created_at ke saath try karo
        $sql = "INSERT INTO users(name, email, password, phone, address, created_at) VALUES('$name','$email','$password','$phone','$address',NOW())";
        if(!mysqli_query($conn, $sql)){
            // agar address/created_at column nahi hai to simple insert
            $sql = "INSERT INTO users(name, email, password, phone) VALUES('$name','$email','$password','$phone')";
            if(mysqli_query($conn, $sql)){
                $_SESSION['success_msg'] = "Account created successfully! Please sign in.";
                header("Location: login.php");
                exit();
            } else {
                $msg = "Registration failed: " . mysqli_error($conn);
            }
        } else {
            $_SESSION['success_msg'] = "Account created successfully! Please sign in.";
            header("Location: login.php");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Create Account | E-Library</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;0,700;1,300;1,400&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
:root{--gold:#c9a84c;--gold-light:#e8c96a;--ink:#0d0d0d;--surface:#141920;--surface2:#1c2333;--border:rgba(255,255,255,0.08);--muted:rgba(255,255,255,0.38);}
html,body{height:100%;}
body{background:var(--ink);color:#f0ece4;font-family:'DM Sans',sans-serif;display:flex;min-height:100vh;overflow:hidden;}
.left-panel{width:46%;flex-shrink:0;position:relative;background:#060a0e;overflow:hidden;display:flex;align-items:center;justify-content:center;}
.left-panel::after{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 65% 55% at 25% 20%,rgba(201,168,76,0.12) 0%,transparent 65%),radial-gradient(ellipse 45% 35% at 85% 80%,rgba(201,168,76,0.06) 0%,transparent 60%);pointer-events:none;z-index:1;}
.left-panel::before{content:'';position:absolute;inset:0;z-index:2;pointer-events:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='300' height='300'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4'/%3E%3C/filter%3E%3Crect width='300' height='300' filter='url(%23n)' opacity='0.06'/%3E%3C/svg%3E");background-size:200px;opacity:0.6;}
.watermark{position:absolute;z-index:1;font-family:'Cormorant Garamond',serif;font-size:28vw;font-weight:700;color:rgba(255,255,255,0.018);line-height:1;top:50%;left:50%;transform:translate(-50%,-50%);pointer-events:none;user-select:none;letter-spacing:-0.05em;}
.q{position:absolute;z-index:3;font-family:'Cormorant Garamond',serif;font-style:italic;color:rgba(201,168,76,0.2);line-height:1.6;pointer-events:none;animation:drift var(--dur,18s) ease-in-out infinite;animation-delay:var(--delay,0s);}
@keyframes drift{0%,100%{transform:translateY(0) rotate(var(--rot,-1deg));opacity:0.45;}40%{transform:translateY(-14px) rotate(calc(var(--rot) * -0.5));opacity:0.9;}70%{transform:translateY(-8px) rotate(var(--rot));opacity:0.65;}}
.panel-inner{position:relative;z-index:4;padding:52px 48px;max-width:410px;width:100%;}
.p-eyebrow{font-size:0.58rem;letter-spacing:0.28em;text-transform:uppercase;color:var(--gold);font-weight:700;margin-bottom:18px;display:flex;align-items:center;gap:10px;}
.p-eyebrow::before{content:'';width:24px;height:1px;background:var(--gold);}
.p-title{font-family:'Cormorant Garamond',serif;font-size:clamp(2.1rem,3.2vw,2.9rem);font-weight:700;color:#fff;line-height:1.12;margin-bottom:14px;}
.p-title em{color:var(--gold);font-style:italic;}
.p-desc{font-size:0.79rem;color:rgba(255,255,255,0.36);line-height:1.85;margin-bottom:34px;}
.p-sep{display:flex;align-items:center;gap:10px;margin-bottom:28px;}
.p-sep::before,.p-sep::after{content:'';flex:1;height:1px;background:rgba(255,255,255,0.06);}
.p-sep-dot{width:5px;height:5px;border-radius:50%;background:var(--gold);box-shadow:0 0 10px rgba(201,168,76,0.6);}
.feat{display:flex;align-items:center;gap:13px;margin-bottom:13px;}
.feat-ic{width:30px;height:30px;border-radius:5px;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:0.72rem;}
.feat span{font-size:0.77rem;color:rgba(255,255,255,0.45);}
.spine-shelf{position:absolute;bottom:0;left:0;right:0;z-index:3;display:flex;align-items:flex-end;gap:3px;padding:0 20px;height:90px;overflow:hidden;}
.sp{border-radius:2px 2px 0 0;flex-shrink:0;animation:spineRise 1s ease-out both;position:relative;overflow:hidden;}
.sp::after{content:'';position:absolute;inset:0;background:linear-gradient(180deg,rgba(255,255,255,0.06) 0%,transparent 50%);}
.sp:nth-child(1){width:16px;height:55px;background:#1a2a1a;animation-delay:0.05s;}
.sp:nth-child(2){width:22px;height:72px;background:#2a1a0a;animation-delay:0.10s;}
.sp:nth-child(3){width:14px;height:48px;background:#0a1a2a;animation-delay:0.15s;}
.sp:nth-child(4){width:18px;height:80px;background:#1a0a2a;animation-delay:0.20s;}
.sp:nth-child(5){width:26px;height:62px;background:#2a1a1a;animation-delay:0.25s;}
.sp:nth-child(6){width:12px;height:44px;background:#0a2a1a;animation-delay:0.30s;}
.sp:nth-child(7){width:20px;height:85px;background:linear-gradient(180deg,rgba(201,168,76,0.25),rgba(201,168,76,0.08));border:1px solid rgba(201,168,76,0.18);animation-delay:0.35s;}
.sp:nth-child(8){width:16px;height:58px;background:#1a1a2a;animation-delay:0.40s;}
.sp:nth-child(9){width:24px;height:70px;background:#2a0a0a;animation-delay:0.45s;}
.sp:nth-child(10){width:14px;height:50px;background:#0a1a0a;animation-delay:0.50s;}
.sp:nth-child(11){width:18px;height:76px;background:#1a2a2a;animation-delay:0.55s;}
.sp:nth-child(12){width:22px;height:60px;background:#2a2a0a;animation-delay:0.60s;}
.sp:nth-child(13){width:16px;height:88px;background:linear-gradient(180deg,rgba(201,168,76,0.18),rgba(201,168,76,0.05));border:1px solid rgba(201,168,76,0.12);animation-delay:0.65s;}
.sp:nth-child(14){width:20px;height:52px;background:#0a0a1a;animation-delay:0.70s;}
.sp:nth-child(15){width:14px;height:64px;background:#1a0a1a;animation-delay:0.75s;}
.sp:nth-child(16){width:26px;height:42px;background:#2a1a2a;animation-delay:0.80s;}
@keyframes spineRise{from{transform:translateY(100%);}to{transform:translateY(0);}}
.right-panel{flex:1;background:var(--ink);display:flex;align-items:center;justify-content:center;padding:40px 52px;overflow-y:auto;position:relative;}
.right-panel::before{content:'';position:absolute;left:0;top:15%;bottom:15%;width:1px;background:linear-gradient(to bottom,transparent,rgba(201,168,76,0.18) 25%,rgba(201,168,76,0.18) 75%,transparent);}
.form-wrap{width:100%;max-width:370px;}
.r-topbar{display:flex;justify-content:space-between;align-items:center;margin-bottom:32px;}
.r-brand{font-family:'Cormorant Garamond',serif;font-size:1.05rem;font-weight:700;color:#fff;text-decoration:none;}
.r-brand span{color:var(--gold);}
.r-signin{font-size:0.72rem;color:var(--muted);text-decoration:none;display:inline-flex;align-items:center;gap:6px;border:1px solid var(--border);padding:6px 13px;border-radius:5px;transition:all 0.2s;}
.r-signin:hover{color:#fff;border-color:rgba(255,255,255,0.2);}
.r-eyebrow{font-size:0.58rem;letter-spacing:0.22em;text-transform:uppercase;color:var(--gold);font-weight:700;margin-bottom:9px;}
.r-title{font-family:'Cormorant Garamond',serif;font-size:1.9rem;font-weight:700;color:#fff;margin-bottom:5px;}
.r-sub{font-size:0.76rem;color:var(--muted);margin-bottom:26px;}
.err-box{display:flex;align-items:center;gap:9px;background:rgba(224,92,92,0.07);border:1px solid rgba(224,92,92,0.18);color:#e05c5c;font-size:0.77rem;padding:10px 13px;border-radius:7px;margin-bottom:20px;}
.fg{position:relative;margin-bottom:16px;}
.fg .fi{width:100%;background:var(--surface);border:1px solid var(--border);border-radius:8px;padding:21px 16px 8px 44px;color:#fff;font-size:0.85rem;font-family:'DM Sans',sans-serif;outline:none;transition:border-color 0.25s,box-shadow 0.25s;}
.fg .fi:focus{border-color:rgba(201,168,76,0.4);box-shadow:0 0 0 3px rgba(201,168,76,0.06);}
.fg .fi::placeholder{color:transparent;}
.fg .fl{position:absolute;left:44px;top:50%;transform:translateY(-50%);font-size:0.82rem;color:rgba(255,255,255,0.28);pointer-events:none;transition:all 0.22s ease;font-family:'DM Sans',sans-serif;}
.fg .fi:focus ~ .fl,.fg .fi:not(:placeholder-shown) ~ .fl{top:9px;transform:none;font-size:0.6rem;letter-spacing:0.12em;text-transform:uppercase;color:var(--gold);font-weight:700;}
.fg .fic{position:absolute;left:15px;top:50%;transform:translateY(-50%);color:rgba(255,255,255,0.2);font-size:0.78rem;pointer-events:none;transition:color 0.22s;}
.fg:focus-within .fic{color:var(--gold);}
.fg .check-mark{position:absolute;right:14px;top:50%;transform:translateY(-50%);color:#4a7c59;font-size:0.78rem;opacity:0;transition:opacity 0.2s;}
.fg .fi:valid:not(:placeholder-shown) ~ .check-mark{opacity:1;}
.pwd-btn{position:absolute;right:14px;top:50%;transform:translateY(-50%);background:none;border:none;color:rgba(255,255,255,0.22);cursor:pointer;font-size:0.78rem;padding:4px;transition:color 0.2s;}
.pwd-btn:hover{color:var(--gold);}
.str-wrap{margin-top:6px;padding:0 2px;display:none;}
.str-wrap.show{display:block;}
.str-bars{display:flex;gap:4px;margin-bottom:5px;}
.sb{height:3px;flex:1;border-radius:2px;background:rgba(255,255,255,0.07);transition:background 0.3s;}
.sb.w{background:#e05c5c;}.sb.f{background:#e8a84c;}.sb.g{background:var(--gold);}.sb.s{background:#4a7c59;}
.str-lbl{font-size:0.63rem;color:rgba(255,255,255,0.28);}
.btn-go{width:100%;background:var(--gold);color:#0d0d0d;border:none;border-radius:8px;padding:14px;margin-top:10px;font-size:0.84rem;font-weight:700;font-family:'DM Sans',sans-serif;cursor:pointer;letter-spacing:0.05em;position:relative;overflow:hidden;transition:background 0.2s,transform 0.15s;}
.btn-go::after{content:'';position:absolute;top:0;left:-110%;width:55%;height:100%;background:linear-gradient(90deg,transparent,rgba(255,255,255,0.28),transparent);transition:left 0.55s;}
.btn-go:hover{background:var(--gold-light);transform:translateY(-1px);}
.btn-go:hover::after{left:150%;}
.r-foot{text-align:center;margin-top:18px;font-size:0.76rem;color:var(--muted);}
.r-foot a{color:var(--gold);font-weight:600;text-decoration:none;margin-left:4px;}
.r-foot a:hover{color:var(--gold-light);}
@media(max-width:860px){body{flex-direction:column;overflow:auto;}.left-panel{width:100%;min-height:200px;}.panel-inner{padding:28px 24px;}.right-panel{padding:28px 24px;}.right-panel::before{display:none;}.spine-shelf{height:60px;}.watermark{font-size:18vw;}.q{display:none;}}
</style>
</head>
<body>

<div class="left-panel">
  <div class="watermark">E</div>
  <div class="q" style="top:7%;left:5%;font-size:0.82rem;--dur:20s;--delay:0s;--rot:-1.5deg;max-width:200px;">"A reader lives a thousand<br>lives before he dies."</div>
  <div class="q" style="top:20%;right:3%;font-size:0.7rem;--dur:17s;--delay:5s;--rot:1deg;max-width:180px;text-align:right;">"Books are a uniquely<br>portable magic."<br><span style="font-size:0.6rem;opacity:0.6;">— Stephen King</span></div>
  <div class="q" style="bottom:32%;left:4%;font-size:0.75rem;--dur:22s;--delay:9s;--rot:-1deg;max-width:190px;">"I declare after all there is no<br>enjoyment like reading!"</div>
  <div class="q" style="bottom:18%;right:5%;font-size:0.68rem;--dur:19s;--delay:3s;--rot:1.2deg;max-width:160px;text-align:right;">"So many books,<br>so little time."</div>
  <div class="panel-inner">
    <div class="p-eyebrow">Join E-Library Today</div>
    <h1 class="p-title">Your next great<br>read is waiting<br>for <em>you.</em></h1>
    <p class="p-desc">Join thousands of readers across Pakistan who have discovered the joy of unlimited digital reading.</p>
    <div class="p-sep"><div class="p-sep-dot"></div></div>
    <div class="feat"><div class="feat-ic" style="background:rgba(201,168,76,0.1);color:#c9a84c;"><i class="fa-solid fa-bolt"></i></div><span>Instant PDF access after purchase</span></div>
    <div class="feat"><div class="feat-ic" style="background:rgba(74,124,89,0.12);color:#6abd7c;"><i class="fa-solid fa-book-open"></i></div><span>Hundreds of free books available</span></div>
    <div class="feat"><div class="feat-ic" style="background:rgba(99,102,241,0.12);color:#818cf8;"><i class="fa-solid fa-trophy"></i></div><span>Participate in essay competitions</span></div>
    <div class="feat"><div class="feat-ic" style="background:rgba(224,92,92,0.1);color:#f87171;"><i class="fa-solid fa-mobile-screen"></i></div><span>Read on any device, anywhere</span></div>
  </div>
  <div class="spine-shelf">
    <div class="sp"></div><div class="sp"></div><div class="sp"></div><div class="sp"></div>
    <div class="sp"></div><div class="sp"></div><div class="sp"></div><div class="sp"></div>
    <div class="sp"></div><div class="sp"></div><div class="sp"></div><div class="sp"></div>
    <div class="sp"></div><div class="sp"></div><div class="sp"></div><div class="sp"></div>
  </div>
</div>

<div class="right-panel">
  <div class="form-wrap">
    <div class="r-topbar">
      <a href="index.php" class="r-brand">📚 <span>E-Library</span></a>
      <a href="login.php" class="r-signin"><i class="fa-solid fa-arrow-right-to-bracket"></i> Sign In</a>
    </div>
    <div class="r-eyebrow">✦ Free Registration</div>
    <h2 class="r-title">Create Account</h2>
    <p class="r-sub">Start your digital reading journey today</p>

    <?php if($msg != ""): ?>
    <div class="err-box"><i class="fa-solid fa-circle-exclamation"></i> <?php echo $msg; ?></div>
    <?php endif; ?>

    <form method="POST" id="regForm">
      <div class="fg">
        <i class="fic fa-regular fa-user"></i>
        <input type="text" name="name" id="fn" class="fi" placeholder="Full Name" required autocomplete="name">
        <label class="fl" for="fn">Full Name</label>
        <i class="fa-solid fa-check check-mark"></i>
      </div>
      <div class="fg">
        <i class="fic fa-regular fa-envelope"></i>
        <input type="email" name="email" id="fe" class="fi" placeholder="Email Address" required autocomplete="email">
        <label class="fl" for="fe">Email Address</label>
        <i class="fa-solid fa-check check-mark"></i>
      </div>
      <div class="fg">
        <i class="fic fa-solid fa-mobile-screen"></i>
        <input type="text" name="phone" id="fph" class="fi" placeholder="Phone Number" required autocomplete="tel">
        <label class="fl" for="fph">Phone Number</label>
        <i class="fa-solid fa-check check-mark"></i>
      </div>
      <div class="fg">
        <i class="fic fa-solid fa-location-dot"></i>
        <input type="text" name="address" id="fadd" class="fi" placeholder="Your Address" autocomplete="street-address">
        <label class="fl" for="fadd">Address (Optional)</label>
        <i class="fa-solid fa-check check-mark"></i>
      </div>
      <div class="fg">
        <i class="fic fa-solid fa-lock"></i>
        <input type="password" name="password" id="fpass" class="fi" placeholder="Password" required autocomplete="new-password" oninput="checkStr(this.value)">
        <label class="fl" for="fpass">Password</label>
        <button type="button" class="pwd-btn" onclick="toggleP()"><i class="fa-regular fa-eye" id="eyeIco"></i></button>
      </div>
      <div class="str-wrap" id="strWrap">
        <div class="str-bars">
          <div class="sb" id="sb1"></div><div class="sb" id="sb2"></div>
          <div class="sb" id="sb3"></div><div class="sb" id="sb4"></div>
        </div>
        <div class="str-lbl" id="strLbl"></div>
      </div>
      <button type="submit" name="register" class="btn-go">Create My Account →</button>
    </form>
    <div class="r-foot">Already have an account?<a href="login.php">Sign In</a></div>
  </div>
</div>

<script>
function toggleP(){var i=document.getElementById('fpass');var ic=document.getElementById('eyeIco');i.type=i.type==='password'?'text':'password';ic.classList.toggle('fa-eye');ic.classList.toggle('fa-eye-slash');}
function checkStr(v){var w=document.getElementById('strWrap');var l=document.getElementById('strLbl');var bs=[document.getElementById('sb1'),document.getElementById('sb2'),document.getElementById('sb3'),document.getElementById('sb4')];bs.forEach(function(b){b.className='sb';});if(!v){w.classList.remove('show');return;}w.classList.add('show');var sc=0;if(v.length>=6)sc++;if(v.length>=10)sc++;if(/[A-Z]/.test(v)&&/[0-9]/.test(v))sc++;if(/[^A-Za-z0-9]/.test(v))sc++;var lvl=[{c:'w',t:'Weak — keep going'},{c:'f',t:'Fair — getting better'},{c:'g',t:'Good — almost there'},{c:'s',t:'Strong — great!'}];for(var i=0;i<sc;i++)bs[i].classList.add(lvl[sc-1].c);l.textContent=lvl[sc-1].t;}
</script>
</body>
</html>
