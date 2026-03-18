<?php
// admin_header.php - E-Library Dark Gold Theme (Fixed Scroll System)
if(!isset($page_title)) $page_title = "Dashboard";
if(!isset($page_subtitle)) $page_subtitle = "E-Book Admin Panel";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Book-Astra | Admin - <?php echo $page_title; ?></title>

<!-- Font Awesome aur Favicon ke liye (Optional: Agar aapke paas favicon icon hai) -->
<link rel="icon" type="image/x-icon" href="file.svg">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        
        :root {
            --gold: #c9a84c;
            --gold-light: #dfc070;
            --dark: #0d0d0d;
            --dark2: #141920;
            --dark3: #1c2333;
            --border: #1e2530;
            --muted: #4a5568;
            --sage: #4a7a59;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        
        /* FIX 1: Body overflow setup */
        body { 
            font-family: 'DM Sans', sans-serif; 
            background: #0a0c10; 
            color: #e2e8f0; 
            min-height: 100vh;
            overflow-x: hidden; /* Horizontal scroll rokhne ke liye */
        }

        .sidebar {
            width: 250px;
            background: #0d0d0d;
            border-right: 1px solid var(--border);
            height: 100vh; /* Fixed height */
            position: fixed;
            left: 0; top: 0;
            display: flex;
            flex-direction: column;
            z-index: 100;
        }
        
        .sidebar-logo {
            padding: 26px 22px 20px;
            border-bottom: 1px solid var(--border);
            flex-shrink: 0; /* Logo ko shrink hone se rokna */
        }
        .sidebar-logo h1 {
            font-family: 'Cormorant Garamond', serif;
            color: var(--gold);
            font-size: 1.3rem;
            font-weight: 700;
        }
        .sidebar-logo p { color: var(--muted); font-size: 0.65rem; margin-top: 4px; letter-spacing: 0.08em; text-transform: uppercase; }

        .nav-section-title { color: var(--muted); font-size: 0.58rem; font-weight: 700; letter-spacing: 0.18em; text-transform: uppercase; padding: 18px 22px 7px; }
        .nav-item {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 12px; color: #506070;
            font-size: 0.82rem; font-weight: 600;
            cursor: pointer; transition: all 0.2s;
            text-decoration: none; margin: 2px 10px; border-radius: 7px;
        }
        .nav-item:hover { background: rgba(201,168,76,0.07); color: #c9a84c; }
        .nav-item.active { background: rgba(201,168,76,0.1); color: var(--gold); border: 1px solid rgba(201,168,76,0.18); }
        .nav-icon { width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; border-radius: 6px; background: rgba(255,255,255,0.03); font-size: 0.76rem; flex-shrink: 0; }
        .nav-item.active .nav-icon { background: rgba(201,168,76,0.12); }

        .sidebar-footer { margin-top: auto; padding: 14px 10px; border-top: 1px solid var(--border); flex-shrink: 0; }
        .logout-btn { display: flex; align-items: center; gap: 10px; padding: 9px 12px; color: #ef4444; font-size: 0.82rem; font-weight: 600; cursor: pointer; border-radius: 7px; transition: all 0.2s; text-decoration: none; }
        .logout-btn:hover { background: rgba(239,68,68,0.08); }

        /* FIX 2: Main content width and scroll */
        .main-content { 
            margin-left: 250px; 
            padding: 26px; 
            min-height: 100vh;
            width: calc(100% - 250px); /* Poori width cover karne ke liye */
            display: flow-root; /* Layout collapse fix */
        }

        .topbar {
            background: var(--dark2);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 14px 22px;
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 24px;
        }
        .topbar h2 { font-family: 'Cormorant Garamond', serif; font-size: 1.5rem; font-weight: 700; color: #fff; }
        .topbar p { color: var(--muted); font-size: 0.72rem; margin-top: 2px; }
        .admin-badge { display: flex; align-items: center; gap: 10px; background: #0d0d0d; padding: 7px 14px; border-radius: 8px; border: 1px solid var(--border); }
        .admin-avatar { width: 34px; height: 34px; background: rgba(201,168,76,0.1); border: 1px solid rgba(201,168,76,0.25); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: var(--gold); font-weight: 700; font-size: 0.8rem; }

        .section-card { background: var(--dark2); border-radius: 12px; border: 1px solid var(--border); overflow: hidden; }
        .section-header { padding: 16px 22px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; }
        .section-header h3 { font-size: 0.88rem; font-weight: 700; color: #e2e8f0; }

        table { width: 100%; border-collapse: collapse; }
        th { padding: 11px 20px; text-align: left; font-size: 0.6rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: var(--muted); background: #0d0d0d; border-bottom: 1px solid var(--border); }
        td { padding: 13px 20px; border-bottom: 1px solid rgba(30,37,48,0.7); font-size: 0.84rem; color: #94a3b8; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: rgba(201,168,76,0.02); }

        .badge { padding: 3px 10px; border-radius: 20px; font-size: 0.63rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; display: inline-block; }
        .badge-free { background: rgba(74,122,89,0.12); color: #4a7a59; border: 1px solid rgba(74,122,89,0.22); }
        .btn-primary { background: var(--gold); color: #0d0d0d; padding: 9px 18px; border-radius: 8px; font-size: 0.78rem; font-weight: 700; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 7px; transition: all 0.2s; text-decoration: none; }
        .btn-primary:hover { background: var(--gold-light); transform: translateY(-1px); color: #0d0d0d; }

        /* FIX 3: Global Scrollbar styling */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #0a0c10; }
        ::-webkit-scrollbar-thumb { background: var(--border); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--gold); }
    </style>
</head>
<body>

<!-- Browser Tab Title Update -->
<title>Book-Astra | Admin <?php echo $page_title; ?></title>

<!-- Font Awesome aur Favicon ke liye (Optional: Agar aapke paas favicon icon hai) -->
<link rel="icon" type="image/x-icon" href="file.svg">


<div class="sidebar">
    <div class="sidebar-logo" style="padding: 20px 25px;">
        <a href="dashboard.php" style="text-decoration: none; display: block;">
            <!-- Logo Image -->
            <div style="display: flex; align-items: center; gap: 12px;">
                <img src="file.svg" alt="Book-Astra Logo" style="width: 45px; height: auto; filter: drop-shadow(0 0 5px rgba(201,168,76,0.3));">
                
                <div style="display: flex; flex-direction: column;">
                    <h1 style="font-family: 'Cormorant Garamond', serif; color: var(--gold); font-size: 1.4rem; font-weight: 700; line-height: 1; margin: 0;">
                        Book-Astra
                    </h1>
                    
                </div>
            </div>
        </a>
    </div>
    
    <!-- Baki navigation niche wese hi rahegi -->

    <!-- Navigation container with its own scroll if items are too many -->
    <div style="overflow-y:auto; flex:1; padding-bottom:10px;">
        <div class="nav-section-title">Main</div>
        <a href="dashboard.php" class="nav-item <?php echo ($page_title=='Dashboard')?'active':''; ?>">
            <div class="nav-icon"><i class="fa-solid fa-gauge-high"></i></div> Dashboard
        </a>

        <div class="nav-section-title">Books</div>
        <a href="upload_book.php" class="nav-item <?php echo ($page_title=='Upload Book')?'active':''; ?>">
            <div class="nav-icon"><i class="fa-solid fa-upload"></i></div> Upload Book
        </a>
        <a href="view_books.php" class="nav-item <?php echo ($page_title=='View Books')?'active':''; ?>">
            <div class="nav-icon"><i class="fa-solid fa-book"></i></div> View Books
        </a>
        <a href="books_list.php" class="nav-item <?php echo ($page_title=='Books List')?'active':''; ?>">
            <div class="nav-icon"><i class="fa-solid fa-list"></i></div> Books List
        </a>

        <div class="nav-section-title">Community</div>
        <a href="view_users.php" class="nav-item <?php echo ($page_title=='Users')?'active':''; ?>">
            <div class="nav-icon"><i class="fa-solid fa-users"></i></div> View Users
        </a>
        <a href="manage_competitions.php" class="nav-item <?php echo ($page_title=='Competitions')?'active':''; ?>">
            <div class="nav-icon"><i class="fa-solid fa-trophy"></i></div> Competitions
        </a>
        <a href="view_submissions.php" class="nav-item <?php echo ($page_title=='Submissions')?'active':''; ?>">
            <div class="nav-icon"><i class="fa-solid fa-file-lines"></i></div> Submissions
        </a>

        <div class="nav-section-title">Sales</div>
        <a href="orders.php" class="nav-item <?php echo ($page_title=='Orders')?'active':''; ?>">
            <div class="nav-icon"><i class="fa-solid fa-cart-shopping"></i></div> Orders
        </a>
    </div>

    <div class="sidebar-footer">
        <a href="logout.php" class="logout-btn">
            <div style="width:30px;height:30px;background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.18);border-radius:6px;display:flex;align-items:center;justify-content:center;">
                <i class="fa-solid fa-right-from-bracket" style="font-size:0.76rem;"></i>
            </div>
            Logout
        </a>
    </div>
</div>

<div class="main-content">
    <div class="topbar">
        <div>
            <h2><?php echo $page_title; ?></h2>
            <p><?php echo $page_subtitle; ?></p>
        </div>
        <div style="display:flex;align-items:center;gap:12px;">
            <div style="font-size:0.68rem;color:var(--muted);letter-spacing:0.04em;"><?php echo date('d M Y'); ?></div>
            <div class="admin-badge">
                <div class="admin-avatar"><i class="fa-solid fa-user" style="font-size:0.7rem;"></i></div>
                <div>
                    <div style="font-size:0.76rem;font-weight:700;color:#e2e8f0;">Admin</div>
                    <div style="font-size:0.6rem;color:var(--muted);">Super Admin</div>
                </div>
            </div>
        </div>
    </div>