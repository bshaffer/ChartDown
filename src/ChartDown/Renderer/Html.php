<?php

/**
*
*/
class ChartDown_Renderer_Html implements ChartDown_RendererInterface
{
    protected $templates;
    protected $options;

    public function __construct($templates = array(), $options = array())
    {
        $this->options = array_merge(array(
            'template_dir' => dirname(__FILE__).'/../../../data/template',
        ), $options);
        
        $loader       = new sfTemplateLoaderFilesystem(array_merge(array(
            $this->options['template_dir'].'/_%name%.php',
            $this->options['template_dir'].'/%name%Chart.php'
        ), $templates));

        $this->engine = new sfTemplateEngine($loader);
    }

    public function render($chart, $outfile = null, $template = 'default')
    {
        $html = $this->engine->render($template, array('chart' => $chart, 'renderer' => $this));

        if (is_null($outfile)) {
            return $html;
        }

        return file_put_contents($outfile, $html);
    }

    // Methods called in the template
    public function renderChordExpressions(ChartDown_Chart_Chord $chord)
    {
        $classes = array();
        foreach ($chord->getExpressions() as $expression) {
          $classes[] = $this->renderExpression($expression);
        }
        return count($classes) ? ' ' . implode(' ', $classes) : '';
    }

    public function renderChartObjectClass($object)
    {
        if ($object instanceof ChartDown_Chart_Chord) {
            return 'chord'.$this->renderChordExpressions($object);
        } else if ($object instanceof ChartDown_Chart_Rhythm) {
            return 'rhythm';
        }
        return $this->renderExpression($object);
    }
    
    public function renderChartObjectAttributes($object)
    {
        if ($object instanceof ChartDown_Chart_Bar) {
            foreach ($object->getExpressions() as $expression) {
                if (!is_null($value = $expression->getValue())) {
                    return sprintf('%s="%s"', str_replace(' ', '', $expression->getName()), $value);
                }
            }
        } 
        return '';
    }

    public function renderChartObject($object)
    {
        if ($object instanceof ChartDown_Chart_Chord) {
            return (string) $object;
        } 
        
        return '&nbsp;';
    }
    
    public function renderBarExpressions(ChartDown_Chart_Bar $bar)
    {
        $classes = array();
        foreach ($bar->getExpressions() as $expression) {
          $classes[] = $this->renderExpression($expression);
        }
        return count($classes) ? ' ' . implode(' ', $classes) : '';
    }
    
    public function rowHasTopExpression(ChartDown_Chart_Row $row)
    {
        foreach ($row->getBars() as $bar) {
            foreach ($bar->getExpressions() as $expression) {
                if ($expression->getPosition() == 'top' || $expression->getPosition() == 'bar-top') {
                    return true;
                }
            }
            foreach ($bar->getChords() as $chord) {
                if ($chord instanceof ChartDown_Chart_Expression) {
                    if ($chord->getPosition() == 'top' || $expression->getPosition() == 'bar-top') {
                        return true;
                    }
                } elseif ($chord instanceof ChartDown_Chart_Chord) {
                    foreach ($chord->getExpressions() as $expression) {
                        if ($expression->getPosition() == 'top' || $expression->getPosition() == 'bar-top') {
                            return true;
                        }
                    }
                }
            }
        }
        return false;
    }
    
    public function getBarsInRow(ChartDown_Chart_Row $row)
    {
        return count($row->getBars());
    }
    
    public function getMaxBarsInChart(ChartDown_Chart $chart)
    {
        $barCount = 0;
        foreach ($chart->getRows() as $row) {
            $barCount = max($barCount, $this->getBarsInRow($row));
        }
        return $barCount;
    }
    
    public function getPercentage( $rhythm, $meters)
    {
        $meterCount = 0;
        foreach ($meters as $meter) {
            $meterCount += $meter->getRelativeMeter();
        }
        return $meterCount ? 100 * ($rhythm->getRelativeMeter() / $meterCount) : 100;
    }

    public function renderExpression(ChartDown_Chart_Expression $expression)
    {
        return str_replace(' ', '-', $expression->getName());
    }
    
    public function getEngine()
    {
        return $this->engine;
    }
}
