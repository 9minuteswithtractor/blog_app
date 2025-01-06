<?php

class LoginController
{

    public function __construct()
    {
        echo 'Login controller created!';
    }

    function handleLogin()
    {
        // require "../models/Users.php";


        // access Users model db
        // $allUsersFromDB = Users::getAllUsers();


        // json_encode($allUsersFromDB);

        echo json_encode(['id' => 1, 'username' => 'bob', 'pass' => 'mypass']);
    }
}
