<?php
use System\Application;

class BlogApplication extends Application
{
   
    
    protected function getUrlMapping()
    {
         return array(
            '#/(?P<controller>\w+)/(?P<action>\w+)/(?P<id>\d+)#' => array(
                'namespace' => 'BlogApplication\Controller',
            ),
            '#/(?P<controller>\w+)/(?P<action>\w+)/#' => array(
                'namespace' => 'BlogApplication\Controller',
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



