$(document).ready(function() {
  // Set up canvas element
  var canvas = document.getElementById('canvas');
  canvas.width = $('html').width();
  canvas.height = $('html').height() + 100;

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

  // Ties
  $('.tie').each(function() {
    var nextChord = $(this).parents('.bar').next().find('.chord');
    var radius = nextChord.length > 0 ? (nextChord.offset().left - $(this).offset().left) / 2 : 50;
    var x = $(this).offset().left + $(this).width() + (radius/1.2);
    var y = $(this).offset().top + (radius/1.4);

    // Draw a tie between two chords
    $('#canvas').drawArc({
      strokeStyle: "#000",
      strokeWidth: 4,
      strokeCap: "round",
      x: x,
      y: y,
      radius: radius,
      start: -45,
      end: 45
    });

    $('#canvas').drawArc({
      strokeStyle: "#fff",
      x: x,
      y: y+7,
      radius: radius + 5,
      start: -45,
      end: 45
    });
  });

  // Diamonds
  $('.diamond').each(function(){
    // Calculate center of chord
    var x = $(this).offset().left + ($(this).width()/2);
    var y = $(this).offset().top + ($(this).height()/2);
    var radius = Math.min(15 + ($(this).width()/4), 22);

    $("#canvas").drawPolygon({
      strokeStyle: "#000",
      strokeWidth: 1,
      x: x,
      y: y,
      sides: 4,
      radius: radius,
      angle: 45
    });
  });

  // Repeats
  $('.repeat-start').each(function() {
    var x = $(this).offset().left;
    var y = $(this).offset().top;

    // First Line
    $("#canvas").drawLine({
      strokeStyle: "#000",strokeWidth: 4,
      x1: x, y1: y,
      x2: x, y2: y + $(this).height()
    });

    // Second Line
    $("#canvas").drawLine({
      strokeStyle: "#000",strokeWidth: 1,
      x1: x+5, y1: y,
      x2: x+5, y2: y + $(this).height()
    });

    // Dot 1
    $("#canvas").drawArc({
      fillStyle: "#000",radius: 1.5,
      x: x + 10,y: y + $(this).height()/3,
      start: 0,end: 360
    });

    // Dot 2
    $("#canvas").drawArc({
      fillStyle: "#000",radius: 1.5,
      x: x + 10,y: y + ($(this).height() * (2/3)),
      start: 0,end: 360
    });
  });

  $('.repeat-bar .chord-row').each(function() {
    var dimentions = $(this).width()/15;
    var x_offset = ($(this).width() - dimentions) / 2;
    var y_offset = $(this).height() - dimentions;

    var left = $(this).offset().left + x_offset;
    var right = $(this).offset().left + $(this).width() - x_offset;
    var top = $(this).offset().top + y_offset;
    var bottom = $(this).offset().top + $(this).height();

    // First Line
    $("#canvas").drawLine({
      strokeStyle: "#000",strokeWidth: 2.5,strokeCap: 'round',
      x1: left, y1: bottom,   // bottom left
      x2: right, y2: top // top right
    });

    // Dot 1 (top left)
    $("#canvas").drawArc({
      fillStyle: "#000",radius: 1.8,
      x: left, y: top,
      start: 0,end: 360
    });

    // Dot 2 (bottom right)
    $("#canvas").drawArc({
      fillStyle: "#000",radius: 1.8,
      x: right, y: bottom,
      start: 0,end: 360
    });
  });

  $('.repeat-finish').each(function() {
    var x = $(this).offset().left + $(this).width();
    var y = $(this).offset().top;

    // First Line
    $("#canvas").drawLine({
      strokeStyle: "#000",strokeWidth: 4,
      x1: x-5, y1: y,
      x2: x-5, y2: y + $(this).height()
    });

    // Second Line
    $("#canvas").drawLine({
      strokeStyle: "#000",strokeWidth: 1,
      x1: x-10, y1: y,
      x2: x-10, y2: y + $(this).height()
    });

    // Dot 1
    $("#canvas").drawArc({
      fillStyle: "#000",radius: 1.5,
      x: x - 15,y: y + $(this).height()/3,
      start: 0,end: 360
    });

    // Dot 2
    $("#canvas").drawArc({
      fillStyle: "#000",radius: 1.5,
      x: x - 15,y: y + ($(this).height() * (2/3)),
      start: 0,end: 360
    });

    // Set 5px gap between following bar (done here because otherwise the bars get knocked down a row)
    $(this).attr('style', 'width:'+($(this).width()-5).toString()+'px;margin-right:5px');
  });
});
