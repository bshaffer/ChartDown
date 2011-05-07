<?php

include_once(dirname(__FILE__).'/src/ChartDown/Autoloader.php');
ChartDown_Autoloader::register();

$env = new ChartDown_Environment();
$data = file_get_contents('chart.txt');
$chart = $env->loadChart($data);

$filename = dirname(__FILE__).'/test.pdf';
$renderer = new ChartDown_Renderer_Pdf();
$renderer->render($chart, $filename);

// $renderer = new ChartDown_Renderer_Html();
// $html = $renderer->render($chart);
// file_put_contents(dirname(__FILE__).'/test.html', $html);

exit("SUCCESS");
