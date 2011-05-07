<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
  <link href="file://<?php echo dirname(__FILE__) ?>/chart.css" media="screen" rel="stylesheet" type="text/css" />
</head>
<body>
  <div id="chart">
    <div id="header">
      <h1><?php echo $chart->getTitle() ?></h1>
      <h2><?php echo $chart->getAuthor() ?></h2>
      <div class="chart-info">
          <dl>
              <dt>Key</dt> <dd><?php echo $chart->getKey() ?></dd>
              <dt>Time</dt> <dd><?php echo $chart->getTimeSignature() ?></dd>
          </dl>
      </div>
    </div>
    
    <div id="content">
      <?php foreach ($chart->getBars() as $i => $bar): ?>
        <?php if ($bar === null): ?>
          <hr />
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
</body>
</html>