<?php 
// read theme from file in data/theme
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?= h($_SERVER['SERVER_NAME']) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= $this->fetch('meta') ?>
    <link rel="stylesheet" href="/css/<?= h($theme) ?>/bootstrap.css">
    <link rel="stylesheet" href="/vendor/jquery/ui/jquery-ui.min.css">
    <link rel="stylesheet" href="/vendor/jquery/ui/jquery-ui.structure.min.css">
    <link rel="stylesheet" href="/vendor/jquery/ui/jquery-ui.theme.min.css">
    <link rel="stylesheet" href="/css/custom.min.css">
    <?= $this->fetch('css') ?>
  </head>
  <body>
    
    <div class="navbar navbar-expand-lg fixed-top navbar-dark bg-primary">
      <div class="container">
        <a href="/" class="navbar-brand"><?= h($_SERVER['SERVER_NAME']) ?></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav">
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" id="themes">Themes <span class="caret"></span></a>
              <div class="dropdown-menu" aria-labelledby="themes">
                <a class="dropdown-item" href="/sounds/theme/cerulean">Cerulean</a>
                <a class="dropdown-item" href="/sounds/theme/cosmo">Cosmo</a>
                <a class="dropdown-item" href="/sounds/theme/cyborg">Cyborg</a>
                <a class="dropdown-item" href="/sounds/theme/darkly">Darkly</a>
                <a class="dropdown-item" href="/sounds/theme/flatly">Flatly</a>
                <a class="dropdown-item" href="/sounds/theme/journal">Journal</a>
                <a class="dropdown-item" href="/sounds/theme/litera">Litera</a>
                <a class="dropdown-item" href="/sounds/theme/lumen">Lumen</a>
                <a class="dropdown-item" href="/sounds/theme/lux">Lux</a>
                <a class="dropdown-item" href="/sounds/theme/materia">Materia</a>
                <a class="dropdown-item" href="/sounds/theme/minty">Minty</a>
                <a class="dropdown-item" href="/sounds/theme/pulse">Pulse</a>
                <a class="dropdown-item" href="/sounds/theme/sandstone">Sandstone</a>
                <a class="dropdown-item" href="/sounds/theme/simplex">Simplex</a>
                <a class="dropdown-item" href="/sounds/theme/sketchy">Sketchy</a>
                <a class="dropdown-item" href="/sounds/theme/slate">Slate</a>
                <a class="dropdown-item" href="/sounds/theme/solar">Solar</a>
                <a class="dropdown-item" href="/sounds/theme/spacelab">Spacelab</a>
                <a class="dropdown-item" href="/sounds/theme/superhero">Superhero</a>
                <a class="dropdown-item" href="/sounds/theme/united">United</a>
                <a class="dropdown-item" href="/sounds/theme/yeti">Yeti</a>
              </div>
            </li>
            </ul>
        </div>
      </div>
    </div>

    <div class="container">
      
      <?= $this->Flash->render() ?>
      <?= $this->fetch('content') ?>
      <?= $this->element('footer') ?>

    </div>

    <script src="/vendor/jquery/dist/jquery.min.js"></script>
    <script src="/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/vendor/jquery/ui/jquery-ui.min.js"></script>
    <script src="/js/custom.js"></script>
    
    <?= $this->fetch('script') ?>
    
  </body>
</html>
