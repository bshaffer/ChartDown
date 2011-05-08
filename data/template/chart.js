$(document).ready(function() {
  // Set up canvas element
  var canvas = document.getElementById('canvas');
  canvas.width = $('html').width();
  canvas.height = $('html').height();
  
  // Ties
  $('.tie').each(function() {
    var nextChord = $(this).parent('.bar').next().find('.chord');
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
    var radius = 20 + ($(this).width()/10);

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

  $('.repeat-ending').each(function() {
    
  });
  
  $('.repeat-end').each(function() {
    var x = $(this).offset().left + $(this).width();
    var y = $(this).offset().top;
    
    // First Line
    $("#canvas").drawLine({
      strokeStyle: "#000",strokeWidth: 4,
      x1: x-4, y1: y,
      x2: x-4, y2: y + $(this).height()
    });

    // Second Line
    $("#canvas").drawLine({
      strokeStyle: "#000",strokeWidth: 1,
      x1: x-9, y1: y,
      x2: x-9, y2: y + $(this).height()
    });
    
    // Dot 1
    $("#canvas").drawArc({
      fillStyle: "#000",radius: 1.5,
      x: x - 14,y: y + $(this).height()/3,
      start: 0,end: 360
    });

    // Dot 2
    $("#canvas").drawArc({
      fillStyle: "#000",radius: 1.5,
      x: x - 14,y: y + ($(this).height() * (2/3)),
      start: 0,end: 360
    });
  });
});
