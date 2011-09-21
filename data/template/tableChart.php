<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
  <script type="text/javascript">
    var imagepath = "<?php echo dirname(__FILE__) ?>/../web/images";
  </script>

  <link href="file://<?php echo dirname(__FILE__) ?>/../web/css/chart.css" media="screen" rel="stylesheet" type="text/css" />
  <script src="file://<?php echo dirname(__FILE__) ?>/../web/js/jquery-1.6.min.js" type="text/javascript"></script>
  <script src="file://<?php echo dirname(__FILE__) ?>/../web/js/raphael-min.js" type="text/javascript"></script>
  <script src="file://<?php echo dirname(__FILE__) ?>/../web/js/chart-raphael.js" type="text/javascript"></script>
  <script src="file://<?php echo dirname(__FILE__) ?>/../web/js/chart.js" type="text/javascript"></script>
</head>
<body>
  <div id="chart">
    <div id="header">
      <h1><?php echo $chart->getTitle() ?></h1>
      <h2><?php echo $chart->getAuthor() ?></h2>
      <div class="chart-info">
          <dl>
              <?php if ($key = $chart->getKey()): ?>
                  <dt>Key</dt> <dd><?php echo $key ?></dd>
              <?php endif ?>

              <?php if ($time = $chart->getTimeSignature()): ?>
                  <dt>Time</dt> <dd><?php echo $time ?></dd>
              <?php endif ?>

              <?php if ($tempo = $chart->getTempo()): ?>
                  <dt>&#9833;</dt> <dd><?php echo $tempo ?></dd>
              <?php endif ?>
          </dl>
      </div>
    </div>

    <canvas id="canvas"></canvas>

    <table id="content">
      <?php foreach ($chart->getRows() as $row): ?>
          <tr class="text-row">
          <?php foreach ($row as $bar): ?>
             <td>
             <?php if ($bar->hasTopText()): ?>
               <?php echo $bar->renderTopText() ?>
             <?php else: ?>
                 &nbsp;
             <?php endif ?>
            </td>
          <?php endforeach ?>
          </tr>

         <tr class="chord-row">
          <?php foreach ($row as $bar): ?>
             <td width="25%" class="chord-cell<?php echo $renderer->renderBarExpressions($bar) ?>">
                 <?php if (false && $ending = $bar->hasRepeatEnding()): ?>
                     <div class="repeat-ending-row repeat-ending-start"><span class="repeat-ending-number"><?php echo $ending->getValue() ?></span></div>
                 <?php endif ?>
                 
                 <?php if (count($chords = $bar->getChords()) > 0): ?>
                     <table>
                         <tr>
                    <?php $percent = 100 * (1/count($chords)) ?>
                     <?php foreach ($chords as $chord): ?>
                         <?php echo $this->render('cell', array('chord' => $chord, 'percent' => $percent, 'renderer' => $renderer)) ?>
                     <?php endforeach ?>
                         </tr>
                    </table>
                 <?php else: ?>
                     &nbsp;
                 <?php endif ?>

              </td>
          <?php endforeach ?>
             </tr>

             <tr class="text-row">
          <?php foreach ($row as $bar): ?>
              <td>
             <?php if ($bar->hasBottomText()): ?>
               <?php echo $bar->renderBottomText() ?>
             <?php else: ?>
                 &nbsp;
             <?php endif ?>
             </td>
          <?php endforeach ?>
             </tr>
      <?php endforeach ?>
    </table>
  </div>
</body>
</html>