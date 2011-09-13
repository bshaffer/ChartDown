$(document).ready(function() {
	var paper = Raphael(0, 0, $('html').width(), $('html').height() + 100);

	var circle = paper.circle(50, 40, 10);
	circle.attr("fill", "#f00");
	circle.attr("stroke", "#fff");
});