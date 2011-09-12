# ChartDown

## Installation

 1. Download the package
 2. Create a text file using ChartDown syntax
 3. Render the text file as you see fit

You can render the chart as follows:

    $chartdown = new ChartDown();
    $chart = $chartdown->loadChart('my_chart.txt');
    $renderer = new ChartDown_Renderer_Pdf();
    $renderer->render($chart, 'my_chart.pdf');
    
The result will be a file named *my_chart.pdf*. Leaving the second argument to `render`
empty will add the file in your system tmp directory.  You can use any renderer you like.
Another renderer which may be useful is `ChartDown_Renderer_Html`, which renders a valid
HTML document which you can modify and then render to PDF, if tweaking is required.

    // render your chart into html...
    $chartdown = new ChartDown();
    $chart = $chartdown->loadChart('my_chart.txt');
    $renderer = new ChartDown_Renderer_Html();
    $html = $renderer->render($chart, 'my_chart.html');

    // do your customization here...

    // and then get your PDF...
    $renderer = new ChartDown_Renderer_Pdf();   
    $renderer->render($chart, 'my_chart.pdf', file_get_contents('my_chart.html'));

## Syntax Guide

Rhythm
------

Rhythm can be performed by using the **period** to designate beat divisions.  This is called `Dot Notation`

    G . B . | G B . . | G B . . | . G . B

    G . B . . . . C | G B . | . . . . G B

In the above example, the first measure would split *G* and *B* evenly across the bar. 
This is the same as `G B` with no periods.  The second line indicates the arbitrary 
amount of periods that is possible with this style of notations.  Additional periods will
divide the bar into the appropriate number of sections.  The first bar of the second line
contains *five* periods and *three* chords.  This will divide that bar into *eight* subsections.
There are no restrictions, so *nine*, *ten*, or even *thirteen* subsections is appropriate,
although the rhythm should be kept in mind, as *thirteen* is not a valid beat division in
most time signatures.

**Note**: `Beat Notation` can be devised from `Dot Notation`, so it is recommended to use 
a subdivision of your time signature for the number of divisions.  For a *3/4* time signature,
subdivisions of *three*, *six*, *nine*, and *twelve* are encouraged.

Expressions
-----------

The following expressions are valid:

  * **^**: accent
  * **_**: tenudo
  * *****: Diamond
  * **>**: Anticipation
  * **()**: Separates articulation from chord
  * **{:**: Repeat start
  * **:}**: Repeat end
  * **{1}, {2}...**: First ending, second ending
  * **$**: Coda
  * **&**: Signa
  * **!**: Fermata
  * **.**: Rhymic Separation

these can go before or after the chord, as long as they are contiguous.

Labels and Annotations
----------------------

Labels and annotations are created using markdown. The following is an example of a label:

    label: Chorus
    G C | D G | C G | G*
    
Annotations happen below the staff.  They are created by prefixing a line with **t:**

    label: Chorus
    G C | D G | C G | G*
    text: There once was a man | with a fork in his hand | who wanted | to eat

Page Characters 
---------------

 * **--**: Line Break
 * **==**: Page Break
 * **#**: Metadata / Comment

Chords
------

 * **|**: Bar Break
 * **Numbers 1 through 7**: Chord Value
 * **Letters A through G**: Chord Value
 * **b**: flat
 * **#**: sharp
 * **-**: minor
 * **dim**: diminished
 * **aug**: augmented

