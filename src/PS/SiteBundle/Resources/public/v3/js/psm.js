(function ($) {
    $(document).on("ready", function (e) {
        var sync1 = $("#mainslider");
        var sync2 = $("#mainslider-nav");
        sync1.owlCarousel({
            singleItem: true,
            slideSpeed: 1000,
            paginationSpeed: 800,
            navigation: false,
            pagination: false,
            autoPlay: 7500,
            afterAction: syncPosition,
            responsiveRefreshRate: 200,
        });

        sync2.owlCarousel({
            items: 4,
            itemsDesktop: [1199, 4],
            itemsDesktopSmall: [979, 4],
            itemsTablet: [768, 3],
            itemsMobile: [479, 2],
            pagination: false,
            responsiveRefreshRate: 100,
            afterInit: function (el) {
                el.find(".owl-item").eq(0).addClass("synced");
            }
        });

        function syncPosition(el) {
            var current = this.currentItem;
            $("#mainslider-nav").find(".owl-item").removeClass("synced").eq(current).addClass("synced")
            if ($("#mainslider-nav").data("owlCarousel") !== undefined) {
                center(current)
            }
        }
        $("#mainslider-nav").on("click", ".owl-item", function (e) {
            e.preventDefault();
            var number = $(this).data("owlItem");
            sync1.trigger("owl.goTo", number);
        });

        function center(number) {
            var sync2visible = sync2.data("owlCarousel").owl.visibleItems;
            var num = number;
            var found = false;
            for (var i in sync2visible) {
                if (num === sync2visible[i]) {
                    var found = true;
                }
            }
            if (found === false) {
                if (num > sync2visible[sync2visible.length - 1]) {
                    sync2.trigger("owl.goTo", num - sync2visible.length + 2)
                } else {
                    if (num - 1 === -1) {
                        num = 0;
                    }
                    sync2.trigger("owl.goTo", num);
                }
            } else if (num === sync2visible[sync2visible.length - 1]) {
                sync2.trigger("owl.goTo", sync2visible[1])
            } else if (num === sync2visible[0]) {
                sync2.trigger("owl.goTo", num - 1)
            }
        }


    });

    $(document).on("ready", function (e) {
        // ______________ TESTIMONIALSs
        $("#testimonials-carousel").owlCarousel({
            items: 1,
            autoPlay: 5000,
            itemsDesktop: [1199, 1],
            itemsDesktopSmall: [979, 1],
            itemsTablet: [768, 1]
        });

        // ______________ VIDEOPOPUP
        $("a.autoplay").VideoPopUp();
        $("a.noautoplay").VideoPopUp({
            autoplay: 0
        }); // Disable autoplay

        // ______________ PARALLAX
        //$('.section-parallax').parallax("50%", 0.4);
        // ______________ STATS
        $('.statistics').waypoint(function () {
            $('#myStat1').circliful();
            $('#myStat2').circliful();
            $('#myStat3').circliful();
            $('#myStat4').circliful();

        }, {
            offset: 800,
            triggerOnce: true
        });
    });

    // $(document).ready(function () {
    //     function FaireClignoterImage() { $("#js-pharmagarde").fadeOut(200).delay(300).fadeIn(200); }
    //     setInterval(FaireClignoterImage, 700);
    // });

    //document.getElementsByTagName("#js-pharmagarde")[0].style.fontSize = "80px";

}(jQuery));