<?php

use ChartDown\Chart\Chart;
use ChartDown\Chart\Row;
use ChartDown\Chart\Bar;
use ChartDown\Chart\Chord;
use ChartDown\Chart\Expression;
use ChartDown\Chart\Rhythm\Rhythm;

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
            'assets_path'  => 'file://'.dirname(__FILE__).'/../../../data/web',
        ), $options);
        
        $loader = new sfTemplateLoaderFilesystem(array_merge(array(
            $this->options['template_dir'].'/_%name%.php',
            $this->options['template_dir'].'/%name%Chart.php'
        ), $templates));

        $this->engine = new sfTemplateEngine($loader);
    }

    public function render($chart, $outfile = null, $template = 'default')
    {
        $parameters = array_merge($this->options, array(
            'chart'     => $chart,
            'renderer'  => $this,
        ));

        $html = $this->engine->render($template, $parameters);

        if (is_null($outfile)) {
            return $html;
        }

        return file_put_contents($outfile, $html);
    }

    // Methods called in the template
    public function renderChordExpressions(Chord $chord)
    {
        $classes = array();
        foreach ($chord->getExpressions() as $expression) {
          $classes[] = $this->renderExpression($expression);
        }
        return count($classes) ? ' ' . implode(' ', $classes) : '';
    }

    public function renderChartObjectClass($object)
    {
        if ($object instanceof Chord) {
            return 'chord'.$this->renderChordExpressions($object);
        } else if ($object instanceof Rhythm) {
            return 'rhythm';
        }
        return $this->renderExpression($object);
    }
    
    public function renderChartObjectAttributes($object)
    {
        if ($object instanceof Bar) {
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
        if ($object instanceof Chord) {
            return (string) $object;
        } 
        
        return '&nbsp;';
    }
    
    public function renderBarExpressions(Bar $bar)
    {
        $classes = array();
        foreach ($bar->getExpressions() as $expression) {
          $classes[] = $this->renderExpression($expression);
        }
        return count($classes) ? ' ' . implode(' ', $classes) : '';
    }
    
    public function rowHasTopExpression(Row $row)
    {
        foreach ($row->getBars() as $bar) {
            foreach ($bar->getExpressions() as $expression) {
                if ($expression->getPosition() == 'top' || $expression->getPosition() == 'bar-top') {
                    return true;
                }
            }
            foreach ($bar->getChords() as $chord) {
                if ($chord instanceof Expression) {
                    if ($chord->getPosition() == 'top' || $chord->getPosition() == 'bar-top') {
                        return true;
                    }
                } elseif ($chord instanceof Chord) {
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
    
    public function getBarsInRow(Row $row)
    {
        return count($row->getBars());
    }
    
    public function getMaxBarsInChart(Chart $chart)
    {
        $barCount = 0;
        foreach ($chart->getRows() as $row) {
            $barCount = max($barCount, $this->getBarsInRow($row));
        }
        return $barCount;
    }
    
    public function getColspan(Row $row, Bar $bar, $maxBars = null)
    {
        $barsInRow = count($row->getBars());
        $maxBars   = is_null($maxBars) ? $barsInRow : $maxBars;
        $colspan   = 1;
        $reset     = false;
        
        // rewind until current bar.  
        // if text doesn't exist in the bars immediately before this one, extend colspan
        foreach (array_reverse($row->getBars()) as $sibling) {
            if ($sibling == $bar) {
                break;
            } elseif ($sibling->hasText()) {
                // reset to 1
                $colspan = 1;
                $reset   = true;
            } else {
                $colspan++;
            }
        }
        
        // if there are bars left over, and this is the last bar in the row, add the extra bars
        if ($barsInRow < $maxBars && !$reset) {
            $colspan += $maxBars - $barsInRow;
        }

        return $colspan;
    }
    
    public function getPercentage( $rhythm, $meters)
    {
        $meterCount = 0;
        foreach ($meters as $meter) {
            $meterCount += $meter->getRelativeMeter();
        }
        return $meterCount ? 100 * ($rhythm->getRelativeMeter() / $meterCount) : 100;
    }

    public function renderExpression(Expression $expression)
    {
        return str_replace(' ', '-', $expression->getName());
    }
    
    public function getEngine()
    {
        return $this->engine;
    }
}
