$(document).ready(function() {
	// Set up canvas element
	var canvas = document.getElementById('canvas');
	canvas.width = $('html').width();
	canvas.height = $('html').height() + 100;

	//Set up paper element
	var paper = Raphael(0, 0, $('html').width(), $('html').height() + 100);

	// Repeat ending nonsense
	$('.repeat-ending').each(function() {
		var bar = $(this);
		var row = $(this).parents('.row');
		var rowIndex = $('.row').index(row);
		var barIndex = row.find('.bar').index(bar);

		// This row
		var repeatFinish = $('.row:eq('+rowIndex.toString()+') .bar:gt('+ barIndex.toString()+')').filter('.repeat-finish:first');;
		if (repeatFinish.length == 0) {
		  	// preceeding rows
		  	repeatFinish = $('.row:gt('+rowIndex.toString()+') .repeat-finish');
		};

		var allBars = $('.bar');
		var barAllIndex = allBars.index(bar);
		if (repeatFinish.length > 0) {
			bars = $('.bar:gt('+barAllIndex.toString()+'):lt('+(allBars.index(repeatFinish)-barAllIndex).toString()+')');
			bars.prepend('<div class="repeat-ending-row repeat-ending-active">&nbsp;</div>');

			// add "repeat-ending-row" to each bar in a relevant row (if they do not exist)
			bars.parents('.row').find('.bar').each(function(){
			  	if ($(this).find('.repeat-ending-row').length == 0) {
			    	$(this).prepend('<div class="repeat-ending-row">&nbsp;</div>');
			  	};
			});
		};
	});

	$('.tie').each(function() {
		var fromChord 	= $(this).parent().find('.chord:last');
		
		if (!fromChord.length) {
			console.log("no fromChord found for tie!");
		} else {
			var toChord   	= null;
			var isNextChord = null;

			$(this).parents('.chord-row:first').find('.chord').each(function(i) {
				if (isNextChord) {
					toChord = this; isNextChord = false;
				}
				isNextChord = (this == fromChord[0]);
			});

		    var radius = toChord ? ($(toChord).offset().left - $(fromChord).offset().left - 20) / 3.8 : 50;

		    var left   = $(fromChord).offset().left + 20;
		    var bottom = $(fromChord).offset().top;

			paper.tie(left, bottom, 3.8 * radius, radius);
		}
	});

	$('.diamond').each(function(){
		paper.diamond(this);
	});

	$('.repeat-start').each(function() {
		var x = $(this).offset().left;
		var y = $(this).offset().top;
		paper.repeat(x, y, 20, $(this).height());
	});

	$('.repeat-bar').each(function() {
		var left = $(this).offset().left + ($(this).width()/2) - 10;
		var top = $(this).offset().top + ($(this).height()/2) - 10;
		paper.repeat_bar(left, top, 20, 20);
	});

	$('.repeat-finish').each(function() {
		var x = $(this).offset().left + $(this).width();
		var y = $(this).offset().top;
		paper.repeat(x, y, -20, $(this).height());
	});

	$('.accent').each(function() {
		paper.accent(this);
	});
	
	$('.anticipation').each(function() {
		paper.anticipation(this);
	});
	
	$('.tenudo').each(function() {
		paper.tenudo(this);
	});
	
	$('.fermata').each(function() {
		paper.fermata(this);	
	});
	
	$('.segno').each(function() {
		paper.segno(this);	
	});
	
	$('.coda').each(function() {
		paper.coda(this);
	});
});
