/**
 * Product Gallery Script - FIXED VERSION
 * Handles thumbnail switching for ModuloBox gallery
 */

(function() {
    'use strict';
    
    // Wait for DOM to be ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initGallery);
    } else {
        initGallery();
    }
    
    function initGallery() {
        // Get gallery elements
        const mainImageWrapper = document.querySelector('.product-main-image-wrapper');
        const mainImage = document.getElementById('mainProductImage');
        const mainImageLink = mainImageWrapper ? mainImageWrapper.querySelector('a.mbox') : null;
        const thumbnails = document.querySelectorAll('.product-thumbnail');
        
        // Debug: Log what we found
        console.log('Product Gallery Init:', {
            mainImage: mainImage ? 'Found' : 'Not found',
            mainLink: mainImageLink ? 'Found' : 'Not found',
            thumbnailCount: thumbnails.length
        });
        
        // Exit if no thumbnails or no main image
        if (!mainImage || !mainImageLink || thumbnails.length === 0) {
            console.log('Gallery not initialized: missing elements');
            return;
        }
        
        // Add click handlers to thumbnails
        thumbnails.forEach(function(thumbnail, index) {
            thumbnail.addEventListener('click', function(e) {
                // Prevent any default behavior
                e.preventDefault();
                e.stopPropagation();
                
                // Get URLs from data attributes
                const largeUrl = this.getAttribute('data-large');
                const fullUrl = this.getAttribute('data-full');
                
                console.log('Thumbnail clicked:', index, {largeUrl, fullUrl});
                
                if (!largeUrl || !fullUrl) {
                    console.error('Missing image URLs on thumbnail');
                    return;
                }
                
                // Update the main image
                updateMainImage(mainImage, mainImageLink, largeUrl, fullUrl, index);
                
                // Update active state
                updateActiveThumbnail(thumbnails, index);
            });
            
            // Add keyboard support
            thumbnail.setAttribute('tabindex', '0');
            thumbnail.addEventListener('keypress', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.click();
                }
            });
        });
        
        console.log('Product gallery initialized successfully');
    }
    
    /**
     * Update the main product image
     */
    function updateMainImage(imgElement, linkElement, largeUrl, fullUrl, index) {
        // Fade out
        imgElement.style.opacity = '0';
        
        // Wait for fade, then change image
        setTimeout(function() {
            // Update image src
            imgElement.src = largeUrl;
            
            // Update link href for ModuloBox
            linkElement.href = fullUrl;
            
            // Update link title
            const baseTitle = linkElement.getAttribute('title').split(' - ')[0];
            linkElement.setAttribute('title', baseTitle + ' - Image ' + (index + 1));
            
            // Fade in
            setTimeout(function() {
                imgElement.style.opacity = '1';
            }, 50);
        }, 300);
    }
    
    /**
     * Update active thumbnail state
     */
    function updateActiveThumbnail(thumbnails, activeIndex) {
        thumbnails.forEach(function(thumb, index) {
            if (index === activeIndex) {
                thumb.classList.add('active');
            } else {
                thumb.classList.remove('active');
            }
        });
    }
    
})();