<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <link rel="stylesheet" href="assets/css/style.css" />
  </head>
  <body>
    <header class="site-header" role="banner">
      <div class="container header-inner">
        <div class="brand">
          <img
            src="logo_quizzeo-png.png"
            alt="Logo"
            style="width: 200px; height: 55px"
          />
        </div>

        <!-- Bouton menu (mobile/tablette) -->
        <button
          id="menu-toggle"
          class="menu-btn"
          aria-label="Ouvrir le menu"
          aria-expanded="false"
          aria-controls="side-menu"
        >
          <span class="hamburger" aria-hidden="true"></span>
        </button>

        <!-- Nav principale (desktop) -->
        <nav
          class="top-nav"
          role="navigation"
          aria-label="Navigation principale"
        >
          <ul>
            <li><a href="#">Accueil</a></li>
            <li><a href="#">Quiz</a></li>
            <li><a href="#">Dashboard</a></li>
            <li><a href="#" class="btn-connexion">Connexion</a></li>
          </ul>
        </nav>
      </div>

      <!-- Menu latéral slide-in -->
      <aside
        id="side-menu"
        class="side-menu"
        aria-hidden="true"
        role="dialog"
        aria-label="Menu principal"
      >
        <div class="side-inner">
          <button
            id="side-close"
            class="side-close"
            aria-label="Fermer le menu"
          >
            &times;
          </button>

          <nav class="side-nav" role="navigation" aria-label="Menu mobile">
            <ul>
              <li><a href="#">Accueil</a></li>
              <li><a href="#">Quiz</a></li>
              <li><a href="#">Dashboard</a></li>
              <li><a href="#" class="btn-adhere">Connexion</a></li>
            </ul>
          </nav>

          <div class="side-cta">
            <a href="#" class="btn-primary large">Connexion</a>
          </div>
        </div>
      </aside>

      <!-- Overlay quand menu ouvert -->
      <div id="menu-overlay" class="menu-overlay" aria-hidden="true"></div>
    </header>
    <script src="destock/script.js"></script>
  </body>
</html>
