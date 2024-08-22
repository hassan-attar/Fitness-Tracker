<?php 
function render_footer(){
  return '<footer>
            <div class="container-footer">
              <div class="footer-contacts">
                <h4>Follow us</h4>
                <div class="social-media-list">
                  <a href="#">
                    <ion-icon name="logo-instagram" class="social-media-logo"></ion-icon>
                  </a>
                  <a href="#">
                    <ion-icon name="logo-youtube" class="social-media-logo"></ion-icon>
                  </a>
                  <a href="#">
                    <ion-icon name="logo-tiktok" class="social-media-logo"></ion-icon>
                  </a>
                  <a href="#">
                    <ion-icon name="logo-whatsapp" class="social-media-logo"></ion-icon>
                  </a>
                  <a href="#">
                    <ion-icon name="logo-facebook" class="social-media-logo"></ion-icon>
                  </a>
                </div>
                <h4>Contact us</h4>
                <p class="contacts">
                  Email:
                  <a href="mailto:info@fitness-tracker.ca">info@fitness-tracker.ca</a><br />
                  Phone: 604-555-3445
                </p>
              </div>
              <div class="links">

                <div class="links-group">
                  <h4>Menu</h4>
                  <ul class="links-container">
                    <li><a href="'.$_ENV["PUBLIC_URL"].'">Home</a></li>
                    <li><a href="'.$_ENV["PUBLIC_URL"].'/recipes.php">Recipes</a></li>
                    <li><a href="#" class="unavailable">Todo</a></li>
                    <li><a href="#" class="unavailable">Fitness Tracker</a></li>
                  </ul>
                </div>
                <div class="links-group">
                  <h4>Quick Links</h4>
                  <ul class="links-container">
                    <li><a href="'.$_ENV["PUBLIC_URL"].'/login.php">Login</a></li>
                    <li><a href="'.$_ENV["PUBLIC_URL"].'/signup.php">Sign up</a></li>
                    <li><a href="'.$_ENV["PUBLIC_URL"].'" class="unavailable" >API</a></li>
                  </ul>
                </div>
              </div>
              <div class="copy-right">
                <p>
                  Copyright &copy; Hassan Attar for Web project class Summer
                  2023. All rights reserved.
                </p>
              </div>
            </div>
            <div class="footer-ribbon">&nbsp;</div>
          </footer>';
}


?>