Raphael.fn.coda = function (chord) {
	x = $(chord).offset().left - ($(chord).width()/2); // center coda directly above chord
	y = $(chord).offset().top;

	return this.image(imagepath+"/coda.png", x, y, 30, 30)
		.translate(0, -30);	
};

Raphael.fn.segno = function (chord) {
	x = $(chord).offset().left;
	y = $(chord).offset().top;
	
	return this.image(imagepath+"/segno.png", x, y, 30, 30)
		.translate(0, -30);	
};

Raphael.fn.fermata = function (chord) {
	x = $(chord).offset().left;
	y = $(chord).offset().top;
	return this.image(imagepath+"/fermata.png", x, y-10, 15, 10);	
};

Raphael.fn.accent = function (chord) {
	x = $(chord).offset().left;
	y = $(chord).offset().top - 2;

	return this.spath("M%s L%s L%s", [x+3, y], [x+6, y-6], [x+9, y])
		.attr({'stroke-width': 2});
};

Raphael.fn.anticipation = function (chord) {
	x = $(chord).offset().left;
	y = $(chord).offset().top;
	return this.spath("M%s L%s L%s", [x, y], [x+6, y-3], [x, y-6])
		.translate(-4, 5)
		.attr({'stroke-width': 2});
};

Raphael.fn.tenudo = function (chord) {
	x = $(chord).offset().left + 2;
	y = $(chord).offset().top;
	return this.spath("M%s L%s", [x, y], [x+10, y])
		.attr({'stroke-width': 2});
};

Raphael.fn.tie = function (left, bottom, width, height) {
	var thickness = 3;
	var top = bottom - height;
	var right = left + width;
	var radius = width / 2.6;

	return this.spath("M%c C%c %c %c C%c %c %cz", 
		[left, bottom], 
		[left+radius, top], [right-radius, top], [right, bottom],
		[right-radius, top+thickness], [left+radius, top+thickness], [left, bottom]
	).attr({'fill': 'black'});
};

Raphael.fn.diamond = function(chord) {
	// Calculate center of chord
    var x = $(chord).offset().left + ($(chord).width()/2);
    var y = $(chord).offset().top + ($(chord).height()/2);

    var width = 10+($(chord).width()/2);
    var height = Math.min(width, 28);

	return this.spath("M%s L%s L%s L%sz", [x-width, y], [x, y-height], [x+width, y], [x, y+height])
		.attr({'fill': 'white'});;
};

Raphael.fn.repeat = function(left, top, width, height) {
	var fatLine   = this.spath("M%s L%s", [left, top], [left, top+height]).attr({'stroke-width': 4});
	var thinLine  = this.spath("M%s L%s", [left+(width/3), top], [left+(width/3), top+height]).attr({'stroke-width': 2});
	var topDot    = this.circle(left+(width/1.5), top+(height/3), 1).attr({"fill": "black"});
	var bottomDot = this.circle(left+(width/1.5), top+(height/1.5), 1).attr({"fill": "black"});
	return [fatLine, thinLine, topDot, bottomDot];
};

Raphael.fn.rhythm = function(bar) {
	var width  = 5;
	var height = $(bar).height()/2;
	var x      = $(bar).offset().left + (width);
	var y      = $(bar).offset().top + (height/2);
	
 	return this.spath("M%s L%s", [x, y+height], [x+width, y]).attr({'stroke-width': 1});
};

Raphael.fn.repeat_bar = function(bar) {
	var left = $(bar).offset().left + ($(bar).width()/2) - 5;
	var top = $(bar).offset().top + ($(bar).height()/2) - 5;
	var width = 10;
	var height = 10;

	var line   	  = this.spath("M%s L%s", [left, top+height], [left+width, top]).attr({'stroke-width': 2});
	var topDot    = this.circle(left, top, .5).attr({"fill": "black"});
	var bottomDot = this.circle(left+width, top+height, .5).attr({"fill": "black"});
	return [line, topDot, bottomDot];
};

Raphael.fn.repeat_ending = function(fromBar, toBar, endingNumber) {
	if (toBar && toBar.length > 0) {
		allBars = $('.chord-cell');
		fromBarIndex = allBars.index(fromBar);

		bars = $('.chord-cell:gt('+fromBarIndex.toString()+'):lt('+(allBars.index(toBar)-fromBarIndex).toString()+')');
		bars.addClass('repeat-ending-cell');

		this.text(fromBar.offset().left+5, fromBar.offset().top-6, endingNumber+'.');
	} else {
		console.log('no ending bar found');
	};
};

Raphael.fn.spath = function(path) {
  var args = arguments;
  var i = 1;
  path = path.replace(/(%s|%c)/g, function(match, number) {
    var coordinates = typeof args[i] != 'undefined' ? args[i] : [0, 0];
    var replace;
	switch (match) {
		case '%s':
			replace = Math.round(coordinates[0]) + ' ' + Math.round(coordinates[1]);
		 	break;
		case '%c':
			replace = Math.round(coordinates[0]) + ',' + Math.round(coordinates[1]);
		 	break;
	}
	i++;
	return replace;
  });

  return this.path(path);
};