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
                
                if (isset($match['_namespace'])){
                    $namespace = $this->classify($match['_namespace']);
                    unset($match['_namespace']);
                } else {
                    $namespace = $this->classify($options['_namespace']);
                }
                
                if (isset($match['_class'])){
                    $class = $this->classify($match['_class']);
                    unset($match['_class']);
                } else {
                    $class = $this->classify($options['_class']);
                }
                
                if (isset($match['_method'])){
                    $method = $this->camelize($match['_method']);
                    unset($match['_method']);
                } else {
                    $method = $this->camelize($options['_method']);
                }
                
                $this->responseClass = $namespace . '\\' . $this->prefixClass
                                        . $class . $this->posfixClass;
                        
                $this->responseMethod = $this->prefixMethod . $method 
                                        . $this->posfixMethod;
                $this->responseParams = $match;
                return true;
                
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
    
    public function getRequestUri()
    {
        return $this->getUrl();
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
        return '#'. preg_replace("#\\\:(\w+)#","(?P<_$1>\w+)",preg_quote($word)) .'#';
        /*
        preg_match_all('#\\\:(\w+)#',preg_quote($word),$match);
        return str_replace($match[0],array_map(function($v){
            return sprintf('(?P< %s >)',$v);
        },$match[1]),preg_quote($word));
        */
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
