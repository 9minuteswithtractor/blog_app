<?php


require_once 'Users.php';

class Articles extends Users
{


    public $pathToArticles;

    public function __construct()
    {
        $this->pathToArticles = $this->getFilePath(POSTS_DB);
    }

    public function showPath()
    {

        print_r($this->pathToArticles);
    }
}
