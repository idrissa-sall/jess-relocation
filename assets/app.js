import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

$(function(){
    // owl-carousel js
    $(".owl-carousel").owlCarousel({
        //Basic Speeds
        slideSpeed : 200,
        paginationSpeed : 800,
     
        //Autoplay
        autoPlay : true,
        goToFirst : true,
        goToFirstSpeed : 1000,
     
        // Navigation
        navigation : false,
        navigationText : ["prev","next"],
        pagination : true,
        paginationNumbers: false,
     
        // Responsive
        responsive: true,
        items : 4,
        itemsDesktop : [1199,4],
        itemsDesktopSmall : [980,3],
        itemsTablet: [768,2],
        itemsMobile : [479,1]
    });
    // end

    // gestion de la class active sur le header
    var path = window.location.pathname.split("/").pop();

    $('.nav-link').each(function(){
        
        var actualPath = $(this).attr('href').split('/').pop();
        if(actualPath == 'contact' && actualPath == path)
        {
            console.log(actualPath)
            $('#contact-nav-link').addClass('active');
        }
    });
    
    
})