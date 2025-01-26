<?php

class LogoutController
{

    public function handleLogout(): void
    {
        session_destroy();
    }
}
