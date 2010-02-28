<?php
use System\Application;

class BlogApplication extends Application
{
   
    
    protected function getUrlMappings()
    {
         return array(
            '/:class/:method/:id' => array(
                '_namespace' => 'BlogApplication\Controller',
            ),
            '/:class/:method' => array(
                '_namespace' => 'BlogApplication\Controller',
            ),
            '/' => array(
                '_namespace' => 'BlogApplication\Controller',
                '_class' => 'Home',
                '_method' => 'index'
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



