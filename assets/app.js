import $ from 'jquery';
window.$ = window.jQuery = $;

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

        if(actualPath == '' && actualPath == path)
        {
            $('#home-nav-link').addClass('active');
        }
        else if(actualPath == 'about' && actualPath == path)
        {
            $('#about-nav-link').addClass('active');
        }
        else if(actualPath == 'appointment' && actualPath == path)
        {
            $('#appointment-nav-link').addClass('active');
        }
        else if(actualPath == 'contact' && actualPath == path)
        {
            $('#contact-nav-link').addClass('active');
        }
    });
    
    // init wow js library
    new WOW().init();

    // control review form show and hide
    const letReviewBtn = $('#let-review-btn');
    const reviewFormSection = $('#review-form-section');
    const closeReviewFormBtn = $('#close-review-form-btn');

    //* review form
    // hide add button by default
    letReviewBtn.hide();

    // display review form after click on button
    letReviewBtn.on('click', (e) => {
        e.preventDefault();
        reviewFormSection.show(400);
        letReviewBtn.hide(400);
    });

    // close review form after click on close button
    closeReviewFormBtn.on('click', (e) => {
        reviewFormSection.hide(400);
        letReviewBtn.show(400);
    });
    //* end

    //* appointment date input
    // date input
    let appointmentDate = $('#appointment_date_apm');
    let btnSubmitAppointment = $('#btn-submit-appointment');

    btnSubmitAppointment.on('click', (e) => {
        var selectedDate = new Date(appointmentDate.val());
        // week end invalid
        if(selectedDate.getDay() == 6 || selectedDate.getDay() == 0)
        {
            alert('Date invalide');
            e.preventDefault();
        }
    });
    
});