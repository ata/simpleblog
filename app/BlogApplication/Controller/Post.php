<?php
namespace BlogApplication\Controller;

class Post
{
    public function index()
    {
        $this->name = 'Ata';
    }
    
    public function view($id)
    {
        echo "ID : " . $id;
    }
}
