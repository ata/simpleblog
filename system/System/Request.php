<?php

namespace System;

class Request
{
    
    protected $prefixClass = '';
    protected $prefixMethod = '';
    protected $posfixClass = '';
    protected $posfixMethod = '';
    protected $urlMappings = array();
    protected $baseUrl;
    protected $url = null;
    protected $responseClass;
    protected $responseMethod;
    protected $responseParams = array();
    
    // misalnya adalah info path
    public function dispatcher()
    {
        foreach ($this->urlMappings as $pattern => $options) {
            if(preg_match($this->toRegex($pattern),$this->getUrl(),$match)){
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
                
                if (isset($match['class'])){
                    $class = $this->classify($match['class']);
                    unset($match['class']);
                } else {
                    $class = $this->classify($options['class']);
                }
                
                if (isset($match['method'])){
                    $method = $this->camelize($match['method']);
                    unset($match['method']);
                } else {
                    $method = $this->camelize($options['method']);
                }
                
                $this->responseClass = $namespace . '\\' . $this->prefixClass
                                        . $class . $this->posfixClass;
                        
                $this->responseMethod = $this->prefixMethod . $method 
                                        . $this->posfixMethod;
                
            }
        }
    }
    
    
    public function getDefaultBaseUrl()
    {
        return str_replace('/index.php','',$_SERVER['PHP_SELF']);
    }
    
    public function getBaseUrl()
    {
        return $this->url?:$this->getDefaultBaseUrl();
    }
    
    
    public function getUrl()
    {
        return str_replace($this->getBaseUrl(),'',$_SERVER['REQUEST_URI']);
    }
    
    
    
    protected function classify($word)
    {
        return str_replace(" ", "", ucwords(strtr($word, "_-", "  ")));
    }
    
    protected function camelize($word)
    {
        return lcfirst($this->classify($word));
    }
    
    public function setUrlMappings($urlMappings)
    {
        $this->urlMappings = $urlMappings;
    }
    
    /**
     * convert '/:foo/:bar' to '#/(?P<foo>\w+)/(?P<bar>\w+)#'
     */
    private function toRegex($word){
         return '#'.preg_replace('#\\\:(\w+)#','(?P<$1>\w+)',preg_quote($word)) .'#';
    }
    
    
    public function setPrefixClass($word)
    {
        $this->prefixClass = $word;
    }
    
    public function setPrefixMethod($word)
    {
        $this->prefixMethod = $word;
    }
    
    public function setPosfixClass($word)
    {
        $this->posfixClass = $word;
    }
    
    public function setPosfixMethod($word)
    {
        $this->posfixMethod = $word;
    }
    
    public function getResponseClass()
    {
        return $this->responseClass;
    }
    
    public function getResponseMethod()
    {
        return $this->responseMethod;
    }
    
    public function getResponseParams()
    {
        return $this->responseParams;
    }
}
