<?php
require_once '../conf/init.php';


/**
 *  LoginController class
 * 
 *  Provides data exchange with Model classes
 *  Responsible for sending back data to Client
 */

class LoginController
{
    public function __construct()
    {
        // echo 'Login controller created!';

    }

    public function handleLogin(): void
    {
        require_once "../models/Users.php";

        $loginReq = new Users;
        $respToClient =  $loginReq->login();

        echo json_encode($respToClient);
    }
}
