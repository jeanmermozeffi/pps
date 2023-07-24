jQuery(document).ready(function(){
	$objWindow = $(window);

	$('section[data-type="background"]').each(function(){
		var $bgObj = $(this);

		$(window).scroll(function() {

			var yPos = -($objWindow.scrollTop() / $bgObj.data('speed'));
			var coords = '100% '+ yPos + 'px';
			// Change the position of background
			$bgObj.css({ backgroundPosition: coords });  
		});  
	});
});