/* Start: Fix Header */
$(window).scroll(function () {
  var scroll = $(window).scrollTop();
  if (scroll > 10) {
    $("#header").addClass("active-header");
  }
  else {
    $("#header").removeClass("active-header");
  }
});
/* End: Fix Header */

/*Start: Back to Top */
$(window).scroll(function () {
  if ($(this).scrollTop() > 650) {
    $('#scrollTop').fadeIn();
  } else {
    $('#scrollTop').fadeOut();
  }
});
// scroll up function
$('#scrollTop').click(function () {
  $('html, body').animate({ scrollTop: 0 }, 650);
});
/*End: Back to Top */

$(document).ready(function () {
  $(".request-slider").slick({
    centerMode: true,
    centerPadding: '0px',
    slidesToShow: 3,
    slidesToScroll: 1,
    autoplay: false,	
    autoplaySpeed: 1500,	
    dots: false,
    pauseOnHover: false,
    arrows: true, 
    responsive: [
     {
       breakpoint: 1199,
       settings: {
         slidesToShow: 3,
         slidesToScroll: 1,
         arrows: true,
         dots: false,   
       },
     },

     {
       breakpoint: 991,
       settings: {
         slidesToShow: 2,
         slidesToScroll: 2,
         arrows: true,
         dots: false,    
       },
     },

     {
       breakpoint: 767,
       settings: {
         slidesToShow: 1,
         slidesToScroll: 1,
         arrows: true,
         dots: false,     
       },           
     }, 
          
   ],
       
 });
});


