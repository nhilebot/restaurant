

	$(document).ready(function () {
		$(document).on("scroll", onScroll);
 
		$('a[href^="#"]').on('click', function (e) {
			e.preventDefault();
			$(document).off("scroll");
 
			$('a').each(function () {
				$(this).removeClass('navactive');
			})
			$(this).addClass('navactive');
 
			var target = this.hash;
			$target = $(target);
			$('html, body').stop().animate({
				'scrollTop': $target.offset().top+2
			}, 500, 'swing', function () {
				window.location.hash = target;
				$(document).on("scroll", onScroll);
			});
		});
	});
 
	function onScroll(event) {
    var scrollPosition = $(document).scrollTop();
    $('.nav li a').each(function () {
        var currentLink = $(this);
        var href = currentLink.attr("href");

        // Chỉ xử lý nếu href bắt đầu bằng "#"
        if (href && href.startsWith("#")) {
            var refElement = $(href);
            if (refElement.length) {
                if (refElement.position().top <= scrollPosition &&
                    refElement.position().top + refElement.height() > scrollPosition) {
                    $('ul.nav li a').removeClass("navactive");
                    currentLink.addClass("navactive");
                } else {
                    currentLink.removeClass("navactive");
                }
            }
        }
    });

    // MixItUp init
    $(function () {
        $('#portfolio').mixitup({
            targetSelector: '.item',
            transitionSpeed: 350
        });
    });

    // Datepicker init
    $(document).ready(function () {
    $("#datepicker").datepicker();
});
}

