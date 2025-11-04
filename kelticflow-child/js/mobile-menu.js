document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.querySelector('.mobile-menu-toggle');
    const navigation = document.querySelector('.header-navigation');
    const overlay = document.querySelector('.mobile-menu-overlay');
    const body = document.body;

    if (menuToggle && navigation && overlay) {
        // Toggle menu
        menuToggle.addEventListener('click', function(e) {
            e.preventDefault();
            menuToggle.classList.toggle('active');
            navigation.classList.toggle('active');
            overlay.classList.toggle('active');
            body.classList.toggle('mobile-menu-open');
        });

        // Close menu when clicking overlay
        overlay.addEventListener('click', function() {
            menuToggle.classList.remove('active');
            navigation.classList.remove('active');
            overlay.classList.remove('active');
            body.classList.remove('mobile-menu-open');
        });

        // Close menu when clicking a menu item
        const menuLinks = navigation.querySelectorAll('a');
        menuLinks.forEach(function(link) {
            link.addEventListener('click', function() {
                menuToggle.classList.remove('active');
                navigation.classList.remove('active');
                overlay.classList.remove('active');
                body.classList.remove('mobile-menu-open');
            });
        });
    }
});