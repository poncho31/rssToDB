$(document).ready(function(){
	var val = $('progress.progression');
	val.on('click', function(){
		$('.progression')
		.queue(function (next) { 
		    $(this).css({'display' : 'none'});
		})
	})		
})