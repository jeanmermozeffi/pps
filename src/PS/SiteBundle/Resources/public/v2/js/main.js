$(document).ready(function() {
    var $main_nav = $('.main-nav');
    var body = $('body');
    var $html = $('html');
    var $site_header = $('.site-header');
    var skip_fix = false;
    var id;
    $('#locale').on('change', function() {
        var url = $('option:selected', $(this)).data('url');
        document.location.href = url;
    });
    $('.list-target').find('a').tooltip({
        html: true,
        container: '.cible-text',
        title: function() {
            return $(this).next('div').html();
        }
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
    /*function fix_items() {
        if (skip_fix = Modernizr.mq('(max-width: 991px)')) {
            $main_nav.removeClass('fixed-top');
            $site_header.removeClass('fixed-top');
            localStorage.removeItem('psm.fixed-nav');
        } else {
            $('.menu-col').show();
        }
    }
    $(window).resize(function() {
        clearTimeout(id);
        id = setTimeout(fix_items, 0);
    });
    //Call doneResizing on instantiation
    fix_items();
    if (localStorage.getItem('psm.fixed-nav')) {
        $main_nav.addClass('fixed-top');
        $site_header.removeClass('fixed-top');
    } else {
        $main_nav.removeClass('fixed');
        $site_header.addClass('fixed-top');
    }
    $(document).on('scroll', function() {
        var $this = $(this);
        var cover_img_height = $('.site-logo').height();
        var scroll_position = $this.scrollTop();
        //console.log('foo');
        if (scroll_position >= 30 && !skip_fix) {
            $main_nav.addClass('fixed-top');
            $site_header.removeClass('fixed-top');
            localStorage.setItem('psm.fixed-nav', 1);
        } else {
            $main_nav.removeClass('fixed-top');
            $site_header.addClass('fixed-top');
            localStorage.removeItem('psm.fixed-nav');
        }
    });
    //Scroll
    var $w = $(window),
        $btn = $('.fixed-icon-up');
    $w.scroll(function() {
        if ($w.scrollTop() > 500) {
            $btn.addClass('active').stop().animate({
                right: '10px'
            }, 100);
        } else {
            $btn.removeClass('active').stop().animate({
                right: '-60px'
            }, 0);
        }
    });*/
    $('.scroll-link').on('click', function(e) {
        e.preventDefault();
        const $this = $(this);
        let href = $this.attr('href');
        if (href) {
            const $section = $(href);
            if ($section.length) {
                let position = $section.position().top;
                $('html,body').animate({
                    scrollTop: position
                }, 1500);
            }
        }
    });
});