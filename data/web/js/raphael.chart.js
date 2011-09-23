Raphael.fn.coda = function (chord) {
	x = $(chord).offset().left;
	y = $(chord).offset().top;
	height = 30;

	return this.image(imagepath+"/coda.png", x, y-height, 30, 30);	
};

Raphael.fn.segno = function (chord) {
	x = $(chord).offset().left;
	y = $(chord).offset().top;
	height = 30;
	
	return this.image(imagepath+"/segno.png", x, y-height, 30, 30);	
};

Raphael.fn.fermata = function (chord) {
	x = $(chord).offset().left;
	y = $(chord).offset().top;
	return this.image(imagepath+"/fermata.png", x, y-10, 15, 10);	
};

Raphael.fn.accent = function (chord) {
	x = $(chord).offset().left;
	y = $(chord).offset().top;
	return this.spath("M%s L%s L%s", [x, y], [x+5, y-10], [x+10, y]);
};

Raphael.fn.anticipation = function (chord) {
	x = $(chord).offset().left;
	y = $(chord).offset().top;
	return this.spath("M%s L%s L%s", [x, y], [x+10, y-5], [x, y-10]);
};

Raphael.fn.tenudo = function (chord) {
	x = $(chord).offset().left;
	y = $(chord).offset().top;
	return this.spath("M%s L%s", [x, y], [x+10, y]);
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
    var x = $(chord).offset().left - ($(chord).width());
    var y = $(chord).offset().top - ($(chord).height()/2);
    var radius = Math.min(15 + ($(chord).width()/4), 22);
	return this.spath("M%s L%s L%s L%s L%s", [x, y+radius], [x+radius, y], [x+(2*radius), y+radius], [x+radius, y+(2*radius)], [x, y+radius]);
};

Raphael.fn.repeat = function(left, top, width, height) {
	var fatLine   = this.spath("M%s L%s", [left, top], [left, top+height]).attr({'stroke-width': 4});
	var thinLine  = this.spath("M%s L%s", [left+(width/3), top], [left+(width/3), top+height]).attr({'stroke-width': 2});
	var topDot    = this.circle(left+(width/1.5), top+(height/3), 1).attr({"fill": "black"});
	var bottomDot = this.circle(left+(width/1.5), top+(height/1.5), 1).attr({"fill": "black"});
	return [fatLine, thinLine, topDot, bottomDot];
};

Raphael.fn.repeat_bar = function(left, top, width, height) {
	var line   	  = this.spath("M%s L%s", [left, top+height], [left+width, top]).attr({'stroke-width': 3});
	var topDot    = this.circle(left, top, 1).attr({"fill": "black"});
	var bottomDot = this.circle(left+width, top+height, 1).attr({"fill": "black"});
	return [line, topDot, bottomDot];
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