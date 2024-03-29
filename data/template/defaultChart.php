<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
  <title><?php echo $chart->getTitle() ?> | <?php echo $chart->getArtist() ?></title>
  <script type="text/javascript">
    var assetpath = "<?php echo $assets_path ?>";
  </script>

  <link href="<?php echo $assets_path ?>/css/chart.css" media="screen" rel="stylesheet" type="text/css" />
  <script src="<?php echo $assets_path ?>/js/jquery-1.6.min.js" type="text/javascript"></script>
  <script src="<?php echo $assets_path ?>/js/raphael-min.js" type="text/javascript"></script>
  <script src="<?php echo $assets_path ?>/js/raphael.chart.js" type="text/javascript"></script>
  <script src="<?php echo $assets_path ?>/js/chart.js" type="text/javascript"></script>
</head>
<body>
  <div id="chart">
    <div id="header">
      <h1><?php echo $chart->getTitle() ?></h1>
      <h3><?php echo $chart->getArtist() ?></h3>
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

    <table id="content">
      <?php foreach ($chart->getRows() as $row): ?>
          <?php if ($row->hasText()): ?>
              <?php echo $this->render('text', array('row' => $row, 'position' => 'top', 'renderer' => $renderer, 'maxBars' => $renderer->getMaxBarsInChart($chart))) ?>            
          <?php elseif ($row->hasChords()): ?>
              <?php if ($renderer->rowHasTopExpression($row)): ?>
                  <tr class="expression-row">
                      <td colspan="<?php echo $renderer->getMaxBarsInChart($chart) ?>">&nbsp;</td>
                  </tr>
              <?php endif ?>

             <?php if ($row->hasChords()): ?>
             <tr class="chord-row">
              <?php foreach ($row as $bar): ?>
                 <td width="25%" class="chord-cell<?php echo $renderer->renderBarExpressions($bar) ?>" <?php echo $renderer->renderChartObjectAttributes($bar) ?>>
                     <?php if (count($chords = $bar->getChords()) > 0): ?>
                         <table style="width:100%">
                             <tr>
                         <?php foreach ($chords as $chord): ?>
                             <?php echo $this->render('chord', array('chord' => $chord, 'percent' => $renderer->getPercentage($chord, $chords), 'renderer' => $renderer)) ?>
                         <?php endforeach ?>
                             </tr>
                        </table>
                     <?php else: ?>
                         &nbsp;
                     <?php endif ?>
                 </td>
              <?php endforeach ?>
             </tr>
            <?php endif ?>
          <?php else: ?>
              <tr class="row-break">
                <td colspan="<?php echo $renderer->getMaxBarsInChart($chart) ?>">&nbsp;</td>
              </tr>
          <?php endif ?>
      <?php endforeach ?>
    </table>
  </div>
</body>
</html>