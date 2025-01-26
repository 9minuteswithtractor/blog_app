<?php

class ShowArticlesController
{
    public function __construct()
    {
        // echo 'show Articles controller created!';
    }


    public function handleShowArticles()
    {

        require_once '../models/Articles.php';
        $showArticlesReq = new Articles;
        $showArticlesReq->showPath();
    }
}
