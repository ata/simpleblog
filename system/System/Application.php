<?php

namespace System;

abstract class Application
{
    
    protected $prefixController = '';
    protected $prefixAction = '';
    protected $posfixController = '';
    protected $posfixAction = '';
    
    // misalnya adalah info path
    public function run()
    {
        $mappings = $this->getUrlMapping();
        foreach ($mappings as $pattern => $options) {
            if(preg_match($pattern,$this->getUrl(),$match)){
                $match = array_unique($match);
                $url = $this->getUrl();
                $match = array_filter($match,function($v) use($url){
                    return $v != $url;
                });
                
                if (isset($match['namespace'])){
                    $namespace = $this->classify($match['namespace']);
                    unset($match['namespace']);
                } else {
                    $namespace = $this->classify($options['namespace']);
                }
                
                if (isset($match['controller'])){
                    $controller = $this->classify($match['controller']);
                    unset($match['controller']);
                } else {
                    $controller = $this->classify($options['controller']);
                }
                
                if (isset($match['action'])){
                    $action = $this->camelize($match['action']);
                    unset($match['action']);
                } else {
                    $action = $this->camelize($options['action']);
                }
                
                $class = $namespace . '\\' . $this->prefixController
                        . $controller . $this->posfixController;
                        
                $method = $this->prefixAction . $action . $this->posfixAction;
                
                $objectResponse = new $class;
                $this->prepare($objectResponse);
                $this->inject($objectResponse);
                call_user_func_array(array($objectResponse,$method),$match);
                $this->setupTemplate($objectResponse,$method);
                return true;
                
            }
        }
    }
    
    protected function prepare($objectResponse)
    {
        
    }

    
    protected function inject($objectResponse)
    {
        
    }
    
    protected function setupTemplate($objectResponse,$method)
    {
        $ref = new \ReflectionClass(get_class($objectResponse));
        $path = str_replace('.php','/'. $method . '.php' ,$ref->getFileName());
        if (file_exists($path)){
            $content = new Template($path);
            $content->setObject($objectResponse);
            $layout = new Template($this->getLayout());
            $layout->content = $content;
            $layout->display();
            return true;
            
        }
        return false;
    }
    
    
    public function getBaseUrl()
    {
        return str_replace('/index.php','',$_SERVER['PHP_SELF']);
    }
    
    public function getUrl()
    {
        return str_replace($this->getBaseUrl(),'',$_SERVER['REQUEST_URI']);
    }
    
    public function classify($word)
    {
        return str_replace(" ", "", ucwords(strtr($word, "_-", "  ")));
    }
    
    public function camelize($word)
    {
        return lcfirst($this->classify($word));
    }
    
    abstract protected function getUrlMapping();
    abstract protected function getLayout();
    
}
