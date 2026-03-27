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
