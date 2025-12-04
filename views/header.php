<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>page header</title>
    <link rel="stylesheet" href="assets/css/style.css">


</head>
<body>
    <header class="site-header" role="banner">
  <div class="container header-inner">
    <div class="brand">
        <img src="assets/img/logo_quizzeo-png.png" alt="Logo" style="width: 200px; height: 60px;">
    </div>

    <!-- Bouton menu (mobile/tablette) -->
    <button id="menu-toggle" class="menu-btn" aria-label="Ouvrir le menu" aria-expanded="false" aria-controls="side-menu">
      <span class="hamburger" aria-hidden="true"></span>
    </button>

    <!-- Nav principale (desktop) -->
    <nav class="top-nav" role="navigation" aria-label="Navigation principale">
      <ul>
        <li><a href="index.php">Accueil</a></li>
        <li><a href="views/quizzes.php">Quizz</a></li>
        <li><a href="views/login.php" class="btn-adhere">Connexion</a></li>
      </ul>
    </nav>
  </div>

  <!-- Overlay quand menu ouvert -->
  <div id="menu-overlay" class="menu-overlay" aria-hidden="true"></div>
</header>
