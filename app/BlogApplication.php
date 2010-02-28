<?php
use System\Application;

class BlogApplication extends Application
{
   
    
    protected function getUrlMappings()
    {
         return array(
            '/:class/:method' => array(
                'namespace' => 'BlogApplication\Controller',
            ),
            '/' => array(
                'namespace' => 'BlogApplication\Controller',
                'class' => 'Home',
                'method' => 'index'
            )
        );
    }
    
    
    protected function inject($responseObject)
    {
        $responseObject->app = $this;
        $responseObject->request = $this->request;
    }
    
    protected function getDefaultLayout()
    {
        return __DIR__ . '/BlogApplication/layout/main.php';
    }
    
    
    
}



