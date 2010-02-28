<?php

namespace System;

abstract class Application
{
    protected $request;
    protected $responseObject;
    public function __construct($request)
    {
        $this->request = $request;
        $this->request->setUrlMappings($this->getUrlMappings());
        $this->request->dispatcher();
        $this->setup($this->request->getResponseClass());
    }
    
    
    protected function setup($class)
    {
        $this->responseObject = new $class;
    }
    
    
    public function run()
    {
        call_user_func_array(array($this->responseObject,
                                    $this->request->getResponseMethod()),
                                    $this->request->getResponseParams());
        $this->render($this->responseObject);
    }
    
    protected function render($responseObject)
    {
        $ref = new \ReflectionClass(get_class($responseObject));
        $path = str_replace('.php','/'. $this->request->getResponseMethod() 
                . '.php' ,$ref->getFileName());
        if (file_exists($path)){
            $content = new Template($path,$responseObject);
            $layout = new Template($this->getLayout());
            $layout->content = $content;
            $layout->display();
            return true;
            
        }
        return false;
    }
    
    abstract protected function getUrlMappings();
    abstract protected function getLayout();
    
}
