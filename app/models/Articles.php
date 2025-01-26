<?php


require_once 'Users.php';

class Articles extends Users
{


    public function __construct()
    {
        // echo 'Articles model created ..';
    }

    public function fetchAllArticles()
    {

        $reqData = $this->getAll(ARTICLES_DB);
        return $reqData ? $reqData : false;
    }
}
