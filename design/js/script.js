$(document).ready(function(){
	var val = $('progress.progression').val();
	if ( val == 8) {
		$('.progression')
		.delay(5000)
		.queue(function (next) { 
		    $(this).css({'display' : 'none'});
		})
	}
})