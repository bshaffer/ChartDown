$(document).ready(function() {

	//Set up paper element
	var paper = Raphael(0, 0, $('html').width(), $('html').height() + 100);

	// Repeat ending nonsense
	$('.repeat-ending').each(function() {
		var row 	= $(this).parents('.chord-row');
		var finish 	= $(this).nextAll('.repeat-finish');

		if (!finish.length) {
		  	finish = $('.chord-row:gt('+$('.chord-row').index(row).toString()+') .repeat-finish:first');
		};

		paper.repeat_ending($(this), finish, $(this).attr('repeatending'));
	});

	$('.tie').each(function() {
		var fromChord 	= $(this).parents('tr').find('.chord:last');
		
		if (!fromChord.length) {
			console.log("no fromChord found for tie!");
			return;
		}

		row 	= $(this).parents('.chord-row:first');
		index 	= row.find('.chord').index(fromChord);
		toChord = row.find('.chord:eq('+(index+1)+')');

	    radius = toChord.length ? ($(toChord).offset().left - $(fromChord).offset().left - 20) / 3.8 : 50;

	    left   = $(fromChord).offset().left + 20;
	    bottom = $(fromChord).offset().top;

		paper.tie(left, bottom, 3.8 * radius, Math.min(radius, 30));
	});

	$('.diamond').each(function(){
		paper.diamond(this);
	});

	$('.repeat-start').each(function() {
		var x = $(this).offset().left;
		var y = $(this).offset().top;
		paper.repeat(x, y, 15, $(this).outerHeight());
	});

	$('.repeat-finish').each(function() {
		var postion = $(this).position();
		var x = $(this).offset().left + $(this).outerWidth();
		var y = $(this).offset().top;
		paper.repeat(x, y, -15, $(this).outerHeight());
	});

	$('.repeat-bar').each(function() {
		paper.repeat_bar(this);
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
	
	$('.rhythm').each(function() {
		paper.rhythm($(this).parent());
	});
});
