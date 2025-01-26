<?php

/**
 * Summary of ShowArticlesController
 */
class ShowArticlesController
{
    public function __construct()
    {
        // echo 'show Articles controller created!';
    }

    /**
     * Summary of handleShowArticles
     * @return void
     */
    public function handleShowArticles()
    {
        require_once '../models/Articles.php';
        $articlesReq = new Articles;
        $requestedData = $articlesReq->fetchAllArticles();

        try {
            echo json_encode($requestedData);
        } catch (Exception $err) {
            $errInfo = ['error' => $err->getMessage()];
            echo json_encode($errInfo); // in case shit hits the fan ..
        }
    }
}
