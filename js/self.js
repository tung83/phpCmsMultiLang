var recaptchaChecked = false;
function recaptchaCallback() {
    recaptchaChecked = true;
    ToogleEnableSubmitButton();
};

function ToogleEnableSubmitButton(){
    var empty = false;
    $('form input,form textarea').each(function() {
        var attr = $(this).attr('required');
        if (typeof attr !== typeof undefined && attr !== false && $(this).val() == '' ) {
            empty = true;
        }
    });

    if (empty || !recaptchaChecked) {
        $('.submit-button').addClass('disabled'); // updated according to http://stackoverflow.com/questions/7637790/how-to-remove-disabled-attribute-with-jquery-ie
    } else {
        $('.submit-button').removeClass('disabled'); // updated according to http://stackoverflow.com/questions/7637790/how-to-remove-disabled-attribute-with-jquery-ie
    }
}
$(function(){    
    new WOW().init();
    $('form input,form textarea').keyup(function() {
        ToogleEnableSubmitButton();
    });
    $( "#tabs" ).tabs();
    $("body").append('<a href="#" class="scrollTo-top" ><i class="fa fa-angle-double-up"></i></a>');
    var viewPortWidth = $(window).width();
    $(window).scroll(function(event) {
        event.preventDefault();
        if ($(this).scrollTop() > 180) {
            $('.scrollTo-top').fadeIn();
        } else {
            $('.scrollTo-top').fadeOut();
        }
    });    
    $('.scrollTo-top').click(function(event) {
        $('html, body').animate({scrollTop : 0 }, 600);
        event.preventDefault();
    }); 
    
    
    $(".test-popup-link").magnificPopup({
      type: "image",
      zoom: {
        enabled: true,
        duration: 300
      }
    });
    $('.popup-gallery').magnificPopup({
        delegate: 'a',
        type: 'image',
        tLoading: 'Loading image #%curr%...',
        mainClass: 'mfp-img-mobile',
        gallery: {
            enabled: true,
            navigateByImgClick: true,
            preload: [0,1] // Will preload 0 - before current, and 1 after the current image
        },
        zoom: {
            enabled: true,
            duration: 300
        },
        image: {
            verticalFit:true
        }
	});   
    $('.popup-youtube, .popup-vimeo, .popup-gmaps').magnificPopup({
      disableOn: 700,
      type: 'iframe',
      mainClass: 'mfp-fade',
      removalDelay: 160,
      preloader: false,
      zoom: {
            enabled: true,
            duration: 300
      },
      fixedContentPos: false
    });      
})
$(function() {
    // slick
    $('.slick').slick({
        dots: false,
        autoplay: true,
        autoplaySpeed: 2500,
        infinite: true,
        speed: 100,
        slidesToShow: 4,
        slidesToScroll: 1
    });
    
    $("#search").on('submit',function(e){
        e.preventDefault();
        var val=$(this).find("#hint").val();
        var searchLink=$(this).find("#search-link").val();

        $( location ).attr("href",searchLink+val);
    });
    });



