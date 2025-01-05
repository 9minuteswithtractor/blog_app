<?php

function getArticle()
{
    if (session_status() != 2 || isLoggedIn() != true) {
        $msg = array(
            "error" => true,
            "message" => "Please login first"
        );

        echo json_encode($msg);
        return;
    }


    $msg = array(
        "title" => "Secret cat article",
        "message" => "Purr purr purr"
    );

    echo json_encode($msg);
    return;
}

function getAllArticles()
{
    //todo: get all
}

function isLoggedIn()
{
    return isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == true;
}
