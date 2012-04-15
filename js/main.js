$(function() {
	$('#menu a[href^=#]').click(function() {
		var hash = $(this).attr('href');
		$('html,body').animate({ scrollTop: $(hash).offset().top - 5 }, 'fast');
		return false;
	});

	$('a[href^=#more]').click(function() {
		$('#more').slideDown();
		return false;
	});
});
