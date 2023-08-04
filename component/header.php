<?php 
function render_header($userName){
  if(!$userName){
    return '<header>
    <div class="container-header">
      <nav>
        <div class="logo">
          <a href="'.$_ENV["PUBLIC_URL"].'"><img src="./public/img/fitness-tracker-high-resolution-logo-color-on-transparent-background.png" /></a>
        </div>
        <input type="checkbox" id="menu-toggle" />
        <label for="menu-toggle" class="menu-icon">&#9776;</label>
        <ul class="menu">
        <li><a href="#">Fitness Tracker</a></li>
        <li><a href="#">Todo List</a></li>
        <li><a href="'.$_ENV["PUBLIC_URL"].'/recipes.php">Recipes</a></li>
          <li><a href="'.$_ENV["PUBLIC_URL"].'/login.php">Login</a></li>
        </ul>
      </nav>
    </div>
  </header>';
  } else {
    return '<header>
    <div class="container-header">
      <nav>
        <div class="logo">
        <a href="'.$_ENV["PUBLIC_URL"].'"><img src="./public/img/fitness-tracker-high-resolution-logo-color-on-transparent-background.png" /></a>
        </div>
        <input type="checkbox" id="menu-toggle" />
        <label for="menu-toggle" class="menu-icon">&#9776;</label>
        <ul class="menu">
          <li><a href="#">Fitness Tracker</a></li>
          <li><a href="#">Todo List</a></li>
          <li><a href="'.$_ENV["PUBLIC_URL"].'/recipes.php">Recipes</a></li>
          <li><div><span>Hi '.$userName.'</span><a href="'.$_ENV["PUBLIC_URL"].'/logout.php">Logout</a></div></li>
        </ul>
      </nav>
    </div>
  </header>';
  }
  
}


?>