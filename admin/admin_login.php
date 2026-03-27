<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="file.svg">
    <title>Book-Astra | Your Premium Book Store</title>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'DM Sans', sans-serif; }
        
        body { 
            min-height: 100vh; 
            background: #0d0d0d; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            position: relative; 
            overflow: hidden; 
        }

        /* Ambient Background Background */
        body::before { 
            content: ''; 
            position: absolute; 
            inset: 0; 
            background: radial-gradient(circle at 50% -20%, rgba(201,168,76,0.15) 0%, transparent 50%),
                        radial-gradient(circle at 0% 100%, rgba(201,168,76,0.05) 0%, transparent 40%);
        }

        .card { 
            width: 400px; 
            background: rgba(20, 25, 32, 0.8); /* Slight transparency */
            backdrop-filter: blur(10px); /* Modern glass effect */
            border: 1px solid rgba(255,255,255,0.05);
            border-top: 1px solid rgba(201,168,76,0.3); /* Top highlight */
            border-radius: 20px; 
            padding: 45px 40px; 
            position: relative; 
            z-index: 1; 
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .logo { text-align: center; margin-bottom: 35px; }
        
        /* Smooth floating animation for logo */
      .logo-icon { 
    width: 65px; 
    height: 65px; 
    /* Premium Gold Gradient Background */
    background: linear-gradient(135deg, rgba(201,168,76,0.15), rgba(201,168,76,0.05)); 
    border: 1px solid rgba(201,168,76,0.3); 
    border-radius: 16px; 
    display: flex; 
    align-items: center; 
    justify-content: center; 
    margin: 0 auto 16px;
    overflow: hidden; /* Image bahar na nikle */
    /* Animation line yahan se hata di gayi hai */
}
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-8px); }
        }

        .logo-icon img { 
            color: #c9a84c; 
            font-size: 1.6rem;
            width: 100%;
            height:100%; 
            object-fit: cover;
            display :block;
            width: 100%;
            filter: drop-shadow(0 0 8px rgba(201,168,76,0.3));

         }
        .logo h1 { 
            font-family: 'Cormorant Garamond', serif; 
            color: #c9a84c; 
            font-size: 1.8rem; 
            font-weight: 700; 
            letter-spacing: 1px;
        }
        
        .logo p { color: #64748b; font-size: 0.7rem; margin-top: 5px; letter-spacing: 0.15em; text-transform: uppercase; }

        .err { 
            background: rgba(239, 68, 68, 0.1); 
            border-left: 3px solid #f87171; 
            color: #f87171; 
            padding: 12px 16px; 
            border-radius: 6px; 
            margin-bottom: 20px; 
            font-size: 0.82rem; 
            animation: shake 0.4s ease-in-out;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .field { margin-bottom: 20px; }
        .field label { font-size: 0.65rem; font-weight: 700; color: #94a3b8; display: block; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.1em; }

        .input-wrap { position: relative; }
        .input-wrap i { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #475569; font-size: 0.85rem; transition: color 0.3s; }

        .input-wrap input { 
            width: 100%; 
            padding: 12px 14px 12px 42px; 
            border: 1px solid #1e2530; 
            border-radius: 10px; 
            font-size: 0.9rem; 
            background: #09090b; 
            color: #f1f5f9; 
            outline: none; 
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
        }

        .input-wrap input:focus { 
            border-color: #c9a84c; 
            box-shadow: 0 0 0 4px rgba(201,168,76,0.1);
            background: #0d0d0d;
        }

        .input-wrap input:focus + i { color: #c9a84c; }

        .btn { 
            width: 100%; 
            background: #c9a84c; 
            color: #0d0d0d; 
            padding: 14px; 
            border-radius: 10px; 
            font-weight: 700; 
            font-size: 0.95rem; 
            border: none; 
            cursor: pointer; 
            margin-top: 10px; 
            transition: all 0.3s; 
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn:hover { 
            background: #dfc070; 
            transform: translateY(-2px); 
            box-shadow: 0 8px 20px rgba(201,168,76,0.2); 
        }

        .btn:active { transform: translateY(0); }

        /* Subtle footer text */
        .footer-note { text-align: center; margin-top: 25px; color: #475569; font-size: 0.75rem; }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo">
            <div class="logo-icon"><img src="file.svg" alt=""></div>
            <h1>Book-Astra</h1>
            <p>Admin Portal Access</p>
        </div>

        <?php if(isset($_GET['error'])): ?>
            <div class="err">
                <i class="fa-solid fa-circle-exclamation"></i> 
                Invalid credentials. Please try again.
            </div>
        <?php endif; ?>

        <form method="POST" action="admin_login_process.php" autocomplete="off">
            <div class="field">
                <label>Username</label>
                <div class="input-wrap">
                    <i class="fa-solid fa-user"></i>
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
            <button type="submit" class="btn">
                <span>Sign In to Dashboard</span>
                <i class="fa-solid fa-arrow-right-long"></i>
            </button>
        </form>
        
        <div class="footer-note">
            Secure Admin Encryption Active
        </div>
    </div>

    <?php if(isset($_GET['logout'])): ?>
    <script>
        Swal.fire({
            title: 'Logged Out!',
            text: 'Your session has been securely closed.',
            icon: 'success',
            background: '#141920',
            color: '#e2e8f0',
            confirmButtonColor: '#c9a84c',
            iconColor: '#c9a84c',
            timer: 3000,
            showConfirmButton: false
        });
    </script>
    <?php endif; ?>
</body>
</html>