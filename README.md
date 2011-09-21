# ChartDown

## Installation

 1. Download the package
 2. Create a text file using ChartDown syntax
 3. Render the text file as you see fit

You can render the chart as follows:

    $chartdown = new ChartDown_Environment();
    $chart = $chartdown->loadChart(file_get_contents('my_chart.txt'));
    $renderer = new ChartDown_Renderer_Pdf();
    $renderer->render($chart, 'my_chart.pdf');
    
The result will be a file named *my_chart.pdf*. Leaving the second argument to `render`
empty will add the file in your system tmp directory.  You can use any renderer you like.
Another renderer which may be useful is `ChartDown_Renderer_Html`, which renders a valid
HTML document which you can modify and then render to PDF, if tweaking is required.

    // render your chart into html...
    $chartdown = new ChartDown_Environment();
    $chart = $chartdown->loadChart(file_get_contents('my_chart.txt'));
    $renderer = new ChartDown_Renderer_Html();
    file_put_contents('my_chart.html', $renderer->render($chart));
    
    /** do your customization here... **/

    // and then generate your PDF...
    $renderer = new ChartDown_Renderer_Pdf();
    $renderer->render($chart, 'my_chart.pdf', file_get_contents('my_chart.html'));

## Syntax Guide

Expressions
-----------

The following expressions are valid:

 * **^**: Accent
 * **>**: Anticipation
 * **$**: Coda
 * **\***: Diamond
 * **!**: Fermata
 * **%**: Repeat a bar
 * **{: ... :}**: Repeat start and repeat end
 * **{1}**, **{2}**...: First ending, second ending
 * **&**: Segno
 * **_**: Tenudo
 * **~**: Tie

These can go before or after the chord, as long as they are contiguous.

Page Characters 
---------------

The following characters are important, as they determine basic page functionality.  The Metadata
and comment character is used to determine your chart's *title*, *key*, *time signature*. etc.  It
can also be used to change the key or time signature for any line of the song.  The double 
dash is for separating lines, and will be used often.  The double equals is for separating
pages, and will be used only in the rare case where the default page break is not good enough.

 * **#**: Metadata
 * **--** (double dash): Line Break
 * **==**: Page Break
 * **[**...**]**: Group two or more chords into one beat
 * **.**: Rhymic Separation (explained below)

Rhythm
------

Rhythm can be performed by using the **period** to designate beat divisions.  This is called `Dot Notation`

    G . B . | G B . . | G B . . | . [G B] . B

    G . B . . . . C | G B . | . . . . G B

In the above example, the first measure would split *G* and *B* evenly across the bar. 
This is the same as `G B` with no periods.  The second line indicates the arbitrary 
amount of periods that is possible with this style of notations.  Additional periods will
divide the bar into the appropriate number of sections.  The first bar of the second line
contains *five* periods and *three* chords.  This will divide that bar into *eight* subsections.
There are no restrictions, so *nine*, *ten*, or even *thirteen* subsections is appropriate,
although the rhythm should be kept in mind, as *thirteen* is not a valid beat division in
most time signatures.

**Note**: `Rhythmic Notation` can be devised from `Dot Notation`, so it is recommended to use 
a subdivision of your time signature for the number of divisions.  For a `3/4` time signature,
subdivisions of *three*, *six*, *nine*, or *twelve* are encouraged.

Labels and Annotations
----------------------

Labels and annotations are created using textile. They can be added above or below the chord lines, 
and are created by prefixing a line with **text:**.  Here is an example of a **Chorus** header:

    text: h2. Chorus    
    G C | D G | C G | G*

The following is an example of how one might add lyrics:

    G C | D G | C G | G*
    text: There once was a man | with a fork in his hand | who wanted | to eat

These are not phenomenal lyrics, but the technical requirements for better poetry are the same.
Below is a more complex example of how text can be used to create complex chart requirements:

    --
    text: h1. Chorus
    {: G C | D G | & C G | G*
    text: There once was a man | with a fork in his hand | who wanted | to eat
    --
    text: | | | p>. *D.S. Al Coda*
    G C | D G $ | C G | G* :}
    text: The poor man cried | when the food arrived | and it smelled like feet
    
In this example, not only are the poor lyrics continued, but a D.S. Al Coda is added.  The text
for the coda will appear above the final bar and be aligned right, so that it will be directly over the 
repeat, as is appropriate in a chord chart.  Read more on 
[http://www.redmine.org/projects/redmine/wiki/RedmineTextFormatting#Headings](Textile Syntax),
I assure you it's easier than you might think.

Chords
------

 * **|** (pipe): Bar Break
 * **Numbers 1 through 7**: Chord Value
 * **Letters A through G**: Chord Value
 * **b**: flat
 * **#**: sharp
 * **-** (dash): minor
 * **dim**: diminished
 * **aug**: augmented


ToDo
----

Please contact me with any suggestions or feature requests. My goal is for this to be as useful
and usable as possible. All feedback is appreciated!

Below is a list of possible future enhancements.

**Additional Notation:**

 * **'** (apostrophe/singlequote) - Staccato
 * **(**...**)**: Separates articulation from chord
 * **x/o**: Rhythmic notation for beat subdivisions
