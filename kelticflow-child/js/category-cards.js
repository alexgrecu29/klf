document.addEventListener('DOMContentLoaded', function() {
    const categoryCards = document.querySelectorAll('.category-card');
    
    categoryCards.forEach(function(card) {
        card.addEventListener('click', function(e) {
            // Don't follow link if clicking the button directly
            if (!e.target.classList.contains('view-products-btn')) {
                const url = this.getAttribute('data-href');
                if (url) {
                    window.location.href = url;
                }
            }
        });
    });
});
