import owlCarousel from 'owl.carousel2';

$('.owl-carousel').owlCarousel({
    loop: true,
    items: 6,
    autoplay: true,
    autoplayTimeout: 5000,
    autoplayHoverPause: false,
    autoplaySpeed: 1000,
    margin: 30,
    nav: false,
    navText: ["<i class='fa fa-chevron-left slide-nav slide-previous' aria-hidden='true'></i>", "<i class='fa fa-chevron-right slide-nav slide-next' aria-hidden='true'></i>"],
    dots: false,
})

