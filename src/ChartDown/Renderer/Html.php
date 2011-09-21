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

    public function render(ChartDown_Chart $chart, $outfile = null, $template = 'default')
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
          $classes[] = str_replace(' ', '-', $expression->getName());
        }
        return count($classes) ? ' ' . implode(' ', $classes) : '';
    }

    public function renderBarExpressions(ChartDown_Chart_Bar $bar)
    {
        $classes = array();
        foreach ($bar->getExpressions() as $expression) {
          $classes[] = str_replace(' ', '-', $expression->getName());
        }

        return count($classes) ? ' ' . implode(' ', $classes) : '';
    }
    
    public function getEngine()
    {
        return $this->engine;
    }
}
