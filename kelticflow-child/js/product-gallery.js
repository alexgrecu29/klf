/**
 * Product Gallery Script - FINAL VERSION
 * Handles thumbnail clicks to update main image
 * Works with ModuloBox (.mobx and data-rel)
 */

(function() {
    'use strict';
    
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initGallery);
    } else {
        initGallery();
    }
    
    function initGallery() {
        const mainImageWrapper = document.querySelector('.product-main-image-wrapper');
        const mainImage = document.getElementById('mainProductImage');
        const mainImageLink = mainImageWrapper ? mainImageWrapper.querySelector('a.mobx') : null;
        const thumbnailWrappers = document.querySelectorAll('.product-thumbnail-wrapper');
        
        console.log('Product Gallery Init:', {
            mainImage: mainImage ? 'Found' : 'Not found',
            mainLink: mainImageLink ? 'Found' : 'Not found',
            thumbnailCount: thumbnailWrappers.length
        });
        
        if (!mainImage || !mainImageLink || thumbnailWrappers.length === 0) {
            console.log('Gallery not initialized: missing elements');
            return;
        }
        
        // Add click handlers to thumbnail images
        thumbnailWrappers.forEach(function(wrapper, index) {
            const thumbnailImg = wrapper.querySelector('.thumbnail-image');
            
            if (thumbnailImg) {
                thumbnailImg.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const largeUrl = wrapper.getAttribute('data-large');
                    const fullUrl = wrapper.getAttribute('data-full');
                    const thumbUrl = wrapper.getAttribute('data-thumb');
                    
                    console.log('Thumbnail clicked:', index, {largeUrl, fullUrl, thumbUrl});
                    
                    if (!largeUrl || !fullUrl) {
                        console.error('Missing image URLs on thumbnail');
                        return;
                    }
                    
                    // Update main image
                    updateMainImage(mainImage, mainImageLink, largeUrl, fullUrl, thumbUrl, index);
                    
                    // Update active state
                    updateActiveThumbnail(thumbnailWrappers, index);
                });
                
                // Keyboard support
                thumbnailImg.setAttribute('tabindex', '0');
                thumbnailImg.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        this.click();
                    }
                });
                
                // Make cursor pointer
                thumbnailImg.style.cursor = 'pointer';
            }
        });
        
        console.log('Product gallery initialized successfully');
    }
    
    function updateMainImage(imgElement, linkElement, largeUrl, fullUrl, thumbUrl, index) {
        // Fade out
        imgElement.style.opacity = '0';
        
        setTimeout(function() {
            // Update image src
            imgElement.src = largeUrl;
            
            // Update link href for ModuloBox
            linkElement.href = fullUrl;
            
            // Update data-thumb if provided
            if (thumbUrl) {
                linkElement.setAttribute('data-thumb', thumbUrl);
            }
            
            // Update link title
            const baseTitle = linkElement.getAttribute('data-title');
            if (baseTitle) {
                const titleBase = baseTitle.split(' - ')[0];
                linkElement.setAttribute('data-title', titleBase + ' - Image ' + (index + 1));
            }
            
            // Fade in
            setTimeout(function() {
                imgElement.style.opacity = '1';
            }, 50);
        }, 300);
    }
    
    function updateActiveThumbnail(wrappers, activeIndex) {
        wrappers.forEach(function(wrapper, index) {
            if (index === activeIndex) {
                wrapper.classList.add('active');
            } else {
                wrapper.classList.remove('active');
            }
        });
    }
    
})();