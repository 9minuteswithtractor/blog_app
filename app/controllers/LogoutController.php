<?php

class LogoutController
{


    public function handleLogout()
    {
        session_destroy();
    }
}
