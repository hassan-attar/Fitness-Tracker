<?php
function render_head($title){
  return '<meta charset="UTF-8" />
          <meta name="viewport" content="width=device-width, initial-scale=1.0" />
          <title>'.$title.'</title>
          <link rel="icon" href="/public/img/favicon.png" type="image/png">
          <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
          <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
          <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
          <script src="script.js" defer></script>
          <link rel="stylesheet" href="style.css" />';
}

?>