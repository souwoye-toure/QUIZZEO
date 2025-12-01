/* =====================================================
   Menu latéral slide-in + animations dynamiques
   ===================================================== */

function c() {
  // Elements
  const menuToggle = document.getElementById('menu-toggle');
  const sideMenu = document.getElementById('side-menu');
  const sideClose = document.getElementById('side-close');
  const overlay = document.getElementById('menu-overlay');
  const body = document.body;

  // Open menu
  function openMenu() {
    menuToggle.classList.add('open');
    menuToggle.setAttribute('aria-expanded', 'true');
    sideMenu.classList.add('open');
    overlay.classList.add('open');
    sideMenu.setAttribute('aria-hidden', 'false');
    overlay.setAttribute('aria-hidden', 'false');
    body.style.overflow = 'hidden'; // lock scroll
    // move focus to first focusable inside menu
    const firstFocusable = sideMenu.querySelector('a, button, input');
    if (firstFocusable) firstFocusable.focus();
  }

  // Close menu
  function closeMenu() {
    menuToggle.classList.remove('open');
    menuToggle.setAttribute('aria-expanded', 'false');
    sideMenu.classList.remove('open');
    overlay.classList.remove('open');
    sideMenu.setAttribute('aria-hidden', 'true');
    overlay.setAttribute('aria-hidden', 'true');
    body.style.overflow = ''; // restore scroll
    menuToggle.focus();
  }

  // Toggle
  menuToggle &&
    menuToggle.addEventListener('click', function () {
      const isOpen = sideMenu.classList.contains('open');
      if (isOpen) closeMenu();
      else openMenu();
    });

  sideClose && sideClose.addEventListener('click', closeMenu);
  overlay && overlay.addEventListener('click', closeMenu);

  // Close on ESC
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && sideMenu.classList.contains('open')) {
      closeMenu();
    }
  });

  // Close menu when link clicked (mobile)
  document.querySelectorAll('#side-menu a').forEach((a) => {
    a.addEventListener('click', function () {
      // allow normal navigation, but close menu with short delay to show the animation
      setTimeout(() => closeMenu(), 150);
    });
  });
}
