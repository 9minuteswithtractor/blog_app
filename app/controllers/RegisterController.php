<?php

class RegisterController
{
    public function __construct()
    {
        // echo 'Register controller created ...';
    }

    public function handleRegistration(): void
    {

        require_once "../models/Users.php";
        $registrationReq = new Users;
        $userRegistrationStatus =  $registrationReq->registerUser();

        echo json_encode($userRegistrationStatus);
    }
}
