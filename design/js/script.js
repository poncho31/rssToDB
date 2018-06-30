$(document).ready(function(){
	var val = $('progress.progression');
	val.on('click', function(){
		$('.progression')
		.queue(function (next) { 
		    $(this).css({'display' : 'none'});
		})
	})

	$.ajax({
	  url: "?section=occurence",
	  cache: false
	})
	  .done(function( html ) {
	  	var nom= []; var note = [];
	    var obj= jQuery.parseJSON(html);
	    $.each(obj, function(key, value){
	    	nom.push(key);
	    	note.push(value);
	    });
	    //CHARTS
	    var ctx = document.getElementById("myChart").getContext('2d');
			var myChart = new Chart(ctx, {
			    type: 'bar',
			    data: {
			        labels: [nom[0], nom[1]],
			        datasets: [{
			            label: 'Note / Elève',
			            data: [note[0], note[1]],
			            backgroundColor: [
			                'rgba(255, 99, 132, 0.2)',
			                'red'
			            ],
			            borderColor: [
			                'rgba(255,99,132,1)',
			                'rgba(0, 0, 0, 1)'
			            ],
			            borderWidth: 1
			        }]
			    },
			    options: {
			        scales: {
			            yAxes: [{
			                ticks: {
			                    beginAtZero:true
			                }
			            }]
			        }
			    }
			});
	    });

})