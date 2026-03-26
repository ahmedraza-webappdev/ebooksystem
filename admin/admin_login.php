<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- <title>Admin Login | E-Library</title> -->
    <link rel="icon" type="image/png" href="file.svg">
<title>Book-Astra | Your Premium Book Store</title>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 for beautiful messages -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* Aapki purani CSS yahan rahegi (Maine wahi rakhi hai) */
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'DM Sans', sans-serif; }
        body { min-height: 100vh; background: #0d0d0d; display: flex; align-items: center; justify-content: center; position: relative; overflow: hidden; }
        body::before { content: ''; position: absolute; inset: 0; background: radial-gradient(ellipse 70% 50% at 50% 0%, rgba(201,168,76,0.07) 0%, transparent 65%); }
        .card { width: 400px; background: #141920; border: 1px solid #1e2530; border-radius: 14px; padding: 40px; position: relative; z-index: 1; }
        .logo { text-align: center; margin-bottom: 32px; }
        .logo-icon { width: 58px; height: 58px; background: rgba(201,168,76,0.1); border: 1px solid rgba(201,168,76,0.22); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 14px; }
        .logo-icon i { color: #c9a84c; font-size: 1.4rem; }
        .logo h1 { font-family: 'Cormorant Garamond', serif; color: #c9a84c; font-size: 1.6rem; font-weight: 700; }
        .logo p { color: #4a5568; font-size: 0.75rem; margin-top: 4px; letter-spacing: 0.06em; text-transform: uppercase; }
        .err { background: rgba(239,68,68,0.07); border: 1px solid rgba(239,68,68,0.18); color: #f87171; padding: 11px 14px; border-radius: 8px; margin-bottom: 18px; font-size: 0.82rem; font-weight: 600; display: flex; align-items: center; gap: 8px; }
        .field { margin-bottom: 16px; }
        .field label { font-size: 0.68rem; font-weight: 700; color: #4a5568; display: block; margin-bottom: 7px; text-transform: uppercase; letter-spacing: 0.08em; }
        .input-wrap { position: relative; }
        .input-wrap i { position: absolute; left: 13px; top: 50%; transform: translateY(-50%); color: #2d3748; font-size: 0.8rem; }
        .input-wrap input { width: 100%; padding: 11px 14px 11px 38px; border: 1px solid #1e2530; border-radius: 8px; font-size: 0.845rem; background: #0d0d0d; color: #e2e8f0; outline: none; font-family: 'DM Sans', sans-serif; transition: all 0.2s; }
        .input-wrap input:focus { border-color: rgba(201,168,76,0.4); box-shadow: 0 0 0 3px rgba(201,168,76,0.07); }
        .input-wrap input::placeholder { color: #2a3445; }
        .btn { width: 100%; background: #c9a84c; color: #0d0d0d; padding: 13px; border-radius: 8px; font-weight: 700; font-size: 0.9rem; border: none; cursor: pointer; margin-top: 6px; transition: all 0.2s; font-family: 'DM Sans', sans-serif; letter-spacing: 0.02em; }
        .btn:hover { background: #dfc070; transform: translateY(-1px); }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo">
            <div class="logo-icon"><i class="fa-solid fa-book-open"></i></div>
            <h1>E-Library</h1>
            <p>Admin Panel</p>
        </div>

        <?php if(isset($_GET['error'])): ?>
            <div class="err"><i class="fa-solid fa-circle-exclamation"></i> Invalid username or password</div>
        <?php endif; ?>

        <!-- Added autocomplete="off" here -->
        <form method="POST" action="admin_login_process.php" autocomplete="off">
            <div class="field">
                <label>Username</label>
                <div class="input-wrap">
                    <i class="fa-solid fa-user"></i>
                    <!-- Use autocomplete="new-password" to trick modern browsers -->
                    <input type="text" name="username" placeholder="Enter username" required autocomplete="new-password">
                </div>
            </div>
            <div class="field">
                <label>Password</label>
                <div class="input-wrap">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" name="password" placeholder="Enter password" required autocomplete="new-password">
                </div>
            </div>
            <button type="submit" class="btn"><i class="fa-solid fa-right-to-bracket" style="margin-right:7px;"></i>Sign In</button>
        </form>
    </div>

    <?php if(isset($_GET['logout'])): ?>
    <script>
        Swal.fire({
            title: 'Logged Out!',
            text: 'You are Successfully Logout.',
            icon: 'success',
            background: '#141920',
            color: '#e2e8f0',
            confirmButtonColor: '#c9a84c',
            timer: 3000
        });
    </script>
    <?php endif; ?>
</body>
</html>