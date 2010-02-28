<?php
namespace System;

class Template
{
    protected $path;
    protected $object;
    
    public function __construct($path,$object = null)
    {
        $this->path = $path;
        $this->object = $object;
    }
    public function render()
    {
        extract(get_object_vars($this->object?:$this));
        ob_start();
        include $this->path;
        return ob_get_clean();
    }
    
    public function display()
    {
        echo $this->render();
    }
    
    public function __toString()
    {
        return $this->render();
    }
}
