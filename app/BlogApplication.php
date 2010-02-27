<?php
use System\Application;

class BlogApplication extends Application
{
   
    
    protected function getUrlMapping()
    {
         return array(
            '#/(?P<controller>\w+)/(?P<action>\w+)/#' => array(
                'namespace' => 'BlogApplication\Controllers',
            ),
            '#/#' => array(
                'namespace' => 'BlogApplication\Controller',
                'controller' => 'Home',
                'action' => 'index'
            )
        );
    }
    
    protected function getLayout()
    {
        return __DIR__ . '/BlogApplication/layout/main.php';
    }
    
}



