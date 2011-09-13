<?php

class ChartDown_FluidObjectTraverser
{
    protected $subject;
    protected $invoker;

    public function __construct($subject, $invoker)
    {
        $this->subject = $subject;
        $this->invoker = $invoker;
    }

    public function __call($method, $arguments)
    {
        $ret = call_user_func_array(array($this->subject, $method), $arguments);

        if (is_null($ret)) {
            return $this;
        }
        
        if ($ret instanceof ChartDown_FluidObjectTraverser) {
            // we are drilling down deeper.  Set this traverser as the envoker
            $ret->setInvoker($this);
        }

        return $ret;
    }
    
    public function setInvoker($invoker)
    {
        $this->invoker = $invoker;
    }

    public function end()
    {
        return $this->invoker;
    }
}