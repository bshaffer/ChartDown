<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
  <link href="file://<?php echo dirname(__FILE__) ?>/chart.css" media="screen" rel="stylesheet" type="text/css" />
  <script src="file://<?php echo dirname(__FILE__) ?>/jquery-1.6.min.js" type="text/javascript"></script>
  <script src="file://<?php echo dirname(__FILE__) ?>/jcanvas.min.js" type="text/javascript"></script>
  <script src="file://<?php echo dirname(__FILE__) ?>/chart.js" type="text/javascript"></script>
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

    <div id="content">
        <div class="row">
      <?php foreach ($chart->getBars() as $i => $bar): ?>
        <?php if ($bar === null): ?>
         </div>
        <div class="row">
          <?php continue; ?>
        <?php endif ?>

        <?php if ($label = $bar->getLabel()): ?>
            <h3><?php echo $label ?></h3>
         <?php endif ?>

        <div class="bar">
          <?php foreach ($bar->getChords() as $chord): ?>
            <span class="chord"><?php echo $chord ?></span>
          <?php endforeach ?>
          <br />
          <span class="lyric"><?php echo $bar->getLyric() ?></span>
        </div>
      <?php endforeach ?>
      </div>
    </div>
  </div>
</body>
</html>