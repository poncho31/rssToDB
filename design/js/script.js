$(document).ready(function(){
	var progression = $('progress.progression');
	progression.on('click', function(){
		$('.progression')
		.queue(function (next) { 
		    $(this).css({'display' : 'none'});
		})
	})

	//Réception des données issues de occurences.php wordOccurence()
	//$array[$mot] = $occurence
	var occurenceValues = $('#occurenceValues').val();
	var occurenceJson = jQuery.parseJSON(occurenceValues);
	var word= []; var occurence = [];
	$.each(occurenceJson, function(key, value){
	 word.push(key);
	 occurence.push(value);
	});
	var ctx = document.getElementById("myChart").getContext('2d');

	var myChart = new Chart(ctx, {
	    type: 'bar',
	    data: {
			        labels: $.each(word, function(key, value){[value]}),
			        datasets: [{
			            label: $.each(word, function(key, value){[value]}),
			            data: $.each(occurence, function(key, value){[value]}),
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
	// $.ajax({
	//   url: "index.php/",
	//   cache: false
	// })
	//   .done(function( html ) {
	//   	var nom= []; var note = [];
	//     var obj= jQuery.parseJSON(json);
	//     $.each(obj, function(key, value){
	//     	nom.push(key);
	//     	note.push(value);
	//     });
	//     //CHARTS
	//     var ctx = document.getElementById("myChart").getContext('2d');
	// 		var myChart = new Chart(ctx, {
	// 		    type: 'bar',
	// 		    data: {
	// 		        labels: [nom[0], nom[1]],
	// 		        datasets: [{
	// 		            label: 'Note / Elève',
	// 		            data: [note[0], note[1]],
	// 		            backgroundColor: [
	// 		                'rgba(255, 99, 132, 0.2)',
	// 		                'red'
	// 		            ],
	// 		            borderColor: [
	// 		                'rgba(255,99,132,1)',
	// 		                'rgba(0, 0, 0, 1)'
	// 		            ],
	// 		            borderWidth: 1
	// 		        }]
	// 		    },
	// 		    options: {
	// 		        scales: {
	// 		            yAxes: [{
	// 		                ticks: {
	// 		                    beginAtZero:true
	// 		                }
	// 		            }]
	// 		        }
	// 		    }
	// 		});
	//     });

})