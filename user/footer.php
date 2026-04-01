<style>
.e-footer{background:#0a0a0a;border-top:1px solid rgba(255,255,255,0.06);padding:56px 0 24px;margin-top:80px;font-family:'DM Sans',sans-serif;}
.e-footer .fi{max-width:1200px;margin:0 auto;padding:0 30px;}
.e-footer .fg{display:grid;grid-template-columns:2.2fr 1fr 1fr;gap:50px;margin-bottom:48px;}
.e-footer .fbrand{font-family:'Cormorant Garamond',serif;font-size:1.35rem;font-weight:700;color:#fff;margin-bottom:12px;}
.e-footer .fbrand span{color:#c9a84c;}
.e-footer .fdesc{font-size:0.77rem;color:rgba(255,255,255,0.3);line-height:1.9;max-width:270px;}
.e-footer .ct{font-size:0.58rem;letter-spacing:0.22em;text-transform:uppercase;color:rgba(255,255,255,0.22);font-weight:700;margin-bottom:16px;}
.e-footer ul{list-style:none;padding:0;margin:0;}
.e-footer ul li{margin-bottom:9px;}
.e-footer ul li a{color:rgba(255,255,255,0.35);text-decoration:none;font-size:0.77rem;transition:color 0.2s;}
.e-footer ul li a:hover{color:#c9a84c;}
.e-footer .ci{display:flex;align-items:center;gap:9px;color:rgba(255,255,255,0.3);font-size:0.77rem;margin-bottom:10px;}
.e-footer .ci i{color:#c9a84c;width:13px;font-size:0.72rem;}
.e-footer .fbot{border-top:1px solid rgba(255,255,255,0.05);padding-top:22px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:14px;}
.e-footer .copy{font-size:0.67rem;color:rgba(255,255,255,0.18);letter-spacing:0.06em;}
.e-footer .socials{display:flex;gap:8px;}
.e-footer .socials a{width:32px;height:32px;border-radius:5px;background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.07);display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,0.28);font-size:0.7rem;text-decoration:none;transition:all 0.2s;}
.e-footer .socials a:hover{border-color:#c9a84c;color:#c9a84c;}
@media(max-width:700px){.e-footer .fg{grid-template-columns:1fr;gap:30px;}.e-footer .fbot{flex-direction:column;text-align:center;}}

/* Auth Modal Styles globally */
.auth-modal { position: fixed; z-index: 2000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); backdrop-filter: blur(5px); display: flex; align-items: center; justify-content: center; }
.auth-modal-content { background: #141920; border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; padding: 40px; text-align: center; max-width: 400px; width: 90%; position: relative; box-shadow: 0 20px 50px rgba(0,0,0,0.5); animation: modalIn 0.3s ease-out; font-family: 'DM Sans', sans-serif;}
@keyframes modalIn { from { transform: scale(0.9); opacity: 0; } to { transform: scale(1); opacity: 1; } }
.close-auth { position: absolute; top: 15px; right: 20px; font-size: 1.5rem; color: rgba(255,255,255,0.5); cursor: pointer; transition: 0.2s; }
.close-auth:hover { color: #fff; transform: scale(1.1); }
.btn-primary-gold-modal { background: #c9a84c; color: #0d0d0d; font-size: 0.75rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; padding: 12px 26px; border-radius: 5px; text-decoration: none; transition: background 0.2s; display: inline-block; }
.btn-primary-gold-modal:hover { background: #dfc17b; }
.btn-outline-modal { background: transparent; border: 1px solid rgba(255,255,255,0.12); color: rgba(255,255,255,0.55); font-size: 0.75rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; padding: 12px 26px; border-radius: 5px; text-decoration: none; transition: all 0.2s; display: inline-block; }
.btn-outline-modal:hover { border-color: rgba(255,255,255,0.3); color: #fff; }
</style>
<footer class="e-footer">
  <div class="fi">
    <div class="fg">
      <div>
        <div class="fbrand"><img src="file.svg" alt="logo" style = "height:30px; width:auto; vertical-align:middle;" > <span>Book-Astra</span></div>
        <p class="fdesc">Your ultimate destination for digital knowledge. Read, learn, and grow with thousands of books at your fingertips.</p>
      </div>
      <div>
        <div class="ct">Quick Links</div>
        <ul>
          <li><a href="index.php">Home</a></li>
          <li><a href="category.php">Categories</a></li>
          <li><a href="index.php?filter=free">Free Books</a></li>
          <li><a href="competition.php">Competitions</a></li>
          <li><a href="winners.php">Winners</a></li>
          <li><a href="about.php">About Us</a></li>
        </ul>
      </div>
      <div>
        <div class="ct">Contact</div>
        <div class="ci"><i class="fa-solid fa-location-dot"></i> Karachi, Pakistan</div>
        <div class="ci"><i class="fa-regular fa-envelope"></i> helpBookastra.com</div>
        <div class="ci"><i class="fa-solid fa-phone"></i> +92 317 0010116</div>
      </div>
    </div>
    <div class="fbot">
      <div class="copy">© <?php echo date("Y"); ?> Book-Astra — All Rights Reserved.</div>
      <div class="socials">
        <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
        <a href="#"><i class="fa-brands fa-instagram"></i></a>
        <a href="#"><i class="fa-brands fa-twitter"></i></a>
      </div>
    </div>
  </div>
</footer>

<!-- Auth Modal (Included Globally via Footer) -->
<div id="authModal" class="auth-modal" style="display:none;">
   <div class="auth-modal-content">
      <span class="close-auth" onclick="document.getElementById('authModal').style.display='none'">&times;</span>
      <div style="font-size: 3rem; color: #c9a84c; margin-bottom: 10px;"><i class="fa-solid fa-lock"></i></div>
      <h3 style="font-family:'Cormorant Garamond',serif; font-size: 1.8rem; margin:0 0 10px 0; color:#fff;">Authentication Required</h3>
      <p style="color: rgba(255,255,255,0.5); font-size: 0.85rem; margin-bottom: 25px;">You need to create an account or sign in to <strong id="authActionText" style="color: #fff;">access</strong> this book.</p>
      <div style="display:flex; gap:10px; justify-content:center;">
          <a href="login.php" class="btn-outline-modal">Login</a>
          <a href="register.php" class="btn-primary-gold-modal">Sign Up</a>
      </div>
   </div>
</div>

<script>
function showAuthModal(e, actionType) {
    if(e) e.preventDefault();
    var actionTextEl = document.getElementById('authActionText');
    if(actionTextEl) actionTextEl.innerText = actionType.toLowerCase();
    var modalEl = document.getElementById('authModal');
    if(modalEl) modalEl.style.display = 'flex';
}
window.addEventListener('click', function(event) {
    var modal = document.getElementById('authModal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
});
</script>
