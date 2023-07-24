$('#myTab').find('a').each(function() {
    var $this = $(this);
    if (!$this.hasClass('active')) {
        $this.parent('li').addClass('not-active');
    }
});
$('#myTab').on('shown.bs.tab', function(e) {
    $(this).find('li').removeClass('not-active');
    $(e.relatedTarget).parent('li').addClass('not-active');
});
$('.list-target').find('a').tooltip({
    html: true,
    container: '.section-has-tooltip',
    title: function() {
        return $(this).next('div').html();
    }
});
var $main_nav = $('#main-nav');
$(document).on('scroll', function() {
    var $this = $(this);
    var scroll_position = $this.scrollTop();
    if (scroll_position >= $main_nav.height()) {
        $main_nav.addClass('active-nav');
        localStorage.setItem('psm.nav_fixed', 1);
        localStorage.setItem('psm.nav_url', current_url);
    } else {
        $main_nav.removeClass('active-nav');
        localStorage.removeItem('psm.nav_fixed');
        localStorage.removeItem('psm.nav_url');
    }
});
if (localStorage.getItem('psm.nav_fixed') && localStorage.getItem('psm.nav_url') == current_url) {
    $main_nav.addClass('active-nav');
} else {
    $main_nav.removeClass('active-nav');
}
$('.scroll-link').on('click', function(e) {
    e.preventDefault();
    const $this = $(this);
    let href = $this.attr('href');
    const [, id] = href.split('#');
    if (id) {
        const $section = $('#' + id);
        if ($section.length) {
           
            let position = $section.position().top;
            $('html,body').animate({
                scrollTop: position - parseInt($section.css('paddingTop'))
            }, 1500);
        }
    }
});
$('#locale').on('change', function() {
    var url = $('option:selected', $(this)).data('url');
    document.location.href = url;
});
var $slick_carousel = $('.slick-carousel');
//Carousel des actualités
$slick_carousel.each(function() {
    var $this = $(this);
    $this.on('init', function() {
        //Retrait des textes prev et next qui s'affichent malgré nextText, prevText
        $('.slick-arrow').text('');
    }).slick({
        infinite: true,
        arrows: $this.data('arrows') == false ? false : true,
        slidesToShow: $this.data('maxItems'),
        slidesToScroll: $this.data('scrollItems'),
        nextText: null,
        prevText: null,
        slide: $this.data('slide') || 'div',
        cssEase: 'ease',
        autoplaySpeed: $this.data('timeout') ? $this.data('timeout') : 4500,
        dots: $this.data('dots'),
        fade: $this.data('fade'),
        autoplay: $this.data('auto'),
        responsive: [{
            breakpoint: 991,
            settings: {
                slidesToShow: 3,
                slidesToScroll: 3,
                infinite: true,
                //dots: true
            }
        }, {
            breakpoint: 510,
            settings: {
                slidesToShow: 1,
                slidesToScroll: 1,
                infinite: true,
                //dots: true
            }
        }, ]
    });
});


var $w = $(window),
        $btn = $('.float-btn-group');
    $w.scroll(function() {
        if ($w.scrollTop() > 800) {
            $btn.stop().animate({
                right: '10px'
            }, 100);
        } else {
            $btn.stop().animate({
                right: '-100px'
            }, 0);
        }
    });