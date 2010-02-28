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
    
    protected function getLayout()
    {
        return __DIR__ . '/BlogApplication/layout/main.php';
    }
    
}



