$(document).ready(function() {
    $('.icon-link').hover(function() {
        const $this = $(this);
        $this.stop().animate({
            width: '91px'
        });
    }, function() {
        const $this = $(this);
        $this.stop().animate({
            width: '-=57px'
        })
    });
    $('.fa-menu-icon').closest('.menu-button').on('click', function() {
        $('.menu-col').slideToggle();
    });
    var $main_nav = $('.main-nav');
    var skip_fix = false;
    var id;

    function fix_items() {
        if (skip_fix = Modernizr.mq('(max-width: 991px)')) {
            $main_nav.removeClass('fixed');
            localStorage.removeItem('ct.fixed-nav');
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
        $main_nav.addClass('fixed');
    } else {
        $main_nav.removeClass('fixed');
    }
    $(document).on('scroll', function() {
        var $this = $(this);
        var cover_img_height = $('.site-logo').height();
        var scroll_position = $this.scrollTop();
        //console.log('foo');
        if (scroll_position >= 30 && !skip_fix) {
            $main_nav.addClass('fixed');
            localStorage.setItem('psm.fixed-nav', 1);
        } else {
            $main_nav.removeClass('fixed');
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
    });
});